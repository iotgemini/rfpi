#!/bin/bash
#clear

/etc/rfpi/install/bbb/release_port_80.sh

echo "Set Authorization .... "
/etc/rfpi/install/set_authorization.sh

echo "Installing ........."

cp /etc/rfpi/install/bbb/rfpi /etc/init.d/
chmod +x /etc/init.d/rfpi

cd /etc/init.d
update-rc.d rfpi defaults

echo "copying the service under /lib/systemd/"
cp /etc/rfpi/install/bbb/rfpi.service /lib/systemd/

echo "creating a link under /etc/systemd/system/"
cd /etc/systemd/system/
ln /lib/systemd/rfpi.service rfpi.service

echo "Make systemd reload the configuration file, start the service immediately..."

systemctl daemon-reload
systemctl start rfpi.service
systemctl enable rfpi.service


#/etc/rfpi/install/bbb/change_desktop_background/change_desktop_background.sh


echo "To apply the modifications reboot the system!"

