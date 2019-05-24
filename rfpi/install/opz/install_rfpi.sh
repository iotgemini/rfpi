#!/bin/bash
clear
echo "Set Authorization .... "
/etc/rfpi/install/set_authorization.sh

echo "Installing RFPI .... "
sudo cp ./rfpi /etc/init.d/
sudo chmod +x /etc/init.d/rfpi
cd /etc/init.d
sudo update-rc.d rfpi defaults


echo "copying the service under /lib/systemd/"
cp /etc/rfpi/install/opz/rfpi.service /lib/systemd/


echo "creating a link under /etc/systemd/system/"
cd /etc/systemd/system/
ln /lib/systemd/rfpi.service rfpi.service

echo "Make systemd reload the configuration file, start the service immediately..."

systemctl daemon-reload
systemctl start rfpi.service
systemctl enable rfpi.service

echo "To applay the modifications reboot the system!"
