#!/bin/bash
clear
echo "Last update of this script was on 18-03-2020"
echo "Installing rfpi ........."

echo "Updating apt-get..."
sudo apt-get update
#sudo apt-get update && time sudo apt-get dist-upgrade


DIRECTORY_RFPI=/etc/rfpi
DIRECTORY_WWW=/var/www
DIRECTORY_SAMBA=/etc/samba
FILE_FSTAB=/etc/fstab

disable_getty="0"


if [ ! -d "$DIRECTORY_RFPI" ]; then
	# Control will enter here if $DIRECTORY doesn't exist.
	echo "Creating foolder RFPI....."
	sudo mkdir $DIRECTORY_RFPI
else
	echo "RFPI Exist! Thus going to stop the service and update the RFPI Software....."
	sudo service rfpi stop
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


echo "Creating a folder into ram adding a line into /etc/fstab ....."
if test -f "$FILE_FSTAB"; then
	if grep -Fxq "#RFPI FIFO:" /etc/fstab; then
		# code if found
		echo "Already there!"
	else
		# code if not found
		echo "#RFPI FIFO:" >> /etc/fstab
		echo "tmpfs /etc/rfpi/fifo tmpfs defaults, noatime, nosuid, mode=0755, size=2m 0 0" >> /etc/fstab
		echo "Added succesfully!"
	fi
else
	echo "Impossible to find /etc/fstab"
	echo "WARNING! All fifo file will be written on SD memory. These file fifo are written many times per days, thus will erode the SD cards! Find out where is the file fstab and add the following line:"
	echo "tmpfs /etc/rfpi/fifo tmpfs defaults, noatime, nosuid, mode=0755, size=2m 0 0"
	sleep 5
fi


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
echo " IT: E' indispensabile avere una GUI (Grafica Utente Interfaccia) per controllare la rete RFPI!"
#echo "     se non vuoi la nostra GUI puoi sempre creartene una con Node-Red e controllare la rete attraverso i nodi dedicati!"
echo "     Vuoi installare il webserver Apache e la GUI WWW? (Y=si or N=no) "
echo " EN: It is indispensable to have a GUI (Graphics User Interface) to control RFPI network!"
read -p "     Do you want install Apache webserver and the GUI WWW? (Y or N) " -n 1 -r
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


#if [ "$disable_getty" -eq "1" ]; then
#	#echo "Edit the file inittab - Disable the getty"
#	#sed -i 's/TO:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100/#TO:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100/g' /etc/inittab
#	echo "Disabling the getty....."
#	sudo sysctl -p
#	sudo systemctl stop serial-getty@ttyS0.service
#	sudo systemctl disable serial-getty@ttyS0.service
#fi
########################## END INSTALL RFPI SERVICE ##########################



########################## BEGIN INSTALL SAMBA ##########################
#if [ ! -d "$DIRECTORY_SAMBA" ]; then
	echo " "
	echo "########################## INSTALL SAMBA ##########################"
	echo " IT: Vuoi installare samba per poi condividere le cartelle rfpi e www? (Y=si or N=no) "
	read -p " EN: Do you want install samba to then share rfpi and www folders? (Y or N) " -n 1 -r
	echo "" #new line
	if [[ $REPLY =~ ^[Yy]$ ]]
	then
		echo "Install Samba:"
		sudo apt-get -y install samba samba-common-bin
	fi
#fi

########################## END INSTALL SAMBA ##########################



########################## BEGIN SHARE FOLDERS ##########################
echo " "
echo "########################## SHARE FOLDERS ##########################"
if grep -Fxq "[rfpi]" /etc/samba/smb.conf; then
	# code if found
	echo "Already shared!"
else
	# code if not found	
	echo " IT: Vuoi condividere le cartelle rfpi e www? (Y=si or N=no) "
	read -p " EN: Do you want share rfpi and www folders? (Y or N) " -n 1 -r
	echo "" #new line
	if [[ $REPLY =~ ^[Yy]$ ]]
	then
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
				
				echo "At the end of the file /etc/samba/smb.conf have been added the lines to share RFPI and WWW!"
		fi
	fi
fi
########################## END SHARE FOLDERS ##########################



#echo "Change network name:"
#sed -i 's/raspberry/rfpi/g' /etc/hostname
#sed -i 's/raspberry/rfpi/g' /etc/hosts


########################## BEGIN INSTALL NODE-RED ##########################
#if [ ! -d "$DIRECTORY_SAMBA" ]; then
	echo " "
	echo "########################## INSTALL NODE-RED ##########################"
	echo " IT: Vuoi installare Node-Red per creare le tue automazioni personalizzate e pannelli di controllo personalizzati? (Y=si or N=no) "
	echo "     se decidi di installare Node-Red dovrai essere paziente perche' ci vorra' un po' di tempo........."
	echo " EN: Do you want to install Node-Red to create your custom automations and custom control panels? (Y or N) "
	read -p "     if you decide to install Node-Red you will have to be patient because it will take some time ......... " -n 1 -r
	echo "" #new line
	if [[ $REPLY =~ ^[Yy]$ ]]
	then
		echo "Install Node-Red:"
		sudo bash <(curl -sL https://raw.githubusercontent.com/node-red/linux-installers/master/deb/update-nodejs-and-nodered)
		sudo systemctl enable nodered.service
		npm install node-red-contrib-iotgemini
		npm i node-red-dashboard
	fi
#fi

########################## END INSTALL NODE-RED ##########################


echo " "
echo "########################## REBOOT ##########################"
echo " IT: Posso riavviare adesso? (Y=si or N=no) "
read -p " EN: Can I reboot now? (Y or N) " -n 1 -r
echo "" #new line
if [[ $REPLY =~ ^[Yy]$ ]]
then
	echo "Rebooting...."
	sudo reboot
fi
