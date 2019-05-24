#!/bin/bash
sudo systemctl stop serial-getty@ttyS0.service
sudo chmod 777 /dev/ttyS0
echo "Starting RFPI routine:"
/etc/rfpi/bin/rfpi