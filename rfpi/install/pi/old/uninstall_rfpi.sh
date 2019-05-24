#!/bin/bash
clear
echo "Uninstalling RFPI .... "
sudo update-rc.d rfpi disable
sudo update-rc.d -f rfpi remove
sudo rm /etc/init.d/rfpi
