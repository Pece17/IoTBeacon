#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez
import os

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                returnedList = ScanUtility.parse_events(sock, 10)
                for item in returnedList:
                        if item.get("macAddress") == "e2:e3:23:d1:b0:54":
                                print("")
                                os.system("php /home/projektimies/Lighthouse/DatabaseInsert1.php")
                                print("")
				print("BEACON 1")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                        if item.get("macAddress") == "d6:2c:ca:c0:d4:9c":
                                print("")
                                os.system("php /home/projektimies/Lighthouse/DatabaseInsert2.php")
                                print("")
				print("BEACON 2")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                        if item.get("macAddress") == "f2:36:00:21:c0:50":
                                print("")
                                os.system("php /home/projektimies/Lighthouse/DatabaseInsert3.php")
                                print("")
				print("BEACON 3")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                print("not found")
except KeyboardInterrupt:
        pass
