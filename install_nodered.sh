#!/bin/bash
clear
echo "Last update of this script was on 12-04-2025"

if  [ $(id -u) = 0 ]; then
   	echo "The script need to be run as normal user."
	echo "Try: bash install.sh." >&2
   exit 1
fi


echo "########################## AGREEMENT ##########################"
echo " "
echo " *    RFPI is free software: you can redistribute it and/or modify"
echo " *    it under the terms of the GNU Lesser General Public License as published by"
echo " *    the Free Software Foundation, either version 3 of the License, or"
echo " *    (at your option) any later version."
echo " *"
echo " *    RFPI is distributed in the hope that it will be useful,"
echo " *    but WITHOUT ANY WARRANTY; without even the implied warranty of"
echo " *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the"
echo " *    GNU Lesser General Public License for more details."
echo " *"
echo " *    You should have received a copy of the GNU Lesser General Public License"
echo " *    along with RFPI.  If not, see <http://www.gnu.org/licenses/>."
echo " "
echo " ATTENTION:  This software is provided in a way"
echo "             free and without any warranty"
echo "             The author does not assume any"
echo "             liability for damage brought or"
echo "             caused by this software."
echo " "							
echo " ATTENZIONE: Questo software viene fornito in modo "
echo "             gratuito e senza alcuna garanzia"
echo "             L'autore non si assume nessuna "
echo "             responsabilità per danni portati o"
echo "             causati da questo software."
echo " "
echo "########################## AGREEMENT ##########################"
echo " "
echo " IT: Se premi Y dichiari di aver preso visione dell'accordo riportato sopra. Vuoi continuare? (Y=si or N=no) "
read -p " EN: If you press Y you declare that you have read the above agreement. Do you wish to continue? (Y or N) " -n 1 -r
echo "" #new line
if [[ $REPLY =~ ^[Yy]$ ]] 
then #start of if for agreement

echo "Updating apt-get..."
sudo apt-get update
#sudo apt-get update && time sudo apt-get dist-upgrade

SUB_DIRECTORY_NODE_IOTGEMINI=/node_modules/node-red-contrib-iotgemini
SUB_DIRECTORY_NODE_DASHBOARD=/node_modules/node-red-dashboard
SUB_DIRECTORY_NODE_ALEXA=/node_modules/node-red-contrib-alexa-home-skill

########################## BEGIN INSTALL NODE-RED ##########################
#echo "checking if node-red is installed......"
#NODERED_INSTALLED=0
#DIRECTORY_NODERED=$(sudo find / -type d -name ".node-red")

#if [ ! -d "$DIRECTORY_NODERED" ]; then
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
		NODERED_SCRIPT=0;
		bash <(curl -sL https://raw.githubusercontent.com/node-red/linux-installers/master/deb/update-nodejs-and-nodered)
		NODERED_SCRIPT=$?
		if [ $NODERED_SCRIPT == 0 ]; then
			echo "NODE-RED Script TRUE: $NODERED_SCRIPT"
			sudo systemctl enable nodered.service
			NODERED_INSTALLED=1
		else
			echo "NODE-RED Script FALSE: $NODERED_SCRIPT"
			NODERED_INSTALLED=0
		fi
		echo "DONE!"
	fi
#else
#	echo "NODE-RED is already INSTALLED!"
#	NODERED_INSTALLED=1
#fi

if [ $NODERED_INSTALLED == 1 ]; then
	echo "finding node-red path......"
	DIRECTORY_NODERED=$(sudo find / -type d -name ".node-red")
	if [  -d "$DIRECTORY_NODERED" ]; then
		cd $DIRECTORY_NODERED
		echo "found directory node-red: $DIRECTORY_NODERED"
		if [ -d "$DIRECTORY_NODERED$SUB_DIRECTORY_NODE_IOTGEMINI" ]; then
			echo "Updating IOTGEMINI NODES......"
			npm install node-red-contrib-iotgemini
			npm update -g node-red-contrib-iotgemini
		else
			echo "Installing IOTGEMINI NODES......"
			npm install node-red-contrib-iotgemini
		fi
		if [ -d "$DIRECTORY_NODERED$SUB_DIRECTORY_NODE_DASHBOARD" ]; then
			echo "Updating DASHBOARD NODES......"
			npm install node-red-dashboard
			npm update -g node-red-dashboard
		else
			echo "Installing DASHBOARD NODES......"
			npm install node-red-dashboard
		fi
		if [ -d "$DIRECTORY_NODERED$SUB_DIRECTORY_NODE_ALEXA" ]; then
			echo "Updating ALEXA-NODE-RED-SKILL NODES......"
			npm install node-red-contrib-alexa-home-skill
			npm update -g node-red-contrib-alexa-home-skill
		else
			echo "Installing ALEXA-NODE-RED-SKILL NODES......"
			npm install node-red-contrib-alexa-home-skill
		fi
	else
		echo "NODE-RED Directory does not exist!"
	fi
fi

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

	exit 0
else 
	exit 1
fi #end of if for agreement
