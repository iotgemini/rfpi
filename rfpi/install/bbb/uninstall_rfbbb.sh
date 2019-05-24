#!/bin/bash
clear
echo "Uninstalling .... "
update-rc.d rfpi disable
update-rc.d -f rfpi remove
rm /etc/init.d/rfpi

unlink /etc/systemd/system/rfpi.service
rm /lib/systemd/rfpi.service
