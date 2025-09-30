#!/usr/bin/env python3
"""
Database Corruption Detection and Recovery System
Monitors both admin and main databases for corruption and provides recovery mechanisms
"""

import sqlite3
import logging
import hashlib
import json
from datetime import datetime, timedelta
from pathlib import Path
from typing import Dict, List, Optional, Tuple
import subprocess
import os

from config import ADMIN_DB_PATH, BACKUP_DB_PATH, MAIN_DB_PATH, EMERGENCY_ADMIN_EMAIL, EMERGENCY_ADMIN_PASSWORD

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class CorruptionDetector:
    """
    Detects and handles database corruption issues
    """
    
    def __init__(self):
        self.admin_db_path = ADMIN_DB_PATH
        self.backup_db_path = BACKUP_DB_PATH
        self.main_db_path = MAIN_DB_PATH
        self.corruption_log = Path(__file__).parent / 'corruption_log.json'
        
    def check_database_integrity(self, db_path: str) -> Dict:
        """
        Check database integrity using SQLite PRAGMA commands
        Returns: {'is_corrupt': bool, 'errors': list, 'integrity_score': float}
        """
        result = {
            'is_corrupt': False,
            'errors': [],
            'integrity_score': 1.0,
            'checked_at': datetime.now().isoformat()
        }
        
        try:
            with sqlite3.connect(db_path) as conn:
                cursor = conn.cursor()
                
                # Check database integrity
                cursor.execute("PRAGMA integrity_check")
                integrity_result = cursor.fetchone()[0]
                
                if integrity_result != "ok":
                    result['is_corrupt'] = True
                    result['errors'].append(f"Integrity check failed: {integrity_result}")
                    result['integrity_score'] = 0.0
                
                # Check foreign key constraints
                cursor.execute("PRAGMA foreign_key_check")
                fk_errors = cursor.fetchall()
                if fk_errors:
                    result['is_corrupt'] = True
                    result['errors'].extend([f"Foreign key error: {error}" for error in fk_errors])
                    result['integrity_score'] -= 0.2
                
                # Check table structure
                cursor.execute("SELECT name FROM sqlite_master WHERE type='table'")
                tables = cursor.fetchall()
                
                for table in tables:
                    table_name = table[0]
                    try:
                        cursor.execute(f"SELECT COUNT(*) FROM {table_name}")
                        cursor.fetchone()
                    except sqlite3.DatabaseError as e:
                        result['is_corrupt'] = True
                        result['errors'].append(f"Table {table_name} error: {str(e)}")
                        result['integrity_score'] -= 0.1
                
                # Ensure integrity score doesn't go below 0
                result['integrity_score'] = max(0.0, result['integrity_score'])
                
        except sqlite3.DatabaseError as e:
            result['is_corrupt'] = True
            result['errors'].append(f"Database connection error: {str(e)}")
            result['integrity_score'] = 0.0
        except Exception as e:
            result['errors'].append(f"Unexpected error: {str(e)}")
            result['integrity_score'] = 0.5
        
        return result
    
    def backup_database(self, source_path: str, backup_path: str) -> bool:
        """Create backup of database"""
        try:
            import shutil
            shutil.copy2(source_path, backup_path)
            logger.info(f"Database backed up: {source_path} -> {backup_path}")
            return True
        except Exception as e:
            logger.error(f"Backup failed: {e}")
            return False
    
    def restore_database(self, backup_path: str, target_path: str) -> bool:
        """Restore database from backup"""
        try:
            if not Path(backup_path).exists():
                logger.error(f"Backup file not found: {backup_path}")
                return False
            
            import shutil
            shutil.copy2(backup_path, target_path)
            logger.info(f"Database restored: {backup_path} -> {target_path}")
            return True
        except Exception as e:
            logger.error(f"Restore failed: {e}")
            return False
    
    def repair_database(self, db_path: str) -> bool:
        """Attempt to repair corrupted database"""
        try:
            # Create backup before repair
            backup_path = f"{db_path}.repair_backup"
            self.backup_database(db_path, backup_path)
            
            # Try to dump and restore
            temp_dump = f"{db_path}.dump"
            
            # Dump database to SQL
            with open(temp_dump, 'w') as f:
                subprocess.run(['sqlite3', db_path, '.dump'], stdout=f, check=True)
            
            # Remove corrupted database
            os.remove(db_path)
            
            # Restore from dump
            with open(temp_dump, 'r') as f:
                subprocess.run(['sqlite3', db_path], stdin=f, check=True)
            
            # Clean up
            os.remove(temp_dump)
            
            logger.info(f"Database repaired: {db_path}")
            return True
            
        except Exception as e:
            logger.error(f"Repair failed: {e}")
            # Restore from backup if repair failed
            if Path(backup_path).exists():
                self.restore_database(backup_path, db_path)
            return False
    
    def create_emergency_admin(self) -> bool:
        """Create emergency admin account in case of corruption"""
        try:
            from admin_database import AdminDatabase
            
            # Try to initialize admin database
            admin_db = AdminDatabase()
            
            # Create emergency admin
            success = admin_db.create_admin(
                email=EMERGENCY_ADMIN_EMAIL,
                password=EMERGENCY_ADMIN_PASSWORD,
                name='Emergency Administrator',
                role='admin'
            )
            
            if success:
                logger.info("Emergency admin account created")
                return True
            else:
                logger.warning("Emergency admin account already exists")
                return True
                
        except Exception as e:
            logger.error(f"Failed to create emergency admin: {e}")
            return False
    
    def log_corruption_event(self, db_path: str, corruption_info: Dict):
        """Log corruption events for analysis"""
        try:
            log_entry = {
                'timestamp': datetime.now().isoformat(),
                'database': db_path,
                'corruption_info': corruption_info
            }
            
            # Load existing log
            if self.corruption_log.exists():
                with open(self.corruption_log, 'r') as f:
                    log_data = json.load(f)
            else:
                log_data = {'events': []}
            
            # Add new entry
            log_data['events'].append(log_entry)
            
            # Keep only last 100 events
            if len(log_data['events']) > 100:
                log_data['events'] = log_data['events'][-100:]
            
            # Save log
            with open(self.corruption_log, 'w') as f:
                json.dump(log_data, f, indent=2)
                
        except Exception as e:
            logger.error(f"Failed to log corruption event: {e}")
    
    def get_corruption_history(self) -> List[Dict]:
        """Get corruption history"""
        try:
            if self.corruption_log.exists():
                with open(self.corruption_log, 'r') as f:
                    return json.load(f).get('events', [])
            return []
        except Exception as e:
            logger.error(f"Failed to get corruption history: {e}")
            return []
    
    def run_health_check(self) -> Dict:
        """Run comprehensive health check on all databases"""
        health_report = {
            'timestamp': datetime.now().isoformat(),
            'admin_db': None,
            'main_db': None,
            'overall_health': 'healthy',
            'recommendations': []
        }
        
        # Check admin database
        if Path(self.admin_db_path).exists():
            admin_check = self.check_database_integrity(self.admin_db_path)
            health_report['admin_db'] = admin_check
            
            if admin_check['is_corrupt']:
                health_report['overall_health'] = 'critical'
                health_report['recommendations'].append('Admin database is corrupted - immediate action required')
                
                # Log corruption event
                self.log_corruption_event(self.admin_db_path, admin_check)
                
                # Attempt repair
                if self.repair_database(self.admin_db_path):
                    health_report['recommendations'].append('Admin database repair attempted')
                else:
                    health_report['recommendations'].append('Admin database repair failed - restore from backup')
        else:
            health_report['admin_db'] = {'is_corrupt': True, 'errors': ['Database file not found']}
            health_report['overall_health'] = 'critical'
            health_report['recommendations'].append('Admin database missing - recreate required')
        
        # Check main database
        if Path(self.main_db_path).exists():
            main_check = self.check_database_integrity(self.main_db_path)
            health_report['main_db'] = main_check
            
            if main_check['is_corrupt']:
                if health_report['overall_health'] == 'healthy':
                    health_report['overall_health'] = 'warning'
                health_report['recommendations'].append('Main database has integrity issues')
                
                # Log corruption event
                self.log_corruption_event(self.main_db_path, main_check)
        else:
            health_report['main_db'] = {'is_corrupt': True, 'errors': ['Database file not found']}
            if health_report['overall_health'] == 'healthy':
                health_report['overall_health'] = 'warning'
            health_report['recommendations'].append('Main database missing')
        
        # Create emergency admin if needed
        if health_report['overall_health'] == 'critical':
            self.create_emergency_admin()
            health_report['recommendations'].append('Emergency admin account created')
        
        return health_report
    
    def schedule_health_checks(self):
        """Schedule regular health checks (to be run as cron job)"""
        health_report = self.run_health_check()
        
        # Log health report
        logger.info(f"Health check completed: {health_report['overall_health']}")
        
        # Send alerts if critical
        if health_report['overall_health'] == 'critical':
            logger.critical("CRITICAL: Database corruption detected!")
            for recommendation in health_report['recommendations']:
                logger.critical(f"Action required: {recommendation}")

# Utility functions for Laravel integration
def check_admin_db_health() -> Dict:
    """Check admin database health - called from Laravel"""
    detector = CorruptionDetector()
    return detector.run_health_check()

def emergency_admin_login(email: str, password: str) -> bool:
    """Emergency admin login - bypasses normal authentication"""
    return (email == EMERGENCY_ADMIN_EMAIL and 
            password == EMERGENCY_ADMIN_PASSWORD)

if __name__ == '__main__':
    detector = CorruptionDetector()
    health_report = detector.run_health_check()
    
    print("=== Database Health Report ===")
    print(f"Overall Health: {health_report['overall_health']}")
    print(f"Timestamp: {health_report['timestamp']}")
    
    if health_report['admin_db']:
        print(f"\nAdmin DB: {'CORRUPT' if health_report['admin_db']['is_corrupt'] else 'HEALTHY'}")
        if health_report['admin_db']['errors']:
            for error in health_report['admin_db']['errors']:
                print(f"  - {error}")
    
    if health_report['main_db']:
        print(f"\nMain DB: {'CORRUPT' if health_report['main_db']['is_corrupt'] else 'HEALTHY'}")
        if health_report['main_db']['errors']:
            for error in health_report['main_db']['errors']:
                print(f"  - {error}")
    
    if health_report['recommendations']:
        print("\nRecommendations:")
        for rec in health_report['recommendations']:
            print(f"  - {rec}")
