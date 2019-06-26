#!/bin/bash
clear
echo "Uninstalling RFPI .... "
#sudo update-rc.d rfpi disable
#sudo update-rc.d -f rfpi remove
#sudo rm /etc/init.d/rfpi


sudo sysctl -p
sudo systemctl stop rfpi.service
sudo systemctl disable rfpi.service

sudo unlink /etc/systemd/system/rfpi.service
sudo rm /lib/systemd/rfpi.service

sudo systemctl daemon-reload
