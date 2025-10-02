#!/usr/bin/env python3
"""
ACR122U RFID Reader for Silencio Gym Management System
A complete, production-ready RFID reader implementation
"""

import time
import json
import requests
import logging
from datetime import datetime
from smartcard.System import readers
from smartcard.util import toHexString
from smartcard.Exceptions import CardConnectionException, NoCardException
import smartcard

class ACR122UReader:
    def __init__(self, config_file="rfid_config.json"):
        """Initialize the ACR122U RFID reader"""
        self.config = self.load_config(config_file)
        self.api_url = self.config.get('api', {}).get('url', 'http://localhost:8000')
        self.device_id = self.config.get('reader', {}).get('device_id', 'acr122u_main')
        self.read_delay = self.config.get('reader', {}).get('read_delay', 0.5)
        self.duplicate_prevention = self.config.get('reader', {}).get('duplicate_prevention_seconds', 2)
        
        self.connection = None
        self.reader = None
        self.last_card_uid = None
        self.last_card_time = 0
        
        # Two-tap system state tracking
        self.card_states = {}  # Track state for each card UID
        self.current_card_on_reader = None  # Track which card is currently on reader
        
        # Setup logging
        self.setup_logging()
        
        # Initialize reader
        self.initialize_reader()
    
    def load_config(self, config_file):
        """Load configuration from JSON file"""
        try:
            with open(config_file, 'r') as f:
                return json.load(f)
        except FileNotFoundError:
            # Default configuration
            return {
                "api": {
                    "url": "http://localhost:8000",
                    "endpoint": "/api/rfid/tap",
                    "timeout": 5
                },
                "reader": {
                    "device_id": "acr122u_main",
                    "read_delay": 0.5,
                    "duplicate_prevention_seconds": 2
                },
                "feedback": {
                    "success_sound": True,
                    "error_sound": True,
                    "led_indicator": True
                },
                "logging": {
                    "enabled": True,
                    "log_file": "rfid_activity.log",
                    "log_level": "INFO"
                }
            }
    
    def setup_logging(self):
        """Setup logging configuration"""
        if self.config.get('logging', {}).get('enabled', True):
            log_file = self.config.get('logging', {}).get('log_file', 'rfid_activity.log')
            log_level = self.config.get('logging', {}).get('log_level', 'INFO')
            
            logging.basicConfig(
                level=getattr(logging, log_level.upper()),
                format='%(asctime)s - %(levelname)s - %(message)s',
                handlers=[
                    logging.FileHandler(log_file),
                    logging.StreamHandler()
                ]
            )
            self.logger = logging.getLogger(__name__)
        else:
            self.logger = logging.getLogger(__name__)
            self.logger.disabled = True
    
    def initialize_reader(self):
        """Initialize the ACR122U reader"""
        try:
            available_readers = readers()
            if not available_readers:
                raise Exception("No smart card readers found")
            
            # Find ACR122U reader
            acr122u_reader = None
            for reader in available_readers:
                if 'ACR122' in str(reader):
                    acr122u_reader = reader
                    break
            
            if not acr122u_reader:
                raise Exception("ACR122U reader not found")
            
            self.reader = acr122u_reader
            self.logger.info(f"ACR122U reader initialized: {self.reader}")
            
        except Exception as e:
            self.logger.error(f"Failed to initialize ACR122U reader: {e}")
            raise
    
    def connect(self):
        """Connect to the ACR122U reader with forced T0 protocol"""
        try:
            if not self.reader:
                raise Exception("Reader not initialized")
            
            self.connection = self.reader.createConnection()
            # Force T0 protocol for ACR122U compatibility
            self.connection.connect(protocol=0)
            self.logger.info("Connected to ACR122U reader with T0 protocol")
            return True
            
        except Exception as e:
            self.logger.error(f"Failed to connect to reader: {e}")
            return False
    
    def disconnect(self):
        """Disconnect from the ACR122U reader"""
        try:
            if self.connection:
                self.connection.disconnect()
                self.connection = None
                self.logger.info("Disconnected from ACR122U reader")
        except Exception as e:
            self.logger.error(f"Error disconnecting from reader: {e}")
    
    def get_card_uid(self):
        """Read the UID of the card on the reader"""
        try:
            if not self.connection:
                if not self.connect():
                    return None
            
            # Get UID command for ACR122U (using RAW protocol)
            get_uid_command = [0xFF, 0xCA, 0x00, 0x00, 0x00]
            data, sw1, sw2 = self.connection.transmit(get_uid_command)
            
            if sw1 == 0x90 and sw2 == 0x00:
                uid = toHexString(data).replace(' ', '')
                self.logger.info(f"Card UID read: {uid}")
                return uid
            else:
                # Try alternative UID command
                alt_command = [0xFF, 0xCA, 0x00, 0x00, 0x04]
                data, sw1, sw2 = self.connection.transmit(alt_command)
                
                if sw1 == 0x90 and sw2 == 0x00:
                    uid = toHexString(data).replace(' ', '')
                    self.logger.info(f"Card UID read (alt): {uid}")
                        return uid
                else:
                    self.logger.warning(f"Failed to read card UID. Status: {sw1:02X} {sw2:02X}")
                        return None
                        
        except NoCardException:
            # No card present - this is normal
            return None
        except CardConnectionException as e:
            self.logger.error(f"Card connection error: {e}")
            self.handle_protocol_error()
            return None
        except Exception as e:
            self.logger.error(f"Error reading card UID: {e}")
            return None
    
    def is_duplicate_card(self, card_uid):
        """Check if this is a duplicate card read within the prevention window"""
        current_time = time.time()
        
        # If same card is still on reader, prevent duplicate processing
        if (self.last_card_uid == card_uid and 
            current_time - self.last_card_time < self.duplicate_prevention):
            return True
            
        self.last_card_uid = card_uid
        self.last_card_time = current_time
            return False

    def get_card_state(self, card_uid):
        """Get the current state of a card (None, 'first_tap', 'second_tap')"""
        return self.card_states.get(card_uid, None)
    
    def set_card_state(self, card_uid, state):
        """Set the state of a card"""
        self.card_states[card_uid] = state
        self.logger.info(f"Card {card_uid} state set to: {state}")
    
    def handle_card_tap(self, card_uid):
        """Handle a card tap with two-tap logic"""
        current_state = self.get_card_state(card_uid)
        
        if current_state is None:
            # First tap - check in
            self.set_card_state(card_uid, 'first_tap')
            self.logger.info(f"First tap detected for card {card_uid} - Check-in")
            return True
        elif current_state == 'first_tap':
            # Second tap - check out
            self.set_card_state(card_uid, 'second_tap')
            self.logger.info(f"Second tap detected for card {card_uid} - Check-out")
                        return True
                    else:
            # Already completed session, ignore
            self.logger.info(f"Card {card_uid} already completed session, ignoring tap")
                    return False
                    
    def reset_card_state(self, card_uid):
        """Reset card state when card is removed"""
        if card_uid in self.card_states:
            del self.card_states[card_uid]
            self.logger.info(f"Card {card_uid} state reset (card removed)")
    
    def detect_card_removal(self, card_uid):
        """Detect if card has been removed from reader"""
        if self.current_card_on_reader == card_uid:
            # Card was on reader, now it's not - it was removed
            self.current_card_on_reader = None
            self.reset_card_state(card_uid)
            self.logger.info(f"Card {card_uid} removed from reader")
            return True
                return False
                
    def handle_protocol_error(self):
        """Handle protocol errors by reconnecting with T0"""
        self.logger.warning("Protocol error detected, reconnecting with T0...")
        try:
            self.disconnect()
            self.connect()
            self.logger.info("Successfully reconnected with T0 protocol")
        except Exception as e:
            self.logger.error(f"Failed to reconnect: {e}")
    
    def send_to_api(self, card_uid):
        """Send card data to Laravel API"""
        try:
            data = {
                'card_uid': card_uid,
                'device_id': self.device_id
            }
            
            headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
            
            endpoint = self.config.get('api', {}).get('endpoint', '/api/rfid/tap')
            url = f"{self.api_url.rstrip('/')}{endpoint}"
            timeout = self.config.get('api', {}).get('timeout', 5)
            
            self.logger.info(f"Sending card data to API: {url}")
            
            response = requests.post(url, json=data, headers=headers, timeout=timeout)
            
            if response.status_code == 200:
                result = response.json()
                self.logger.info(f"API Success: {result.get('message', 'Success')}")
                return True
            else:
                self.logger.error(f"API Error: {response.status_code}")
                if response.text:
                    try:
                        error = response.json()
                        self.logger.error(f"API Error Message: {error.get('message', 'Unknown error')}")
                    except:
                        self.logger.error(f"API Error Response: {response.text}")
                return False
                
        except requests.exceptions.RequestException as e:
            self.logger.error(f"Network error: {e}")
            return False
        except Exception as e:
            self.logger.error(f"Error sending to API: {e}")
            return False
    
    def run(self):
        """Main loop for reading cards with two-tap system"""
        self.logger.info("Starting ACR122U RFID reader with two-tap system...")
        self.logger.info(f"API URL: {self.api_url}")
        self.logger.info(f"Device ID: {self.device_id}")
        self.logger.info("Waiting for cards...")
        self.logger.info("System: Each card requires 2 taps to complete a session")
        
        try:
            while True:
                try:
                    # Try to read a card
                    card_uid = self.get_card_uid()
                    
                    if card_uid:
                        # Check if this is a new card on the reader
                        if self.current_card_on_reader != card_uid:
                            # New card detected
                            if self.current_card_on_reader:
                                # Previous card was removed
                                self.detect_card_removal(self.current_card_on_reader)
                            
                            self.current_card_on_reader = card_uid
                            
                            # Check for duplicates (prevent rapid re-reading)
                            if self.is_duplicate_card(card_uid):
                                self.logger.info(f"Duplicate card read prevented: {card_uid}")
                                continue
                            
                            # Handle the card tap with two-tap logic
                            should_process = self.handle_card_tap(card_uid)
                            
                            if should_process:
                                # Send to API
                                self.logger.info(f"Processing card: {card_uid}")
                                success = self.send_to_api(card_uid)
                                
                                if success:
                                    self.logger.info(f"Card processed successfully: {card_uid}")
                                else:
                                    self.logger.error(f"Failed to process card: {card_uid}")
                            else:
                                self.logger.info(f"Card {card_uid} tap ignored (session already completed)")
                        else:
                            # Same card still on reader - ignore
                            self.logger.debug(f"Card {card_uid} still on reader, ignoring")
                    else:
                        # No card detected
                        if self.current_card_on_reader:
                            # Card was removed
                            self.detect_card_removal(self.current_card_on_reader)
                    
                    # Wait before next read
                    time.sleep(self.read_delay)
                    
                except KeyboardInterrupt:
                    self.logger.info("Received interrupt signal, shutting down...")
                    break
                except Exception as e:
                    self.logger.error(f"Error in main loop: {e}")
                    time.sleep(1)  # Wait before retrying
                    
        finally:
            self.disconnect()
            self.logger.info("ACR122U RFID reader stopped")

def main():
    """Main entry point"""
    try:
        reader = ACR122UReader()
    reader.run()
    except Exception as e:
        print(f"Failed to start RFID reader: {e}")
        return 1
    
    return 0

if __name__ == "__main__":
    exit(main())
