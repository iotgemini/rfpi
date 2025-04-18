/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					14/04/2025


Description: application rfpi.c to run the RFPI network

 *    RFPI is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    RFPI is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with RFPI.  If not, see <http://www.gnu.org/licenses/>.
							
	
	ATTENTION:				This software is provided in a way
							free and without any warranty
							The author does not assume any
							liability for damage brought or
							caused by this software.
							
	ATTENZIONE:				Questo software viene fornito in modo 
							gratuito e senza alcuna garanzia
							L'autore non si assume nessuna 
							responsabilità per danni portati o
							causati da questo software.


******************************************************************************************/

#define DEBUG_LEVEL 			2	//enable the debug setting this parameter with a value above 0
#define MAX_BUF_DATA_RFPI		47	 //it is the data coming from the uart
									 //into the answer there are 23bytes + the \0. Example: OK*0001RBu1............

#include <stdio.h>
#include <stdlib.h>
#include <sys/stat.h>

#include "config/rfpi_conf.h"

#include "lib/librfpi.h"
#include "lib/iotg.h"

#include "lib/librfpi.c"
#include "lib/iotg.c"


int main(int argc, char **argv){ 	

	//pointers used to manage the data of all linked peripheral
	peripheraldata *rootPeripheralData=0;

	unsigned char dataRFPI[MAX_BUF_DATA_RFPI]; dataRFPI[0]='\0';
	int numBytesDataRFPI = 0;
	char str_net_name_and_address[50];
	int count1=0;
	int cmd_execution=0;
	
	//initializing the fifo to receive command from the GUI
	FILE *fp=0;
	fp  = fopen (FIFO_GUI_CMD_SYNC, "w+");
	fclose (fp);
	chmod(FIFO_GUI_CMD_SYNC, 0777);
	
	fp  = fopen (FIFO_GUI_CMD, "w+");
	fclose (fp);
	chmod(FIFO_GUI_CMD, 0777);
	
	fp  = fopen (FIFO_RFPI_RUN, "w+");
	if(fp != NULL){
		fprintf(fp, "%s", MSG_FIFO_RFPI_RUN_BUSY); //this would become TRUE after the execution of InitRFPI(...)
	}
	fclose (fp);
	chmod(FIFO_RFPI_RUN, 0777);
	
	fp  = fopen (FIFO_RFPI_STATUS, "w+");
	fclose (fp);
	chmod(FIFO_RFPI_STATUS, 0777);
	
	fp  = fopen (FIFO_RFPI_PERIPHERAL, "w+");
	fclose (fp);
	chmod(FIFO_RFPI_PERIPHERAL, 0777);
	
	fp  = fopen (FIFO_RFPI_PERIPHERAL_JSON, "w+");
	fclose (fp);
	chmod(FIFO_RFPI_PERIPHERAL_JSON, 0777);
	
	fp  = fopen (FIFO_RFPI_NET_NAME, "w+");
	fclose (fp);
	chmod(FIFO_RFPI_NET_NAME, 0777);
	
	fp  = fopen (FIFO_GET_BYTES_U, "w+");
	fclose (fp);
	chmod(FIFO_GET_BYTES_U, 0777);
	
	fp  = fopen (FIFO_SEND_BYTES_F, "w+");
	fclose (fp);
	chmod(FIFO_SEND_BYTES_F, 0777);
		
	fp  = fopen (FIFO_RTC, "w+");
	fclose (fp);
	chmod(FIFO_RTC, 0777);
	
	fp  = fopen (FIFO_RFPI_PERIPHERAL_SYNC, "w+");
	fclose (fp);
	chmod(FIFO_RFPI_PERIPHERAL_SYNC, 0777);


	sem_serial_communication_via_usb=0; //if the communication is via USB then no gpio will control leds. This would be updated by function return_serial_port_path(....)
	//sem_ctrl_led = 0;  //this enable or disable the control of the leds by the gpio. If the transceiver is connected via USB then no led are connected to the gpio
	//this function test all serial port under the path given by path_search
	serial_port_path_str = return_serial_port_path(path_to_search_serial_port, serial_port_path_str, &handleUART);
	#if DEBUG_LEVEL>0
		printf("Serial port path found: %s", serial_port_path_str);
	#endif

	//it init the RFPI
	rootPeripheralData=InitRFPI(rootPeripheralData, serial_port_path_str);
	
	#ifdef RTC_MODEL
	if(RTC_MODEL==RTC_DS1307){
		//this function make to start the DS1307 when it is new!
		start_DS1307_if_new();
	}
	#endif // RTC_MODEL
	
	cmd_execution=1;
    do{// beginning of the infinite loop

		//it check the data into the buffer of the UART, return the data on the string given
		numBytesDataRFPI=checkDataIntoUART(&handleUART, dataRFPI, MAX_BUF_DATA_RFPI);
			
		//it parse the data given. In case of data from peripheral, it will update the struct data
		rootPeripheralData=parseDataFromUART(dataRFPI, &numBytesDataRFPI, rootPeripheralData, &cmd_execution);

		if(cmd_execution!=0){
			#if DEBUG_LEVEL>0
				printf("\n\nRewriting fifo.....");
			#endif
			//tell to the GUI the network name
			//fifoWriter(FIFO_RFPI_NET_NAME, networkName);
			addressFromName(networkName, networkAddress); //networkAddress will contain the the address in hex format like 1FA2
			strcpy(str_net_name_and_address,networkName);
			strcat(str_net_name_and_address,"\n");
			strcat(str_net_name_and_address,networkAddress);
			fifoWriter(FIFO_RFPI_NET_NAME, str_net_name_and_address);

			//create the fifo to give the status of the peripheral to the GUI
			writeFifoPeripheralLinked(rootPeripheralData); 
			writeFifoJsonPeripheralLinked(rootPeripheralData);
		
			//tell to the GUI the various status
			fifoWriter(FIFO_RFPI_STATUS, statusRFPI); 
			fifoWriter(FIFO_RFPI_RUN, statusInit);

			#if DEBUG_LEVEL>0
				printf("FIFO REWRITTEN!\n\n");
			#endif
		}

		cmd_execution=0;
		for(count1=0;count1<EXECUTION_DELAY && cmd_execution==0;count1++){
			//it parse the data coming from the GUI. It will write the FIFO RFPI STATUS. Thus into the FIFO RFPI STATUS there will be written the response after have parsed the data from the GUI.
			rootPeripheralData=ParseFIFOdataGUI(&handleUART, rootPeripheralData, &cmd_execution);
			if(cmd_execution==0){ delay_ms(DELAY_AFTER_PARSED_DATA_GUI); }
		}
		
		// Turn a led ON and OFF to shows the application is running 
		blinkLed();
		
		//read the RTC on the i2c bus and update a FIFO called fifortc
		#ifdef RTC_MODEL
		if(RTC_MODEL!=NO_RTC){
			read_RTC();
		}
		#endif // RTC_MODEL
			
	}while(1);//end of infinite loop
	
	serialClose (handleUART) ;
	
	return 0;
}

