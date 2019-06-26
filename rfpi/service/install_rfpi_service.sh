#!/bin/bash
clear
echo "Installing RFPI .... "
#sudo cp /etc/rfpi/install/pi3/rfpi /etc/init.d/
#sudo chmod +x /etc/init.d/rfpi
#cd /etc/init.d
#sudo update-rc.d rfpi defaults


echo "copying the service under /lib/systemd/"
sudo cp /etc/rfpi/service/rfpi.service /lib/systemd/

echo "creating a link under /etc/systemd/system/"
cd /etc/systemd/system/
sudo ln /lib/systemd/rfpi.service rfpi.service
sudo chmod 777 rfpi.service

echo "Make systemd reload the configuration file, start the service immediately..."

sudo systemctl daemon-reload
sudo systemctl start rfpi.service
sudo systemctl enable rfpi.service

