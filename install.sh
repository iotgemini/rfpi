#!/bin/bash
clear
echo "Last update of this script was on 26-06-2019"
echo "Installing rfpi ........."

echo "Updating apt-get..."
sudo apt-get update
#sudo apt-get update && time sudo apt-get dist-upgrade


DIRECTORY_RFPI=/etc/rfpi
DIRECTORY_WWW=/var/www
DIRECTORY_SAMBA=/etc/samba

disable_getty="0"


if [ ! -d "$DIRECTORY_RFPI" ]; then
	# Control will enter here if $DIRECTORY doesn't exist.
	echo "Creating foolder RFPI....."
	sudo mkdir $DIRECTORY_RFPI
else
	echo "RFPI Exist! Thus going to stop the service and update the RFPI Software....."
	sudo pkill rfpi
fi

if [ ! -d "$DIRECTORY_RFPI" ]; then
  # Control will enter here if $DIRECTORY doesn't exist.
   echo "Impossible to create ${DIRECTORY_RFPI} ..... try to gives to the path the permission to write!".
   exit 1
fi

echo "Copying rfpi files....."
SOURCE="./rfpi"
DESTINATION=$DIRECTORY_RFPI
sudo cp -r "$SOURCE/"* "$DESTINATION/"


echo "Checking ID Operating System.........!"
DISTRIBUTION_ID=$(lsb_release --id)
echo $DISTRIBUTION_ID
string=$DISTRIBUTION_ID
if [[ $string == *"Raspbian"* ]]; then
  echo "Found Raspbian OS!"
  echo "Going to edit the file /etc/rfpi/lib/librfpi.h to make rfpi run on Raspberry Pi"
  sed -i 's/#define\ PLATFORM\ 7/#define\ PLATFORM\ 3/g' /etc/rfpi/lib/librfpi.h
  disable_getty="1"
fi


echo "Installing GCC, G++ and LIBRARY:"
sudo apt-get -y install gcc libi2c-dev
sudo apt-get -y install g++

echo "Compiling the rfpi.c ......"
sudo gcc -o $DIRECTORY_RFPI/rfpi $DIRECTORY_RFPI/rfpi.c
sudo mv $DIRECTORY_RFPI/rfpi $DIRECTORY_RFPI/bin
sudo chmod 777 -R $DIRECTORY_RFPI/bin/rfpi


########################## BEGIN INSTALL APACHE AND GUI WWW ##########################
echo " "
echo "########################## APACHE AND GUI WWW ##########################"
echo " It is indispensable to have a GUI (Graphics User Interface) to control RFPI network......"
read -p " Do you want install Apache webserver and the GUI WWW? (Y or N) " -n 1 -r
echo "" #new line
if [[ $REPLY =~ ^[Yy]$ ]]
then
	echo "Install Apache:"
	sudo apt-get -y install apache2 php
	sudo apt-get -y install libapache2-mod-php

	sudo sed -i 's/html//g' /etc/apache2/sites-available/000-default.conf

	echo "Copying www files....."
	SOURCE="./www"
	DESTINATION=$DIRECTORY_WWW
	sudo cp -r "$SOURCE/"* "$DESTINATION/"

	sudo service apache2 restart


	sudo bash $DIRECTORY_RFPI/install/set_authorization.sh
fi
########################## END INSTALL APACHE AND GUI WWW ##########################


########################## BEGIN INSTALL RFPI SERVICE ##########################
echo "Installing the service rfpi...."
#sudo cp $DIRECTORY_RFPI/service/rfpi /etc/init.d/
#sudo chmod +x /etc/init.d/rfpi
#cd /etc/init.d
#sudo update-rc.d rfpi defaults

#making executable
chmod u+x /etc/rfpi/runrfpi.sh

echo "copying the service under /lib/systemd/"
sudo cp $DIRECTORY_RFPI/service/rfpi.service /lib/systemd/

echo "creating a link under /etc/systemd/system/"
cd /etc/systemd/system/
sudo ln /lib/systemd/rfpi.service rfpi.service
sudo chmod 777 rfpi.service

echo "Make systemd reload the configuration file, start the service immediately..."
sudo systemctl daemon-reload
sudo systemctl start rfpi.service
sudo systemctl enable rfpi.service


if [ "$disable_getty" -eq "1" ]; then
#	#echo "Edit the file inittab - Disable the getty"
#	#sed -i 's/TO:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100/#TO:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100/g' /etc/inittab
	echo "Disabling the getty....."
	sudo sysctl -p
	sudo systemctl stop serial-getty@ttyS0.service
	sudo systemctl disable serial-getty@ttyS0.service
fi
########################## END INSTALL RFPI SERVICE ##########################



########################## BEGIN INSTALL SAMBA ##########################
if [ ! -d "$DIRECTORY_SAMBA" ]; then
	echo " "
	echo "########################## INSTALL SAMBA ##########################"
	read -p " Do you want install samba and share rfpi and www folders? (Y or N) " -n 1 -r
	echo "" #new line
	if [[ $REPLY =~ ^[Yy]$ ]]
	then
		echo "Install Samba:"
		sudo apt-get -y install samba samba-common-bin

		if [ -d "$DIRECTORY_SAMBA" ]; then
			echo " " >> /etc/samba/smb.conf
			echo "[www]" >> /etc/samba/smb.conf
			echo "comments = www share" >> /etc/samba/smb.conf
			echo "path = /var/www" >> /etc/samba/smb.conf
			echo "read only = no" >> /etc/samba/smb.conf
			echo "guest ok = yes" >> /etc/samba/smb.conf
			echo "force user = root" >> /etc/samba/smb.conf
			echo " " >> /etc/samba/smb.conf
			echo "[rfpi]" >> /etc/samba/smb.conf
			echo "comments = rfpi share" >> /etc/samba/smb.conf
			echo "path = /etc/rfpi" >> /etc/samba/smb.conf
			echo "read only = no" >> /etc/samba/smb.conf
			echo "guest ok = yes" >> /etc/samba/smb.conf
			echo "force user = root" >> /etc/samba/smb.conf
		fi
	fi
fi
########################## END INSTALL SAMBA ##########################


#echo "Change network name:"
#sed -i 's/raspberry/rfpi/g' /etc/hostname
#sed -i 's/raspberry/rfpi/g' /etc/hosts

echo " "
echo "########################## REBOOT ##########################"
read -p " Can I reboot now? (Y or N) " -n 1 -r
echo "" #new line
if [[ $REPLY =~ ^[Yy]$ ]]
then
	echo "Rebooting...."
	sudo reboot
fi
