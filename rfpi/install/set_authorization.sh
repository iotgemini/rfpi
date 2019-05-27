#!/bin/bash
#clear
echo Setting authorization for the folder /etc/rfpi/fifo
sudo chmod 777 -R /etc/rfpi/fifo
echo Setting authorization for the folder /etc/rfpi/config
sudo chmod 777 -R /etc/rfpi/config
echo Setting authorization for the folder /var/www/config
sudo chmod 777 -R /var/www/config
echo Setting authorization for the folder /var/www/lib/peripheral_3/db
sudo chmod 777 -R /var/www/lib/peripheral_3/db
echo Setting authorization for the folder /var/www/lib/peripheral_9/data
sudo chmod 777 -R /var/www/lib/peripheral_9/data

