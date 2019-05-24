#!/bin/bash
clear
echo "Set Authorization .... "
/etc/rfpi/install/set_authorization.sh

echo "Installing RFPI .... "
sudo cp ./rfpi /etc/init.d/
sudo chmod +x /etc/init.d/rfpi
cd /etc/init.d
sudo update-rc.d rfpi defaults

echo "Edit the file inittab - Disable the getty"
#sed -i 's/TO:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100/#TO:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100/g' /etc/inittab
sudo sysctl -p
sudo systemctl stop serial-getty@ttyAMA0.service
sudo systemctl disable serial-getty@ttyAMA0.service

