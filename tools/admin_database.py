#!/usr/bin/env python3
"""
Admin Database Management System
Handles separate SQLite database for admin authentication
"""

import sqlite3
import hashlib
import secrets
import logging
from datetime import datetime, timedelta
from pathlib import Path
from typing import Optional, Dict, List, Tuple
import json

from config import ADMIN_DB_PATH, BACKUP_DB_PATH, PASSWORD_HASH_ROUNDS

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class AdminDatabase:
    """
    Manages admin authentication in separate SQLite database
    """
    
    def __init__(self, db_path: str = None):
        self.db_path = db_path or ADMIN_DB_PATH
        self.backup_path = BACKUP_DB_PATH
        self.init_database()
    
    def init_database(self):
        """Initialize admin database with required tables"""
        try:
            with sqlite3.connect(self.db_path) as conn:
                cursor = conn.cursor()
                
                # Create admin_users table
                cursor.execute('''
                    CREATE TABLE IF NOT EXISTS admin_users (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        email TEXT UNIQUE NOT NULL,
                        password_hash TEXT NOT NULL,
                        name TEXT NOT NULL,
                        role TEXT DEFAULT 'admin',
                        is_active BOOLEAN DEFAULT 1,
                        last_login TIMESTAMP,
                        login_attempts INTEGER DEFAULT 0,
                        locked_until TIMESTAMP,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )
                ''')
                
                # Create admin_sessions table
                cursor.execute('''
                    CREATE TABLE IF NOT EXISTS admin_sessions (
                        id TEXT PRIMARY KEY,
                        admin_id INTEGER NOT NULL,
                        ip_address TEXT,
                        user_agent TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        expires_at TIMESTAMP NOT NULL,
                        FOREIGN KEY (admin_id) REFERENCES admin_users (id)
                    )
                ''')
                
                # Create admin_logs table for audit trail
                cursor.execute('''
                    CREATE TABLE IF NOT EXISTS admin_logs (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        admin_id INTEGER,
                        action TEXT NOT NULL,
                        details TEXT,
                        ip_address TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (admin_id) REFERENCES admin_users (id)
                    )
                ''')
                
                conn.commit()
                logger.info(f"Admin database initialized at {self.db_path}")
                
        except Exception as e:
            logger.error(f"Failed to initialize admin database: {e}")
            raise
    
    def hash_password(self, password: str) -> str:
        """Hash password using PBKDF2"""
        salt = secrets.token_hex(16)
        pwd_hash = hashlib.pbkdf2_hmac('sha256', 
                                      password.encode('utf-8'), 
                                      salt.encode('utf-8'), 
                                      PASSWORD_HASH_ROUNDS)
        return f"{salt}:{pwd_hash.hex()}"
    
    def verify_password(self, password: str, password_hash: str) -> bool:
        """Verify password against hash"""
        try:
            salt, hash_hex = password_hash.split(':')
            pwd_hash = hashlib.pbkdf2_hmac('sha256',
                                          password.encode('utf-8'),
                                          salt.encode('utf-8'),
                                          PASSWORD_HASH_ROUNDS)
            return pwd_hash.hex() == hash_hex
        except Exception as e:
            logger.error(f"Password verification error: {e}")
            return False
    
    def create_admin(self, email: str, password: str, name: str, role: str = 'admin') -> bool:
        """Create new admin user"""
        try:
            with sqlite3.connect(self.db_path) as conn:
                cursor = conn.cursor()
                
                # Check if admin already exists
                cursor.execute('SELECT id FROM admin_users WHERE email = ?', (email,))
                if cursor.fetchone():
                    logger.warning(f"Admin with email {email} already exists")
                    return False
                
                # Create admin
                password_hash = self.hash_password(password)
                cursor.execute('''
                    INSERT INTO admin_users (email, password_hash, name, role)
                    VALUES (?, ?, ?, ?)
                ''', (email, password_hash, name, role))
                
                admin_id = cursor.lastrowid
                
                # Log admin creation
                cursor.execute('''
                    INSERT INTO admin_logs (admin_id, action, details)
                    VALUES (?, ?, ?)
                ''', (admin_id, 'ADMIN_CREATED', f"Admin {email} created"))
                
                conn.commit()
                logger.info(f"Admin {email} created successfully")
                return True
                
        except Exception as e:
            logger.error(f"Failed to create admin {email}: {e}")
            return False
    
    def authenticate_admin(self, email: str, password: str, ip_address: str = None) -> Optional[Dict]:
        """Authenticate admin user"""
        try:
            with sqlite3.connect(self.db_path) as conn:
                cursor = conn.cursor()
                
                # Get admin user
                cursor.execute('''
                    SELECT id, email, password_hash, name, role, is_active, 
                           login_attempts, locked_until
                    FROM admin_users WHERE email = ?
                ''', (email,))
                
                admin = cursor.fetchone()
                if not admin:
                    logger.warning(f"Admin authentication failed: {email} not found")
                    return None
                
                admin_id, admin_email, password_hash, name, role, is_active, login_attempts, locked_until = admin
                
                # Check if account is locked
                if locked_until and datetime.fromisoformat(locked_until) > datetime.now():
                    logger.warning(f"Admin account {email} is locked")
                    return None
                
                # Check if account is active
                if not is_active:
                    logger.warning(f"Admin account {email} is inactive")
                    return None
                
                # Verify password
                if not self.verify_password(password, password_hash):
                    # Increment login attempts
                    cursor.execute('''
                        UPDATE admin_users 
                        SET login_attempts = login_attempts + 1,
                            locked_until = CASE 
                                WHEN login_attempts >= 4 THEN datetime('now', '+15 minutes')
                                ELSE locked_until
                            END
                        WHERE id = ?
                    ''', (admin_id,))
                    
                    # Log failed attempt
                    cursor.execute('''
                        INSERT INTO admin_logs (admin_id, action, details, ip_address)
                        VALUES (?, ?, ?, ?)
                    ''', (admin_id, 'LOGIN_FAILED', f"Failed login attempt", ip_address))
                    
                    conn.commit()
                    logger.warning(f"Admin authentication failed: {email} wrong password")
                    return None
                
                # Reset login attempts and update last login
                cursor.execute('''
                    UPDATE admin_users 
                    SET login_attempts = 0, locked_until = NULL, last_login = CURRENT_TIMESTAMP
                    WHERE id = ?
                ''', (admin_id,))
                
                # Log successful login
                cursor.execute('''
                    INSERT INTO admin_logs (admin_id, action, details, ip_address)
                    VALUES (?, ?, ?, ?)
                ''', (admin_id, 'LOGIN_SUCCESS', f"Successful login", ip_address))
                
                conn.commit()
                
                logger.info(f"Admin {email} authenticated successfully")
                return {
                    'id': admin_id,
                    'email': admin_email,
                    'name': name,
                    'role': role,
                    'is_active': bool(is_active)
                }
                
        except Exception as e:
            logger.error(f"Admin authentication error for {email}: {e}")
            return None
    
    def create_session(self, admin_id: int, ip_address: str = None, user_agent: str = None) -> str:
        """Create admin session"""
        try:
            session_id = secrets.token_urlsafe(32)
            expires_at = datetime.now() + timedelta(hours=1)
            
            with sqlite3.connect(self.db_path) as conn:
                cursor = conn.cursor()
                cursor.execute('''
                    INSERT INTO admin_sessions (id, admin_id, ip_address, user_agent, expires_at)
                    VALUES (?, ?, ?, ?, ?)
                ''', (session_id, admin_id, ip_address, user_agent, expires_at.isoformat()))
                conn.commit()
            
            logger.info(f"Session created for admin {admin_id}")
            return session_id
            
        except Exception as e:
            logger.error(f"Failed to create session for admin {admin_id}: {e}")
            return None
    
    def validate_session(self, session_id: str) -> Optional[Dict]:
        """Validate admin session"""
        try:
            with sqlite3.connect(self.db_path) as conn:
                cursor = conn.cursor()
                cursor.execute('''
                    SELECT s.id, s.admin_id, s.expires_at, u.email, u.name, u.role
                    FROM admin_sessions s
                    JOIN admin_users u ON s.admin_id = u.id
                    WHERE s.id = ? AND s.expires_at > datetime('now')
                ''', (session_id,))
                
                session = cursor.fetchone()
                if session:
                    return {
                        'session_id': session[0],
                        'admin_id': session[1],
                        'expires_at': session[2],
                        'email': session[3],
                        'name': session[4],
                        'role': session[5]
                    }
                return None
                
        except Exception as e:
            logger.error(f"Session validation error: {e}")
            return None
    
    def get_all_admins(self) -> List[Dict]:
        """Get all admin users"""
        try:
            with sqlite3.connect(self.db_path) as conn:
                cursor = conn.cursor()
                cursor.execute('''
                    SELECT id, email, name, role, is_active, last_login, created_at
                    FROM admin_users
                    ORDER BY created_at DESC
                ''')
                
                admins = []
                for row in cursor.fetchall():
                    admins.append({
                        'id': row[0],
                        'email': row[1],
                        'name': row[2],
                        'role': row[3],
                        'is_active': bool(row[4]),
                        'last_login': row[5],
                        'created_at': row[6]
                    })
                
                return admins
                
        except Exception as e:
            logger.error(f"Failed to get admins: {e}")
            return []
    
    def backup_database(self) -> bool:
        """Create backup of admin database"""
        try:
            import shutil
            shutil.copy2(self.db_path, self.backup_path)
            logger.info(f"Admin database backed up to {self.backup_path}")
            return True
        except Exception as e:
            logger.error(f"Failed to backup admin database: {e}")
            return False
    
    def restore_from_backup(self) -> bool:
        """Restore admin database from backup"""
        try:
            import shutil
            if Path(self.backup_path).exists():
                shutil.copy2(self.backup_path, self.db_path)
                logger.info(f"Admin database restored from backup")
                return True
            return False
        except Exception as e:
            logger.error(f"Failed to restore admin database: {e}")
            return False

# Initialize admin database with default admin
def initialize_default_admin():
    """Initialize with default admin account"""
    admin_db = AdminDatabase()
    
    # Create default admin if none exists
    admins = admin_db.get_all_admins()
    if not admins:
        admin_db.create_admin(
            email='admin@gmail.com',
            password='admin123',
            name='System Administrator',
            role='admin'
        )
        admin_db.create_admin(
            email='adminjed@gmail.com',
            password='jed12345',
            name='Jed Zapanta',
            role='admin'
        )
        logger.info("Default admin accounts created")

if __name__ == '__main__':
    initialize_default_admin()
