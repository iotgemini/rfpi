#!/bin/bash
echo "Last update of this script was on 15-04-2025"
echo "BEGIN TO INSTALL DRIVER for LCD 5 INCH TOUCH waveshare-ads7846: "
#
DIRECTORY_BOOT=/boot
#DIRECTORY_OVERLAYS=/boot/overlays
#
#https://www.waveshare.com/wiki/5inch_HDMI_LCD
#
########################## EDITING /boot/config.txt ##########################
echo " "
echo "########################## EDITING /boot/config.txt ##########################"
if grep -Fxq "ads7846" /boot/config.txt; then
	# code if found
	echo "Already installed the driver ads7846!"
else
	# code if not found	
	echo " IT: Vuoi modificare il file /boot/config.txt? (Y=si or N=no) "
	read -p " EN: Do you want edit the file /boot/config.txt? (Y or N) " -n 1 -r
	echo "" #new line
	if [[ $REPLY =~ ^[Yy]$ ]]
	then
		if [ -d "$DIRECTORY_BOOT" ]; then
				echo " " >> /boot/config.txt
				echo "hdmi_group=2" >> /boot/config.txt
				echo "hdmi_mode=87" >> /boot/config.txt
				echo "hdmi_cvt 800 480 60 6 0 0 0" >> /boot/config.txt
				echo "dtoverlay=ads7846,cs=1,penirq=25,penirq_pull=2,speed=50000,keep_vref_on=0,swapxy=0,pmax=255,xohms=150" >> /boot/config.txt
				echo "hdmi_drive=1" >> /boot/config.txt
				echo "hdmi_force_hotplug=1" >> /boot/config.txt
				echo "enable_uart=1" >> /boot/config.txt
				
				echo "At the end of the file /boot/config.txt have been added these lines:"
				echo "hdmi_group=2"
				echo "hdmi_mode=87"
				echo "hdmi_cvt 800 480 60 6 0 0 0"
				echo "dtoverlay=ads7846,cs=1,penirq=25,penirq_pull=2,speed=50000,keep_vref_on=0,swapxy=0,pmax=255,xohms=150"
				echo "hdmi_drive=1"
				echo "hdmi_force_hotplug=1"
				echo "enable_uart=1"
		fi
	fi
fi
########################## END EDITING /boot/config.txt ##########################
#
########################## EDITING /etc/X11/xorg.conf.d/99-calibration.conf ##########################
echo " "
echo "########################## EDITING /etc/X11/xorg.conf.d/99-calibration.conf ##########################"
#
CONFIG_FILE="/etc/X11/xorg.conf.d/99-calibration.conf"
CONFIG_CONTENT='Section "InputClass"
        Identifier      "calibration"
        MatchProduct    "ADS7846 Touchscreen"
        Option  "Calibration"   "200 3971 200 3971"
        Option  "SwapAxes"      "0"
        Option "EmulateThirdButton" "1"
        Option "EmulateThirdButtonTimeout" "1000"
        Option "EmulateThirdButtonMoveThreshold" "300"
        Option "TransformationMatrix" "0 -1 1 -1 0 1 0 0 1"
EndSection'
#
# Controlla se il file esiste già
if [ ! -f "$CONFIG_FILE" ]; then
    echo "Il file $CONFIG_FILE non esiste, lo creo..."
#
    # Verifica se la directory esiste, altrimenti la crea
    if [ ! -d "/etc/X11/xorg.conf.d" ]; then
        echo "Creo la directory /etc/X11/xorg.conf.d"
        sudo mkdir -p /etc/X11/xorg.conf.d
    fi
#
    # Crea il file con il contenuto specificato
    echo "$CONFIG_CONTENT" | sudo tee "$CONFIG_FILE" > /dev/null
#
    # Imposta i permessi appropriati
    sudo chmod 644 "$CONFIG_FILE"
#
    echo "File creato con successo!"
#	sudo systemctl restart lightdm
else
    echo "Il file $CONFIG_FILE esiste già, non faccio nulla."
fi
########################## END EDITING /etc/X11/xorg.conf.d/99-calibration.conf ##########################
#
sudo apt-get install -y xserver-xorg-input-evdev
echo "Installing calibrator: "
sudo apt-get install -y xinput-calibrator
echo "Installing keyboard on screen: "
sudo apt-get install -y matchbox-keyboard
#
#sudo git clone https://github.com/waveshare/LCD-show.git
#cd LCD-show/
#sudo chmod +x LCD5-show
#./LCD5-show
#
exit 0