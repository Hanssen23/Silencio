#!/usr/bin/env python3
"""
RFID Reader Debug Script
Diagnoses issues with ACR122U reader and card detection
"""

import time
import json
from smartcard.System import readers
from smartcard.util import toHexString
from smartcard.Exceptions import CardConnectionException, NoCardException

def test_readers():
    """Test available smart card readers"""
    print("=== Testing Smart Card Readers ===")
    
    try:
        available_readers = readers()
        print(f"Found {len(available_readers)} reader(s)")
        
        for i, reader in enumerate(available_readers):
            print(f"Reader {i}: {reader}")
            
            # Test connection
            try:
                connection = reader.createConnection()
                print(f"  [OK] Connection created successfully")
                
                # Try different protocols
                protocols = [
                    (0, "T0"),
                    (1, "T1"), 
                    (2, "RAW")
                ]
                
                for protocol_id, protocol_name in protocols:
                    try:
                        connection.connect(protocol=protocol_id)
                        print(f"  [OK] Connected with {protocol_name} protocol")
                        
                        # Test UID reading
                        try:
                            get_uid_command = [0xFF, 0xCA, 0x00, 0x00, 0x00]
                            data, sw1, sw2 = connection.transmit(get_uid_command)
                            
                            if sw1 == 0x90 and sw2 == 0x00:
                                uid = toHexString(data).replace(' ', '')
                                print(f"  [OK] UID read successfully: {uid}")
                            else:
                                print(f"  [WARN] UID read failed. Status: {sw1:02X} {sw2:02X}")
                                
                        except Exception as e:
                            print(f"  [ERROR] UID read error: {e}")
                        
                        connection.disconnect()
                        break
                        
                    except Exception as e:
                        print(f"  [ERROR] {protocol_name} protocol failed: {e}")
                        
            except Exception as e:
                print(f"  [ERROR] Connection failed: {e}")
                
    except Exception as e:
        print(f"[ERROR] Error listing readers: {e}")

def test_card_detection():
    """Test card detection with different methods"""
    print("\n=== Testing Card Detection ===")
    
    try:
        available_readers = readers()
        if not available_readers:
            print("[ERROR] No readers found")
            return
            
        reader = available_readers[0]
        print(f"Using reader: {reader}")
        
        connection = reader.createConnection()
        
        # Try RAW protocol first (most compatible with ACR122U)
        try:
            connection.connect(protocol=2)  # RAW protocol
            print("[OK] Connected with RAW protocol")
            
            print("Place an RFID card on the reader...")
            print("Waiting 10 seconds for card detection...")
            
            for i in range(10):
                try:
                    # Method 1: Standard UID command
                    get_uid_command = [0xFF, 0xCA, 0x00, 0x00, 0x00]
                    data, sw1, sw2 = connection.transmit(get_uid_command)
                    
                    if sw1 == 0x90 and sw2 == 0x00:
                        uid = toHexString(data).replace(' ', '')
                        print(f"[OK] Card detected! UID: {uid}")
                        return uid
                    else:
                        print(f"Attempt {i+1}: No card (Status: {sw1:02X} {sw2:02X})")
                        
                except NoCardException:
                    print(f"Attempt {i+1}: No card present")
                except Exception as e:
                    print(f"Attempt {i+1}: Error - {e}")
                    
                time.sleep(1)
                
        except Exception as e:
            print(f"[ERROR] RAW protocol failed: {e}")
            
            # Try T1 protocol
            try:
                connection.connect(protocol=1)  # T1 protocol
                print("[OK] Connected with T1 protocol")
                
                print("Place an RFID card on the reader...")
                print("Waiting 10 seconds for card detection...")
                
                for i in range(10):
                    try:
                        get_uid_command = [0xFF, 0xCA, 0x00, 0x00, 0x00]
                        data, sw1, sw2 = connection.transmit(get_uid_command)
                        
                        if sw1 == 0x90 and sw2 == 0x00:
                            uid = toHexString(data).replace(' ', '')
                            print(f"[OK] Card detected! UID: {uid}")
                            return uid
                        else:
                            print(f"Attempt {i+1}: No card (Status: {sw1:02X} {sw2:02X})")
                            
                    except NoCardException:
                        print(f"Attempt {i+1}: No card present")
                    except Exception as e:
                        print(f"Attempt {i+1}: Error - {e}")
                        
                    time.sleep(1)
                    
            except Exception as e:
                print(f"[ERROR] T1 protocol failed: {e}")
                
        connection.disconnect()
        print("[ERROR] No card detected within 10 seconds")
        return None
        
    except Exception as e:
        print(f"[ERROR] Card detection test failed: {e}")
        return None

def test_api_connection():
    """Test connection to Laravel API"""
    print("\n=== Testing API Connection ===")
    
    try:
        import requests
        
        # Test API endpoint
        api_url = "http://localhost:8003/api/rfid/tap"
        test_data = {
            'card_uid': 'TEST_UID_123',
            'device_id': 'debug_test'
        }
        
        print(f"Testing API: {api_url}")
        
        response = requests.post(api_url, json=test_data, timeout=5)
        
        if response.status_code == 200:
            result = response.json()
            print(f"[OK] API connection successful")
            print(f"Response: {result}")
        else:
            print(f"[ERROR] API connection failed. Status: {response.status_code}")
            print(f"Response: {response.text}")
            
    except requests.exceptions.ConnectionError:
        print("[ERROR] API connection failed: Laravel server not running")
        print("Start Laravel server with: php artisan serve")
    except Exception as e:
        print(f"[ERROR] API test failed: {e}")

def main():
    """Main diagnostic function"""
    print("RFID Reader Diagnostic Tool")
    print("=" * 40)
    
    # Test 1: Check readers
    test_readers()
    
    # Test 2: Check card detection
    uid = test_card_detection()
    
    # Test 3: Check API connection
    test_api_connection()
    
    print("\n=== Diagnostic Summary ===")
    if uid:
        print("[OK] RFID system is working correctly")
        print(f"[OK] Card UID detected: {uid}")
        print("[OK] Ready for use with main RFID reader")
    else:
        print("[ERROR] RFID system has issues")
        print("[ERROR] No card UID detected")
        print("Check the following:")
        print("1. ACR122U reader is connected via USB")
        print("2. Reader drivers are installed")
        print("3. RFID card is placed on the reader")
        print("4. Laravel server is running (php artisan serve)")

if __name__ == "__main__":
    main()
