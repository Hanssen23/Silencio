#!/usr/bin/env python3
"""
Admin Authentication API
REST API for admin operations and authentication
"""

from flask import Flask, request, jsonify, session
from flask_cors import CORS
import logging
from datetime import datetime
from typing import Dict, Optional

from auth_manager import DualAuthManager
from admin_database import AdminDatabase
from corruption_detector import CorruptionDetector
from config import API_HOST, API_PORT, API_DEBUG

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Initialize Flask app
app = Flask(__name__)
app.secret_key = 'your-secret-key-change-in-production'
CORS(app)

# Initialize managers
auth_manager = DualAuthManager()
admin_db = AdminDatabase()
corruption_detector = CorruptionDetector()

@app.route('/api/auth/login', methods=['POST'])
def login():
    """Authenticate user"""
    try:
        data = request.get_json()
        email = data.get('email')
        password = data.get('password')
        ip_address = request.remote_addr
        
        if not email or not password:
            return jsonify({
                'success': False,
                'error': 'Email and password are required'
            }), 400
        
        # Authenticate user
        auth_result = auth_manager.authenticate_user(email, password, ip_address)
        
        if auth_result['success']:
            # Store session information
            session['user_id'] = auth_result['user_data']['id']
            session['user_email'] = auth_result['user_data']['email']
            session['user_type'] = auth_result['user_type']
            session['auth_source'] = auth_result['auth_source']
            
            if 'session_id' in auth_result:
                session['admin_session_id'] = auth_result['session_id']
            
            return jsonify({
                'success': True,
                'user': auth_result['user_data'],
                'user_type': auth_result['user_type'],
                'auth_source': auth_result['auth_source']
            })
        else:
            return jsonify({
                'success': False,
                'error': auth_result.get('error', 'Authentication failed')
            }), 401
            
    except Exception as e:
        logger.error(f"Login error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/auth/logout', methods=['POST'])
def logout():
    """Logout user"""
    try:
        # Clear session
        session.clear()
        
        return jsonify({
            'success': True,
            'message': 'Logged out successfully'
        })
        
    except Exception as e:
        logger.error(f"Logout error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/auth/validate', methods=['GET'])
def validate_session():
    """Validate current session"""
    try:
        if 'user_id' not in session:
            return jsonify({
                'success': False,
                'error': 'No active session'
            }), 401
        
        # Validate session
        if 'admin_session_id' in session:
            session_result = auth_manager.validate_session(session['admin_session_id'])
            if not session_result:
                session.clear()
                return jsonify({
                    'success': False,
                    'error': 'Session expired'
                }), 401
        
        return jsonify({
            'success': True,
            'user': {
                'id': session['user_id'],
                'email': session['user_email'],
                'type': session['user_type']
            },
            'auth_source': session.get('auth_source', 'unknown')
        })
        
    except Exception as e:
        logger.error(f"Session validation error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/admin/users', methods=['GET'])
def get_admin_users():
    """Get all admin users"""
    try:
        # Check if user is admin
        if session.get('user_type') != 'admin':
            return jsonify({
                'success': False,
                'error': 'Admin access required'
            }), 403
        
        admins = admin_db.get_all_admins()
        return jsonify({
            'success': True,
            'admins': admins
        })
        
    except Exception as e:
        logger.error(f"Get admin users error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/admin/users', methods=['POST'])
def create_admin_user():
    """Create new admin user"""
    try:
        # Check if user is admin
        if session.get('user_type') != 'admin':
            return jsonify({
                'success': False,
                'error': 'Admin access required'
            }), 403
        
        data = request.get_json()
        email = data.get('email')
        password = data.get('password')
        name = data.get('name')
        role = data.get('role', 'admin')
        
        if not all([email, password, name]):
            return jsonify({
                'success': False,
                'error': 'Email, password, and name are required'
            }), 400
        
        success = admin_db.create_admin(email, password, name, role)
        
        if success:
            return jsonify({
                'success': True,
                'message': 'Admin user created successfully'
            })
        else:
            return jsonify({
                'success': False,
                'error': 'Failed to create admin user'
            }), 500
            
    except Exception as e:
        logger.error(f"Create admin user error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/health', methods=['GET'])
def health_check():
    """Get system health status"""
    try:
        health_report = auth_manager.run_health_check()
        return jsonify({
            'success': True,
            'health': health_report
        })
        
    except Exception as e:
        logger.error(f"Health check error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/stats', methods=['GET'])
def get_statistics():
    """Get authentication statistics"""
    try:
        stats = auth_manager.get_auth_statistics()
        return jsonify({
            'success': True,
            'statistics': stats
        })
        
    except Exception as e:
        logger.error(f"Statistics error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/emergency/admin', methods=['POST'])
def emergency_admin():
    """Emergency admin access"""
    try:
        data = request.get_json()
        email = data.get('email')
        password = data.get('password')
        
        if email == 'emergency@admin.com' and password == 'EmergencyAdmin123!':
            # Create emergency session
            session['user_id'] = 0
            session['user_email'] = email
            session['user_type'] = 'admin'
            session['auth_source'] = 'emergency'
            
            return jsonify({
                'success': True,
                'user': {
                    'id': 0,
                    'email': email,
                    'name': 'Emergency Administrator',
                    'role': 'admin'
                },
                'user_type': 'admin',
                'auth_source': 'emergency'
            })
        else:
            return jsonify({
                'success': False,
                'error': 'Invalid emergency credentials'
            }), 401
            
    except Exception as e:
        logger.error(f"Emergency admin error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/corruption/repair', methods=['POST'])
def repair_databases():
    """Repair corrupted databases"""
    try:
        # Check if user is admin
        if session.get('user_type') != 'admin':
            return jsonify({
                'success': False,
                'error': 'Admin access required'
            }), 403
        
        repair_results = []
        
        # Repair admin database
        admin_repaired = corruption_detector.repair_database(admin_db.db_path)
        repair_results.append({
            'database': 'admin',
            'repaired': admin_repaired
        })
        
        # Repair main database
        main_repaired = corruption_detector.repair_database(auth_manager.main_db_path)
        repair_results.append({
            'database': 'main',
            'repaired': main_repaired
        })
        
        return jsonify({
            'success': True,
            'repair_results': repair_results
        })
        
    except Exception as e:
        logger.error(f"Database repair error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.route('/api/corruption/history', methods=['GET'])
def get_corruption_history():
    """Get corruption history"""
    try:
        # Check if user is admin
        if session.get('user_type') != 'admin':
            return jsonify({
                'success': False,
                'error': 'Admin access required'
            }), 403
        
        history = corruption_detector.get_corruption_history()
        return jsonify({
            'success': True,
            'corruption_history': history
        })
        
    except Exception as e:
        logger.error(f"Corruption history error: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error'
        }), 500

@app.errorhandler(404)
def not_found(error):
    return jsonify({
        'success': False,
        'error': 'Endpoint not found'
    }), 404

@app.errorhandler(500)
def internal_error(error):
    return jsonify({
        'success': False,
        'error': 'Internal server error'
    }), 500

if __name__ == '__main__':
    logger.info(f"Starting Admin Authentication API on {API_HOST}:{API_PORT}")
    app.run(host=API_HOST, port=API_PORT, debug=API_DEBUG)
