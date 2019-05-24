#!/bin/bash
clear
echo "Set Authorization .... "
sudo /etc/rfpi/install/set_authorization.sh

echo "Installing RFPI .... "
sudo cp /etc/rfpi/install/pi3/rfpi /etc/init.d/
sudo chmod +x /etc/init.d/rfpi
cd /etc/init.d
sudo update-rc.d rfpi defaults


echo "copying the service under /lib/systemd/"
sudo cp /etc/rfpi/install/pi3/rfpi.service /lib/systemd/

echo "creating a link under /etc/systemd/system/"
cd /etc/systemd/system/
sudo ln /lib/systemd/rfpi.service rfpi.service
sudo chmod 777 rfpi.service

echo "Make systemd reload the configuration file, start the service immediately..."

sudo systemctl daemon-reload
sudo systemctl start rfpi.service
sudo systemctl enable rfpi.service

echo "Edit the file inittab - Disable the getty"
#sed -i 's/TO:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100/#TO:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100/g' /etc/inittab
sudo sysctl -p
sudo systemctl stop serial-getty@ttyS0.service
sudo systemctl disable serial-getty@ttyS0.service

