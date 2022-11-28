# rfpi
You need a IOTGEMINI transceiver that has the USB, plug it into the Raspberry Pi or any PC with Linux Debian (Ubuntu, Linux Mint, ecc.)
This software has been tested on:

	- PC with Linux Mint
	- Raspberry Pi with Raspbian

To install the software just follow the instruction here.
Open the terminal and type the following commands:
	
	sudo git clone https://github.com/iotgemini/rfpi.git
	
	cd rfpi
	
	sudo bash install.sh
	
The bash script will installs the deamon rfpi that is witten in C language and run as service,
then it installs the gui that run on apache server.
If you want install node-red and the IOTGEMINI nodes run on terminal this command:

	bash install_nodered.sh
	
If you need just update the rfpi deamon then enter into the folder where you cloned the rfpi.git 
and run on termina the following commands:

	git pull
	
	sudo bash install.sh
	
Thanks for your interest in our project!
