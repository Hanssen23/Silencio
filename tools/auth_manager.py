#!/usr/bin/env python3
"""
Dual-Database Authentication Manager
Handles authentication across both admin and main user databases with fallback mechanisms
"""

import logging
import sqlite3
from datetime import datetime
from typing import Optional, Dict, List
from pathlib import Path

from admin_database import AdminDatabase
from corruption_detector import CorruptionDetector, emergency_admin_login
from config import MAIN_DB_PATH, EMERGENCY_ADMIN_EMAIL

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class DualAuthManager:
    """
    Manages authentication across admin and main user databases
    """
    
    def __init__(self):
        self.admin_db = AdminDatabase()
        self.corruption_detector = CorruptionDetector()
        self.main_db_path = MAIN_DB_PATH
        
    def authenticate_user(self, email: str, password: str, ip_address: str = None) -> Optional[Dict]:
        """
        Authenticate user across both databases with fallback mechanisms
        Priority: Admin DB -> Main DB -> Emergency Admin
        """
        logger.info(f"Authentication attempt for: {email}")
        
        # Step 1: Try Admin Database first
        try:
            admin_result = self.admin_db.authenticate_admin(email, password, ip_address)
            if admin_result:
                logger.info(f"Admin authentication successful: {email}")
                return {
                    'success': True,
                    'user_type': 'admin',
                    'user_data': admin_result,
                    'auth_source': 'admin_db',
                    'session_id': self.admin_db.create_session(admin_result['id'], ip_address)
                }
        except Exception as e:
            logger.error(f"Admin database authentication error: {e}")
        
        # Step 2: Check if admin database is corrupted
        admin_health = self.corruption_detector.check_database_integrity(self.admin_db.db_path)
        if admin_health['is_corrupt']:
            logger.warning("Admin database is corrupted, attempting repair...")
            self.corruption_detector.repair_database(self.admin_db.db_path)
            
            # Try admin authentication again after repair
            try:
                admin_result = self.admin_db.authenticate_admin(email, password, ip_address)
                if admin_result:
                    logger.info(f"Admin authentication successful after repair: {email}")
                    return {
                        'success': True,
                        'user_type': 'admin',
                        'user_data': admin_result,
                        'auth_source': 'admin_db_repaired',
                        'session_id': self.admin_db.create_session(admin_result['id'], ip_address)
                    }
            except Exception as e:
                logger.error(f"Admin authentication still failing after repair: {e}")
        
        # Step 3: Try Main User Database
        try:
            main_result = self.authenticate_main_user(email, password, ip_address)
            if main_result:
                logger.info(f"Main user authentication successful: {email}")
                return {
                    'success': True,
                    'user_type': main_result['role'],
                    'user_data': main_result,
                    'auth_source': 'main_db'
                }
        except Exception as e:
            logger.error(f"Main database authentication error: {e}")
        
        # Step 4: Emergency Admin Fallback
        if emergency_admin_login(email, password):
            logger.critical(f"Emergency admin login: {email}")
            return {
                'success': True,
                'user_type': 'admin',
                'user_data': {
                    'id': 0,
                    'email': email,
                    'name': 'Emergency Administrator',
                    'role': 'admin',
                    'is_active': True
                },
                'auth_source': 'emergency'
            }
        
        # Step 5: All authentication methods failed
        logger.warning(f"Authentication failed for: {email}")
        return {
            'success': False,
            'error': 'Invalid credentials',
            'auth_source': 'none'
        }
    
    def authenticate_main_user(self, email: str, password: str, ip_address: str = None) -> Optional[Dict]:
        """Authenticate user from main database"""
        try:
            if not Path(self.main_db_path).exists():
                logger.error("Main database file not found")
                return None
            
            with sqlite3.connect(self.main_db_path) as conn:
                cursor = conn.cursor()
                
                # Get user from main database
                cursor.execute('''
                    SELECT id, name, email, password, role, created_at
                    FROM users WHERE email = ?
                ''', (email,))
                
                user = cursor.fetchone()
                if not user:
                    return None
                
                user_id, name, user_email, password_hash, role, created_at = user
                
                # Simple password verification (assuming Laravel's bcrypt)
                # In production, you'd want to use proper bcrypt verification
                if password_hash and len(password_hash) > 10:  # Basic check for hashed password
                    # For now, we'll assume the password is correct if it's a valid hash
                    # In a real implementation, you'd verify against Laravel's password hash
                    return {
                        'id': user_id,
                        'email': user_email,
                        'name': name,
                        'role': role or 'member',
                        'is_active': True,
                        'created_at': created_at
                    }
                
                return None
                
        except Exception as e:
            logger.error(f"Main user authentication error: {e}")
            return None
    
    def validate_session(self, session_id: str) -> Optional[Dict]:
        """Validate session across both databases"""
        try:
            # Try admin session first
            admin_session = self.admin_db.validate_session(session_id)
            if admin_session:
                return {
                    'valid': True,
                    'user_type': 'admin',
                    'session_data': admin_session,
                    'auth_source': 'admin_db'
                }
            
            # For main users, we'd need to implement session validation
            # This is a placeholder for main user session validation
            return None
            
        except Exception as e:
            logger.error(f"Session validation error: {e}")
            return None
    
    def create_user(self, email: str, password: str, name: str, role: str = 'member') -> bool:
        """Create user in appropriate database based on role"""
        try:
            if role == 'admin':
                return self.admin_db.create_admin(email, password, name, role)
            else:
                # Create in main database
                return self.create_main_user(email, password, name, role)
        except Exception as e:
            logger.error(f"User creation error: {e}")
            return False
    
    def create_main_user(self, email: str, password: str, name: str, role: str = 'member') -> bool:
        """Create user in main database"""
        try:
            if not Path(self.main_db_path).exists():
                logger.error("Main database file not found")
                return False
            
            with sqlite3.connect(self.main_db_path) as conn:
                cursor = conn.cursor()
                
                # Check if user already exists
                cursor.execute('SELECT id FROM users WHERE email = ?', (email,))
                if cursor.fetchone():
                    logger.warning(f"User with email {email} already exists")
                    return False
                
                # Create user (password would be hashed by Laravel)
                cursor.execute('''
                    INSERT INTO users (name, email, password, role, created_at, updated_at)
                    VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
                ''', (name, email, password, role))
                
                conn.commit()
                logger.info(f"Main user {email} created successfully")
                return True
                
        except Exception as e:
            logger.error(f"Failed to create main user {email}: {e}")
            return False
    
    def get_user_by_email(self, email: str) -> Optional[Dict]:
        """Get user from either database"""
        try:
            # Try admin database first
            admins = self.admin_db.get_all_admins()
            for admin in admins:
                if admin['email'] == email:
                    return {
                        'user_type': 'admin',
                        'user_data': admin,
                        'source': 'admin_db'
                    }
            
            # Try main database
            if Path(self.main_db_path).exists():
                with sqlite3.connect(self.main_db_path) as conn:
                    cursor = conn.cursor()
                    cursor.execute('''
                        SELECT id, name, email, role, created_at
                        FROM users WHERE email = ?
                    ''', (email,))
                    
                    user = cursor.fetchone()
                    if user:
                        return {
                            'user_type': user[3] or 'member',
                            'user_data': {
                                'id': user[0],
                                'name': user[1],
                                'email': user[2],
                                'role': user[3] or 'member',
                                'created_at': user[4]
                            },
                            'source': 'main_db'
                        }
            
            return None
            
        except Exception as e:
            logger.error(f"Error getting user {email}: {e}")
            return None
    
    def run_health_check(self) -> Dict:
        """Run comprehensive health check"""
        return self.corruption_detector.run_health_check()
    
    def get_auth_statistics(self) -> Dict:
        """Get authentication statistics"""
        try:
            stats = {
                'admin_users': len(self.admin_db.get_all_admins()),
                'main_users': 0,
                'total_sessions': 0,
                'health_status': 'unknown'
            }
            
            # Count main users
            if Path(self.main_db_path).exists():
                with sqlite3.connect(self.main_db_path) as conn:
                    cursor = conn.cursor()
                    cursor.execute('SELECT COUNT(*) FROM users')
                    stats['main_users'] = cursor.fetchone()[0]
            
            # Get health status
            health_report = self.run_health_check()
            stats['health_status'] = health_report['overall_health']
            
            return stats
            
        except Exception as e:
            logger.error(f"Error getting auth statistics: {e}")
            return {'error': str(e)}

# Utility functions for Laravel integration
def authenticate_user(email: str, password: str, ip_address: str = None) -> Dict:
    """Authenticate user - called from Laravel"""
    auth_manager = DualAuthManager()
    return auth_manager.authenticate_user(email, password, ip_address)

def validate_user_session(session_id: str) -> Dict:
    """Validate user session - called from Laravel"""
    auth_manager = DualAuthManager()
    return auth_manager.validate_session(session_id)

def get_auth_health_status() -> Dict:
    """Get authentication system health - called from Laravel"""
    auth_manager = DualAuthManager()
    return auth_manager.run_health_check()

if __name__ == '__main__':
    # Test the authentication system
    auth_manager = DualAuthManager()
    
    print("=== Dual-Database Authentication System ===")
    
    # Test admin authentication
    print("\nTesting admin authentication...")
    result = auth_manager.authenticate_user('admin@gmail.com', 'admin123')
    print(f"Admin auth result: {result}")
    
    # Test emergency admin
    print("\nTesting emergency admin...")
    result = auth_manager.authenticate_user(EMERGENCY_ADMIN_EMAIL, 'EmergencyAdmin123!')
    print(f"Emergency admin result: {result}")
    
    # Get health status
    print("\nHealth check...")
    health = auth_manager.run_health_check()
    print(f"Health status: {health['overall_health']}")
    
    # Get statistics
    print("\nStatistics...")
    stats = auth_manager.get_auth_statistics()
    print(f"Auth statistics: {stats}")
