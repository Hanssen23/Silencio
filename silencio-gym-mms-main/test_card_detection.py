#!/usr/bin/env python3
"""
Simple ACR122U Card Detection Test
Tests if the reader can detect RFID cards
"""

import time
from smartcard.System import readers
from smartcard.util import toHexString
from smartcard.Exceptions import CardConnectionException, NoCardException

def test_card_detection():
    """Test if ACR122U can detect RFID cards"""
    print("ACR122U Card Detection Test")
    print("=" * 40)
    
    try:
        # Get available readers
        available_readers = readers()
        if not available_readers:
            print("[ERROR] No smart card readers found")
            return False
            
        print(f"Found {len(available_readers)} reader(s)")
        
        # Use the first reader (should be ACR122U)
        reader = available_readers[0]
        print(f"Using reader: {reader}")
        
        # Create connection
        connection = reader.createConnection()
        
        # Try to connect with different protocols
        protocols = [
            (0, "T0"),
            (1, "T1"), 
            (2, "RAW")
        ]
        
        connected = False
        for protocol_id, protocol_name in protocols:
            try:
                print(f"Trying {protocol_name} protocol...")
                connection.connect(protocol=protocol_id)
                print(f"[OK] Connected with {protocol_name} protocol")
                connected = True
                break
            except Exception as e:
                print(f"[ERROR] {protocol_name} protocol failed: {e}")
                
        if not connected:
            print("[ERROR] Could not connect with any protocol")
            return False
            
        # Test card detection
        print("\nPlace an RFID card on the reader...")
        print("Waiting for card detection (30 seconds)...")
        
        for attempt in range(30):
            try:
                # Try to read UID
                get_uid_command = [0xFF, 0xCA, 0x00, 0x00, 0x00]
                data, sw1, sw2 = connection.transmit(get_uid_command)
                
                if sw1 == 0x90 and sw2 == 0x00:
                    uid = toHexString(data).replace(' ', '')
                    print(f"\n[SUCCESS] Card detected!")
                    print(f"UID: {uid}")
                    print(f"Attempt: {attempt + 1}")
                    return True
                else:
                    if attempt % 5 == 0:  # Print every 5 seconds
                        print(f"Attempt {attempt + 1}: No card (Status: {sw1:02X} {sw2:02X})")
                        
            except NoCardException:
                if attempt % 5 == 0:  # Print every 5 seconds
                    print(f"Attempt {attempt + 1}: No card present")
            except Exception as e:
                if attempt % 5 == 0:  # Print every 5 seconds
                    print(f"Attempt {attempt + 1}: Error - {e}")
                    
            time.sleep(1)
            
        print("\n[ERROR] No card detected within 30 seconds")
        print("Make sure:")
        print("1. RFID card is placed on the reader")
        print("2. Card is not damaged")
        print("3. Reader is properly connected")
        
        connection.disconnect()
        return False
        
    except Exception as e:
        print(f"[ERROR] Test failed: {e}")
        return False

def main():
    """Main function"""
    success = test_card_detection()
    
    if success:
        print("\n[OK] ACR122U reader is working correctly!")
        print("You can now use the main RFID reader script.")
    else:
        print("\n[ERROR] ACR122U reader has issues.")
        print("Check the hardware and try again.")

if __name__ == "__main__":
    main()
