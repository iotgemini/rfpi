/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					28/07/2019


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

#include <stdio.h>
#include <stdlib.h>
#include <sys/stat.h>
#include "lib/librfpi.h"
#include "lib/librfpi.c"


#define MAX_BUF_DATA_RFPI		47	 //it is the data coming from the uart
									 //into the answer there are 23bytes + the \0. Example: OK*0001RBu1............


int main(int argc, char **argv){ 	

	//pointers used to manage the data of all linked peripheral
	peripheraldata *rootPeripheralData=0;

	unsigned char dataRFPI[MAX_BUF_DATA_RFPI];
	int numBytesDataRFPI = 0;
	char str_net_name_and_address[50];

	sem_serial_communication_via_usb=0; //if the communication is via USB then no gpio will control leds. This would be updated by function return_serial_port_path(....)
	//sem_ctrl_led = 0;  //this enable or disable the control of the leds by the gpio. If the transceiver is connected via USB then no led are connected to the gpio
	//this function test all serial port under the path given by path_search
	serial_port_path_str = return_serial_port_path(path_to_search_serial_port, serial_port_path_str, &handleUART); 
	printf("Serial port path found: %s", serial_port_path_str);
	

	//it init the RFPI
	rootPeripheralData=InitRFPI(rootPeripheralData, serial_port_path_str);
	
	#ifdef RTC_MODEL
	if(RTC_MODEL==RTC_DS1307){
		//this function make to start the DS1307 when it is new!
		start_DS1307_if_new();
	}
	#endif // RTC_MODEL
	
	//send the status to the support
	//send_status_to_support(FILE_LIST_PERIPHERAL, networkName);


    do{// beginning of the infinite loop
	   	  
		//tell to the GUI the init status
		fifoWriter(FIFO_RFPI_RUN, statusInit);

		//printf(" statusInit=%s\n",statusInit); fflush(stdout); // Prints immediately to screen
		
		char tempVar_Semaphore = 0;
		//if(strcmp(statusInit,"TRUE")!=0){  
		if(strlen(statusInit)==4){
			if(strcmp(statusInit,"TRUE")!=0){
				tempVar_Semaphore = 1;
			}
		}
		
		if(tempVar_Semaphore==1){
			delay_ms(ERROR_BLINK_LED_DELAY);
			#ifdef LED_YES
			if(sem_serial_port_USB == 0){
				#if PLATFORM == PLATFORM_RPI
					bcm2835_gpio_write(PIN_LED_DS1, LOW); //led which indicate if an error occurred
				#elif PLATFORM == PLATFORM_BBB
					linux_gpio_set_value(BBB_PIN_LED_DS2, LOW_GPIO);
				#elif PLATFORM == PLATFORM_OPZ
					linux_gpio_set_value(OPZ_PIN_LED_DS2, LOW_GPIO);
				#endif
			}
			#endif
		}else{ 
			#ifdef LED_YES
			if(sem_serial_port_USB == 0){
				#if PLATFORM == PLATFORM_RPI
					bcm2835_gpio_write(PIN_LED_DS1, HIGH); //led which indicate if an error occurred
				#elif PLATFORM == PLATFORM_BBB
					linux_gpio_set_value(BBB_PIN_LED_DS2, HIGH_GPIO);
				#elif PLATFORM == PLATFORM_OPZ
					linux_gpio_set_value(OPZ_PIN_LED_DS2, HIGH_GPIO);
				#endif
			}
			#endif
			
			//it check the data into the buffer of the UART, return the data on the string given
			numBytesDataRFPI=checkDataIntoUART(&handleUART, dataRFPI, MAX_BUF_DATA_RFPI);
			
			//it parse the data given. In case of data from peripheral, it will update the struct data
			rootPeripheralData=parseDataFromUART(dataRFPI, &numBytesDataRFPI, rootPeripheralData);
		}	
		
		//tell to the GUI the various status
		fifoWriter(FIFO_RFPI_STATUS, statusRFPI); 
		//printf(" statusInit=%s\n",statusRFPI); fflush(stdout); // Prints immediately to screen

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
		//writeFifoJsonOneLinePeripheralLinked(rootPeripheralData);

		//it parse the data coming from the GUI. It will write the FIFO RFPI STATUS. Thus into the FIFO RFPI STATUS there will be written the response after have parsed the data from the GUI.
		rootPeripheralData=ParseFIFOdataGUI(&handleUART, rootPeripheralData);
		
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

