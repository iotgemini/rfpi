#!/bin/bash
echo "Starting RFPI routine:"
sudo systemctl stop serial-getty@ttyAMA0.service
sudo systemctl stop serial-getty@ttyS0.service
sudo systemctl stop serial-getty@serial0.service
sudo chmod 777 /dev/ttyAMA0
sudo chmod 777 /dev/ttyS0
sudo chmod 777 /dev/ttyS1
sudo chmod 777 /dev/serial0
sudo chmod 777 /dev/ttyUSB0
sudo chmod 777 /dev/ttyUSB1
sudo chmod 777 /dev/ttyUSB2
sudo chmod 777 /dev/ttyUSB3
sudo chmod 777 /dev/ttyO0
sudo chmod 777 /dev/ttyO1
sudo chmod 777 /dev/ttyO2
sudo chmod 777 /dev/ttyO3
/etc/rfpi/bin/rfpi