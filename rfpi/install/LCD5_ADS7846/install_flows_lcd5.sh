#!/bin/bash
NAME_FILE_FLOW_ORIGINAL="flows.json"
NODE_RED_DIR="/home/pi/.node-red"
PATH_FLOW_FILE_TO_COPY="/etc/rfpi/install/LCD5_ADS7846/flows_lcd5.json"  # SOSTITUIRE con il percorso del file flow
NAME_FILE_FLOW_ORIGINAL="flows.json"
HOSTNAME=$(hostname)

SUB_DIRECTORY_NODE_IOTGEMINI=/node_modules/node-red-contrib-iotgemini
SUB_DIRECTORY_NODE_DASHBOARD=/node_modules/node-red-dashboard
SUB_DIRECTORY_NODE_ALEXA=/node_modules/node-red-contrib-alexa-home-skill
SUB_DIRECTORY_NODE_PLAY_SOUND=/node_modules/node-red-contrib-play-sound
SUB_DIRECTORY_NODE_PLAY_SOUND=/node_modules/node-red-contrib-play-sound
SUB_DIRECTORY_NODE_ADVANCED_PING=/node_modules/node-red-contrib-advanced-ping
SUB_DIRECTORY_NODE_FS_OPS=/node_modules/node-red-contrib-fs-ops
SUB_DIRECTORY_NODE_UI_LEVEL=/node_modules/node-red-contrib-ui-level
SUB_DIRECTORY_NODE_COUNTER=/node_modules/node-red-contrib-counter
SUB_DIRECTORY_NODE_CACHE=/node_modules/node-red-contrib-cache


echo "🔍 Verifica installazione Node-RED in corso..."
# Controllo multiplo dell'installazione
if command -v node-red &>/dev/null || \
   { systemctl list-unit-files | grep -q nodered.service && systemctl is-active --quiet nodered; } || \
   [ -f "/usr/bin/node-red" ] || \
   [ -d "$NODE_RED_DIR" ]; then

    echo "✅ NODE-RED È INSTALLATO"
    echo ""

	# 1. Verifica se il file flow esiste
	if [ ! -f "$PATH_FLOW_FILE_TO_COPY" ]; then
		echo "ERRORE: File $PATH_FLOW_FILE_TO_COPY non trovato!"
		exit 1
	fi

	# 2. Crea backup del flow esistente
	BACKUP_FILE="$NODE_RED_DIR/$NAME_FILE_FLOW_ORIGINAL.bak"
	if [ -f "$NODE_RED_DIR/$NAME_FILE_FLOW_ORIGINAL" ]; then
		echo "Creo backup del flow esistente..."
		cp "$NODE_RED_DIR/$NAME_FILE_FLOW_ORIGINAL" "$BACKUP_FILE"
	fi

	# 3. Installa il nuovo flow
	echo "Installo il nuovo flow..."
	cp "$PATH_FLOW_FILE_TO_COPY" "$NODE_RED_DIR/$NAME_FILE_FLOW_ORIGINAL"


############# SW AND PATH USED BY THE FLOWS #############
	sudo mkdir -p /etc/rfpi/music
	sudo chmod 777 -R /etc/rfpi/music
	sudo apt install mplayer
	sudo cp -rf ./kill_chromium.sh /etc/rfpi/
	sudo chmod 777 /etc/rfpi/kill_chromium.sh

############# BEGIN: INSTALL NODES USED BY THE FLOWS #############
	if [  -d "$NODE_RED_DIR" ]; then
		cd $NODE_RED_DIR
		echo "found directory node-red: $NODE_RED_DIR"
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_IOTGEMINI" ]; then
			echo "Updating IOTGEMINI NODES......"
			npm install node-red-contrib-iotgemini
			npm update -g node-red-contrib-iotgemini
		else
			echo "Installing IOTGEMINI NODES......"
			npm install node-red-contrib-iotgemini
		fi
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_DASHBOARD" ]; then
			echo "Updating DASHBOARD NODES......"
			npm install node-red-dashboard
			npm update -g node-red-dashboard
		else
			echo "Installing DASHBOARD NODES......"
			npm install node-red-dashboard
		fi
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_ALEXA" ]; then
			echo "Updating ALEXA-NODE-RED-SKILL NODES......"
			npm install node-red-contrib-alexa-home-skill
			npm update -g node-red-contrib-alexa-home-skill
		else
			echo "Installing ALEXA-NODE-RED-SKILL NODES......"
			npm install node-red-contrib-alexa-home-skill
		fi
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_PLAY_SOUND" ]; then
			echo "Updating PLAY-SOUND NODES......"
			npm install node-red-contrib-play-sound
			npm update -g node-red-contrib-play-sound
		else
			echo "Installing PLAY-SOUND NODES......"
			npm install node-red-contrib-play-sound
		fi
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_ADVANCED_PING" ]; then
			echo "Updating ADVANCED-PING NODES......"
			npm install node-red-contrib-advanced-ping
			npm update -g node-red-contrib-advanced-ping
		else
			echo "Installing ADVANCED-PING NODES......"
			npm install node-red-contrib-advanced-ping
		fi
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_FS_OPS" ]; then
			echo "Updating FS-OPS NODES......"
			npm install node-red-contrib-fs-ops
			npm update -g node-red-contrib-fs-ops
		else
			echo "Installing FS-OPS NODES......"
			npm install node-red-contrib-fs-ops
		fi
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_UI_LEVEL" ]; then
			echo "Updating UI-LEVEL NODES......"
			npm install node-red-contrib-ui-level
			npm update -g node-red-contrib-ui-level
		else
			echo "Installing UI-LEVEL NODES......"
			npm install node-red-contrib-ui-level
		fi
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_COUNTER" ]; then
			echo "Updating COUNTER NODES......"
			npm install node-red-contrib-counter
			npm update -g node-red-contrib-counter
		else
			echo "Installing COUNTER NODES......"
			npm install node-red-contrib-counter
		fi
		if [ -d "$NODE_RED_DIR$SUB_DIRECTORY_NODE_CACHE" ]; then
			echo "Updating CACHE NODES......"
			npm install node-red-contrib-cache
			npm update -g node-red-contrib-cache
		else
			echo "Installing CACHE NODES......"
			npm install node-red-contrib-cache
		fi
	else
		echo "NODE-RED Directory does not exist!"
	fi
	
	node-red-restart
    echo "Installazione completata!"
	echo "Flow installato: $NODE_RED_DIR/$NAME_FILE_FLOW_ORIGINAL"
	echo "Backup creato: $BACKUP_FILE"
	echo "If you do not see any flow than check the second line of this bash script where is NAME_FILE_FLOW_ORIGINAL"
############# END: INSTALL NODES USED BY THE FLOWS #############

else
    echo "❌ NODE-RED NON È INSTALLATO"
    echo ""
    echo "Per installare:"
    echo "1. Automatico:"
    echo "   bash <(curl -sL https://raw.githubusercontent.com/node-red/linux-installers/master/deb/update-nodejs-and-nodered)"
    echo "2. Manuale:"
    echo "   sudo apt install nodered"
    echo ""
    echo "Dopo l'installazione:"
    echo "   sudo systemctl enable nodered"
    echo "   sudo systemctl start nodered"
fi