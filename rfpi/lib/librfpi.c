/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					20/11/2020


Description: library for the RFPI

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


#if PLATFORM == PLATFORM_RPI
	#include "bcm2835.c"		//library to manage the GPIO (used version 1.33). 
								//Source: http://www.airspayce.com/mikem/bcm2835/
#endif

#include "wiringSerial.c"	//library to manage the serial port ttyAMA0 (used version). 
							//Source: https://projects.drogon.net/raspberry-pi/wiringpi/ 
							//	( http://wiringpi.com/reference/serial-library/ )
							

//#include "bbb_gpio.c"		//library for the GPIO of the Beaglebone Black

#include "linux_gpio.c"		//library for the GPIO of the  OrangePi Zero

#include <stdio.h>
#include <string.h>

#include <fcntl.h>
#include <sys/stat.h>
#include <sys/types.h>
#include <unistd.h>
#include <time.h>

#include<linux/i2c-dev.h>


//#include "librfpi.h"
#include "email.c"

#include "peri/peri_9.h"
#include "peri/peri_9.c"

#include "rfpi_json.h"
#include "rfpi_json.c"


int random_num(int min, int max){
   return min + rand() / (RAND_MAX / (max - min + 1) + 1);
}

unsigned char checksum (unsigned char *ptr, size_t sz) {
	unsigned char sum=0;
	unsigned char i;
	for(i=0;i<(sz-1);i++){ //last byte is the byte to compare for checksum
		sum += ptr[i];
	}
	if(sum != ptr[sz-1]){
		sum=1; //erro into data!
	}else{
		sum=0; //DATA ALL OK!
	}
    return sum;
}

unsigned char checksum2 (unsigned char *ptr, size_t sz) {
	unsigned char sum=0;
	unsigned char i;
	for(i=1;i<(sz);i++){ //the first byte is the byte to compare for checksum
		sum += ptr[i];
	}
	if(sum != ptr[0]){
		sum=1; //erro into data!
	}else{
		sum=0; //DATA ALL OK!
	}
    return sum;
}

unsigned char calc_checksum (unsigned char *ptr, size_t sz) {
	unsigned char sum=0;
	unsigned char i;
	for(i=0;i<sz;i++){ 
		sum += ptr[i];
	}
    return sum;
}


void blink_led_OPZ_PA18(void){
	for (;;){
		#ifdef LED_YES
		if(sem_serial_port_USB == 0){
			//if(sem_ctrl_led == 1){
				linux_gpio_set_value(18, HIGH_GPIO);	// Led On
				#if DEBUG_LEVEL>0
					printf (" blink ON\n") ;
				#endif
			//}
		}
		#endif
		usleep  (200*1000) ;	//200 mS
		#ifdef LED_YES
		if(sem_serial_port_USB == 0){
			//if(sem_ctrl_led == 1){
				linux_gpio_set_value(18, LOW_GPIO);	// Led Off
				#if DEBUG_LEVEL>0
					printf (" blink OFF\n") ;
				#endif
			//}
		}
		#endif
		usleep  (200*1000) ;	//200 mS
	}
}


int nsleep(long miliseconds)
{
   struct timespec req, rem;

   if(miliseconds > 999)
   {   
        req.tv_sec = (int)(miliseconds / 1000);                            // Must be Non-Negative 
        req.tv_nsec = (miliseconds - ((long)req.tv_sec * 1000)) * 1000000; // Must be in range of 0 to 999999999
   }   
   else
   {   
        req.tv_sec = 0;                         // Must be Non-Negative 
        req.tv_nsec = miliseconds * 1000000;    // Must be in range of 0 to 999999999 
   }   

   return nanosleep(&req , &rem);
}

//delay milliseconds
void delay_ms(unsigned int millis){
	unsigned int i,j;

	//millis=millis*2; //to delete! it is here to solve temporarily problem communication.

	struct timespec sleeper;
 
    sleeper.tv_sec  = (time_t)(millis / 1000);
    sleeper.tv_nsec = (long)(millis % 1000) * 1000000;


	nanosleep(&sleeper, NULL);
		
}


//it will init the serial communication between Transceiver and Raspberry Pi. 
unsigned int InitSerialCommunication(int *handleUART, char *serial_port_path){ 	
	char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF];
	char *cmd=0;
	unsigned int i=0;
	int numCharacters=0;
	unsigned int baud=9600;
	unsigned int varTmpExit=1;

	varTmpExit=0;
	do{
		if(strcmp(serial_port_path,"null")!=0){
			*handleUART = serialOpen (serial_port_path, baud) ;
			
		}else{
			*handleUART = serialOpen (SERIAL_PORT_PATH, baud) ;
			
		}
		
		delay_ms(500);
		
		serialPrintf(*handleUART, "C85" ) ; //sending the command to the Radio		
		delay_ms(CMD_WAIT1);
		
		delay_ms(400);
		
		numCharacters=serialDataAvail (*handleUART) ;
		if(numCharacters>0){ 
			#if DEBUG_LEVEL>0
				if(numCharacters>=MAX_LEN_BUFFER_ANSWER_RF) printf("\n Too high quantity of data: %d \n", numCharacters);
			#endif
			for(i=0;i<numCharacters && i<MAX_LEN_BUFFER_ANSWER_RF;i++){ 
				answerRFPI[i] =serialGetchar(*handleUART) ;
			}
			if(answerRFPI[0]=='*')
				varTmpExit=1;
			
			

			
			numCharacters = 0;
		}
		if(varTmpExit!=1){
			serialClose (*handleUART) ; //closing the serial port
		}
		
		if(varTmpExit==0){
			if(baud==38400)
				baud=57600;
			else
				baud=baud*2;
		}
			
	}while(baud<=115200 && varTmpExit==0);	
	
	#if DEBUG_LEVEL>0
		if(varTmpExit==1){
			printf(" Baud rate set: %d\n", baud);
		}else{
			printf("### SOMETHING WENT WRONG WITH THE SERIAL COMMUNICATION! ###\n");
		}
		fflush(stdout); // Prints immediately to screen 
	#endif
	
	return varTmpExit;
}


//it will init the serial communication between Transceiver and Raspberry Pi. 
//It chang the baudrate of the transceiver with the one defined by BAUD_RATE_SERIAL_PORT
unsigned int InitSerialCommunicationWithDefaultBaudRate(int *handleUART, char *serial_port_path){ 	
	char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF];
	char *cmd=0;
	unsigned int i=0;
	int numCharacters;
	unsigned int baud=9600;
	unsigned int varTmpExit=1;
	
	#if DEBUG_LEVEL>0
		printf("\nI am going to set the speed of the serial port to %d on the path %s\n", BAUD_RATE_SERIAL_PORT, serial_port_path);
	#endif
	
	varTmpExit=0;
	do{

		if(strcmp(serial_port_path,"null")!=0){
			*handleUART = serialOpen (serial_port_path, baud) ;
			
		}else{
			*handleUART = serialOpen (SERIAL_PORT_PATH, baud) ;
			
		}

		if(BAUD_RATE_SERIAL_PORT==9600){
			SerialCmdRFPi(handleUART, "C553", answerRFPI, CMD_WAIT1);
		}else if(BAUD_RATE_SERIAL_PORT==19200){
			SerialCmdRFPi(handleUART, "C554", answerRFPI, CMD_WAIT1);
		}else if(BAUD_RATE_SERIAL_PORT==38400){
			SerialCmdRFPi(handleUART, "C555", answerRFPI, CMD_WAIT1);
		}else if(BAUD_RATE_SERIAL_PORT==57600){
			SerialCmdRFPi(handleUART, "C556", answerRFPI, CMD_WAIT1);
		}else if(BAUD_RATE_SERIAL_PORT==115200){
			SerialCmdRFPi(handleUART, "C557", answerRFPI, CMD_WAIT1);
		}

		serialPrintf(*handleUART, "C85" ) ; //sending the command to the Radio		
		delay_ms(CMD_WAIT1);
		
		numCharacters=serialDataAvail (*handleUART) ;
		if(numCharacters>0){ 
			#if DEBUG_LEVEL>0
				if(numCharacters>=MAX_LEN_BUFFER_ANSWER_RF) printf("\n Too high quantity of data: %d \n", numCharacters);
			#endif
			for(i=0;i<numCharacters && i<MAX_LEN_BUFFER_ANSWER_RF;i++){ 
				answerRFPI[i] =serialGetchar(*handleUART) ;
			}
			if(answerRFPI[0]=='*')
				varTmpExit=1;
			
			

			
			numCharacters = 0;
		}
		serialClose (*handleUART) ; //closing the serial port
		
		if(varTmpExit==0){
			if(baud==38400)
				baud=57600;
			else
				baud=baud*2;
		}
			
	}while(baud<=115200 && varTmpExit==0);	
	
	delay_ms(700);

	if(strcmp(serial_port_path,"null")!=0){
			*handleUART = serialOpen (serial_port_path, BAUD_RATE_SERIAL_PORT) ;
	}else{
			*handleUART = serialOpen (SERIAL_PORT_PATH, BAUD_RATE_SERIAL_PORT) ;
	}
	
	delay_ms(700);
	
	serialPrintf(*handleUART, "C85" ) ; //sending the command to the Radio		
	delay_ms(CMD_WAIT1);
	numCharacters=serialDataAvail (*handleUART) ;
	if(numCharacters>0){ 
		#if DEBUG_LEVEL>0
			if(numCharacters>=MAX_LEN_BUFFER_ANSWER_RF) printf("\n Too high quantity of data: %d \n", numCharacters);
		#endif
		for(i=0;i<numCharacters && i<MAX_LEN_BUFFER_ANSWER_RF;i++){ 
			answerRFPI[i] =serialGetchar(*handleUART) ;
		}
		if(answerRFPI[0]=='*'){
			varTmpExit=1;
			#if DEBUG_LEVEL>0
				printf(" Baud rate set: %d\n", baud);
			#endif
		}else{
			varTmpExit=0;
			serialClose (*handleUART) ; //closing the serial port
			#if DEBUG_LEVEL>0
				printf("### SOMETHING WENT WRONG WITH THE SERIAL COMMUNICATION! ###\n");
			#endif
		}
			
	}
	

	fflush(stdout); // Prints immediately to screen 
	
	return varTmpExit;
}


//it reset the Transceiver and turn on the LED called DS2
void ResetRFPI(void){ 
	
	#ifndef SERIAL_PORT_FTDI_USB
	if(sem_serial_port_USB == 0){ 
		#if PLATFORM == PLATFORM_RPI
			// Set the pin to be an output
			bcm2835_gpio_fsel(PIN_RESET, BCM2835_GPIO_FSEL_OUTP);
			#ifdef LED_YES
				bcm2835_gpio_fsel(PIN_LED_DS1, BCM2835_GPIO_FSEL_OUTP);
				bcm2835_gpio_fsel(PIN_LED_DS2, BCM2835_GPIO_FSEL_OUTP);
				bcm2835_gpio_set(PIN_LED_DS1);
				// Turn it on
				bcm2835_gpio_write(PIN_LED_DS2, HIGH);
			#endif
			bcm2835_gpio_write(PIN_RESET, LOW);
			// wait
			delay_ms(1000);
			// turn it off
			#ifdef LED_YES
				bcm2835_gpio_write(PIN_LED_DS2, LOW);
			#endif
			bcm2835_gpio_write(PIN_RESET, HIGH);
			// wait 
			delay_ms(1000);
		#endif

		#if PLATFORM == PLATFORM_BBB
			#ifdef LED_YES
				linux_gpio_set_value(BBB_PIN_LED_DS2, LOW_GPIO);
			#endif
			linux_gpio_set_value(BBB_PIN_RESET, LOW_GPIO);
			// wait
			delay_ms(1000);
			#ifdef LED_YES
				linux_gpio_set_value(BBB_PIN_LED_DS2, HIGH_GPIO);
			#endif
			linux_gpio_set_value(BBB_PIN_RESET, HIGH_GPIO);
		#endif
		
		#if PLATFORM == PLATFORM_OPZ
			#ifdef LED_YES
				linux_gpio_set_value(OPZ_PIN_LED_DS2, LOW_GPIO);	// Led On		
			#endif
			linux_gpio_set_value(OPZ_PIN_RESET, LOW_GPIO);
			// wait
			delay_ms(1000);
			#ifdef LED_YES
				linux_gpio_set_value(OPZ_PIN_LED_DS2, HIGH_GPIO);	// Led On
			#endif
			linux_gpio_set_value(OPZ_PIN_RESET, HIGH_GPIO);
		#endif
	}
	#endif
	
}


//it will set the baud rate 
void SetBaud(int *handleUART){  

			char *cmd=0;
			unsigned int baud=0;
			
			#if DEBUG_LEVEL>0
				printf(" Type the baud rate wanted: ");
			#endif
			scanf("%d",&baud);
			
			if(baud==1200 || baud==2400 || baud==4800 || baud==9600 || baud==19200 || baud==38400 || baud==57600 || baud==115200){
				//sending the command C55 to the Transceiver to change the baud rate
					
				cmd=GetCmdToSetBaud(&baud);
				
				serialPrintf (*handleUART, cmd ) ; //sending the command to the Transceiver to change the baud rate of the serial port
	
				serialClose (*handleUART) ; //closing the serial on the Raspberry Pi
				*handleUART = serialOpen (SERIAL_PORT_PATH, baud) ; //opening the serial on the Raspberry Pi with a specified Baud Rate
				#if DEBUG_LEVEL>0
					printf(" Baud rate set: %d\n", baud);
				#endif
			}else{
				#if DEBUG_LEVEL>0
					printf(" BAUD RATE NOT ALLOWED!\n");
				#endif
				baud=9600;
			}
}


//it will return the command for the Transceiver to set the baud rate passed
char* GetCmdToSetBaud(unsigned int *baud){ 
	char *cmdStr = (char*) malloc( 5 );
	switch (*baud){
					case 1200:
						strcpy( cmdStr, "C550" ); //command to set the baud rate at 1200
						break;
					case 2400:
						strcpy( cmdStr, "C551" ); //command to set the baud rate at 2400
						break;
					case 4800:
						strcpy( cmdStr, "C552" ); //command to set the baud rate at 4800
						break;
					case 9600:
						strcpy( cmdStr, "C553" ); //command to set the baud rate at 9600
						break;
					case 19200:
						strcpy( cmdStr, "C554" ); //command to set the baud rate at 19200
						break;
					case 38400:
						strcpy( cmdStr, "C555" ); //command to set the baud rate at 38400
						break;
					case 57600:
						strcpy( cmdStr, "C556" ); //command to set the baud rate at 57600
						break;
					case 115200:
						strcpy( cmdStr, "C557" ); //command to set the baud rate at 115200
						break;
					default:
						strcpy( cmdStr, "C553" ); //command to set the baud rate at 9600
						break;
				}
	return cmdStr; 

}


//it create a fifo with written inside the data passed
int fifoWriter(char *fifoname, char *data) {
	char dataFifoSync[10];
	int i=0;
	FILE* file_pointer=0;

		if(access(fifoname, F_OK) == 0){
			//remove(fifoname);
			file_pointer  = fopen(fifoname,  "w+");
			if(file_pointer != NULL){
				//fputs(data, file_pointer);
				fprintf(file_pointer, "%s", data);
			}else{
				#if DEBUG_LEVEL>0
					printf("Impossible to write into FIFO: %s\n",fifoname); fflush(stdout);
				#endif
			}
			fclose(file_pointer);
			//chmod(FIFO_RFPI_STATUS, 0777);
		}else{
			#if DEBUG_LEVEL>0
				printf("No access to FIFO: %s\n",fifoname); fflush(stdout);
			#endif
		}
	
    return 0;
}



int fifoReader(char *data, char *fifoname){ 
	int varReturn=0;
	int fifo_handle=0;
	int fifo_handle_sync=0;
	char dataFifoSync[10];
	
	FILE* file_pointer=0;

	
	if(access(FIFO_GUI_CMD_SYNC, F_OK) == 0){
		file_pointer  = fopen(FIFO_GUI_CMD_SYNC,  "r");
		fscanf(file_pointer, "%s ", dataFifoSync);

		if(dataFifoSync[0] == '1'){	
			if(access(fifoname, F_OK) == 0){
				fifo_handle = open(fifoname, O_RDWR);  
				read(fifo_handle, data, MAX_BUF_FIFO_GUI_CMD);
				close(fifo_handle);
				varReturn = 1;
			}else{ //not exist!
				data[0]='\0';
				varReturn = 0;
			}
			
			rewind(file_pointer);
			freopen(NULL,"w+",file_pointer);
			fprintf(file_pointer, "%d", 0);
			fflush(file_pointer);
			
		}

		fclose(file_pointer);

	}else{ //not exist!
		data[0]='\0';
		varReturn = 0;
	}
	
	return varReturn;

}


//convert a int with value between 0 and 255 to a hexadecimal, example: 255 return FF
void byteToHexStr(char *strHex, int num){
	char hex[] = "0123456789ABCDEF";
	int i=0;
	strHex[3]='\0';
	i=num&0b00001111;
	strHex[1]=hex[i];		
	//it shift the 
	i=((int)((unsigned int)num >> 4))&0b00001111;  
	strHex[0]=hex[i];
}

 
//it just print on the terminal the data into all struct used for the Transceiver
void printPeripheralStructData(peripheraldata *rootPeripheralData){
	#if DEBUG_LEVEL>0
	peripheraldata *currentPeripheralData=0;
	peripheraldatanameinput *currentPeripheralDataNameInput=0;
	peripheraldatanameoutput *currentPeripheralDataNameOutput=0;
	
	currentPeripheralData=rootPeripheralData;
	printf("\n\n");
	int i=0;
	while((currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
		printf("IDtype=%d    TYPE=%d    NAME=%s\n", i, currentPeripheralData->IDtype, currentPeripheralData->Name);
		
		printf("   ---------INPUT(%d)---------\n", currentPeripheralData->NumInput);
		currentPeripheralDataNameInput=currentPeripheralData->rootNameInput;
		currentPeripheralDataNameInput->StatusInput;
		while(currentPeripheralDataNameInput!=0 && currentPeripheralData->NumInput!=0){
			printf("   %s  %d \n", currentPeripheralDataNameInput->NameInput, (int)(currentPeripheralDataNameInput->StatusInput));
			
			currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
		}
		printf("   ---------OUTPUT(%d)---------\n", currentPeripheralData->NumOutput);
		currentPeripheralDataNameOutput=currentPeripheralData->rootNameOutput;
		while(currentPeripheralDataNameOutput!=0 && currentPeripheralData->NumOutput!=0){
			printf("   %s  %d \n", currentPeripheralDataNameOutput->NameOutput, (int)(currentPeripheralDataNameOutput->StatusOutput));
			
			currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
		}
		printf("\n\n");
		
		i++;
		currentPeripheralData=currentPeripheralData->next;
	}
	#endif
 }
 
 
//it read all files necessary to construct the struct containing the data of all peripheral linked
peripheraldata *loadLinkedPeripheral(int *handleUART){

	//variable used to read the files descriptor of each peripheral
	int contJ, contL, contK, lastCont, maxLenLine, varExit;
	contJ = contL = contK = lastCont = maxLenLine = varExit = 0;
	char strLineFile[MAX_LEN_LINE_FILE]; strLineFile[0]='\0';
							
			
	//pointers used to manage the data of all linked peripheral
	peripheraldata *rootPeripheralData=NULL;
	peripheraldata *currentPeripheralData=NULL;
	peripheraldata *nextPeripheralData=NULL;
	
	//pointers used to manage the struct with the name of input
	peripheraldatanameinput *rootPeripheralDataNameInput=NULL; 
	peripheraldatanameinput *currentPeripheralDataNameInput=NULL; 
	peripheraldatanameinput *nextPeripheralDataNameInput=NULL; 
	peripheraldatanameinput *previousPeripheralDataNameInput=NULL; 
	
	//pointers used to manage the struct with the name of output
	peripheraldatanameoutput *rootPeripheralDataNameOutput=NULL; 
	peripheraldatanameoutput *currentPeripheralDataNameOutput=NULL; 
	peripheraldatanameoutput *nextPeripheralDataNameOutput=NULL; 
	peripheraldatanameoutput *previousPeripheralDataNameOutput=NULL; 
	
	int NumPeripheral = 0; //used to keep the number of linked peripheral
	int IdPeripheral = 0; //used to read the file with the list of peripheral
	int TypePeripheral = 0; //used to read the file with the list of files descriptor
	char NamePeripheral[MAX_LEN_NAME];NamePeripheral[0]='\0'; //used to read the file with the list of peripheral
	char NameFileDescriptor[MAX_LEN_NAME]; NameFileDescriptor[0]='\0'; //used to read the file descriptor
	char strPathFile[MAX_LEN_PATH]; strPathFile[0]='\0'; //used to read the file descriptor
	unsigned char peripheralAddress[5];
	int numSpecialFunction = 0;
	int fwVersion = 0;
	
	char strNum[10]; strNum[0]='\0'; //used to convert int to string
	char strMessage[30]; strMessage[0]='\0';//used to construct message, used in multiple places. it is temporary variable
	char strTemp[MAX_LEN_PATH]; strTemp[0]='\0';
	char strHex[10];strHex[0]='\0';
	
	FILE *file_pointer=NULL; //generic pointer to file, used in multiple places
	FILE *file_pointer2=NULL; //generic pointer to file, used in multiple places
	FILE *file_pointer3=NULL; //generic pointer to file, used in multiple places
	
	char  *pointer=NULL;
	char   buffer[256]; buffer[0]='\0';
	
	int i,l,cont_line;
	i=l=cont_line=0;
	
	char networkAddress[5]; networkAddress[0]='\0';
	char networkName[MAX_LEN_NET_NAME]; networkName[0]='\0';

	if(!readNetworkNameFromFile(networkName)){
		addressFromName(networkName, networkAddress);
	}else{
		strcpy(networkAddress,"C733");
	}
	
	
	//load in memory the list of the file descriptor
	
	file_pointer = fopen(FILE_LIST_PERIPHERAL,"r"); //opening the list of peripherals 
	if( file_pointer == NULL){
			perror("Error while opening the file.\n");
			//exit(EXIT_FAILURE);
	}else{ 
			i=-1;
			l=0; 
			varExit=0;
			NumPeripheral=0; 
			cont_line=0;
			while ((!feof(file_pointer)) && varExit==0 && cont_line<32000) {  
				
				IdPeripheral = 0;
				strcpy(NamePeripheral,"unknown");
				strcpy(NameFileDescriptor,"");
				strcpy(peripheralAddress,"FFFF");
				numSpecialFunction = 0;
				fwVersion = 0;
				
				//if (fscanf(file_pointer, "%d %d %s %s %s %d %d\n", &i, &IdPeripheral, NamePeripheral, NameFileDescriptor, peripheralAddress, &numSpecialFunction, &fwVersion)) 
				if ((pointer = fgets(buffer, sizeof(buffer), file_pointer)) != NULL){
					size_t field;
					char  *token;

					field = 0;
					while ((token = strtok(pointer, " ")) != NULL){
						token[strcspn(token, "\n")] = 0;
						
						if(field == 0) i=atoi(token); 
						if(field == 1) IdPeripheral=atoi(token);
						if(field == 2) strcpy(NamePeripheral,token);
						if(field == 3) strcpy(NameFileDescriptor,token);
						if(field == 4) strcpy(peripheralAddress,token);
						if(field == 5) numSpecialFunction=atoi(token); 
						if(field == 6) fwVersion=atoi(token); 

						field  += 1;
						pointer = NULL;
					}
					#if DEBUG_LEVEL>0
					printf("\n LINE GOT = %d %d %s %s %s %d %d \n", i,IdPeripheral, NamePeripheral, NameFileDescriptor, peripheralAddress, numSpecialFunction, fwVersion); fflush(stdout); // Prints immediately to screen 
					#endif
				}
				cont_line++;
				
				if(IdPeripheral>0)
				{  
					//if there is the first line into the file, it is possible to init the root pointer to the struct
					if(l==0){
						rootPeripheralData=(peripheraldata*)malloc(sizeof(peripheraldata));
						rootPeripheralData->next=0;
						rootPeripheralData->Name=0;
						rootPeripheralData->NameFileDescriptor=0;
						rootPeripheralData->rootNameInput=0;
						rootPeripheralData->rootNameOutput=0;
						
						currentPeripheralData=rootPeripheralData;
					}else{ 
						nextPeripheralData=(peripheraldata*)malloc(sizeof(peripheraldata));
						nextPeripheralData->next=0; 
						nextPeripheralData->Name=0;
						nextPeripheralData->NameFileDescriptor=0;
						nextPeripheralData->rootNameInput=0;
						nextPeripheralData->rootNameOutput=0;
						
						currentPeripheralData->next=nextPeripheralData;
						currentPeripheralData=nextPeripheralData;
					}
					
					//saving the address of the peripheral
					//the address of the peripheral is given by the position in the list
					strcpy(currentPeripheralData->PeriAddress, peripheralAddress);	

					//saving the address of the network
					strcpy(currentPeripheralData->NetAddress, networkAddress); 
					
					NumPeripheral++;
					
					//saving the type of peripheral
					currentPeripheralData->IDtype=IdPeripheral;
					
					//saving the number of the special functions
					currentPeripheralData->numSpecialFunction = numSpecialFunction;
					
					//saving the release of the firmware of the peripheral
					currentPeripheralData->fwVersion = fwVersion;
					
					//saving the name of the peripheral
					currentPeripheralData->Name = (char*)malloc((strlen(NamePeripheral)+1)*sizeof(char));
					strcpy(currentPeripheralData->Name, NamePeripheral);

				
					//saving the name of the file descriptor
					currentPeripheralData->NameFileDescriptor = (char*)malloc((strlen(NameFileDescriptor)+1)*sizeof(char));
					strcpy(currentPeripheralData->NameFileDescriptor, NameFileDescriptor);

					if(strlen(NameFileDescriptor)==0){
					
						currentPeripheralData->NumInput=1;
						currentPeripheralData->NumOutput=1;

						strcpy(strMessage, "NO_FILE_DESCRIPTOR");
						
						//init the struct of the input names
						rootPeripheralDataNameInput = (peripheraldatanameinput*)malloc(sizeof(peripheraldatanameinput));
						rootPeripheralDataNameInput->next=0;
						rootPeripheralDataNameInput->NameInput=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
						rootPeripheralDataNameInput->Type=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
						
						//init the struct of the output names
						rootPeripheralDataNameOutput = (peripheraldatanameoutput*)malloc(sizeof(peripheraldatanameoutput));
						rootPeripheralDataNameOutput->next=0;
						rootPeripheralDataNameOutput->NameOutput=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
						rootPeripheralDataNameOutput->Type=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
						
						//returning an error message into the names 
						strcpy(rootPeripheralDataNameInput->NameInput, strMessage);
						strcpy(rootPeripheralDataNameOutput->NameOutput, strMessage);
						strcpy(rootPeripheralDataNameInput->Type, strMessage);
						strcpy(rootPeripheralDataNameOutput->Type, strMessage);
						
						rootPeripheralDataNameInput->StatusInput=-1;
						rootPeripheralDataNameOutput->StatusOutput=-1;
						
						rootPeripheralDataNameInput->StatusCommunication=-1;
						rootPeripheralDataNameOutput->StatusCommunication=-1;
						
						rootPeripheralDataNameInput->id_shield_connected=-1;
						rootPeripheralDataNameOutput->id_shield_connected=-1;
						
						rootPeripheralDataNameInput->num_pin_used_on_the_peri=-1;
						rootPeripheralDataNameOutput->num_pin_used_on_the_peri=-1;
						
						//assigning the roots of the struct names to the main struct
						currentPeripheralData->rootNameInput=rootPeripheralDataNameInput;
						currentPeripheralData->rootNameOutput=rootPeripheralDataNameOutput;
				
					}else{  
						//if the file descriptor exist it check if it is not blank
						strcpy(strPathFile, PATH_CONFIG_FILE); //copying the path where is the file
						strcat(strPathFile, NameFileDescriptor); //adding the name of the file descriptor
						file_pointer3 = fopen(strPathFile,"r"); //opening the file descriptor of the current peripheral
						
						if(file_pointer3==0){ //the file does not exist!
							//RECREATING THE FILE DESCRIPOTR
							file_pointer3 = fopen(strPathFile,"w+");
							fprintf(file_pointer3,"0 \n"); //writing on the file the line of the inputs
							fprintf(file_pointer3,"0 \n"); //writing on the file the line of the inputs
							fclose(file_pointer3);
														
							strcpy(statusInit,ERROR004);
							strcat(statusInit, NameFileDescriptor);
														
							file_pointer3 = fopen(FILE_ERROR_HISTORY,"w+");
							fprintf(file_pointer3,"%s\n",statusInit); //writing on the file the line of the inputs
							fclose(file_pointer3);
							
							file_pointer3 = fopen(strPathFile,"r"); //opening the file descriptor just created							
							
						}
						if(!feof(file_pointer3)){//file descriptor blank?
							
							//init the struct of the input names
							rootPeripheralDataNameInput = (peripheraldatanameinput*)malloc(sizeof(peripheraldatanameinput));
							rootPeripheralDataNameInput->next=0;
							rootPeripheralDataNameInput->NameInput=0;
							rootPeripheralDataNameInput->Type=0;
							rootPeripheralDataNameInput->StatusInput=-1;
							rootPeripheralDataNameInput->StatusCommunication=-1;
							rootPeripheralDataNameInput->id_shield_connected=-1;
							rootPeripheralDataNameInput->num_pin_used_on_the_peri=-1;
						
						
							//init the struct of the output names
							rootPeripheralDataNameOutput = (peripheraldatanameoutput*)malloc(sizeof(peripheraldatanameoutput));
							rootPeripheralDataNameOutput->next=0;
							rootPeripheralDataNameOutput->NameOutput=0;
							rootPeripheralDataNameOutput->Type=0;
							rootPeripheralDataNameOutput->StatusOutput=-1;
							rootPeripheralDataNameOutput->StatusCommunication=-1;
							rootPeripheralDataNameOutput->id_shield_connected=-1;
							rootPeripheralDataNameOutput->num_pin_used_on_the_peri=-1;
						
							//assigning the roots of the struct names to the main struct
							currentPeripheralData->rootNameInput=rootPeripheralDataNameInput;
							currentPeripheralData->rootNameOutput=rootPeripheralDataNameOutput;
							
							//assigning the pointers strucs of the names
							currentPeripheralDataNameInput=rootPeripheralDataNameInput;
							currentPeripheralDataNameOutput=rootPeripheralDataNameOutput;
						
						
							//get the first line of the file descriptor which are the inputs
							if(fgets(strLineFile, MAX_LEN_LINE_FILE, file_pointer3)){
								
								//get number of input
								maxLenLine=strlen(strLineFile);
								contJ=0;
								strNum[0]='\0';
								lastCont=0;
								while (contJ<maxLenLine) {
									if(strLineFile[contJ]==' '){
										lastCont=contJ;
										contJ=maxLenLine;
									}else{
										strNum[contJ] = strLineFile[contJ];
										contJ++;
									}
								}
								strNum[lastCont]='\0'; 
								
								currentPeripheralData->NumInput=atoi(strNum);
								
								if(currentPeripheralData->NumInput>0){
									//get all type of the input
									contJ=lastCont+1;
									contL=0; 
									contK=0;
									while (contL<currentPeripheralData->NumInput && contJ<maxLenLine) {
										if(strLineFile[contJ]==' ' || strLineFile[contJ]=='\n'){ 
											strMessage[contK]='\0';
											contL++;
											contK=0;
											
											//reserve area of memory for the type
											currentPeripheralDataNameInput->Type=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
											strcpy(currentPeripheralDataNameInput->Type,strMessage);
											
											currentPeripheralDataNameInput->StatusInput=-1;
											currentPeripheralDataNameInput->StatusCommunication=-1;
											currentPeripheralDataNameInput->id_shield_connected=-1;
											currentPeripheralDataNameInput->num_pin_used_on_the_peri=-1;
											
											//reserve area of memory for the next struct of name
											nextPeripheralDataNameInput = (peripheraldatanameinput*)malloc(sizeof(peripheraldatanameinput));
											nextPeripheralDataNameInput->next=0;
											
											//keep track of the previous in oprder to delete the last malloc not used when exit from the loop
											previousPeripheralDataNameInput=currentPeripheralDataNameInput;
											
											//set the pointer to point to the new area of memory not yet utilised
											currentPeripheralDataNameInput->next=nextPeripheralDataNameInput;
											currentPeripheralDataNameInput=nextPeripheralDataNameInput;
										}else{
											strMessage[contK]=strLineFile[contJ];
											contK++;
										}
										contJ++;
									}
									
									//delete the last malloc, because when it exit from the loop the last struct is not used
									free(currentPeripheralDataNameInput);
									previousPeripheralDataNameInput->next=0; 
									
									//getting bit resolution of the input
									currentPeripheralDataNameInput=rootPeripheralDataNameInput;
									contL=0;
									contK=0; 
									while (contL<currentPeripheralData->NumInput && contJ<maxLenLine && currentPeripheralDataNameInput!=0) {
										if(strLineFile[contJ]==' ' || strLineFile[contJ]=='\n'){ 
											strMessage[contK]='\0';
											contL++;
											contK=0;
													
											currentPeripheralDataNameInput->BitResolution = atoi(strMessage);
											
											currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
										}else{
											strMessage[contK]=strLineFile[contJ];
											contK++;
										}
										contJ++;
									}

								}else{
									//if there are no name input
									strcpy(strMessage, "No inputs.");
									//currentPeripheralDataNameInput->NameInput=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
									currentPeripheralDataNameInput->Type=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
									//strcpy(currentPeripheralDataNameInput->NameInput, strMessage);
									strcpy(currentPeripheralDataNameInput->Type, strMessage);
									currentPeripheralData->NumInput=0;
								}

							}
			
			
							//get the second line of the file descriptor which are the outputs
							if(fgets(strLineFile, MAX_LEN_LINE_FILE, file_pointer3)){
								
								//get number of outputs
								maxLenLine=strlen(strLineFile);
								contJ=0;
								strNum[0]='\0';
								lastCont=0;
								while (contJ<maxLenLine) {
									if(strLineFile[contJ]==' '){
										lastCont=contJ;
										contJ=maxLenLine;
									}else{
										strNum[contJ] = strLineFile[contJ];
										contJ++;
									}
								}
								strNum[lastCont]='\0'; 
							
								currentPeripheralData->NumOutput=atoi(strNum);
								
								if(currentPeripheralData->NumOutput>0){
									//get all name of the outputs
									contJ=lastCont+1;
									contL=0; 
									contK=0;
									while (contL<currentPeripheralData->NumOutput && contJ<maxLenLine) {
										if(strLineFile[contJ]==' ' || strLineFile[contJ]=='\n'){ 
											strMessage[contK]='\0';
											contL++;
											contK=0;
											
											//reserve area of memory for the name
											currentPeripheralDataNameOutput->Type=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
											strcpy(currentPeripheralDataNameOutput->Type,strMessage);
											
											currentPeripheralDataNameOutput->StatusOutput=-1;
											currentPeripheralDataNameOutput->StatusCommunication=-1;
											currentPeripheralDataNameOutput->id_shield_connected=-1;
											currentPeripheralDataNameOutput->num_pin_used_on_the_peri=-1;
											
											//reserve area of memory for the next struct of name
											nextPeripheralDataNameOutput = (peripheraldatanameoutput*)malloc(sizeof(peripheraldatanameoutput));
											nextPeripheralDataNameOutput->next=0;
											
											//keep track of the previous in oprder to delete the last malloc not used when exit from the loop
											previousPeripheralDataNameOutput=currentPeripheralDataNameOutput;
											
											//set the pointer to point to the new area of memory not yet utilised
											currentPeripheralDataNameOutput->next=nextPeripheralDataNameOutput;
											currentPeripheralDataNameOutput=nextPeripheralDataNameOutput;
										}else{
											strMessage[contK]=strLineFile[contJ];
											contK++;
										}
										contJ++;
									}
									
									//delete the last malloc, because when it exit from the loop the last struct is not used
									free(currentPeripheralDataNameOutput);
									previousPeripheralDataNameOutput->next=0; 
									
									//getting bit resolution of the outputs
									currentPeripheralDataNameOutput=rootPeripheralDataNameOutput;
									contL=0;
									contK=0;
									while (contL<currentPeripheralData->NumOutput && contJ<maxLenLine && currentPeripheralDataNameOutput!=0) {
										if(strLineFile[contJ]==' ' || strLineFile[contJ]=='\n'){ 
											strMessage[contK]='\0';
											contL++;
											contK=0;
														
											currentPeripheralDataNameOutput->BitResolution = atoi(strMessage);
											
											currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
										}else{
											strMessage[contK]=strLineFile[contJ];
											contK++;
										}
										contJ++;
									}
									
								}else{
									//if there are no name output
									strcpy(strMessage, "No output.");
									currentPeripheralDataNameOutput->Type=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
									strcpy(currentPeripheralDataNameOutput->Type, strMessage);
									currentPeripheralData->NumOutput=0;
								}

							}
							
							
							
							//get the third line of the file descriptor which are the name of the inputs
							currentPeripheralDataNameInput=rootPeripheralDataNameInput;
							if(fgets(strLineFile, MAX_LEN_LINE_FILE, file_pointer3)){
								
								//get number of input
								maxLenLine=strlen(strLineFile);
								contJ=0;
								strNum[0]='\0';
								lastCont=0;
								while (contJ<maxLenLine) {
									if(strLineFile[contJ]==' '){
										lastCont=contJ;
										contJ=maxLenLine;
									}else{
										strNum[contJ] = strLineFile[contJ];
										contJ++;
									}
								}
								strNum[lastCont]='\0'; 

								if(currentPeripheralData->NumInput>0){
									//get all name of the input
									contJ=lastCont+1;
									contL=0; 
									contK=0;
									while (contL<currentPeripheralData->NumInput && contJ<maxLenLine && currentPeripheralDataNameInput!=0) {
										if(strLineFile[contJ]==' ' || strLineFile[contJ]=='\n'){ 
											strMessage[contK]='\0';
											contL++;
											contK=0;
											
											//reserve area of memory for the type
											currentPeripheralDataNameInput->NameInput=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
											strcpy(currentPeripheralDataNameInput->NameInput,strMessage);

											currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
										}else{
											strMessage[contK]=strLineFile[contJ];
											contK++;
										}
										contJ++;
									}
									
								}else{
									//if there are no name input
									strcpy(strMessage, "NO_NAME");
									currentPeripheralDataNameInput->NameInput=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
									strcpy(currentPeripheralDataNameInput->NameInput, strMessage);
								}

							}
							
							
							//get the fourth line of the file descriptor which are the outputs
							currentPeripheralDataNameOutput=rootPeripheralDataNameOutput;
							if(fgets(strLineFile, MAX_LEN_LINE_FILE, file_pointer3)){
								
								//get number of outputs
								maxLenLine=strlen(strLineFile);
								contJ=0;
								strNum[0]='\0';
								lastCont=0;
								while (contJ<maxLenLine) {
									if(strLineFile[contJ]==' '){
										lastCont=contJ;
										contJ=maxLenLine;
									}else{
										strNum[contJ] = strLineFile[contJ];
										contJ++;
									}
								}
								strNum[lastCont]='\0'; 
								
								if(currentPeripheralData->NumOutput>0){
									//get all name of the outputs
									contJ=lastCont+1;
									contL=0; 
									contK=0;
									while (contL<currentPeripheralData->NumOutput && contJ<maxLenLine && currentPeripheralDataNameOutput!=0) {
										if(strLineFile[contJ]==' ' || strLineFile[contJ]=='\n'){ 
											strMessage[contK]='\0';
											contL++;
											contK=0;
											
											//reserve area of memory for the name
											currentPeripheralDataNameOutput->NameOutput=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
											strcpy(currentPeripheralDataNameOutput->NameOutput,strMessage);

											currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
										}else{
											strMessage[contK]=strLineFile[contJ];
											contK++;
										}
										contJ++;
									}
									
								}else{
									//if there are no name output
									strcpy(strMessage, "NO_NAME");
									currentPeripheralDataNameOutput->NameOutput=(char*)malloc((strlen(strMessage)+1)*sizeof(char));
									strcpy(currentPeripheralDataNameOutput->NameOutput, strMessage);
								}

							}
							
							
							
							
						}else{ 
							//file descriptor is empty
							//assigning to the pointer of the names of Input and Output 0
							currentPeripheralData->rootNameInput=0;
							currentPeripheralData->rootNameOutput=0;
							
							currentPeripheralData->NumInput=0;
							currentPeripheralData->NumOutput=0;
						}
						fclose(file_pointer3);
					}
				}else{
					//no Id peripheral will stop the memorization of data from the file
					varExit=1;
					if(l==0){
						rootPeripheralData=0;
					}else{ 
						currentPeripheralData->next=0;
					}
				}
				
				l++;
				
			}
			fclose(file_pointer);
	}

	return rootPeripheralData;
}


//write the FIFO in order to give all data to the GUI
void writeFifoPeripheralLinked(peripheraldata *rootPeripheralData){

	//pointers used to manage the data of all linked peripheral
	peripheraldata *currentPeripheralData=NULL;
	
	//pointers used to manage the struct with the name of input
	peripheraldatanameinput *currentPeripheralDataNameInput=NULL; 
	
	//pointers used to manage the struct with the name of output
	peripheraldatanameoutput *currentPeripheralDataNameOutput=NULL; 
	
	//int handleFIFO; 
	char data[MAX_LEN_LINE_FILE]; data[0]='\0';
	char strNum[10]; strNum[0]='\0';
	int i=0;
	FILE *file_pointer=0;
	
	file_pointer  = fopen(FIFO_RFPI_PERIPHERAL,  "w+");
	if(file_pointer == NULL){
		#if DEBUG_LEVEL>0
		printf("Impossible to write into FIFO: %s\n",FIFO_RFPI_PERIPHERAL); fflush(stdout);
		#endif
	}else{

		currentPeripheralData=rootPeripheralData;
			
		i=0;
		while((currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
		
			//write first line
			data[0]='\0';
			intToStr (i,strNum); strcat(data,strNum); strcat(data," ");
			intToStr(currentPeripheralData->IDtype, strNum); strcat(data,strNum); strcat(data," ");
			strcat(data,currentPeripheralData->Name);strcat(data," ");

			strcat(data,currentPeripheralData->PeriAddress); strcat(data," ");
			intToStr(currentPeripheralData->numSpecialFunction, strNum); strcat(data,strNum); strcat(data," ");
			intToStr(currentPeripheralData->fwVersion, strNum); strcat(data,strNum); strcat(data,"\n");
			
			//write on the FIFO
			fprintf(file_pointer, "%s", data);
			
			//write second line
			data[0]='\0';
			intToStr(currentPeripheralData->NumInput, strNum); strcat(data,strNum); strcat(data," ");
			currentPeripheralDataNameInput=currentPeripheralData->rootNameInput;
			//write all name of the input
			while(currentPeripheralDataNameInput!=0 && currentPeripheralData->NumInput!=0){
				if(currentPeripheralDataNameInput->NameInput!=0){
					strcat(data,currentPeripheralDataNameInput->NameInput); strcat(data," ");
				}else{
					strcat(data,"NO_NAME"); strcat(data," ");
				}
				
				currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
			}
			
			//write all status of the input
			currentPeripheralDataNameInput=currentPeripheralData->rootNameInput;
			while(currentPeripheralDataNameInput!=0){
				intToStr(currentPeripheralDataNameInput->StatusInput, strNum); 
				strcat(data,strNum); 
				strcat(data," ");
				
				currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
			}
			strcat(data,"\n");
			//write on the FIFO 
			fprintf(file_pointer, "%s", data);
			
			//write third line
			data[0]='\0';
			intToStr(currentPeripheralData->NumOutput, strNum); strcat(data,strNum); strcat(data," ");
			currentPeripheralDataNameOutput=currentPeripheralData->rootNameOutput;
			//write all name of the input
			while(currentPeripheralDataNameOutput!=0 && currentPeripheralData->NumOutput!=0){
				if(currentPeripheralDataNameOutput->NameOutput!=0){
					strcat(data,currentPeripheralDataNameOutput->NameOutput); strcat(data," ");
				}else{
					strcat(data,"NO_NAME"); strcat(data," ");
				}
				
				currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
			}
			//write all status of the input
			currentPeripheralDataNameOutput=currentPeripheralData->rootNameOutput;
			while(currentPeripheralDataNameOutput!=0){
				intToStr(currentPeripheralDataNameOutput->StatusOutput, strNum); 
				strcat(data,strNum); strcat(data," ");
				
				currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
			}
			strcat(data,"\n");
			//write on the FIFO
			fprintf(file_pointer, "%s", data);
			
			i++;
			currentPeripheralData=currentPeripheralData->next;
		}
		
	} 
	fclose(file_pointer);
}

//convert a number in string
char* intToStr(int i, char *b){
    char const digit[] = "0123456789";
    char* p = b;
    if(i<0){
        *p++ = '-';
        i *= -1;
    }
    int shifter = i;
    do{ //Move to where representation ends
        ++p;
        shifter = shifter/10;
    }while(shifter);
    *p = '\0';
    do{ //Move back, inserting digits as u go
        *--p = digit[i%10];
        i = i/10;
    }while(i);
    return b;
}


//read from file the network name, if the network name is not set it will return '\0' 
int readNetworkNameFromFile(char *networkName){
	char strNameTemp[MAX_LEN_NET_NAME], strNameTemp2[MAX_LEN_NET_NAME]; strNameTemp[0]=strNameTemp2[0]='\0';
	FILE *file_pointer=0;
	int i=0;
	
	strNameTemp[0]='\0'; //if is not set, strNameTemp will remain '\0' 
	if( access( FILE_NETWORK_NAME, F_OK ) == -1 ) {
		//if the file with the network name does not exist it will create
		file_pointer = fopen(FILE_NETWORK_NAME,"w");
		fclose(file_pointer);
	}else{
	
		//check if the network name is set into the file
		file_pointer = fopen(FILE_NETWORK_NAME,"r"); 
		if( file_pointer == NULL ){
			perror("Error while opening the file with the network name.\n");
			exit(EXIT_FAILURE);
		}else{	 
			while(!feof(file_pointer)) { 
				if (fscanf(file_pointer, "%s", strNameTemp2) != 1){
					break; 
				}
				i=0;
				while(i<MAX_LEN_NET_NAME && strNameTemp2[i]!='\0'){
					strNameTemp[i]=strNameTemp2[i];
					i++;
				}
				strNameTemp[i]='\0';
			}
			
		}
		fclose(file_pointer);
		
	}
	
	strcpy(networkName, strNameTemp);
		
	if(strNameTemp[0]=='\0'){
		printf(ERROR003); printf("\n");
		return 1;
	}else{
		return 0;
	}
}


//it will send the command to the RFberry module 
void initRFberryNetwork(int *handleUART){

    char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF]; answerRFPI[0]='\0';
	
	//it will set the address for the current network
	setCurrentNetwork(handleUART);

	//setting up others important function
	SerialCmdRFPi(handleUART, "C151", answerRFPI, CMD_WAIT1); //make the RF module to send * when the command has been executed
	SerialCmdRFPi(handleUART, "C091", answerRFPI, CMD_WAIT1); //set auto AKN to on
	SerialCmdRFPi(handleUART, "C100", answerRFPI, CMD_WAIT1); //set frequency hopping off
	SerialCmdRFPi(handleUART, "C111", answerRFPI, CMD_WAIT1); //activating waiting for AKN
	SerialCmdRFPi(handleUART, "C200100", answerRFPI, CMD_WAIT1); //set maximum waiting time 10mS to find the channel free. After this time it start the auto retry cycle.
	SerialCmdRFPi(handleUART, "C210001", answerRFPI, CMD_WAIT1); //set maximum waiting time for AKN 100mS
	SerialCmdRFPi(handleUART, "C220050", answerRFPI, CMD_WAIT1); //set to 5mS the waiting time between two transmission when an error occur
	SerialCmdRFPi(handleUART, "C2302", answerRFPI, CMD_WAIT1); //max number of retry in case of unsuccessful transmission
	SerialCmdRFPi(handleUART, "C291", answerRFPI, CMD_WAIT1); //set the RF module to answer OK when there is a successful transmission  
	
}


//it will set the default network used to search a new peripheral
void setProgramModeNetwork(int *handleUART){

	char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF]; answerRFPI[0]='\0';
	
	SerialCmdRFPi(handleUART, "C01FFFC", answerRFPI, CMD_WAIT1); //set network address
	SerialCmdRFPi(handleUART, "C02FFFC", answerRFPI, CMD_WAIT1); //set address of this device
	SerialCmdRFPi(handleUART, "C03FFFC", answerRFPI, CMD_WAIT1); //set address of the peripheral
	SerialCmdRFPi(handleUART, "C0416", answerRFPI, CMD_WAIT1); //set number of byte to send each tranmission
	
	//SerialCmdRFPi(handleUART, "C072", answerRFPI, CMD_WAIT1); //set TX power
	SerialCmdRFPi(handleUART, "C0005", answerRFPI, CMD_WAIT1); //set the channel to use CH=5
	SerialCmdRFPi(handleUART, "C08", answerRFPI, CMD_WAIT1); //init the radio of RF module
}


//it will set the address for the current network
void setCurrentNetwork(int *handleUART){

	char strCmd[30]; strCmd[0]='\0';
	char networkAddress[5]; networkAddress[0]='\0';
	char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF]; answerRFPI[0]='\0';
	char networkName[MAX_LEN_NET_NAME]; networkName[0]='\0';
	
	if(!readNetworkNameFromFile(networkName)){
		addressFromName(networkName, networkAddress);
		strcpy(strCmd,"C01");
		strcat(strCmd, networkAddress);
		SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1);
	}else{
		SerialCmdRFPi(handleUART, "C01C733", answerRFPI, CMD_WAIT1);
	}
	
	strcpy(strCmd, "C02");
	strcat(strCmd, ADDRESS_RFPI);
	SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1);
	
	SerialCmdRFPi(handleUART, "C0416", answerRFPI, CMD_WAIT1); //set number of byte to send each transmission
	
	SerialCmdRFPi(handleUART, "C073", answerRFPI, CMD_WAIT1); //set TX power
	SerialCmdRFPi(handleUART, "C0001", answerRFPI, CMD_WAIT1); //set the channel to use CH=1
	SerialCmdRFPi(handleUART, "C08", answerRFPI, CMD_WAIT1); //init the radio of RFberry Pi module
	

}


//it init a default network and then send the data to set the current network to the new peripheral
extern peripheraldata *findNewPeripheral(int *handleUART, char *statusRFPI, peripheraldata *rootPeripheralData){


	unsigned char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF]; answerRFPI[0]='\0';
	unsigned char strCmd[30]; strCmd[0]='\0';
	char networkAddress[5]; networkAddress[0]='\0';
	char peripheralAddress[5]; peripheralAddress[0]='\0';
	char peripheralAddressTemp[5]; peripheralAddressTemp[0]='\0';
	char AddressRFPI[5]; AddressRFPI[0]='\0';
	char AddressInt[10]; AddressInt[0]='\0';
	char networkName[MAX_LEN_NET_NAME]; networkName[0]='\0';
	char strHex[10]; strHex[0]='\0';
	char NamePeripheral[MAX_LEN_NAME]; NamePeripheral[0]='\0';
	char NameFileDescriptor[MAX_LEN_NAME]; NameFileDescriptor[0]='\0';
	
	char strTemp[MAX_LEN_LINE_FILE]; strTemp[0]='\0';
	char strTemp2[MAX_LEN_LINE_FILE]; strTemp2[0]='\0';
	
	FILE *file_pointer=0; //generic file pointer, used to update the file
	
	unsigned int numPeripheral=0; //number of peripherals into the struct data
	unsigned int intPeripheralAddress=0; //contain the integer number of the address of the peripheral
	unsigned int NumInput=0; //number of inputs on the peripheral
	unsigned int NumOutput=0; //number of outputs on the peripheral
	unsigned int IDtype=0;
	unsigned int numInputAnalogue=0; 
	unsigned int numInputDigital=0;
	unsigned int numOutputAnalogue=0;
	unsigned int numOutputDigital=0;
	unsigned int numSpecialFunction=0;
	unsigned int fwVersion=0;

	char strPathFile[MAX_LEN_PATH]; strPathFile[0]='\0';
	
	unsigned char byteH=0;
	unsigned char byteL=0;

	int i=0;
	
	unsigned char array_status[20]; array_status[0]='\0';
		
	
	//pointers used to manage the data of all linked peripheral
	peripheraldata *currentPeripheralData=0;
	peripheraldata *nextPeripheralData=0;
	
	//pointers used to manage the struct with the name of input
	peripheraldatanameinput *rootPeripheralDataNameInput=0; 
	peripheraldatanameinput *currentPeripheralDataNameInput=0; 
	peripheraldatanameinput *nextPeripheralDataNameInput=0; 
	peripheraldatanameinput *previousPeripheralDataNameInput=0; 
	
	//pointers used to manage the struct with the name of output
	peripheraldatanameoutput *rootPeripheralDataNameOutput=0; 
	peripheraldatanameoutput *currentPeripheralDataNameOutput=0; 
	peripheraldatanameoutput *nextPeripheralDataNameOutput=0; 
	peripheraldatanameoutput *previousPeripheralDataNameOutput=0; 
	
	
	setProgramModeNetwork(handleUART);
	
	#if DEBUG_LEVEL>0
		printf("------------------BEGIN: ASSIGNING NETWORK PARAMETER------------------\n\n"); fflush(stdout);
	#endif
	
	//strcpy(statusRFPI,"OK"); //if something will go wrong then it will be changed in NOPERI
	strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOPERI); //if everythings goes right then it will become OK

	//init the string
	strcpy(strCmd,"C30RBwHLHLHL......."); 
	
	//setting the network address
	if(!readNetworkNameFromFile(networkName)){
		addressFromName(networkName, networkAddress);
		sscanf(networkAddress,"%X",&i); //convert string hex in integer
	}else{
		//address C733
		sscanf("C733","%X",&i); //convert string hex in integer
	}
	#if DEBUG_LEVEL>0
		printf("Network Address to assign: %s \n", networkAddress); fflush(stdout);
	#endif
	
	//dividing the integer address contained into i in two bytes High and Low and copying into the strCmd
	byteH=0;
	byteH=byteH | (i>>8);
	strCmd[6]=byteH;
	byteL=0;
	byteL=byteL | i;
	strCmd[7]=byteL;

	//assigning the address to the peripheral
	//solved bug in 1.1
	numPeripheral=0;
	intPeripheralAddress=1; //assigning the first address, in case after it will find is used it will change
	i=0;
	if(!(rootPeripheralData==0)){
		currentPeripheralData=rootPeripheralData;
		while((currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
			i++;
			
			sscanf(currentPeripheralData->PeriAddress,"%X",&numPeripheral); //convert string hex in integer
			
			//check the first address free to assign to the new peripheral
			if(intPeripheralAddress==numPeripheral && i!=43775){ //43775 is the address of the master (Transceiver)
				//if the address is used then has to change the address (adding 1) and restart the checking
				intPeripheralAddress++;
				currentPeripheralData=rootPeripheralData;
			}else{
				currentPeripheralData=currentPeripheralData->next;
			}
		}
	} 
	itoaRFPI(intPeripheralAddress,peripheralAddress,16); //convert integer to hexadecimal in ASCII
	
	//adding the 0 if the itoaRFPI does not return 4 characters. 
	i=0;
	while(i<strlen(peripheralAddress)){ // Reverse the string
		peripheralAddressTemp[i]=peripheralAddress[strlen(peripheralAddress)-i-1];
		i++;
	}
	peripheralAddressTemp[i]='\0';
    strcpy(peripheralAddress,peripheralAddressTemp);
	
	strcpy(peripheralAddressTemp,"0000"); //init the string
	for(i=0;i<strlen(peripheralAddress);i++){
		peripheralAddressTemp[3-i]=peripheralAddress[i];
	}
	peripheralAddressTemp[4]='\0';
	strcpy(peripheralAddress,peripheralAddressTemp);
	
	#if DEBUG_LEVEL>0
		printf("Peri Address to assign: %s \n", peripheralAddress); fflush(stdout);
	#endif
	
	//dividing the integer address contained into i in two bytes High and Low and copying into the strCmd
	byteH=0;
	byteH=byteH | (intPeripheralAddress>>8);
	strCmd[8]=byteH;
	byteL=0;
	byteL=byteL | intPeripheralAddress;
	strCmd[9]=byteL;

	
	//giving the address of the master
	strcpy(AddressRFPI, ADDRESS_RFPI); //copying the address of the Transceiver
	AddressRFPI[4]='\0';
	sscanf(AddressRFPI,"%X",&i); //convert string hex in integer
	#if DEBUG_LEVEL>0
		printf("Master Address: %s \n", AddressRFPI); fflush(stdout);
	#endif
	
	//dividing the integer address contained into i in two bytes High and Low and copying into the strCmd
	byteH=0;
	byteH=byteH | (i>>8);
	strCmd[10]=byteH;
	byteL=0;
	byteL=byteL | i;
	strCmd[11]=byteL;

	//########### v1.1 ###########
	SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2,0);
		
		
	//updating the status if needed
	if(!(answerRFPI[0]=='O' && answerRFPI[1]=='K')){
		strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOPERI); 
		#if DEBUG_LEVEL>0
			printf("PERI DID NOT REPLY!\n"); fflush(stdout);
		#endif
	}else{
		strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK);
		#if DEBUG_LEVEL>0
			printf("PERI HAS GOT THE NETWORK ADDRESSES!\n"); fflush(stdout);
		#endif
	}
	#if DEBUG_LEVEL>0
		printf("\n------------------END: ASSIGNING NETWORK PARAMETER------------------\n"); fflush(stdout);
	#endif
	
	//it send all command to the Transceiver module to reset the current network
	setCurrentNetwork(handleUART);
	
	if(strcmp(statusRFPI,MSG_FIFO_RFPI_STATUS_OK)==0){
	
		//Update files and the struct data
		
		//update address network 
		if(!readNetworkNameFromFile(networkName)){
			addressFromName(networkName, networkAddress);
		}else{
			strcpy(networkAddress,"C733");
		}
				
		delay_ms(50); //gives the time to the peri to set on the new network
		
		strcpy(strCmd,"C03"); //cmd to set address of this new peripheral
		strcat(strCmd,peripheralAddress);
		SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1);
	
		//########### v1.1 ###########
		//ask to the peripheral the type, number of IO
		
		//SendRadioDataAndGetReplyFromPeri(handleUART, "C30RBt.............", 19, answerRFPI, CMD_WAIT2,1); 
		strcpy(strCmd,"C30RBt............."); //C30 is cmd to load the following data to send
		SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2,1); 

		if( !( 		answerRFPI[2]=='*' 
					&& answerRFPI[3]==peripheralAddress[0] 
					&& answerRFPI[4]==peripheralAddress[1]
					&& answerRFPI[5]==peripheralAddress[2]
					&& answerRFPI[6]==peripheralAddress[3]
					&& answerRFPI[9]=='t' 
					&& strlen(answerRFPI)>9 ) 
					){ //there are 10 character for "OK*0001RBt"
			strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOPERI); 
			#if DEBUG_LEVEL>0
				printf("PERI DID NOT REPLY WITH THE CHARATERISTICS!\n"); fflush(stdout);
			#endif
		}else{
			#if DEBUG_LEVEL>0
				printf("PERI REPLIED WITH ITS CHARACTERISTICS!\n"); fflush(stdout);
			#endif
		}
		
		if(strcmp(statusRFPI,MSG_FIFO_RFPI_STATUS_OK)==0){ ////begin of the if where it check the correct reply from pery with all its characteristics
			

			//################ BEGIN ALLOCATING THE STRUCT DATA OF THIS PERIPHERAL ################
			numPeripheral=0;
			if(rootPeripheralData==0){
				nextPeripheralData=(peripheraldata*)malloc(sizeof(peripheraldata));
				nextPeripheralData->next=0; 
				rootPeripheralData=nextPeripheralData;
			}else{
				currentPeripheralData=rootPeripheralData;
				while(currentPeripheralData->next!=0){
					numPeripheral++;
					currentPeripheralData=currentPeripheralData->next;
				}
				numPeripheral++; 
				nextPeripheralData=(peripheraldata*)malloc(sizeof(peripheraldata));
				nextPeripheralData->next=0; 
				currentPeripheralData->next=nextPeripheralData;
			} 
			currentPeripheralData=nextPeripheralData;
			//################ END ALLOCATING THE STRUCT DATA OF THIS PERIPHERAL ################
			
		
			if(answerRFPI[11]==0){ //no peri characteristics have been returned thus there is an error!
				strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOTYPE);
				IDtype=0; 
				numInputDigital=0;
				numOutputDigital=0;
				numInputAnalogue=0; 
				numOutputAnalogue=0;
				numSpecialFunction=0;
				fwVersion=0;
				
				#if DEBUG_LEVEL>0
				printf("\nTHE PERIPHERAL DID NOT REPLY WITH ITS IDENTIFICATION CODE!\n\n"); fflush(stdout);
				#endif
			}else{
				IDtype=0;
				IDtype=answerRFPI[10];
				IDtype=IDtype<<8;
				IDtype=IDtype+answerRFPI[11]; 
				numInputDigital=answerRFPI[12];
				numOutputDigital=answerRFPI[13];
				numInputAnalogue=answerRFPI[14];
				numOutputAnalogue=answerRFPI[15];
				numSpecialFunction=answerRFPI[16];
				fwVersion=answerRFPI[17];

			} 
			NumInput=numInputAnalogue+numInputDigital; //number of inputs on the peripheral
			NumOutput=numOutputAnalogue+numOutputDigital; //number of outputs on the peripheral

			if(NumInput>255) NumInput=255; //max num input 255
			if(NumOutput>255) NumOutput=255; //max num output 255
			
			currentPeripheralData->IDtype=IDtype;

			delay_ms(50); //gives the time to elaborate the last command

			//########### v1.1 ###########
			//ask to the peripheral the name
			//SendRadioDataAndGetReplyFromPeri(handleUART, "C30RBn.............", 19, answerRFPI, CMD_WAIT2,1);
			strcpy(strCmd,"C30RBn............."); //C30 is cmd to load the following data to send
			SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2,1);
			
	
			if(strlen(answerRFPI)<10){ //no name returned, error!
				strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NONAME);
				strcpy(NamePeripheral,MSG_FIFO_RFPI_STATUS_NONAME); 
				#if DEBUG_LEVEL>0
					printf("\nTHE PERIPHERAL DID NOT REPLY WITH ITS NAME!\n\n"); fflush(stdout);
				#endif
			}else{
				strncpy(NamePeripheral, answerRFPI+10, 23); 
				if(strlen(NamePeripheral)==0){
					strcpy(NamePeripheral,MSG_FIFO_RFPI_STATUS_NONAME);
				}
			} 
			
			#if DEBUG_LEVEL>0
				printf("\nPERIPHERAL CHARACTERISTICS:\n");
				printf(" Peripheral Name: %s\n", NamePeripheral);
				printf(" Peripheral ID: %d   NUM INPUT: %d   NUM OUTPUT: %d   NUM ANALOGUE INPUT: %d   NUM ANALOGUE OUTPUT: %d   NUM SPECIAL FUNCTIONS: %d   FW VERSION: %d\n\n", IDtype, NumInput, NumOutput, numInputAnalogue, numOutputAnalogue,numSpecialFunction,fwVersion); 
				fflush(stdout);
			#endif
			
			currentPeripheralData->Name=(char*)malloc((strlen(NamePeripheral)+1)*sizeof(char));
			strcpy(currentPeripheralData->Name, NamePeripheral);
			
			strcpy(currentPeripheralData->NetAddress,networkAddress);
			strcpy(currentPeripheralData->PeriAddress,peripheralAddress);

			//init variabiles struct data peripheral
			currentPeripheralData->rootNameInput = 0;
			currentPeripheralData->rootNameOutput = 0;
			currentPeripheralData->strengthLink = 0;
	
			currentPeripheralData->NumInput=NumInput;
			currentPeripheralData->NumOutput=NumOutput;
			currentPeripheralData->numSpecialFunction=numSpecialFunction;
			currentPeripheralData->fwVersion=fwVersion;
			
			//creating the struct data of inputs and outputs

			//init the struct of the input names
			rootPeripheralDataNameInput=0;
			if(NumInput>0){
				for(i=0;i<NumInput;i++){
				
					if(i<numInputDigital){ //set a name for the kind of input (Analogue or Digital)
						strcpy(strTemp,"DIGITAL"); 
					}else{
						strcpy(strTemp,"ANALOGUE"); 
					}
					if(i==0){
						rootPeripheralDataNameInput = (peripheraldatanameinput*)malloc(sizeof(peripheraldatanameinput));
						rootPeripheralDataNameInput->next=0;
						rootPeripheralDataNameInput->NameInput=(char*)malloc((strlen(strTemp)+1)*sizeof(char));
						strcpy(rootPeripheralDataNameInput->NameInput, strTemp);
						rootPeripheralDataNameInput->Type=(char*)malloc((strlen(strTemp)+1)*sizeof(char));
						strcpy(rootPeripheralDataNameInput->Type, strTemp);
						
						//assigning to the main peripheral data struct the roots pointers of the inputs and outputs structs
						currentPeripheralData->rootNameInput=rootPeripheralDataNameInput;
			
						//rootPeripheralDataNameInput->StatusInput=askInputStatusPeri(handleUART, peripheralAddress, i, &rootPeripheralDataNameInput->id_shield_input, &rootPeripheralDataNameInput->num_pin_used_on_the_peri); //ask to the peripheral for the status of the input
						rootPeripheralDataNameInput->StatusInput = get_IO_Peri_Status(handleUART, currentPeripheralData, i, (char)'i', array_status);
						
						currentPeripheralDataNameInput=rootPeripheralDataNameInput;
					}else{
						nextPeripheralDataNameInput = (peripheraldatanameinput*)malloc(sizeof(peripheraldatanameinput));
						nextPeripheralDataNameInput->next=0;
						nextPeripheralDataNameInput->NameInput=(char*)malloc((strlen(strTemp)+1)*sizeof(char));
						strcpy(nextPeripheralDataNameInput->NameInput, strTemp);
						nextPeripheralDataNameInput->Type=(char*)malloc((strlen(strTemp)+1)*sizeof(char));
						strcpy(nextPeripheralDataNameInput->Type, strTemp);
						
						currentPeripheralDataNameInput->next=nextPeripheralDataNameInput;
						
						nextPeripheralDataNameInput->StatusInput = get_IO_Peri_Status(handleUART, currentPeripheralData, i, (char)'i', array_status);

						currentPeripheralDataNameInput=nextPeripheralDataNameInput;
					}
				}
			}
							
			//init the struct of the output names
			rootPeripheralDataNameOutput=0;
			if(NumOutput>0){
				for(i=0;i<NumOutput;i++){
				
					if(i<numOutputDigital){ //set a name for the kind of input (Analogue or Digital)
						strcpy(strTemp,"DIGITAL"); 
					}else{
						strcpy(strTemp,"ANALOGUE"); 
					}
					if(i==0){
						rootPeripheralDataNameOutput = (peripheraldatanameoutput*)malloc(sizeof(peripheraldatanameoutput));
						rootPeripheralDataNameOutput->next=0;
						rootPeripheralDataNameOutput->NameOutput=(char*)malloc((strlen(strTemp)+1)*sizeof(char));
						strcpy(rootPeripheralDataNameOutput->NameOutput, strTemp);
						rootPeripheralDataNameOutput->Type=(char*)malloc((strlen(strTemp)+1)*sizeof(char));
						strcpy(rootPeripheralDataNameOutput->Type, strTemp);
						
						//assigning to the main peripheral data struct the roots pointers of the inputs and outputs structs
						currentPeripheralData->rootNameOutput=rootPeripheralDataNameOutput;
			
						rootPeripheralDataNameOutput->StatusOutput = get_IO_Peri_Status(handleUART, currentPeripheralData, i, (char)'p', array_status);

						currentPeripheralDataNameOutput=rootPeripheralDataNameOutput;
					}else{
						nextPeripheralDataNameOutput = (peripheraldatanameoutput*)malloc(sizeof(peripheraldatanameoutput));
						nextPeripheralDataNameOutput->next=0;
						nextPeripheralDataNameOutput->NameOutput=(char*)malloc((strlen(strTemp)+1)*sizeof(char));
						strcpy(nextPeripheralDataNameOutput->NameOutput, strTemp);
						nextPeripheralDataNameOutput->Type=(char*)malloc((strlen(strTemp)+1)*sizeof(char));
						strcpy(nextPeripheralDataNameOutput->Type, strTemp);
						
						currentPeripheralDataNameOutput->next=nextPeripheralDataNameOutput;
						
						nextPeripheralDataNameOutput->StatusOutput = get_IO_Peri_Status(handleUART, currentPeripheralData, i, (char)'p', array_status);

						currentPeripheralDataNameOutput=nextPeripheralDataNameOutput;
					}
				}
			}
			
			//creates file descriptor 
			strcpy(NameFileDescriptor,"desc_");
			strcat(NameFileDescriptor,  currentPeripheralData->Name);
			strcat(NameFileDescriptor, peripheralAddress);
			strcat(NameFileDescriptor, ".txt"); 


			//if exist, delete the file descriptor
			strcpy(strPathFile,PATH_CONFIG_FILE);
			strcat(strPathFile, NameFileDescriptor);
			if( access( strPathFile, F_OK ) == -1) { 
					if( remove(strPathFile) ){
						#if DEBUG_LEVEL>0
						printf("%s file deleted successfully.\n", NameFileDescriptor);
						#endif
					}else{
						#if DEBUG_LEVEL>0
						printf("Unable to delete the file %s\n", NameFileDescriptor);
						#endif
						perror("Error");
					}
			}

				
			//saving into the struct the name of the file descriptor
			currentPeripheralData->NameFileDescriptor=(char*)malloc((strlen(NameFileDescriptor)+1)*sizeof(char));
			strcpy(currentPeripheralData->NameFileDescriptor,NameFileDescriptor); 
				
			//now create the file descriptor
			strcpy(strPathFile,PATH_CONFIG_FILE);
			strcat(strPathFile,currentPeripheralData->NameFileDescriptor);
			file_pointer = fopen(strPathFile,"w+"); //creating the file descriptor
		
			//first line with inputs
			currentPeripheralDataNameInput=rootPeripheralDataNameInput; //currentPeripheralData->rootNameInput;
			sprintf(strTemp, "%d", NumInput); //convert int to string
			strcpy(strTemp2, strTemp); //first part of the line: the number of inputs
			while(currentPeripheralDataNameInput!=0){ //writing all name
				strcat(strTemp2, " "); 
				strcat(strTemp2, currentPeripheralDataNameInput->Type); 
				currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
			}

			currentPeripheralDataNameInput=currentPeripheralData->rootNameInput;
			while(currentPeripheralDataNameInput!=0){ //writing all name
				strcat(strTemp2, " "); 
				sprintf(strTemp, "%d", currentPeripheralDataNameInput->BitResolution); //convert int to string
				strcat(strTemp2, strTemp); 
				currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
			}
			fprintf(file_pointer,"%s\n", strTemp2); //writing on the file the line of the inputs
			
			//second line with outputs
			currentPeripheralDataNameOutput=rootPeripheralDataNameOutput; //currentPeripheralData->rootNameOutput;
			sprintf(strTemp, "%d", NumOutput); //convert int to string
			strcpy(strTemp2, strTemp); //first part of the line: the number of inputs
			while(currentPeripheralDataNameOutput!=0){ //writing all name
				strcat(strTemp2, " "); 
				strcat(strTemp2, currentPeripheralDataNameOutput->Type);
				currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
			}
			currentPeripheralDataNameOutput=currentPeripheralData->rootNameOutput;
			while(currentPeripheralDataNameOutput!=0){ //writing all name
				strcat(strTemp2, " "); 
				sprintf(strTemp, "%d", currentPeripheralDataNameOutput->BitResolution); //convert int to string
				strcat(strTemp2, strTemp); 
				currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
			}
			fprintf(file_pointer,"%s\n", strTemp2); //writing on the file the line of the outputs
			
	
			//third line with name of the input
			currentPeripheralDataNameInput=rootPeripheralDataNameInput; 
			sprintf(strTemp, "%d", NumInput); //convert int to string
			strcpy(strTemp2, strTemp); //first part of the line: the number of inputs
			while(currentPeripheralDataNameInput!=0){ //writing all name
				strcat(strTemp2, " "); 
				strcat(strTemp2, currentPeripheralDataNameInput->NameInput); 
				currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
			}
			fprintf(file_pointer,"%s\n", strTemp2); //writing on the file the line of the inputs
			
			
			//third line with name of the outputs
			currentPeripheralDataNameOutput=rootPeripheralDataNameOutput; //currentPeripheralData->rootNameOutput;
			sprintf(strTemp, "%d", NumOutput); //convert int to string
			strcpy(strTemp2, strTemp); //first part of the line: the number of inputs
			while(currentPeripheralDataNameOutput!=0){ //writing all name
				strcat(strTemp2, " "); 
				strcat(strTemp2, currentPeripheralDataNameOutput->NameOutput); 
				currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
			}
			fprintf(file_pointer,"%s\n", strTemp2); //writing on the file the line of the outputs

			fclose(file_pointer); 

			file_pointer = fopen(FILE_LIST_PERIPHERAL,"a+"); //opening the list of peripherals 
			if( file_pointer == NULL){
				perror("Error while opening the file.\n");
				exit(EXIT_FAILURE);
			}else{ 
				fprintf(file_pointer,"%d %d %s %s %s %d %d\n", numPeripheral, currentPeripheralData->IDtype, currentPeripheralData->Name, currentPeripheralData->NameFileDescriptor, currentPeripheralData->PeriAddress, currentPeripheralData->numSpecialFunction, currentPeripheralData->fwVersion);
			}
			fclose(file_pointer);  
			

		} //end of the if where it check the correct reply from pery with all its characteristics
	}
	
	#if DEBUG_LEVEL>0
		printf("status to find new = %s\n",statusRFPI);
	#endif
	
	contStatusMsg=0;
	
	return rootPeripheralData;

}

//it calculate and return the address for the network. 
extern void addressFromName(char *name, char *address){
	
	int i=0;
	unsigned int intAddress=0;
	char strHex[3]; strHex[0]='\0';
	unsigned char byteH=0;
	unsigned char byteL=0;
	
	int lenName=strlen(name); //get the length of the name
	
	if(lenName>MAX_LEN_NET_NAME)	//maximum length allowed is 128 characters
		lenName=128;
		
	intAddress=0;
	for(i=0;i<lenName;i++){
		intAddress=intAddress+name[i];
	}
	
	//dividing the integer address contained into i in two bytes High and Low and copying into the strCmd
	byteH=0;
	byteH=byteH | (intAddress>>8);

	byteL=0;
	byteL=byteL | intAddress;

	byteToHexStr(strHex,byteH);
	address[0]=strHex[0];
	address[1]=strHex[1];
	
	byteToHexStr(strHex,byteL);
	address[2]=strHex[0];
	address[3]=strHex[1];
	
	address[4]='\0';
	
}



//it delete the peripheral with the specified address
peripheraldata *deletePeripheralByAddress(char *addressPeri, peripheraldata *rootPeripheralData){
	int positionId=0;
	
	//pointers used to manage the data of all linked peripheral
	peripheraldata *currentPeripheralData=0;

	int varExit=0;
	if(rootPeripheralData!=0){
		currentPeripheralData=rootPeripheralData;
		while(varExit==0 && currentPeripheralData!=0 && (currentPeripheralData)>0){//for LINUX_MINT
			//check if the address it is same
			if(addressPeri[0]==currentPeripheralData->PeriAddress[0] 
			&& addressPeri[1]==currentPeripheralData->PeriAddress[1] 
			&& addressPeri[2]==currentPeripheralData->PeriAddress[2]
			&& addressPeri[3]==currentPeripheralData->PeriAddress[3]){
				varExit=1;
			}else{
				currentPeripheralData=currentPeripheralData->next;
				//free(PeripheralDataToFree);
				positionId++;
			}			
		}
	}
	
	if(varExit==1){
		rootPeripheralData=deletePeripheral(positionId, rootPeripheralData);
	}
			
	return rootPeripheralData;
}


//it delete the peripheral in the position positionId, the file descriptor will remain
peripheraldata *deletePeripheral(int positionId, peripheraldata *rootPeripheralData){

	FILE *file_pointer=0; //generic file pointer, used to update the file
	
	char strPathFile[MAX_LEN_PATH]; strPathFile[0]='\0'; //used to keep the path of the file
	char strPathFile2[MAX_LEN_PATH]; strPathFile2[0]='\0'; //used to keep the path of the file
	int status=0;
	int i=0;
	int l=0;
	
	//pointers used to manage the data of all linked peripheral
	peripheraldata *currentPeripheralData=0;
	peripheraldata *nextPeripheralData=0;
	peripheraldata *previoustPeripheralData=0;
	
	//v1.1.1 searching into the struct the position of the peripheral to delete
	i=0;
	if(!(rootPeripheralData==0)){
		currentPeripheralData=rootPeripheralData;
		while(i!=positionId && currentPeripheralData!=0 && (currentPeripheralData)>0){//for LINUX_MINT
			currentPeripheralData=currentPeripheralData->next;
			i++;
		}
		//delete the file descriptor
		strcpy(strPathFile,PATH_CONFIG_FILE);
		strcat(strPathFile,currentPeripheralData->NameFileDescriptor);
		status = remove(strPathFile);
		if( status == 0 ){
			#if DEBUG_LEVEL>0
			printf("%s file deleted successfully.\n",currentPeripheralData->NameFileDescriptor);
			#endif
		}else{
			#if DEBUG_LEVEL>0
			printf("Unable to delete the file %s\n", currentPeripheralData->NameFileDescriptor);
			#endif
			perror("Error deleting peripheral!");
		}
		
		//delete the file JSON
		strcpy(strPathFile,PATH_CONFIG_FILE);
		strcat(strPathFile,currentPeripheralData->PeriAddress);
		strcat(strPathFile,".json");
		status = remove(strPathFile);
		if( status == 0 ){
			#if DEBUG_LEVEL>0
			printf("%s file deleted successfully.\n",strPathFile);
			#endif
		}else{
			#if DEBUG_LEVEL>0
			printf("Unable to delete the file %s\n", strPathFile);
			#endif
			perror("Error deleting peripheral!");
		}
	} 

	strcpy(strPathFile,PATH_CONFIG_FILE);
	strcat(strPathFile,"list_peripheral2.txt");
	file_pointer = fopen(strPathFile,"w+"); //opening the list of peripherals 
	if(file_pointer == NULL){
		#if DEBUG_LEVEL>0
		printf("Error while opening the file list_peripheral.txt.\n");
		#endif
		perror("Error while opening the file list_peripheral.txt.\n");
		exit(EXIT_FAILURE);
	}else{ 
		i=0;
		l=0;
		currentPeripheralData=rootPeripheralData;
		previoustPeripheralData=rootPeripheralData;
		//will rewrite the whole file
		while((currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
			if(i==positionId){
				if(i==0){
					rootPeripheralData=currentPeripheralData->next;
					currentPeripheralData=rootPeripheralData;
					previoustPeripheralData=currentPeripheralData;
				}else{
					previoustPeripheralData->next=currentPeripheralData->next;
					currentPeripheralData=previoustPeripheralData;
					currentPeripheralData=currentPeripheralData->next;
				}
			}else{
				fprintf(file_pointer,"%d %d %s %s %s %d %d\n", l, currentPeripheralData->IDtype, currentPeripheralData->Name, currentPeripheralData->NameFileDescriptor, currentPeripheralData->PeriAddress, currentPeripheralData->numSpecialFunction, currentPeripheralData->fwVersion);
				l++;
				previoustPeripheralData=currentPeripheralData;
				currentPeripheralData=currentPeripheralData->next;
			}
			i++;
			
		}
	}
	fclose(file_pointer); 
	
	
	//renaming old file list peripheral in backup
	strcpy(strPathFile,FILE_LIST_PERIPHERAL);
	strcpy(strPathFile2,PATH_CONFIG_FILE);
	strcat(strPathFile2,"list_peripheral.backup");
	status= rename(strPathFile, strPathFile2); //( oldname , newname );
	if ( status == 0 )
		puts ( "File successfully renamed" );
	else
		perror( "Error renaming file" );
	
	
	//renaming new file list peripheral
	strcpy(strPathFile,PATH_CONFIG_FILE);
	strcat(strPathFile,"list_peripheral2.txt");
	strcpy(strPathFile2,FILE_LIST_PERIPHERAL);
	status= rename(strPathFile, strPathFile2); //( oldname , newname );
	if ( status == 0 )
		puts ( "File successfully renamed" );
	else
		perror( "Error renaming file" );

   
	
	//need delete the file otherwise inside there will be the data of the old peripheral
	if(remove(FIFO_RFPI_PERIPHERAL)==0){
		#if DEBUG_LEVEL>0
		printf("FIFO_RFPI_PERIPHERAL removed!");
		#endif
	}else{
		#if DEBUG_LEVEL>0
		printf("Impossible to remove FIFO_RFPI_PERIPHERAL!");
		#endif
	}
	if(remove(FIFO_RFPI_PERIPHERAL_JSON)==0){
		#if DEBUG_LEVEL>0
		printf("FIFO_RFPI_PERIPHERAL_JSON removed!");
		#endif
	}else{
		#if DEBUG_LEVEL>0
		printf("Impossible to remove FIFO_RFPI_PERIPHERAL_JSON!");
		#endif
	}
	//create the fifo to give the status of the peripheral to the GUI
	writeFifoPeripheralLinked(rootPeripheralData); 
	chmod(FIFO_RFPI_PERIPHERAL, 0777);
	writeFifoJsonPeripheralLinked(rootPeripheralData);
	chmod(FIFO_RFPI_PERIPHERAL_JSON, 0777);
					
					
	return rootPeripheralData;
				
}


//send trough the serial port a specified number of characters to the Transceiver module and then send the command C31 and wait the answer from the peipheral.
extern void SendRadioDataAndGetReplyFromPeri(int *handleUART, unsigned char *arrayData, int numCharacters, char *answerRFPI, int maxTimeOutMs, unsigned char mustReply){
			int i,last_i, numC, contMs;
			i = last_i = numC = contMs = 0;
			unsigned char varExit=0;
			unsigned char MaxNumRetry=MAX_NUM_RETRY;
			unsigned char contRetry = 1;
			unsigned char strCmdC31[]="C31";
			unsigned char varchecksum=0;
			int random_delay=25;
			
			//load the data on the Transceiver. This data then will be ready to be sended with the command C31
			loadRadioData(handleUART, arrayData, numCharacters, answerRFPI, CMD_WAIT1);
			
			do{
							
				//emptying serial buffer
				numC=serialDataAvail (*handleUART) ;
				while(numC>0){
					i = serialGetchar (*handleUART);
					numC--;
				}
				
				#if DEBUG_LEVEL>0
				printf(" Try %d of %d sending wireless Data: ",contRetry,MaxNumRetry);
				#endif
				for(i=0;i<strlen(strCmdC31);i++){
					serialPutchar(*handleUART, strCmdC31[i]);
					#if DEBUG_LEVEL>0
					printf("%c", strCmdC31[i]);
					#endif
				}
				#if DEBUG_LEVEL>0
				printf(" -> ");
				fflush(stdout); // Prints immediately to screen
				#endif
				
				//delay_ms(100);
				
				if(varExit==1){ delay_ms(random_delay); } //gives time to the peri to get ready 
				
				
				for(i=0;i<MAX_LEN_BUFFER_ANSWER_RF;i++){ answerRFPI[i]=0; }
				answerRFPI[0]='\0';
				contMs = 0;
				last_i = 0;
				numC=0;
				varExit=0;
				do{
					delay_ms(1);

					do{
						
						for(i=0;i<numC && (i+last_i+1)<(MAX_LEN_BUFFER_ANSWER_RF);i++){
							
							answerRFPI[(i+last_i)]=serialGetchar (*handleUART);
							answerRFPI[(i+last_i+1)]='\0';
							
							#if DEBUG_LEVEL>0
							//this check if the character is inside the ASCII table and then print
							if(answerRFPI[(i+last_i)]<32 || answerRFPI[(i+last_i)] > 126){
								if(answerRFPI[(i+last_i)]<10)
									printf("%d", answerRFPI[(i+last_i)]);
								else
									printf("X");
							}else{
								printf("%c", answerRFPI[(i+last_i)]);
							}
							#endif
						}
						last_i += i;
						numC=serialDataAvail (*handleUART) ;
					}while(numC>0);

					fflush(stdout); // Prints immediately to screen 
					
					if(contMs>maxTimeOutMs){
						varExit=1;
						if(contRetry==1){
							MaxNumRetry = random_num(MIN_NUM_RETRY,MAX_NUM_RETRY);
							#if DEBUG_LEVEL>1
							printf("\n  random-num-retry=%d",random_delay);fflush(stdout);
							#endif
						}
						if(contRetry < (MaxNumRetry+1)){
							random_delay = random_num(MIN_RANDOM_DELAY_RETRY_TX_RF, MAX_RANDOM_DELAY_RETRY_TX_RF);
							#if DEBUG_LEVEL>1
							printf("\n  random-delay=%dmS",random_delay);fflush(stdout);
							#endif
						}
					//}else if(answerRFPI[0] == 'O' && answerRFPI[1] == 'K' && answerRFPI[2] == '*' && last_i > (7+16-1)) //7 is the OK*XXXX and the 16 are the 16byte protocol
					}else if(last_i > (7+16-1)){ //7 is the OK*XXXX and the 16 are the 16byte protocol
						#ifdef ENABLE_RADIO_DATA_CHECKSUM
							//varchecksum = checksum((unsigned char *)&answerRFPI[7], 16);
							varchecksum = checksum2((unsigned char *)&answerRFPI[7], 16);
							if(varchecksum == 0){ //if it is 0 then data is ok!
								varExit=2;
							}else{
								contMs=maxTimeOutMs;
								#if DEBUG_LEVEL>0
								if(varchecksum!=0){
									printf(" CHECKSUM ERROR!!! CHECKSUM = %d\n",varchecksum);fflush(stdout);
								}
								#endif
							}
						#else
							varExit=2;//to delete
						#endif
					}else if(answerRFPI[0] == 'O' && answerRFPI[1] == 'K' && answerRFPI[2] == '*' && mustReply==0){
						varExit=3;
					}
					
					contMs++;
				}while(varExit==0);
				#if DEBUG_LEVEL>0
				printf("\n");
				fflush(stdout); // Prints immediately to screen
				#endif
				contRetry++;

			}while(varExit==1 && contRetry < (MaxNumRetry+1) );
			#if DEBUG_LEVEL>1
			printf("\n");
			#endif
			
}


//load the data on the Transceiver. This data then will be ready to be sended with the command C31
extern void loadRadioData(int *handleUART, unsigned char *arrayData, int numCharacters, char *answerRFPI, int maxTimeOutMs){
			int i, numC, contMs;
			unsigned char varExit=0;
			
			#ifdef	ENABLE_RADIO_DATA_CHECKSUM
			int j;
			unsigned char data_to_send[16];
			for(j=0;j<16;j++) data_to_send[j] = arrayData[j+3];
			data_to_send[0] = calc_checksum (&data_to_send[1], 15);
			arrayData[3] = data_to_send[0]; //first byte is the checksum instead to be the character 'R'
			#endif
			
			//emptying serial buffer
			numC=serialDataAvail (*handleUART) ;
			while(numC>0){
				i = serialGetchar (*handleUART);
				numC--;
			}
			#if DEBUG_LEVEL>0
			printf(" Loading Data on the Radio: ");
			#endif
			for(i=0;i<numCharacters;i++){
				serialPutchar(*handleUART, arrayData[i]);
				#if DEBUG_LEVEL>0
				if(arrayData[i]<32 || arrayData[i] > 126){
					if(arrayData[i]<10)
						printf("%d", arrayData[i]);
					else
						printf("X");
				}else
					printf("%c", arrayData[i]);
				#endif
			}
			#if DEBUG_LEVEL>0
			printf(" -> ");
			#endif
			contMs = 0;
			
			do{
				delay_ms(1);

				numC=serialDataAvail (*handleUART) ;
				
				i=0;
				if(numC>0){
					for(i=0;i<numC && i<(MAX_LEN_BUFFER_ANSWER_RF-1);i++){
						answerRFPI[i]=serialGetchar (*handleUART);
						#if DEBUG_LEVEL>0
						printf("%c", answerRFPI[i] );
						#endif
					}
					#if DEBUG_LEVEL>0
					printf("\n");
					#endif
				}
				answerRFPI[i]='\0';
				
				if(contMs>=maxTimeOutMs)
					varExit=1;
				if(answerRFPI[0] == '*')
					varExit=1;
				if(answerRFPI[0] == 'O' && answerRFPI[1] == 'K' && answerRFPI[2] == '*' )
					varExit=1;
					
				contMs++;
			}while(varExit==0);
			#if DEBUG_LEVEL>0
			printf("\n");
			fflush(stdout); // Prints immediately to screen
			#endif
}


//send trough the serial port a command which will be a string (string has to not contain special characters, for special characters use the function: SerialCmdRFPI)
//it will exit from the function when receive the "*" or receive "OK*" from the Transceiver module or when reach the maxTimeOutMs
extern void SerialCmdRFPi(int *handleUART, unsigned char *strCmd, char *answerRFPI, int maxTimeOutMs){
			int i, numC, numCharacters, contMs;
			i = numC = numCharacters = contMs = 0;
			unsigned char varExit=0;

			//emptying serial buffer
			numC=serialDataAvail (*handleUART) ;
			while(numC>0){
					i = serialGetchar (*handleUART);
					numC--;
			}
			
			numCharacters = strlen(strCmd);
			#if DEBUG_LEVEL>0
			printf("\n CMD to the Radio: ");
			printf("%s", strCmd);
			printf(" -> ");
			#endif

			write (*handleUART, strCmd, numCharacters) ;
			
			contMs = 0;
			do{
				delay_ms(1);

				numC=serialDataAvail (*handleUART) ;
				
				i=0;
				if(numC>0){
					for(i=0;i<numC && i<(MAX_LEN_BUFFER_ANSWER_RF-1);i++){
						answerRFPI[i]=serialGetchar (*handleUART);
						#if DEBUG_LEVEL>0
						printf("%c", answerRFPI[i] );
						#endif
					}
					#if DEBUG_LEVEL>0
					printf("\n");
					#endif
				}
				
				answerRFPI[i]='\0';
				fflush(stdout); // Prints immediately to screen 
				
				if(contMs>=maxTimeOutMs)
					varExit=1;
				if(answerRFPI[0] == '*')
					varExit=1;
				if(answerRFPI[0] == 'O' && answerRFPI[1] == 'K' && answerRFPI[2] == '*' )
					varExit=1;
					
				contMs++;
			}while(varExit==0);
}


//it parse the data coming from the GUI. It will write the FIFO RFPI STATUS. Thus into the FIFO RFPI STATUS there will be written the response after have parsed the data from the GUI.
peripheraldata *ParseFIFOdataGUI(int *handleUART, peripheraldata *rootPeripheralData, int *cmd_executed){
	
	//pointers used to manage the data of all linked peripheral
	peripheraldata *currentPeripheralData=0;
	peripheraldata *nextPeripheralData=0;
	
	char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF]; answerRFPI[0]='\0'; //used to get the answer from the Transceiver
	char strCmd[40]; strCmd[0]='\0'; //used to give command to the Transceiver
	
	//variables used to elaborate FIFO written by the WEB
	char data[(MAX_BUF_FIFO_GUI_CMD*2)+20]; data[0]='\0'; //MAX_BUF_FIFO_GUI_CMD is declared inro librfpi.h
	char tag[10], type[10], value1[MAX_BUF_FIFO_GUI_CMD], value2[MAX_BUF_FIFO_GUI_CMD];
	tag[0] = type[0] = value1[0] = value2[0]='\0';
	char peripheral_name[10]; peripheral_name[0]='\0';
	int  peri_id_position, output_id, positionId, output_value;
	peri_id_position = output_id = positionId = output_value = 0;
	int	 num_bytes_to_get, num_bytes_to_send, num_function;
	num_bytes_to_get = num_bytes_to_send = num_function = 0;
	char strTemp[MAX_LEN_PATH]; strTemp[0]='\0';
	
	char strPathFile[MAX_LEN_PATH]; strPathFile[0]='\0';//used to keep the path of the file
	char strPathFile2[MAX_LEN_PATH]; strPathFile2[0]='\0';//used to keep the path of the file
	int status=0;
	unsigned char stop_by_user=0;
	
	int i,pos,j,cont_packet,num_time_the_peri_did_not_reply;
	i = pos = j = cont_packet = num_time_the_peri_did_not_reply = 0;
	unsigned char sem_1 = 0;
		
	//unsigned char array10BytesToSend[10]; //globaly declared otherwise it does not keep the value
	unsigned char byte_to_send = 0;
	unsigned int t,cont_packet_sent, sem1_exit;
	t = cont_packet_sent = sem1_exit = 0;
	
		
	FILE *file_pointer=0; //generic pointer to file, used in multiple places
	FILE *file_pointer_error=0; //used for the file of the error history

	int id_shield_connected=0; //used to know which shield is connectted to the io
	int num_pin_used_on_the_peri=0; //used to know which is the number of the pin used on the peripheral
	
	char strMsgTemp[20]; strMsgTemp[0]='\0';
	
	unsigned char array_status[20]; array_status[0]='\0';
				
	
	if(contStatusMsg>(TIME_HOLD_MSG_FIFO_RFPI_STATUS+1) || contStatusMsg<0) //contStatusMsg global variable
		contStatusMsg=0;
			
	//each time the statusRFPI change will wait five time and then return to OK
	if(contStatusMsg==TIME_HOLD_MSG_FIFO_RFPI_STATUS){
		strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK);
		fifoWriter(FIFO_RFPI_STATUS, statusRFPI);
		contStatusMsg++;
	}else if(contStatusMsg<TIME_HOLD_MSG_FIFO_RFPI_STATUS){
		contStatusMsg++;
	}

	//it check data from the GUI
	if(fifoReader(data, FIFO_GUI_CMD)) {
				
			sscanf(data,"%s %s %s %s ",tag, type, value1, value2);
			#if DEBUG_LEVEL>0
			printf("From FIFO_GUI_CMD received: %s %s %s %s\n", tag, type, value1, value2);
			#endif
			
			if(strcmp(tag,"FIND")==0 && strcmp(type,"NEW")==0 && strcmp(value1,"PERI")==0){ 
			//it start the procedure to find a new peripheral that is waitng to be installed into the network
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO files

				strcpy(strMsgTemp, MSG_FIFO_RFPI_STATUS_EXECUTING);
				fifoWriter(FIFO_RFPI_STATUS, strMsgTemp);			
				
				//it init a default network and then send the data to set the current network to the new peripheral
				rootPeripheralData=findNewPeripheral(handleUART, statusRFPI, rootPeripheralData);
				

			}else if(strcmp(tag,"DELETE")==0 && strcmp(type,"ADDRESS")==0){	
			//it delete all files and data of the address written in place of xxxx	
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO filess
				
				char addressPeri[5];
				if(strlen(value1)>=4){
					for(i=0;i<4;i++){
						addressPeri[i]=value1[i];
					}
					addressPeri[i]='\0';
					
					//it delete the peripheral with the specified address
					rootPeripheralData=deletePeripheralByAddress(addressPeri, rootPeripheralData);
					
				}
				
	
			}else if(strcmp(tag,"DELETE")==0 && strcmp(type,"PERI")==0){ 
			//it delete all files and data of the ID position written in place of xxxx
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO files
				
				sscanf(value1,"%d", &positionId);
				
				//it delete the peripheral in the postion positionId, the file descriptor will remain
				rootPeripheralData=deletePeripheral(positionId, rootPeripheralData);
				
				
			}else if(strcmp(tag,"PERIOUT")==0){ 
			//	PERIOUT	xa 		xb 		xc		//it set the output of the peripherla with ID position = xa, ID output = xb, value to set = xc
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO files
				
				sscanf(type,"%d", &peri_id_position);
				sscanf(value1,"%d", &output_id);
				sscanf(value2,"%d", &output_value);
				
							
				if(rootPeripheralData!=0){
					i=0;
					currentPeripheralData=rootPeripheralData;
					//while(i<peri_id_position && ((int)currentPeripheralData)>0 && currentPeripheralData!=0){
					while(i<peri_id_position && (currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
						i++;
						currentPeripheralData=currentPeripheralData->next;
					}
					#if DEBUG_LEVEL>0
					printf("PERIOUT | id_position = %d | id_output = %d | value_output = %d\n", peri_id_position, output_id,output_value);
					#endif
					strcpy(strCmd,"C03"); //cmd to set address of peripheral
					strcat(strCmd,currentPeripheralData->PeriAddress);
					SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1);
					
					//cmd C30 is to load data to send and the 1 is the tag to make understand the peripheral 
					//how to treat the following bytes 
					strcpy(strCmd,"C30RBoIO..........."); //C30 is cmd to load the following data to send
					if(output_id>255) output_id=255; //every peripheral is limited to a maximum of 255 outputs
					if(output_id<0) output_id=0; //the first id output has to starts from 0
					
					strCmd[6]=output_id;
					
					if(output_value>255) output_value=255; //the output is limited to a maximum value of 255
					if(output_value<0) output_value=0; //the output is limited to a minimum value of 0
					
					strCmd[7]=output_value;

					//########### v1.1 ###########
					SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2, 0);
					
					if(strcmp(statusRFPI,MSG_FIFO_RFPI_STATUS_OK)==0 && answerRFPI[0]=='O' && answerRFPI[1]=='K'){
						
						//output_value=askOutputStatusPeri(handleUART, currentPeripheralData->PeriAddress, output_id, &id_shield_connected, &num_pin_used_on_the_peri); //ask to the peripheral for the status of the output
						//output_value = 
						get_IO_Peri_Status(handleUART, currentPeripheralData, output_id, (char)'p', array_status);
						
						//updateStructPeriOut(rootPeripheralData, &peri_id_position, &output_id, &output_value, &id_shield_connected, &num_pin_used_on_the_peri);
						//updateStructPeriOut(rootPeripheralData, &peri_id_position, &output_id, &output_value);
						
					}
					
				}
				#if DEBUG_LEVEL>0
				printf(" status= %s\n",answerRFPI);
				#endif
				if(answerRFPI[0]=='O' && answerRFPI[1]=='K'){
					strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK); 
					#if DEBUG_LEVEL>0
					printf("Status=OK\n");
					#endif
				}else{
					strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOTX); 
					#if DEBUG_LEVEL>0
					printf("Status=NOTX\n");
					#endif
				} 

				
			}else if(strcmp(tag,"SETOUT")==0){
			//	PERIOUT	xxxx	xb 		xc			//it set the output of the peripherla with address = xxxx, ID output = xb, value to set = xc
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO files
				
				//sscanf(type,"%d", &peri_id_position);
				char address_peri[10];
				char varError = 0;
				strcpy(address_peri, type);
				sscanf(value1,"%d", &output_id);
				sscanf(value2,"%d", &output_value);
				
				//strcmp(type,"NEW")==0
				
							
				if(rootPeripheralData!=0 && strlen(address_peri)==4){
					i=0;
					currentPeripheralData=rootPeripheralData;
					//while(strcmp(currentPeripheralData->PeriAddress,address_peri)!=0 && ((int)currentPeripheralData)>0 && currentPeripheralData!=0){
					while(strcmp(currentPeripheralData->PeriAddress,address_peri)!=0 && (currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
						i++;
						currentPeripheralData=currentPeripheralData->next;
					}
					peri_id_position=i;
					if(strcmp(currentPeripheralData->PeriAddress,address_peri)==0){
						#if DEBUG_LEVEL>0
						printf("SETOUT | address_peri = %s | id_output = %d | value_output = %d\n", address_peri, output_id, output_value);
						#endif
						strcpy(strCmd,"C03"); //cmd to set address of peripheral
						strcat(strCmd,currentPeripheralData->PeriAddress);
						SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1);
						
						//cmd C30 is to load data to send and the 1 is the tag to make understand the peripheral 
						//how to treat the following bytes 
						strcpy(strCmd,"C30RBoIO..........."); //C30 is cmd to load the following data to send
						if(output_id>255) output_id=255; //every peripheral is limited to a maximum of 255 outputs
						if(output_id<0) output_id=0; //the first id output has to starts from 0
						
						strCmd[6]=output_id;
						
						if(output_value>255) output_value=255; //the output is limited to a maximum value of 255
						if(output_value<0) output_value=0; //the output is limited to a minimum value of 0
						
						strCmd[7]=output_value;

						//########### v1.1 ###########
						SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2, 0);
						
						if(strcmp(statusRFPI,MSG_FIFO_RFPI_STATUS_OK)==0 && answerRFPI[0]=='O' && answerRFPI[1]=='K'){
							get_IO_Peri_Status(handleUART, currentPeripheralData, output_id, (char)'p', array_status);
						}
					}else{
						varError = 1;
					}
				}else{
					varError = 1;
					
				}
				if(varError==1){
					//if te address given is not 4 characters then it is not a valid address 
					answerRFPI[0]='K'; answerRFPI[1]='O';
				}
				#if DEBUG_LEVEL>0
				printf(" status= %s\n",answerRFPI);
				#endif
				if(answerRFPI[0]=='O' && answerRFPI[1]=='K'){
					strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK); 
					#if DEBUG_LEVEL>0
					printf("Status=OK\n");
					#endif
				}else{
					strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOTX); 
					#if DEBUG_LEVEL>0
					printf("Status=NOTX\n");
					#endif
				} 


			}else if(strcmp(tag,"STATUS")==0 && strcmp(type,"RFPI")==0 && strcmp(value1,"GOT")==0){
			//	STATUS 	RFPI 	GOT 	NULL		//it says to the rfpi that the status has been readed, thus rfpi can write into FIFO_RFPI_STATUS the word "OK"
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK);
			
			
			}else if(strcmp(tag,"NAME")==0 && strcmp(type,"NET")==0){ //it will save the network name
			//	NAME 	NET 	xxx... 	NULL		//it save the network name and create a numerical address. Name of the network is xxx...
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				
				//copying the first 128 (MAX_LEN_NET_NAME) characters
				for(i=0;i<strlen(value1) && i<MAX_LEN_NET_NAME;i++){
					networkName[i]=value1[i];
				}
				networkName[i]='\0';
				
				file_pointer = fopen (FILE_NETWORK_NAME,"w");
				if (file_pointer!=NULL){
					fputs (networkName,file_pointer);
					fclose (file_pointer);
					
					if(readNetworkNameFromFile(networkName)){
						strcpy(statusInit, ERROR003);
						file_pointer_error = fopen(FILE_ERROR_HISTORY,"w+");
						fprintf(file_pointer_error,"%s\n",statusInit); //writing on the file the line of the inputs
						fclose(file_pointer_error);
					}else{
						//convert the network name in an address for the Transceiver module
						addressFromName(networkName, networkAddress);
						#if DEBUG_LEVEL>0
						//just to see the output
						printf("NETWORK NAME: %s\n", networkName);
						printf("NETWORK ADDRESS: %s\n", networkAddress);
						printf("\nReinit RFPI Network...\n"); 
						#endif
						
						//init the Transceiver module
						initRFberryNetwork(handleUART);
						
						#if DEBUG_LEVEL>0
						printf("\n\nDone!\n");
						#endif
						
						if(statusInit[0]=='E' && statusInit[5]=='0' && statusInit[6]=='0' && statusInit[7]=='3'){
							strcpy(statusInit, "TRUE");
						}
						 
						if(rootPeripheralData!=0){
							currentPeripheralData=rootPeripheralData;
							//while(((int)currentPeripheralData)>0 && currentPeripheralData!=0){
							while((currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
								//delete the file descriptor
								strcpy(strPathFile,PATH_CONFIG_FILE);
								strcat(strPathFile,currentPeripheralData->NameFileDescriptor);
								status = remove(strPathFile);
								if( status == 0 ){
									#if DEBUG_LEVEL>0
									printf("%s file deleted successfully.\n",currentPeripheralData->NameFileDescriptor);
									#endif
								}else{
									#if DEBUG_LEVEL>0
									printf("Unable to delete the file %s\n", currentPeripheralData->NameFileDescriptor);
									#endif
									perror("Unable to delete the file in saving the NET NAME");
								}
																
								nextPeripheralData=currentPeripheralData->next;
								
								free(currentPeripheralData);
								
								currentPeripheralData=nextPeripheralData;
								
							}
							rootPeripheralData=0;
							
							//renaming old file list peripheral in backup
							strcpy(strPathFile,FILE_LIST_PERIPHERAL);
							strcpy(strPathFile2,PATH_CONFIG_FILE);
							strcat(strPathFile2,"list_peripheral.backup");
							status= rename(strPathFile, strPathFile2); //( oldname , newname );
							if ( status == 0 ){
								//puts ( "File successfully renamed" );
							}else{
								perror( "Error renaming file list_peripheral" );
							}	
							//creating a blank list_peripheral.txt 
							file_pointer = fopen(strPathFile,"w+"); 
							fclose(file_pointer);
							
							
						}
					}
					
				}

			
			}else if(strcmp(tag,"NAME")==0 && strcmp(type,"PERI")==0){ //it will change the name of the peripheral
			//	NAME 	PERI 	xxx...	xxxx  		//it save the name of aperipheral given by xxx... with address given by xxxx
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				
				strcpy(strPathFile,PATH_CONFIG_FILE);
				strcat(strPathFile,"list_peripheral2.txt");
				file_pointer = fopen(strPathFile,"w"); //opening the list of peripherals 
				if(file_pointer == NULL){
					perror("Error while opening the file list_peripheral.txt.\n");
					exit(EXIT_FAILURE);
				}else{ 
					i=0;
					currentPeripheralData=rootPeripheralData;
					//will rewrite the whole file
					//while(((int)currentPeripheralData)>0 && currentPeripheralData!=0){ 
					while((currentPeripheralData)>0 && currentPeripheralData!=0){ //for LINUX_MINT
						if(strcmp(value2, currentPeripheralData->PeriAddress)==0){ 
						
							free(currentPeripheralData->Name);
							currentPeripheralData->Name=(char*)malloc((strlen(value1)+1)*sizeof(char));
							strcpy(currentPeripheralData->Name, value1);
						}
						fprintf(file_pointer,"%d %d %s %s %s %d %d\n", i, currentPeripheralData->IDtype, currentPeripheralData->Name, currentPeripheralData->NameFileDescriptor, currentPeripheralData->PeriAddress, currentPeripheralData->numSpecialFunction, currentPeripheralData->fwVersion);
						i++;
						currentPeripheralData=currentPeripheralData->next;
					}
				}
				fclose(file_pointer); 
				
				//renaming old file list peripheral in backup
				strcpy(strPathFile,FILE_LIST_PERIPHERAL);
				strcpy(strPathFile2,PATH_CONFIG_FILE);
				strcat(strPathFile2,"list_peripheral.backup");
				status= rename(strPathFile, strPathFile2); //( oldname , newname );
				if ( status == 0 ){
					puts ( "File successfully renamed" );
				}else{
					perror( "Error renaming file" );
				}
				//renaming new file list peripheral
				strcpy(strPathFile,PATH_CONFIG_FILE);
				strcat(strPathFile,"list_peripheral2.txt");
				strcpy(strPathFile2,FILE_LIST_PERIPHERAL);
				status= rename(strPathFile, strPathFile2); //( oldname , newname );
				if ( status == 0 )
					puts ( "File successfully renamed" );
				else
					perror( "Error renaming file" );

				
			}else if(strcmp(tag,"REFRESH")==0 && strcmp(type,"PERI")==0 && strcmp(value1,"STATUS")==0 && strcmp(value2,"ALL")==0){ //it will update the status of all inputs and outputs of each peripherals
			//	REFRESH	PERI STATUS	ALL  		//it refressh all input/output status for all peripherals
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO files
				
				strcpy(strMsgTemp, MSG_FIFO_RFPI_STATUS_EXECUTING);
				fifoWriter(FIFO_RFPI_STATUS, strMsgTemp);
				strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK);
				 
				//asks to the peripheral the status of all inputs and outputs and update the status into the struct data
				askAndUpdateIOStatusPeri(handleUART, value2, rootPeripheralData);



			}else if(strcmp(tag,"REFRESH")==0 && strcmp(type,"PERI")==0 && strcmp(value1,"STATUS")==0 && strlen(value2)==4){ //it will update the status of all inputs and outputs of the peripherals with address contained into value2
			//	REFRESH	PERI STATUS	xxxx  		//it refressh all input/output status only for the peripheral with address xxxx
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO files
				
				strcpy(strMsgTemp, MSG_FIFO_RFPI_STATUS_EXECUTING);
				fifoWriter(FIFO_RFPI_STATUS, strMsgTemp);
				strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK);

				//asks to the peripheral the status of all inputs and outputs and update the status into the struct data
				askAndUpdateIOStatusPeri(handleUART, value2, rootPeripheralData);
				
				
			}else if(strcmp(tag,"DATA")==0 && strcmp(type,"RF")==0 && strlen(value1)>3){ //it will send the data written into value2 through the radio at the peripheral with position value1
			//	DATA	RF 	xxxx	strHex16bytes  		//it send the 16bytes to a peripheral with address xxxx. Example of strHex16bytes: 524275010000000300002E2E2E2E2E2E
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
		
				if(rootPeripheralData!=0){ 
					i=0; //used as var exit
					currentPeripheralData=rootPeripheralData; 
					while((currentPeripheralData)>0 && currentPeripheralData!=0 && i==0){//for LINUX_MINT
						if(strcmp(currentPeripheralData->PeriAddress,value1)!=0){
							currentPeripheralData=currentPeripheralData->next;
						}else{
							i=1; //used as var exit
						}
					}
					if((currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT

						//converting lower case in upper case. value2 is the hex data to send to the transceiver
						for( i=0; i<strlen(value2); i++){
							if(value2[i]=='a') value2[i]=='A';
							else if(value2[i]=='b') value2[i]='B';
							else if(value2[i]=='c') value2[i]='C';
							else if(value2[i]=='d') value2[i]='D';
							else if(value2[i]=='e') value2[i]='E';
							else if(value2[i]=='f') value2[i]='F';
			
						}
						
						#if DEBUG_LEVEL>0
						printf("DATA RF | Address = %s | 16byte_hexadecimal_ascii = %s\n", value1, value2);
						#endif
						strcpy(strCmd,"C03"); //cmd to set address of peripheral
						strcat(strCmd,currentPeripheralData->PeriAddress);
						SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1);
							
						//cmd C30 is to load data to send and the 1 is the tag to make understand the peripheral 
						//how to treat the following bytes 
						strcpy(strCmd,"C30RBxxxxxxxxxxxxxx"); //C30 is cmd to load the following data to send

						for( i=0,pos=0; i<16; i++,pos+=2){
							strCmd[i+3]=convert_2ChrHex_to_byte(&value2[pos]);
						}

						//########### v1.1 ###########
						if(strCmd[5]=='p' || strCmd[5]=='i' || strCmd[5]=='n' || strCmd[5]=='t' || strCmd[5]=='u') 
							i=1; else i=0; //if it is the command 'i' or similar by the protocol means has to reply
						SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2, i);
					
					#if DEBUG_LEVEL>0
					}else{
						printf("ERROR there is not address like this %s into the list!\n", value1);
					#endif
					}
				
				}
				
				if( (answerRFPI[0]=='O' && answerRFPI[1]=='K') || (answerRFPI[2]=='*' /*&& answerRFPI[7]=='R'*/ && answerRFPI[8]=='B') ){
					if(strlen(answerRFPI) > 6){ //OK*AAAARBxxxxxxxxxxxxxx -> where AAAA is the address and RBxxxxxxxxxxxxxxx are the 16bytes
						char strHexData[]="00000000000000000000000000000000 ";
						
						for(i=0;i<4;i++)
							statusRFPI[i] = answerRFPI[(i+3)]; //copying the address
								
						statusRFPI[i]='\0';

						for(i=0,pos=0; i<16; i++, pos+=2){
							convert_byte_to_2ChrHex(answerRFPI[7+i], &strHexData[pos]);
						}
						
						strcat(statusRFPI, " ");
						strcat(statusRFPI, strHexData);
						#if DEBUG_LEVEL>0
						printf("Status=OK %s\n",statusRFPI);
						#endif					
					}else{
						strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK);
						#if DEBUG_LEVEL>0
						printf("Status=OK\n");
						#endif
					}
				}else{
					strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOPERI); 
					#if DEBUG_LEVEL>0
					printf("Status=NOTX\n");
					#endif
				} 


			}else if(strcmp(tag,"RTC")==0 && strcmp(type,"SET")==0 /*&& strcmp(value1,"STATUS")==0 && strcmp(value2,"ALL")==0*/){ //it will update the status of all inputs and outputs of each peripherals
			//	RTC		SET		NULL	NULL  		//it set RTC if it is installed on the gateway
				
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO files
				
				
					#ifdef ENABLE_READING_I2C_RTC
					if(set_RTC(value1, value2)){
						//RTC 
					}
					#endif // ENABLE_READING_I2C_RTC

					
			//***************************** Begin: GET_BYTES_U ************************************
			}else if(strcmp(tag,"GET_BYTES_U")==0){
			//	GET_BYTES_U		id_position		num_function	num_bytes_to_get  		//it get a series of data from the peripheral give by the ID position id_position
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				
				sscanf(type,"%d", &peri_id_position);
				sscanf(value1,"%d", &num_function);
				sscanf(value2,"%d", &num_bytes_to_get);
				
				unsigned char arrayBytesFunctionU[num_bytes_to_get];
				
				
				
				if(rootPeripheralData!=0){
					i=0;
					currentPeripheralData=rootPeripheralData;
					while(i<peri_id_position && (currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
						i++;
						currentPeripheralData=currentPeripheralData->next;
					}
					#if DEBUG_LEVEL>0
					printf("GET_BYTES_U | id_position = %d | num_function = %d | num_bytes_to_get = %d\n", peri_id_position, num_function,num_bytes_to_get);
					#endif
					stop_by_user = 0;

					i=0; //this is the counter to get the number of bytes
					cont_packet=0; //this is the counter of the packet to get
					num_time_the_peri_did_not_reply=0; //this is a counter that is incremented each time the peri do not reply
					sem_1 = 0; //this is set to 1 if some byte is received from the peri and there are no error in the enquire (like num_bytes_to_get)
					while(i<num_bytes_to_get && cont_packet<0xFFFF && num_time_the_peri_did_not_reply<MAX_NUM_RETRY){
						//reset the counter to give the time to be read the status by the GUI, after the status return to OK
						contStatusMsg=0;
						strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_READING); 
						#if DEBUG_LEVEL>0
						printf("GET_BYTES_U READING PACKET %d: \n", cont_packet);
						#endif
						//tell to the GUI the various status
						fifoWriter(FIFO_RFPI_STATUS, statusRFPI); 
						
						status = get_bytes_u_Peri(handleUART, currentPeripheralData->PeriAddress, num_function, cont_packet, answerRFPI);
						if(status == 1){
							j=0;
							while(i<num_bytes_to_get && j<10){ 	//j is to get the bytes that are after OKRBuFxx 
																//where F is the number of the function
																//xx are 2 byte used to identify the number of the packet
																//thus remain 10 bytes
								arrayBytesFunctionU[i] = answerRFPI[j+13]; //start from byte 13 to get the 10 bytes
								i++;
								j++;
								
								sem_1 = 1;
							}
							cont_packet++;
							num_time_the_peri_did_not_reply = 0;
							
							
						}else if(status == -1){
							//the peri did not reply
							num_time_the_peri_did_not_reply++;
						}
						
						//delay_ms(50); //just to leave the time to the peri 
						
						//here read the fifo again just to know if the user stopped the process of reading
						if(fifoReader(data, FIFO_GUI_CMD)) {
							sscanf(data,"%s %s %s %s ",tag, type, value1, value2);
							#if DEBUG_LEVEL>0
							printf("From FIFO_GUI_CMD received: %s %s %s %s\n", tag, type, value1, value2);
							#endif
							if(strcmp(tag,"STOP")==0 && strcmp(type,"CMD")==0){
								//the user stopped the process	
								stop_by_user = 1;
							}
						}
						
					}
					#if DEBUG_LEVEL>0
					printf(" status= %s\n",answerRFPI);
					#endif
					if(stop_by_user==1){
						strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_STOPPED); 
						#if DEBUG_LEVEL>0
						printf("Status=STOPPED\n");
						#endif
					}else if(num_time_the_peri_did_not_reply>=MAX_NUM_RETRY){
						strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOTX); 
						#if DEBUG_LEVEL>0
						printf("Status=NOTX\n");
						#endif
					}else if(sem_1 == 1){	//there were not error and the peri replied
						
						//create a file with all data		
						char str_number[] = "64000n";
						char str_currentPeripheralAddress[] = "0000n";
						
						strcpy(str_currentPeripheralAddress,currentPeripheralData->PeriAddress); 
						
						file_pointer = fopen (FIFO_GET_BYTES_U,"w");
						if (file_pointer!=NULL){
							for(i=0; i<num_bytes_to_get ;i++){	
								snprintf(str_number, sizeof(str_number), "%d\n", arrayBytesFunctionU[i]);
								fputs (str_number,file_pointer);
							}
							fclose (file_pointer);
							
							//set the status
							strcpy(statusRFPI,"GET_BYTES_U "); 
							strcat(statusRFPI, str_currentPeripheralAddress);
							strcat(statusRFPI, " ");
							strcat(statusRFPI, FIFO_GET_BYTES_U);
							#if DEBUG_LEVEL>0
							printf("Status=GET_BYTES_U %s %s\n", str_currentPeripheralAddress, FIFO_GET_BYTES_U); //printing address and path of the data file
							#endif
						}else{
							strcpy(statusRFPI,ERROR006); 
							#if DEBUG_LEVEL>0
							printf("Status=");printf(ERROR006);printf("\n");
							#endif
						}
					
						
					}else{
						strcpy(statusRFPI,ERROR005); 
						#if DEBUG_LEVEL>0
						printf("Status=");printf(ERROR005);printf("\n");
						#endif

						file_pointer_error = fopen(FILE_ERROR_HISTORY,"w+");
						fprintf(file_pointer_error,"%s\n", statusInit); //writing on the file the line of the inputs
						fclose(file_pointer_error);
					} 
				
				}

			//***************************** End: GET_BYTES_U ************************************
			
			
			
			//***************************** Begin: SEND_BYTES_F ************************************
			}else if(strcmp(tag,"SEND_BYTES_F")==0){ 
			//	SEND_BYTES_F		id_position		num_function	num_bytes_to_send  		//it sends a series of data to the peripheral give by the ID position id_position
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				
				//tag[0]='\0';
				sscanf(type,"%d", &peri_id_position);
				sscanf(value1,"%d", &num_function);
				sscanf(value2,"%d", &num_bytes_to_send);
				
							
				if(rootPeripheralData!=0){
					i=0;
					currentPeripheralData=rootPeripheralData;
					//while(i<peri_id_position && ((int)currentPeripheralData)>0 && currentPeripheralData!=0){
					while(i<peri_id_position && (currentPeripheralData)>0 && currentPeripheralData!=0){//for LINUX_MINT
						i++;
						currentPeripheralData=currentPeripheralData->next;
					}
					#if DEBUG_LEVEL>0
					printf("SEND_BYTES_F | id_position = %d | num_function = %d | num_bytes_to_send = %d\n", peri_id_position, num_function, num_bytes_to_send);
					#endif
					if(access(FIFO_SEND_BYTES_F, F_OK) == 0){
						//FIFO Exist!
						// remove the old FIFO 
						file_pointer = fopen (FIFO_SEND_BYTES_F,"r");

						stop_by_user = 0;

						cont_packet_sent=0; //this is the counter to get the number of bytes
						cont_packet=0; //this is the counter of the packet to get
						num_time_the_peri_did_not_reply=0; //this is a counter that is incremented each time the peri do not reply
						sem_1 = 1; //this is set to 1 if some byte is received from the peri and there are no error in the enquire (like num_bytes_to_send)
						while(cont_packet_sent<num_bytes_to_send && cont_packet<0xFFFF && (!feof(file_pointer)) && stop_by_user == 0 && num_time_the_peri_did_not_reply<MAX_NUM_RETRY){

							//reset the counter to give the time to be read the status by the GUI, after the status return to OK
							contStatusMsg=0;
							strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_SENDING); 
							//tell to the GUI the various status
							fifoWriter(FIFO_RFPI_STATUS, statusRFPI); 
							
							for(t=0; t<10; t++){ //reset the 10 bytes to send
								array10BytesToSend[t]=0;
							}
							
							for(t=0; t<10 && (!feof(file_pointer)); t++){ //here get 10 bytes from the file
								if (fscanf(file_pointer, "%d", (int *)(&byte_to_send))){ //if the byte is readable into the file
									array10BytesToSend[t]=byte_to_send; 						
								}
								cont_packet_sent++; //increase the counter for the bytes to send
							}
							#if DEBUG_LEVEL>0
							printf("\n");fflush(stdout);
							#endif
							
							num_time_the_peri_did_not_reply = 0;
							sem1_exit = 0;
							do{
								status = send_bytes_f_Peri(handleUART, currentPeripheralData->PeriAddress, num_function, cont_packet, array10BytesToSend, answerRFPI);
								if(status == 1){
									cont_packet++;
									sem1_exit = 1;
								}else if(status == -1){
									//the peri did not reply
									num_time_the_peri_did_not_reply++;
									#if DEBUG_LEVEL>0
									printf(" Retry %d for SEND_BYTES_F \n", num_time_the_peri_did_not_reply);
									#endif
									//cont_packet_sent=cont_packet_sent-t;
									sem_1 = 0; 
								}
							}while(num_time_the_peri_did_not_reply<MAX_NUM_RETRY && sem1_exit == 0);

							//here read the fifo again just to know if the user stopped the process of reading
							if(fifoReader(data, FIFO_GUI_CMD)) {
								sscanf(data,"%s %s %s %s ",tag, type, value1, value2);
								#if DEBUG_LEVEL>0
								printf("From FIFO_GUI_CMD received: %s %s %s %s\n", tag, type, value1, value2);
								#endif
								if(strcmp(tag,"STOP")==0 && strcmp(tag,"CMD")==0){
									//the user stopped the process	
									stop_by_user = 1;
								}
							}
							
						}

						
					
						if(stop_by_user==1){
							strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_STOPPED); 
							#if DEBUG_LEVEL>0
							printf("Status=STOPPED\n");
							#endif
						}else if(num_time_the_peri_did_not_reply>=MAX_NUM_RETRY){
							strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOTX); 
							#if DEBUG_LEVEL>0
							printf("Status=NOTX\n");
							#endif
						}else if(cont_packet_sent<num_bytes_to_send && (!feof(file_pointer))){	
							strcpy(statusRFPI,ERROR009); 
							#if DEBUG_LEVEL>0
							printf("Status=");printf(ERROR009);printf("\n");
							#endif
							file_pointer_error = fopen(FILE_ERROR_HISTORY,"w+");
							fprintf(file_pointer_error,"%s\n", statusInit); //writing on the file the line of the inputs
							fclose(file_pointer_error);
							
						}else if(sem_1 == 1){	//there were not error and the peri replied

							//create a file with all data		
							char str_number[] = "64000n";
							char str_currentPeripheralAddress[] = "0000n";
							
							strcpy(str_currentPeripheralAddress,currentPeripheralData->PeriAddress); 
		
							//set the status
							strcpy(statusRFPI,"SEND_BYTES_F "); 
							strcat(statusRFPI, str_currentPeripheralAddress);
							strcat(statusRFPI, " ");
							strcat(statusRFPI, "OK");
							#if DEBUG_LEVEL>0
							printf("Status=SEND_BYTES_F %s OK\n", str_currentPeripheralAddress); //printing address and OK to say all was ok
							#endif
						}else{
							strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_NOTX); 
							#if DEBUG_LEVEL>0
							printf("Status=NOTX\n");
							#endif
						} 
						#if DEBUG_LEVEL>0
						printf(" Status for SEND_BYTES_F=%s | Num byte sent=%d | Num packet sent=%d\n",statusRFPI,cont_packet_sent,cont_packet); fflush(stdout);
						#endif
						fclose(file_pointer);
					
					
					}else{
						strcpy(statusRFPI,ERROR008); 
						#if DEBUG_LEVEL>0
						printf("Status=");printf(ERROR008);printf("\n");
						#endif

						file_pointer_error = fopen(FILE_ERROR_HISTORY,"w+");
						fprintf(file_pointer_error,"%s\n", statusInit); //writing on the file the line of the inputs
						fclose(file_pointer_error);
					}

					

				}
				
				
			}else
			//***************************** End: SEND_BYTES_F ************************************
		
			if(strcmp(tag,"SENDJSONSETTINGS")==0){
			//	SENDJSONSETTINGS		address_peri		json_file	NULL  		//it sends a json configuration to a periheral with address = address_peri. The function send_to_transceiver_json_settings(...) is kept into file rfpi_json.c
				contStatusMsg=0; //reset the counter to give the time to be read the status by the GUI, after the status return to OK
				*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
				strcpy(strMsgTemp, MSG_FIFO_RFPI_RUN_BUSY); 
				fifoWriter(FIFO_RFPI_RUN, strMsgTemp); 		//this tell to the gui that is busy thus the GUI should not read the FIFO files
				strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);	//when it rewrite the FIFO INIT (into main loop wehn cmd_executed=1) then tell to the GUI that can reads again the FIFO files
				
				//the GUI will wait for the data updated
				strcpy(strMsgTemp, MSG_FIFO_RFPI_STATUS_EXECUTING);
				fifoWriter(FIFO_RFPI_STATUS, strMsgTemp);
				strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK);
				
				
				//sscanf(type,"%d", &peri_id_position);
				char address_peri[10];
				char varError = 0;
				strcpy(address_peri, type);

				//this function build and send command to the transceiver, this is the radio command:
				//	R	B	s	EEPROM_POS	H_ID_shield	L_ID_shield	PIN_used	PIN_mask	PULL-UP_resistor	ID_function0	ID_function1	ID_function2	ID_function3	ID_function4	ID_function5	ID_function6
				//send_to_transceiver_json_settings(rootJsonSettings, address_peri, value1, handleUART);
				rootPeripheralData=send_to_transceiver_json_settings(rootPeripheralData, address_peri, value1, handleUART);
				
				
				
			}else{
				//*cmd_executed=0; //no command have been executed!
			}
			
			
			//reset the counter to give the time to be read the status by the GUI, after the status return to OK
			//contStatusMsg=0;
		
	}
		
	//data[0]='\0';	
	return rootPeripheralData;
}


//it check the data into the buffer of the UART, return the data on the string given
int checkDataIntoUART(int *handleUART, unsigned char *dataRFPI, int lenght_buffer_dataRFPI){
	
	int i=0;
	int numCharacters=serialDataAvail (*handleUART) ;

	if(numCharacters>0){
		if (numCharacters > lenght_buffer_dataRFPI ) numCharacters = lenght_buffer_dataRFPI-1;
		printf(" Data (%d bytes) from the Transceiver: ",numCharacters);
		for(i=0;i<numCharacters;i++){
			dataRFPI[i]=serialGetchar (*handleUART);
			printf("%c", dataRFPI[i]);
		}
		dataRFPI[i]='\0';
		printf("\n");
	}else{
		//erase the data, thus will not parse again the previous data
		dataRFPI[0]='\0';
	}
	
	return numCharacters;
	
}


//it parse the data given. In case of data from peripheral, it will update the struct data
peripheraldata *parseDataFromUART(unsigned char *dataRFPI, int *numBytesDataRFPI, peripheraldata *rootPeripheralData, int *cmd_executed){

	peripheraldata *currentPeripheralData=0;
	peripheraldatanameinput *currentPeripheralDataNameInput=0;
	peripheraldatanameoutput *currentPeripheralDataNameOutput=0;
	
	unsigned int IDinput, statusInput, IDoutput, statusOutput,IDfunction,statusFunction;
	IDinput =  statusInput =  IDoutput =  statusOutput = IDfunction = statusFunction = 0;
	int id_shield_connected=0;
	int num_pin_used_on_the_peri=0;
	
	unsigned char addressPeri[5], str_buffer1[6], str_buffer2[6];
	addressPeri[0] =  str_buffer1[0] =  str_buffer2[0] = '\0';
	
	int i,l, cont_pos, varExit;
	i = l = cont_pos = varExit = 0;

	while(cont_pos < (*numBytesDataRFPI)){ 
		//data must be address, filter RB and tag. example: AA00RBi01
		//check if the data come from RFberry Pi peripheral
		if(dataRFPI[cont_pos]!='\0' /*&& dataRFPI[cont_pos+4]=='R'*/ && dataRFPI[cont_pos+5]=='B'){
				
			if(dataRFPI[cont_pos+6]=='i'){ //an input has been changed
					*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
					
					//coping the address of the peripheral which has transmitted
					addressPeri[0]=dataRFPI[cont_pos];
					addressPeri[1]=dataRFPI[cont_pos+1];
					addressPeri[2]=dataRFPI[cont_pos+2];
					addressPeri[3]=dataRFPI[cont_pos+3];
					addressPeri[4]='\0';

					//get the ID input
					IDinput=dataRFPI[cont_pos+7];
					
					//get the status of the input
					statusInput=dataRFPI[cont_pos+8];
					
					
					//this is used mostly for peripheral 100, it get the ID of the shield connected to this input
					id_shield_connected = dataRFPI[cont_pos+4+POS_IO_ID_SHIELD]; 
					
					//this is the number of the pin used on the peripheral
					num_pin_used_on_the_peri = dataRFPI[cont_pos+4+POS_IO_NUM_PIN]; 
					
					
					itoaRFPI(IDinput,str_buffer1,10); itoaRFPI(statusInput,str_buffer2,10);
					#if DEBUG_LEVEL>0
					printf("  ADDRESS: %s,  CMD: i,  ID=%s,  IN=%s\n",addressPeri,str_buffer1,str_buffer2); fflush(stdout);
					#endif
					i=0; l=0; varExit=0;
					currentPeripheralData=rootPeripheralData;
					while((currentPeripheralData)>0 && currentPeripheralData!=0 && varExit==0){//for LINUX_MINT
						currentPeripheralDataNameInput=currentPeripheralData->rootNameInput;
						if(strcmp(addressPeri,currentPeripheralData->PeriAddress)==0){
							while(currentPeripheralDataNameInput!=0  && varExit==0){
								if(l==IDinput){
									currentPeripheralDataNameInput->StatusInput=statusInput;
									
									if(currentPeripheralDataNameInput->StatusInput == -1){
										currentPeripheralDataNameInput->StatusCommunication=-1;
									}else{
										currentPeripheralDataNameInput->StatusCommunication=1;
										currentPeripheralDataNameInput->id_shield_connected = id_shield_connected;
										currentPeripheralDataNameInput->num_pin_used_on_the_peri = num_pin_used_on_the_peri;
									}

									
									varExit=1;
								}
								l++;
								currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
							}
						}
						i++;
						currentPeripheralData=currentPeripheralData->next;
					}
					
			}else if(dataRFPI[cont_pos+6]=='p'){ //an output has been changed
					*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
					
					//copying the address of the peripheral which has transmitted
					addressPeri[0]=dataRFPI[cont_pos];
					addressPeri[1]=dataRFPI[cont_pos+1];
					addressPeri[2]=dataRFPI[cont_pos+2];
					addressPeri[3]=dataRFPI[cont_pos+3];
					addressPeri[4]='\0';

					//get the ID output
					IDoutput = dataRFPI[cont_pos+7];
					
					//get the status of the output
					statusOutput = dataRFPI[cont_pos+8];
					
					//this is used mostly for peripheral 100, it get the ID of the shield connected to this input
					id_shield_connected = dataRFPI[cont_pos+4+POS_IO_ID_SHIELD];
					
					//this is the number of the pin used on the peripheral
					num_pin_used_on_the_peri = dataRFPI[cont_pos+4+POS_IO_NUM_PIN];
					
					itoaRFPI(IDoutput,str_buffer1,10); itoaRFPI(statusOutput,str_buffer2,10);
					#if DEBUG_LEVEL>0
					printf("  ADDRESS: %s,  CMD: p,  ID=%s,  OUT=%s\n",addressPeri,str_buffer1,str_buffer2); fflush(stdout);
					#endif
					
					i=0; l=0; varExit=0;
					currentPeripheralData=rootPeripheralData;
					while((currentPeripheralData)>0 && currentPeripheralData!=0 && varExit==0){//for LINUX_MINT
						currentPeripheralDataNameOutput=currentPeripheralData->rootNameOutput;
						if(strcmp(addressPeri,currentPeripheralData->PeriAddress)==0){
							while(currentPeripheralDataNameOutput!=0 && varExit==0){
								if(l == IDoutput){
									currentPeripheralDataNameOutput->StatusOutput=statusOutput;
									
									if(currentPeripheralDataNameOutput->StatusOutput == -1){
										currentPeripheralDataNameOutput->StatusCommunication=-1;
									}else{
										currentPeripheralDataNameOutput->StatusCommunication=1;
										currentPeripheralDataNameOutput->id_shield_connected = id_shield_connected;
										currentPeripheralDataNameOutput->num_pin_used_on_the_peri = num_pin_used_on_the_peri;
									}
									
									varExit=1;
								}
								l++;
								currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
							}
						}
						i++;
						currentPeripheralData=currentPeripheralData->next;
					}
					
			}else if(dataRFPI[cont_pos+6]=='u'){ //a special function of a peri sent its status
					*cmd_executed=1; //that will make to rewrite the fifo with all data of the peripherals
					
					//copying the address of the peripheral which has transmitted
					addressPeri[0]=dataRFPI[cont_pos];
					addressPeri[1]=dataRFPI[cont_pos+1];
					addressPeri[2]=dataRFPI[cont_pos+2];
					addressPeri[3]=dataRFPI[cont_pos+3];
					addressPeri[4]='\0';
					
					//get the ID of the function
					IDfunction = dataRFPI[cont_pos+7];
					
					//get the status of the function
					statusFunction = dataRFPI[cont_pos+8];
					
					itoaRFPI(IDfunction,str_buffer1,10); itoaRFPI(statusFunction,str_buffer2,10);
					#if DEBUG_LEVEL>0
					printf("  ADDRESS: %s,  CMD: u,  ID=%s,  STATUS=%s\n",addressPeri,str_buffer1,str_buffer2); fflush(stdout);
					#endif

					i=0; varExit=0;
					currentPeripheralData=rootPeripheralData; 
					//while(((int)currentPeripheralData)>0 && currentPeripheralData!=0 && varExit==0){ 
					while((currentPeripheralData)>0 && currentPeripheralData!=0 && varExit==0){ //for LINUX_MINT
						if(strcmp(addressPeri,currentPeripheralData->PeriAddress)==0){ 
							#if DEBUG_LEVEL>0
							printf("  ID Peri Type: %d\n",currentPeripheralData->IDtype); fflush(stdout); 
							#endif
							if(currentPeripheralData->IDtype==9){
								if(IDfunction==1){ 
									//save data of the Peri9 into a file 
									get_conf_data_peri9(addressPeri);
									//save_data_peri9_into_a_file(dataRFPI, addressPeri);
									
									//save the data sent by the Peri9 into a file, into a row there will be data0, data1, data2, data3, data4, time, date
									save_data_peri9_into_a_file_1row_7data(dataRFPI, addressPeri);
									
									save_data_peri9_into_a_file_and_send_email(dataRFPI, addressPeri, currentPeripheralData->Name);
									varExit=1;
								}
							}
							
						}
						i++;
						currentPeripheralData=currentPeripheralData->next;
					}
					
					
					
					
			}
			
				
		}
		
		cont_pos++;
		varExit=0;
		while(varExit==0){
			if( (cont_pos+18) >= *numBytesDataRFPI){
				cont_pos += 18;
				varExit=1;
			}else if(/*dataRFPI[cont_pos+4]=='R' &&*/ dataRFPI[cont_pos+5]=='B'){
				varExit=1;
			}else{
				cont_pos++;
			}
		}
		
	}
		
	return rootPeripheralData;
}



//asks to the peripheral the status of all inputs and outputs and update the status into the struct data
void askAndUpdateIOStatusPeri(int *handleUART, unsigned char *peripheralAddress, peripheraldata *rootPeripheralData){

	peripheraldata *currentPeripheralData=0;
	peripheraldatanameinput *currentPeripheralDataNameInput=0;
	peripheraldatanameoutput *currentPeripheralDataNameOutput=0;
	
	unsigned int i,l,j;
	i=l=j=0;
	unsigned int varExit=0;
	unsigned int contInOutOFFline, contTotalNumInOut;
	contInOutOFFline = contTotalNumInOut = 0;
	unsigned char array_status[12]; array_status[0]='\0';
	unsigned int numBytesToGetValueStatus=0;
	signed long last_StatusInput;

				i=0; 
				currentPeripheralData=rootPeripheralData;
				while((currentPeripheralData)>0 && currentPeripheralData!=0 && varExit==0){ //for LINUX_MINT
					
					
					if(strcmp(peripheralAddress,currentPeripheralData->PeriAddress)==0 || strcmp(peripheralAddress,"ALL")==0){
						
						if(strcmp(peripheralAddress,"ALL")!=0){
							varExit=1;
						}

						//gets the inputs status
						currentPeripheralDataNameInput=currentPeripheralData->rootNameInput;
						l=0;
						contInOutOFFline=0;
						contTotalNumInOut=0;
						while(currentPeripheralDataNameInput!=0 && currentPeripheralData->NumInput!=0){
							last_StatusInput = currentPeripheralDataNameInput->StatusInput;
							//currentPeripheralDataNameInput->StatusInput=askInputStatusPeri(handleUART, currentPeripheralData->PeriAddress, l); //ask to the peripheral the status
							//get from the peripheral the status of the input/output and it return also the bit resolution, if the bit resolution is over the 8bit then the value is kept into the bytes after the bit resolution byte
							currentPeripheralDataNameInput->StatusInput = get_IO_Peri_Status(handleUART, currentPeripheralData, l, (char)'i', array_status);
							
							if(currentPeripheralDataNameInput->StatusInput == -1){
								contInOutOFFline++;
								if(currentPeripheralData->IDtype == ID_IOTGEMINI_PLATFORM){
									if((l+1) == currentPeripheralData->NumInput){ //last input that contains the MCU Volt
										//thus the last input is the MCU Volt which is also the Vref for the ADC then if it is -1 I will not update the data into the FIFO, so the formula fo the others input would be correct
										currentPeripheralDataNameInput->StatusInput = last_StatusInput;
									}
								}
							}
							contTotalNumInOut++;
							l++;
							currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
						}
						
						//gets the outputs status
						currentPeripheralDataNameOutput=currentPeripheralData->rootNameOutput;
						l=0;
						while(currentPeripheralDataNameOutput!=0 && currentPeripheralData->NumOutput!=0){
							//currentPeripheralDataNameOutput->StatusOutput=askOutputStatusPeri(handleUART, currentPeripheralData->PeriAddress, l); //ask to the peripheral the status
							//get from the peripheral the status of the input/output and it return also the bit resolution, if the bit resolution is over the 8bit then the value is kept into the bytes after the bit resolution byte
							currentPeripheralDataNameOutput->StatusOutput = get_IO_Peri_Status(handleUART, currentPeripheralData, l, (char)'p', array_status);
							
							if(currentPeripheralDataNameOutput->StatusOutput == -1){
								contInOutOFFline++;
							}
							contTotalNumInOut++;
							l++;
							currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
						}
						
						//Now I am going to calculate the percentage of the strength of the link
						currentPeripheralData->strengthLink = (int)((100 / (float)contTotalNumInOut) * ((float)(contTotalNumInOut - contInOutOFFline)));
						
					}
					i++;
					currentPeripheralData=currentPeripheralData->next;
				}
			
}



//get from the peripheral the status of the input/output and it return also the bit resolution, if the bit resolution is over the 8bit then the value is kept into the bytes after the bit resolution byte
extern signed long get_IO_Peri_Status(int *handleUART, peripheraldata *currentPeripheralData, unsigned int ID_IO, char type_IO, unsigned char *array_status){
	//output_value = get_IO_Peri_Status(handleUART, currentPeripheralData, output_id, (char)'p', array_status);
	unsigned char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF]; answerRFPI[0]='\0';
	unsigned char strCmd[30]; strCmd[0]='\0';
	signed long var_return=0;
	unsigned int i,j;
	i=j=0;
	unsigned int cont_ID_IO=0;
	unsigned char varchecksum=0;
	int BitResolution=0;
	unsigned int numBytesToGetValueStatus=0;
	
	signed long Status_IO=0;
	
	struct peripheralnameinput *currentPeripheralDataNameInput=0; //it is like an array containing all name of the inputs
	struct peripheralnameoutput *currentPeripheralDataNameOutput=0; //it is like an array containing all name of the outputs
	
	for(i=0;i<12;i++) array_status[i] = 0;
	
	var_return=-1; //means there is an error! later it would get the value 0 if all goes ok!
	array_status[0]=-1; //status input
	//array_status[1]=0; //bit resolution


	if(type_IO == 'i'){ //it is an input to get
		//here get the address of the struct data of the input to then update the variables
		cont_ID_IO = 0; 
		currentPeripheralDataNameInput=currentPeripheralData->rootNameInput;
		while(cont_ID_IO<ID_IO && currentPeripheralDataNameInput!=0 && cont_ID_IO<currentPeripheralData->NumInput){
			currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
			cont_ID_IO++;
		}
		if(currentPeripheralDataNameInput==0 && cont_ID_IO>=currentPeripheralData->NumInput){
			//the ID_IO is wrong! thus 
			#if DEBUG_LEVEL>0
			printf("ID Input to get the status is wrong!!! ID INPUT = %d, NUM_INPUT = %d\n",ID_IO,currentPeripheralData->NumInput);fflush(stdout);
			#endif
			type_IO = '?'; //this make to jump to the end of this function
		}
		currentPeripheralDataNameInput->StatusInput = -1;
		currentPeripheralDataNameInput->StatusCommunication=-1;
	}else if(type_IO == 'p'){ //it is an output to get
		//here get the address of the struct data of the output to then update the variables
		cont_ID_IO = 0; 
		currentPeripheralDataNameOutput=currentPeripheralData->rootNameOutput;
		while(cont_ID_IO<ID_IO && currentPeripheralDataNameOutput!=0 && cont_ID_IO<currentPeripheralData->NumOutput){
			currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
			cont_ID_IO++;
		}
		if(currentPeripheralDataNameOutput==0 && cont_ID_IO>=currentPeripheralData->NumOutput){
			//the ID_IO is wrong! thus 
			#if DEBUG_LEVEL>0
			printf("ID Output to get the status is wrong!!! ID OUTPUT = %d, NUM_OUTPUT = %d\n",ID_IO,currentPeripheralData->NumOutput);fflush(stdout);
			#endif
			type_IO = '?'; //this make to jump to the end of this function
		}
		currentPeripheralDataNameOutput->StatusOutput = -1;
		currentPeripheralDataNameOutput->StatusCommunication=-1;
	}
	
	if(ID_IO<256 && (type_IO == 'i' || type_IO == 'p') ){ //max number of inputs is 255
	
		strcpy(strCmd,"C03"); //cmd to set address of peripheral
		strcat(strCmd,currentPeripheralData->PeriAddress);
		SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1);
		
		strcpy(strCmd,"C30RB"); //cmd to load data
		strCmd[5]=type_IO; //this can be 'i' or 'p'
		strCmd[6]=ID_IO;//giving the ID of the input to get the status
		strCmd[7]='V';//when the peripheral will reply will change the character in this position with the value of the input
		for(i=8;i<19;i++){
			strCmd[i]='.';
		}
		//the last byte is used for the checksum error
		strCmd[19] = calc_checksum ((unsigned char *)&strCmd[3], 15); //it calc for the 15 bytes of data sent to the Peri
		strCmd[20]='\0';
		
		//########### v1.1 ###########
		SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2, 1);
		
		#ifdef ENABLE_RADIO_DATA_CHECKSUM
			//varchecksum = checksum( &answerRFPI[7], 16);
			varchecksum = checksum2( &answerRFPI[7], 16);
			#if DEBUG_LEVEL>0
			if(varchecksum!=0){
				printf(" CHECKSUM ERROR!!! CHECKSUM = %d, ID_IO = %d\n",varchecksum,ID_IO);fflush(stdout);
			}
			#endif
		#endif
		//checking the address, the tags, if the id input is equal to the one wanted, and eventually the checksum on last byte 
		if(		answerRFPI[3]==currentPeripheralData->PeriAddress[0]
				&& answerRFPI[4]==currentPeripheralData->PeriAddress[1]
				&& answerRFPI[5]==currentPeripheralData->PeriAddress[2] 
				&& answerRFPI[6]==currentPeripheralData->PeriAddress[3] 
				//&& answerRFPI[7]=='R' 
				&& answerRFPI[8]=='B'
				&& answerRFPI[9]==type_IO
				&& answerRFPI[10]==ID_IO  
				&& varchecksum == 0
				){
					
			array_status[0]=answerRFPI[11]; //status input
			array_status[1]=answerRFPI[12]; //bit resolution
			array_status[2]=answerRFPI[13]; //byte H
			array_status[3]=answerRFPI[14]; //
			array_status[4]=answerRFPI[15]; //
			array_status[5]=answerRFPI[16]; //
			array_status[6]=answerRFPI[17]; //
			array_status[7]=answerRFPI[18]; //
			array_status[8]=answerRFPI[19]; //
			array_status[9]=answerRFPI[20]; //
			array_status[10]=answerRFPI[21]; //
			array_status[11]=answerRFPI[22]; // byte L
			var_return=0; //all ok
			
						
			

			if(answerRFPI[7+POS_IO_RESOLUTION]>64){
									if(answerRFPI[7+POS_IO_RESOLUTION]==68) //this mean it is 'D' that in old FW means 'Digital' thus resolution is 1
										BitResolution = 1;
									else if(answerRFPI[7+POS_IO_RESOLUTION]==65) //this mean it is 'A' that in old FW means 'Analogue' thus resolution is 8
										BitResolution = 8;
									else
										BitResolution = 1;
			}else{
									BitResolution = (int)answerRFPI[7+POS_IO_RESOLUTION];
			}
			#if DEBUG_LEVEL>1
			printf("PERIPHERAL FW VERSION: %d\n",currentPeripheralData->fwVersion);
			fflush(stdout);
			#endif	
			#ifndef	ENABLE_OLD_SYSTEM_COMPATIBILITY
			if(BitResolution > 8 && currentPeripheralData->fwVersion > 1){ //this means I have to get the value from the following bytes
									if(BitResolution > 64){
										BitResolution = 64;
									}
									//now I determine on how many byte to get the value
									numBytesToGetValueStatus = (unsigned int)(BitResolution / 8);
									if( (BitResolution - (numBytesToGetValueStatus*8))>0 ){ //calcolo se vi sono dei bit in piu' oltre a quelli divisibili per 8
										numBytesToGetValueStatus++;
									}
									Status_IO = 0;
									for(j=1; j<numBytesToGetValueStatus; j++){
										Status_IO |= (signed long) (  answerRFPI[7+j-1+POS_IO_DATA0] << (8 * (numBytesToGetValueStatus - (j)))  );
									}
									//if( (BitResolution - (numBytesToGetValueStatus * 8)) != 0){
										//this means I have to add still one byte
										//this for the lowest byte:
										Status_IO |= (signed long) (answerRFPI[7+j-1+POS_IO_DATA0]);
									//}
									
			}else{
			#endif	
									Status_IO = (signed long)answerRFPI[7+POS_IO_STATUS_8BIT];
			#ifndef	ENABLE_OLD_SYSTEM_COMPATIBILITY
			}
			#endif						
								
								
			var_return = Status_IO;
			
			#if DEBUG_LEVEL>0
			printf(" RESOLUTION: %d\n",BitResolution);fflush(stdout);
			fflush(stdout);
			#endif	
			if(type_IO == 'i'){ //it is an input to get
				#if DEBUG_LEVEL>0
				printf(" INPUT-%d STATUS GOT = %ld\n",ID_IO,Status_IO);fflush(stdout);
				#endif
				
				currentPeripheralDataNameInput->StatusCommunication = 1;
				
				currentPeripheralDataNameInput->StatusInput = Status_IO;
				currentPeripheralDataNameInput->id_shield_connected = answerRFPI[7+POS_IO_ID_SHIELD];
				currentPeripheralDataNameInput->num_pin_used_on_the_peri = answerRFPI[7+POS_IO_NUM_PIN];
				
			}else if(type_IO == 'p'){ //it is an output to get
				#if DEBUG_LEVEL>0
				printf(" OUTPUT-%d STATUS GOT = %ld\n",ID_IO,Status_IO);fflush(stdout);
				#endif
				
				currentPeripheralDataNameOutput->StatusCommunication = 1;
				
				currentPeripheralDataNameOutput->StatusOutput = Status_IO; 
				currentPeripheralDataNameOutput->id_shield_connected = answerRFPI[7+POS_IO_ID_SHIELD];
				currentPeripheralDataNameOutput->num_pin_used_on_the_peri = answerRFPI[7+POS_IO_NUM_PIN];
				
			}
			
			

		}
		
	}
	
	return var_return;

}


// Implementation of itoa()
char* itoaRFPI(int num, char* str, int base){
    int i = 0;
    unsigned char isNegative = 0;
	char strTemp[100]; strTemp[0]='\0';
 
    // Handle 0 explicitely, otherwise empty string is printed for 0 
    if (num == 0)
    {
        str[i++] = '0';
        str[i] = '\0';
        return str;
    }
 
    // In standard itoa(), negative numbers are handled only with
    // base 10. Otherwise numbers are considered unsigned.
    if (num < 0 && base == 10)
    {
        isNegative = 1;
        num = -num;
    }
 
    // Process individual digits
    while (num != 0)
    {
        int rem = num % base;
        str[i++] = (rem > 9)? (rem-10) + 'A' : rem + '0';
        num = num/base;
    }
 
    // If number is negative, append '-'
    if (isNegative)
        str[i++] = '-';
 
    str[i] = '\0'; // Append string terminator
 
    // Reverse the string
	i=0;
	while(i<strlen(str)){
		strTemp[i]=str[strlen(str)-i-1];
		i++;
	}
	strTemp[i]='\0';
    strcpy(str,strTemp);
 
    return str;
}


//convert 2 Character ASCII in a byte. Example "FF" become 0xFF
unsigned char convert_2ChrHex_to_byte(unsigned char *chr){
	unsigned char pos_last_chr = 2;
	unsigned char byte = 0;
	unsigned char i=0;
	
	for(i=0;i<pos_last_chr;i++){
		if(chr[i] >= 0x30 && chr[i] <= 0x39){ //check if it is between '0' and '9'
			byte = byte | (chr[i] - 0x30);
		}else if(chr[i] >= 0x41 && chr[i] <= 0x46){ //check if it is between 'A' and 'F'
			byte = byte | ((chr[i] - 0x41) + 0x0A);
		}
		
		if(i < (pos_last_chr - 1)){
			byte = byte << 4;
		}
	}
	return byte;
}


//convert a byte in 2 Character ASCII in hexadecimal format. Example 0xFF become "FF"
char* convert_byte_to_2ChrHex(unsigned char byte, char *str2chr){
	unsigned byteTemp=0;
	
	byteTemp = byte >> 4;
	if(byteTemp > 0x09){
		*str2chr = (byteTemp-10) + 0x41; //0x41 = 'A'
	}else{
		*str2chr = byteTemp + 0x30; //0x30 = '0'
	}
	if( byteTemp > 0x0F)
		*(str2chr+1) = '?'; 

	byteTemp = byte & 0x0F;
	if( byteTemp > 0x09){
		*(str2chr+1) = (byteTemp-10) + 0x41; //0x41 = 'A'
	}else{
		*(str2chr+1) = byteTemp + 0x30; //0x30 = '0'
	}
	if( byteTemp > 0x0F)
		*(str2chr+1) = '?'; 

	return str2chr;
}


//it init the RFPI
peripheraldata *InitRFPI(peripheraldata *rootPeripheralData, char *serial_port_path){

	FILE *file_pointer=0; 				//generic pointer to file, used in multiple places
	FILE *file_pointer_error=0; 		//used for the file of the error history
	
	char strMsgTemp[20]; strMsgTemp[0]='\0';

	//if an error happen then it will be rewritten
	strcpy(statusInit,MSG_FIFO_RFPI_RUN_TRUE);
	
	sem_init_gpio_rpi_ok=0;
	
	#ifndef SERIAL_PORT_FTDI_USB
	if(sem_serial_port_USB == 0){ 
		#if PLATFORM == PLATFORM_RPI
			if (!bcm2835_init()){
				sem_init_gpio_rpi_ok=1;
				#if DEBUG_LEVEL>0
				printf(ERROR001);printf("\n");
				#endif
				strcpy(statusInit,ERROR001);
				file_pointer_error = fopen(FILE_ERROR_HISTORY,"w+");
				fprintf(file_pointer_error,"%s\n",statusInit); //writing on the file the line of the inputs
				fclose(file_pointer_error);
				ResetRFPI(); //it reset the Transceiver and turn on the LED called DS2
			}
		#elif PLATFORM == PLATFORM_BBB
			#ifdef LED_YES
				linux_gpio_export(BBB_PIN_LED_DS2);    // The LED DS2
				linux_gpio_set_dir(BBB_PIN_LED_DS2, OUTPUT_PIN);   // The LED DS2 is an output
				ResetRFPI(); //it reset the Transceiver and turn on the LED called DS2
			#endif
		#elif PLATFORM == PLATFORM_OPZ
			#ifdef LED_YES
				linux_gpio_export(OPZ_PIN_LED_DS2);    // The LED DS2
				linux_gpio_set_dir(OPZ_PIN_LED_DS2, OUTPUT_PIN);   // The LED DS2 is an output
			
				linux_gpio_export(OPZ_PIN_BUSY);    // The Busy signal from the radio
				linux_gpio_set_dir(OPZ_PIN_BUSY, INPUT_PIN);   //  The Busy signal from the radio is an input for the OPZ
				ResetRFPI(); //it reset the Transceiver and turn on the LED called DS2
			#endif
		#endif
	} 
	#endif

	//Initialisation of the serial communication with the Transceiver
	if(!InitSerialCommunication(&handleUART, serial_port_path)){
		#if DEBUG_LEVEL>0
		printf("\n"); printf(ERROR002); printf("\n");
		#endif
		strcpy(statusInit,ERROR002);
		file_pointer_error = fopen(FILE_ERROR_HISTORY,"w+");
		fprintf(file_pointer_error,"%s\n",statusInit); //writing on the file the line of the inputs
		fclose(file_pointer_error);
	}
	
	strcpy(statusRFPI,MSG_FIFO_RFPI_STATUS_OK);
	
	//if is not set, networkName[0] will be equal at '\0' and then follows an error message
	if(readNetworkNameFromFile(networkName)){ 
		#if DEBUG_LEVEL>0
		printf("\n"); printf(ERROR003); printf("\n");
		#endif
		strcpy(statusInit,ERROR003);
		file_pointer_error = fopen(FILE_ERROR_HISTORY,"w+");
		fprintf(file_pointer_error,"%s\n",statusInit); //writing on the file the line of the inputs
		fclose(file_pointer_error);
	}else{
	
		//checks if exist the files of the list of peripheral and the descriptors 
		if( access( FILE_LIST_PERIPHERAL, F_OK ) == -1) { 
			//if the file with the list of the peripheral does not exist it will create
			file_pointer = fopen(FILE_LIST_PERIPHERAL,"w");
			fclose(file_pointer);
			#if DEBUG_LEVEL>0
			printf("WARNING: No peripheral linked! Or missing files. \n");
			#endif
		}else{
		
			//load in memory the struct data of all linked peripheral 
			rootPeripheralData=loadLinkedPeripheral(&handleUART);
			#if DEBUG_LEVEL>0
			if(rootPeripheralData==0){
				printf("WARNING: No linked peripherals! Or missing files. \n");
			}
			#endif
			
		}
	
		//init the Transceiver module
		initRFberryNetwork(&handleUART);
	
		//convert the network name in an address for the Transceiver module
		addressFromName(networkName, networkAddress);
		
		#if DEBUG_LEVEL>0
		//just to see the output
		printf("NETWORK NAME: %s\n", networkName);
		printf("NETWORK ADDRESS: %s\n", networkAddress); 
		#endif
		
		//asks to the peripheral the status of all inputs and outputs and update the status into the struct data
		char tag_all[] = "ALL";
		askAndUpdateIOStatusPeri(&handleUART, tag_all, rootPeripheralData);
		
		#if DEBUG_LEVEL>0
			printPeripheralStructData(rootPeripheralData);
		#endif
		
	}	
	
	#if DEBUG_LEVEL>0
	printf("RFPI.C is running! \nPress CTRL+Z to exit\n\n");
	fflush(stdout); // Prints immediately to screen 
	#endif
	
	return rootPeripheralData;
}


//it blink the led and gives a delay to stay under the transmission limit
void blinkLed(){

	#ifdef LED_YES
	
		if(sem_serial_port_USB == 0){
			#if PLATFORM == PLATFORM_RPI
				if(sem_init_gpio_rpi_ok==1){
					if(last_status_blinking_led==0){
						last_status_blinking_led=1;
						bcm2835_gpio_write(PIN_LED_DS2, HIGH);
						//delay_ms(BLINK_LED_DELAY);
					}else{
						last_status_blinking_led=0;
						bcm2835_gpio_write(PIN_LED_DS2, LOW);
						//delay_ms(BLINK_LED_DELAY);
					}
				}else{
					//delay_ms(BLINK_LED_DELAY);
					//delay_ms(BLINK_LED_DELAY);
				}
			#endif
			
			#if PLATFORM == PLATFORM_BBB
				if(last_status_blinking_led==0){
					last_status_blinking_led=1;
					linux_gpio_set_value(BBB_PIN_LED_DS2, HIGH_GPIO);
					//delay_ms(BLINK_LED_DELAY);
				}else{
					last_status_blinking_led=0;
					linux_gpio_set_value(BBB_PIN_LED_DS2, LOW_GPIO);
					//delay_ms(BLINK_LED_DELAY);
				}
			#endif
			
			#if PLATFORM == PLATFORM_OPZ
				if(last_status_blinking_led==0){
					last_status_blinking_led=1;
					linux_gpio_set_value(OPZ_PIN_LED_DS2, HIGH_GPIO);
					//delay_ms(BLINK_LED_DELAY);
				}else{
					last_status_blinking_led=0;
					linux_gpio_set_value(OPZ_PIN_LED_DS2, LOW_GPIO);
					//delay_ms(BLINK_LED_DELAY);
				}
			#endif
		}else{
			//delay_ms(BLINK_LED_DELAY);
			//delay_ms(BLINK_LED_DELAY);
		}	
	
	#else 
		//delay_ms(BLINK_LED_DELAY);
		//delay_ms(BLINK_LED_DELAY);
	#endif

	
}


//read the RTC on the i2c bus and update a FIFO called fifortc
#ifdef ENABLE_READING_I2C_RTC
unsigned char read_RTC(){

	unsigned var_return = -1;
	char fifobuf[64];
	strcpy(fifobuf, "NO RTC");
	
	unsigned char res=0;

	unsigned char I2CBus = 0; 		 //channel where is connected the RTC
	unsigned char I2CAddress = 0x34; //address of the RTC

	__u8 reg_second 	= 	0x00; // Device register to access second
	__u8 reg_minute 	= 	0x01; // Device register to access minute
	__u8 reg_hour 		= 	0x02; // Device register to access hour

	__u8 reg_date 		= 	0x04; // Device register to access number of the day
	__u8 reg_month 		= 	0x05; // Device register to access number of the month
	__u8 reg_year 		= 	0x06; // Device register to access number of the year
			
	unsigned char hour, minute, second;
	unsigned char date, month, year;
	
	hour = minute = second = 0;
	date = month = year = 0;
	
	char namebuf[64]; namebuf[0]='\0';
	int file=0;
	
	if(PLATFORM == PLATFORM_BBB){
	
	
	
		if(RTC_MODEL == RTC_M41T62){
			//the RTC for the BBB is the M41T62
			var_return = 0;
			strcpy(fifobuf, "HH:MM:SS");
			
			
			I2CBus = 1; //channel where is connected the RTC
			I2CAddress = 0x68; //address of the RTC
			
			reg_second = 0x01; // Device register to access second
			reg_minute = 0x02; // Device register to access minute
			reg_hour = 0x03; // Device register to access hour
			
		
			
			
			//char namebuf[64];
			snprintf(namebuf, sizeof(namebuf), "/dev/i2c-%d", I2CBus);
			//int file;
			if ((file = open(namebuf, O_RDWR)) < 0 && var_return==0){
				#if DEBUG_LEVEL>0
					printf("RTC M41T62 ERROR: Failed to open %s I2C Bus\n", namebuf);
				#endif
				snprintf(fifobuf, sizeof(fifobuf), "Failed to open RTC M41T62 on %s I2C Bus\n", namebuf);
				var_return = 1;
			}
			
			if (ioctl(file, I2C_SLAVE, I2CAddress) < 0 && var_return==0){
				#if DEBUG_LEVEL>0
					printf("RTC M41T62 ERROR: I2C_SLAVE address %s  failed...\n", &I2CAddress);
				#endif
				snprintf(fifobuf, sizeof(fifobuf), "I2C_SLAVE address %s  failed...\n", &I2CAddress);
				var_return = 2;
			}
			
			if(var_return==0){
				res = i2c_smbus_read_byte_data(file, reg_hour);
				if (res < 0) {
					#if DEBUG_LEVEL>0
						printf("RTC M41T62 ERROR: hour i2c transaction failed\n");
					#endif
					snprintf(fifobuf, sizeof(fifobuf), "RTC M41T62 ERROR: hour i2c transaction failed\n");
				} else {
					hour = res;
				}
						
				res = i2c_smbus_read_byte_data(file, reg_minute);
				if (res < 0) {
					#if DEBUG_LEVEL>0
						printf("RTC M41T62 ERROR: minute i2c transaction failed\n");
					#endif
					snprintf(fifobuf, sizeof(fifobuf), "RTC M41T62 ERROR: minute i2c transaction failed\n");
				} else {
					minute = res;
				}
						  
				res = i2c_smbus_read_byte_data(file, reg_second);
				if (res < 0) {
					#if DEBUG_LEVEL>0
						printf("RTC M41T62 ERROR: second i2c transaction failed\n");
					#endif
					snprintf(fifobuf, sizeof(fifobuf), "RTC M41T62 ERROR: second i2c transaction failed\n");
				} else {
					second = res;
				}
				
				snprintf(fifobuf, sizeof(fifobuf), "%02x:%02x:%02x", hour, minute, second);
			}
			close(file);
		
		}else
	
	
		if(RTC_MODEL==RTC_DS1307){
			//the RTC for the BBB is the DS1307
			var_return = 0;
			strcpy(fifobuf, "HH:MM:SS DD/MM/YY");
			
			//I2CBus = 0; 		 //channel where is connected the RTC
			I2CBus = 1; 		 //channel where is connected the RTC
			I2CAddress = 0x68; //address of the RTC

			reg_second = 0x00; // Device register to access second
			reg_minute = 0x01; // Device register to access minute
			reg_hour = 0x02; // Device register to access hour
			
			reg_date = 0x04; // Device register to access number of the day
			reg_month = 0x05; // Device register to access number of the month
			reg_year = 0x06; // Device register to access number of the year

			//char namebuf[64];
			snprintf(namebuf, sizeof(namebuf), "/dev/i2c-%d", I2CBus);
			//int file;
			if ((file = open(namebuf, O_RDWR)) < 0 && var_return==0){
				#if DEBUG_LEVEL>0
					printf("RTC DS1307 ERROR: Failed to open %s I2C Bus\n", namebuf);
				#endif
				snprintf(fifobuf, sizeof(fifobuf), "Failed to open RTC DS1307 on %s I2C Bus\n", namebuf);
				var_return = 1;
			}
			
			if (ioctl(file, I2C_SLAVE, I2CAddress) < 0 && var_return==0){
				#if DEBUG_LEVEL>0
					printf("RTC ERROR: I2C_SLAVE address %s  failed...\n", &I2CAddress);
				#endif
				snprintf(fifobuf, sizeof(fifobuf), "I2C_SLAVE address %s  failed...\n", &I2CAddress);
				var_return = 2;
			}
			
			if(var_return==0){
				res = i2c_smbus_read_byte_data(file, reg_hour);
				if (res < 0) {
					#if DEBUG_LEVEL>0
						printf("RTC DS1307 ERROR: hour i2c transaction failed\n");
					#endif
					snprintf(fifobuf, sizeof(fifobuf), "RTC DS1307 ERROR: hour i2c transaction failed\n");
				} else {
					hour = res;
				}
						
				res = i2c_smbus_read_byte_data(file, reg_minute);
				if (res < 0) {
					printf("RTC DS1307 ERROR: minute i2c transaction failed\n");
					snprintf(fifobuf, sizeof(fifobuf), "RTC  DS1307 ERROR: minute i2c transaction failed\n");
				} else {
					minute = res;
				}
						  
				res = i2c_smbus_read_byte_data(file, reg_second);
				if (res < 0) {
					printf("RTC DS1307 ERROR: second i2c transaction failed\n");
					snprintf(fifobuf, sizeof(fifobuf), "RTC DS1307 ERROR: second i2c transaction failed\n");
				} else {
					second = res;
				}
				
				
				
				res = i2c_smbus_read_byte_data(file, reg_date);
				if (res < 0) {
					#if DEBUG_LEVEL>0
						printf("RTC DS1307 ERROR: hour i2c transaction failed\n");
					#endif
					snprintf(fifobuf, sizeof(fifobuf), "RTC DS1307 ERROR: hour i2c transaction failed\n");
				} else {
					date = res;
				}
				
				res = i2c_smbus_read_byte_data(file, reg_month);
				if (res < 0) {
					#if DEBUG_LEVEL>0
						printf("RTC DS1307 ERROR: hour i2c transaction failed\n");
					#endif
					snprintf(fifobuf, sizeof(fifobuf), "RTC DS1307 ERROR: hour i2c transaction failed\n");
				} else {
					month = res;
				}
				
				res = i2c_smbus_read_byte_data(file, reg_year);
				if (res < 0) {
					#if DEBUG_LEVEL>0
						printf("RTC DS1307 ERROR: hour i2c transaction failed\n");
					#endif
					snprintf(fifobuf, sizeof(fifobuf), "RTC DS1307 ERROR: hour i2c transaction failed\n");
				} else {
					year = res;
				}
								
				

				hour 	&= 0b00111111;
				minute 	&= 0b01111111;
				second 	&= 0b01111111;
				
				date 	&= 0b00111111;
				month 	&= 0b00011111;
				year 	&= 0b11111111;
				
				
				
				
				//printf("RTC READ!\n %02x:%02x:%02x \n", hour, minute, second);fflush(stdout); // Prints immediately to screen 
				//printf("RTC READ!\n %02x:%02x:%02x %02x/%02x/%02x \n", hour, minute, second, date, month, year);fflush(stdout); // Prints immediately to screen
				//snprintf(fifobuf, sizeof(fifobuf), "%02x:%02x:%02x", hour, minute, second);
				snprintf(fifobuf, sizeof(fifobuf), "%02x:%02x:%02x %02x/%02x/%02x", hour, minute, second, date, month, year); //&frasl;
			}
			close(file); 
			
		}
		
		
	}
	
	RTC_hour_bcd = hour;
	RTC_minute_bcd = minute;
	RTC_second_bcd = second;
	
	RTC_date_bcd = date;
	RTC_month_bcd = month;
	RTC_year_bcd = year;
	
	fifoWriter(FIFO_RTC, fifobuf);

	return var_return;
}
#endif // ENABLE_READING_I2C_RTC


//set the RTC on the i2c bus. The str_time has the format: hh:mm:ss
#ifdef ENABLE_READING_I2C_RTC
unsigned char set_RTC(unsigned char *str_time, unsigned char *str_data){

	unsigned var_return = -1;
	
	unsigned char res=0;
			
	unsigned char I2CBus = 1; //channel where is connected the RTC
	unsigned char I2CAddress = 0x68; //address of the RTC
			
	__u8 reg_second = 0x01; // Device register to access second
	__u8 reg_minute = 0x02; // Device register to access minute
	__u8 reg_hour = 0x03; 	// Device register to access hour

	__u8 reg_date 		= 	0x04; // Device register to access number of the day
	__u8 reg_month 		= 	0x05; // Device register to access number of the month
	__u8 reg_year 		= 	0x06; // Device register to access number of the year
			
	unsigned char hour, minute, second;
	unsigned char date, month, year;

			
	char namebuf[64]; namebuf[0]='\0';
	int file=0;
			
	if( PLATFORM == PLATFORM_BBB){
	
		if(RTC_MODEL==RTC_M41T62){
			//the RTC for the BBB is the M41T62
			var_return = 0;
			
			
			I2CBus = 1; //channel where is connected the RTC
			I2CAddress = 0x68; //address of the RTC
			
			reg_second = 0x01; // Device register to access second
			reg_minute = 0x02; // Device register to access minute
			reg_hour = 0x03; // Device register to access hour
			
			#if DEBUG_LEVEL>0
				printf("RTC M41T62 SET = \n"); 
			#endif
			
			hour = convert_2ChrHex_to_byte(&str_time[0]);
			#if DEBUG_LEVEL>0
				printf(" hour: %02x\n",hour);
			#endif
			minute = convert_2ChrHex_to_byte(&str_time[3]);
			#if DEBUG_LEVEL>0
				printf(" minute: %02x\n",minute);
			#endif
			second = convert_2ChrHex_to_byte(&str_time[6]);
			#if DEBUG_LEVEL>0
				printf(" second: %02x\n",second);
			#endif

			//char namebuf[64];
			snprintf(namebuf, sizeof(namebuf), "/dev/i2c-%d", I2CBus);
			//int file;
			if ((file = open(namebuf, O_RDWR)) < 0){
				#if DEBUG_LEVEL>0
					printf("Failed to open RTC M41T62 on %s I2C Bus\n", namebuf);
				#endif
				var_return = 1;
			}
			if (ioctl(file, I2C_SLAVE, I2CAddress) < 0){
				#if DEBUG_LEVEL>0
					printf("I2C_SLAVE address %s  failed...\n", &I2CAddress);
				#endif
				var_return = 2;
			}
			
			//set time on the RTC
			i2c_smbus_write_byte_data(file, reg_hour, hour);
			i2c_smbus_write_byte_data(file, reg_minute, minute);
			i2c_smbus_write_byte_data(file, reg_second, second);
			
			close(file);
		}else
		
		
		if(RTC_MODEL==RTC_DS1307){
			//the RTC for the BBB is the DS1307
			var_return = 0;
			
			
			I2CBus = 1; //channel where is connected the RTC
			I2CAddress = 0x68; //address of the RTC
			
			reg_second = 0x00; // Device register to access second
			reg_minute = 0x01; // Device register to access minute
			reg_hour = 0x02; // Device register to access hour
			
			reg_date = 0x04; // Device register to access number of the day
			reg_month = 0x05; // Device register to access number of the month
			reg_year = 0x06; // Device register to access number of the year
			
			#if DEBUG_LEVEL>0
				printf("RTC DS1307 SET = \n"); 
			#endif
			
			hour = convert_2ChrHex_to_byte(&str_time[0]);
			#if DEBUG_LEVEL>0
				printf(" hour: %02x\n",hour);
			#endif
			minute = convert_2ChrHex_to_byte(&str_time[3]);
			#if DEBUG_LEVEL>0
				printf(" minute: %02x\n",minute);
			#endif
			second = convert_2ChrHex_to_byte(&str_time[6]);
			#if DEBUG_LEVEL>0
				printf(" second: %02x\n",second);
			#endif
			
			
			date = convert_2ChrHex_to_byte(&str_data[0]);
			#if DEBUG_LEVEL>0
				printf("\n date: %02x\n",date);
			#endif
			month = convert_2ChrHex_to_byte(&str_data[3]);
			#if DEBUG_LEVEL>0
				printf(" month: %02x\n",month);
			#endif
			year = convert_2ChrHex_to_byte(&str_data[6]);
			#if DEBUG_LEVEL>0
				printf(" year: %02x\n",year);
			#endif
			

			//char namebuf[64];
			snprintf(namebuf, sizeof(namebuf), "/dev/i2c-%d", I2CBus);
			//int file;
			if ((file = open(namebuf, O_RDWR)) < 0){
				#if DEBUG_LEVEL>0
					printf("Failed to open RTC DS1307 on %s I2C Bus\n", namebuf);
				#endif
				var_return = 1;
			}
			if (ioctl(file, I2C_SLAVE, I2CAddress) < 0){
				#if DEBUG_LEVEL>0
					printf("I2C_SLAVE address %s  failed...\n", &I2CAddress);
				#endif
				var_return = 2;
			}
			
			hour 	&= 0b00111111;
			//hour 	|= 0b01000000; //bit 6 high is 12hour selected, otherwise if it is low 24hour is selected
									//if 12hour is selected then bit 5 has to be set to select PM or AM. PM high and AM low
			minute 	&= 0b01111111;
			second 	&= 0b01111111;
			
			date 	&= 0b00111111;
			month 	&= 0b00011111;
			year 	&= 0b11111111;
			
			//set time on the RTC
			i2c_smbus_write_byte_data(file, reg_hour, hour);
			i2c_smbus_write_byte_data(file, reg_minute, minute);
			i2c_smbus_write_byte_data(file, reg_second, second);
			
			i2c_smbus_write_byte_data(file, reg_date, date);
			i2c_smbus_write_byte_data(file, reg_month, month);
			i2c_smbus_write_byte_data(file, reg_year, year);
			
			close(file);
		}
			
	}
	
	
	return var_return;
}
#endif // ENABLE_READING_I2C_RTC


//get the bytes for the function u. Will write into answerRFPI the reply from the peri 
int get_bytes_u_Peri(int *handleUART, unsigned char *peripheralAddress, unsigned int num_function, unsigned int num_packet_to_get, unsigned char *answerRFPI){

	unsigned char strCmd[30]; strCmd[0]='\0';
	
	int statusReply=0;
	
	unsigned int i=0;
	
	if(num_function<256){ //max number of functions is 255
	
		strcpy(strCmd,"C03"); //cmd to set address of peripheral
		strcat(strCmd,peripheralAddress);
		SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1); //set the address of the peripheral
		
		strcpy(strCmd,"C30RBu"); //cmd to load data
		strCmd[6]=num_function;//giving the ID of the function to get the data
		strCmd[7]=(unsigned char)(num_packet_to_get>>8); //it is the number of the packet to get
		strCmd[8]=(unsigned char)num_packet_to_get; //it is the number of the packet to get
		for(i=9;i<19;i++){
			strCmd[i]='.';
		}
		strCmd[20]='\0';
		
		//########### v1.1 ###########
		SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2,1);
		
		
		//checking the address, the tags and if the id input is equal to the one wanted
		if(answerRFPI[3]==peripheralAddress[0] && answerRFPI[4]==peripheralAddress[1] && answerRFPI[5]==peripheralAddress[2] && answerRFPI[6]==peripheralAddress[3] /*&& answerRFPI[7]=='R'*/ && answerRFPI[8]=='B' && answerRFPI[9]=='u' && answerRFPI[10]==num_function){
			statusReply=1;
		}else{
			statusReply=-1;
		}
		
	}else{
		statusReply=-1;
	}
	
	return statusReply;

}



//send the bytes for the function u. Will write into answerRFPI the reply from the peri 
int send_bytes_f_Peri(int *handleUART, unsigned char *peripheralAddress, unsigned int num_function, unsigned int num_packet_to_write, unsigned char *array10BytesToSend, unsigned char *answerRFPI){

	unsigned char strCmd[30]; strCmd[0]='\0';
	unsigned char strCmdC31[]="C31";
	
	int statusReply=0;
	
	unsigned int i,j;
	i = j = 0;
	
	if(num_function<256){ //max number of functions is 255
	
		#if DEBUG_LEVEL>0
			printf("SENDING CMD SEND_BYTES_F:\n");
		#endif
		
		strcpy(strCmd,"C03"); //cmd to set address of peripheral
		strcat(strCmd,peripheralAddress);
		SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1); //set the address of the peripheral
		
		
		//SENDING DATA
		strcpy(strCmd,"C30RBf"); //cmd to load data
		strCmd[6]=num_function;//giving the ID of the function to get the data
		strCmd[7]=(unsigned char)(num_packet_to_write>>8); //it is the number of the packet to write
		strCmd[8]=(unsigned char)num_packet_to_write; //it is the number of the packet to write
		#if DEBUG_LEVEL>0
			printf(" NUM FUNCTION TO SEND: %d | NUM PACKET TO SEND: %d | ", num_function, num_packet_to_write);
			printf(" BYTES TO SEND: ");
		#endif
		for(i=9,j=0;i<19;i++,j++){
			strCmd[i]=array10BytesToSend[j];
			
			#if DEBUG_LEVEL>0
				printf("%d ",strCmd[i]);
			#endif
			/*if(strCmd[i]>16)
				printf("0x%x ",strCmd[i]);
			else
				printf("0x0%x ",strCmd[i]);*/
		}
		strCmd[20]='\0'; 
		#if DEBUG_LEVEL>0
			printf("\n");
			fflush(stdout); // Prints immediately to screen 
		#endif
		
		//load the data on the Transceiver. This data then will be ready to be sended with the command C31
		loadRadioData(handleUART, strCmd, 19, answerRFPI, CMD_WAIT1);
		
		//printf(" Sending wireless Data with command F: %s\n",strCmd); fflush(stdout); // Prints immediately to screen
		
		for(i=0;i<strlen(strCmdC31);i++){
					serialPutchar(*handleUART, strCmdC31[i]);
					#if DEBUG_LEVEL>0
						printf("%c", strCmdC31[i]);
					#endif
		}
		delay_ms(100);
		//printf("\n REPLY F = %s\n", answerRFPI); fflush(stdout); // Prints immediately to screen 
		
		//GETTING THE DATA TO CHECK IF DATA HAS BEEN SENT CORECTLY
		strcpy(strCmd,"C30RBu"); //cmd to load data
		strCmd[6]=num_function;//giving the ID of the function to get the data
		strCmd[7]=(unsigned char)(num_packet_to_write>>8); //it is the number of the packet to get
		strCmd[8]=(unsigned char)num_packet_to_write; //it is the number of the packet to get
		for(i=9;i<19;i++){
			strCmd[i]='.';
		}
		strCmd[20]='\0';
		
		for(i=0; i<MAX_LEN_BUFFER_ANSWER_RF ;i++){	answerRFPI[i]='\0'; } //empting the buffer
		SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2,1);
		
		i=answerRFPI[11]<<8;
		i|=answerRFPI[12];
		#if DEBUG_LEVEL>0
			printf(" NUM FUNCTION GOT: %d | NUM PACKET GOT: %d | ", answerRFPI[10], i);
			printf(" BYTES GOT: ");
			for(i=0;i<10;i++){
				if(answerRFPI[13+i]>16)
					printf("0x%x ",answerRFPI[13+i]);
				else
					printf("0x0%x ",answerRFPI[13+i]);
			}
			fflush(stdout); // Prints immediately to screen 
		#endif
		
		
		//checking the address, the tags and if the id input is equal to the one wanted
		if(answerRFPI[3]==peripheralAddress[0] && answerRFPI[4]==peripheralAddress[1] && answerRFPI[5]==peripheralAddress[2] && answerRFPI[6]==peripheralAddress[3] /*&& answerRFPI[7]=='R'*/ && answerRFPI[8]=='B' && answerRFPI[9]=='u' 
		&& answerRFPI[10]==num_function
		&& answerRFPI[11]==((unsigned char)(num_packet_to_write>>8))
		&& answerRFPI[12]==((unsigned char)(num_packet_to_write))
		&& answerRFPI[13]==array10BytesToSend[0]	//check byte 0
		&& answerRFPI[14]==array10BytesToSend[1]	//check byte 1
		&& answerRFPI[15]==array10BytesToSend[2]	//check byte 2
		&& answerRFPI[16]==array10BytesToSend[3]	//check byte 3
		&& answerRFPI[17]==array10BytesToSend[4]	//check byte 4
		&& answerRFPI[18]==array10BytesToSend[5]	//check byte 5
		&& answerRFPI[19]==array10BytesToSend[6]	//check byte 6
		&& answerRFPI[20]==array10BytesToSend[7]	//check byte 7
		&& answerRFPI[21]==array10BytesToSend[8]	//check byte 8
		&& answerRFPI[22]==array10BytesToSend[9]	//check byte 9
		){
			statusReply=1;
		}else{
			statusReply=-1;
		}
		
		#if DEBUG_LEVEL>0
			printf("\n STATUS %d \n", statusReply);
		#endif
		//printf("\n STATUS %d REPLY U = %s\n", statusReply,answerRFPI); fflush(stdout); // Prints immediately to screen 
				
	}else{
		statusReply=-1;
	}
	
	return statusReply;

}



//this function make to start the DS1307 when it is new!
#ifdef ENABLE_READING_I2C_RTC
void start_DS1307_if_new(void){

	
	unsigned var_return = -1;
	
	unsigned char res=0;
			
	unsigned char I2CBus = 1; //channel where is connected the RTC
	unsigned char I2CAddress = 0x68; //address of the RTC
			
	__u8 reg_second = 0x01; // Device register to access second
	__u8 reg_minute = 0x02; // Device register to access minute
	__u8 reg_hour = 0x03; 	// Device register to access hour

	__u8 reg_date 		= 	0x04; // Device register to access number of the day
	__u8 reg_month 		= 	0x05; // Device register to access number of the month
	__u8 reg_year 		= 	0x06; // Device register to access number of the year
			
	unsigned char hour, minute, second;
	unsigned char date, month, year;

			
	char namebuf[64]; namebuf[0]='\0';
	int file=0;
	
	
	//the RTC is the DS1307
	var_return = 0;
			
	//I2CBus = 0; 		 //channel where is connected the RTC
	I2CBus = 1; 		 //channel where is connected the RTC
	I2CAddress = 0x68; //address of the RTC

	reg_second = 0x00; // Device register to access second
	reg_minute = 0x01; // Device register to access minute
	reg_hour = 0x02; // Device register to access hour
			
	reg_date = 0x04; // Device register to access number of the day
	reg_month = 0x05; // Device register to access number of the month
	reg_year = 0x06; // Device register to access number of the year

	snprintf(namebuf, sizeof(namebuf), "/dev/i2c-%d", I2CBus);

	if ((file = open(namebuf, O_RDWR)) < 0 && var_return==0){
		#if DEBUG_LEVEL>0
			printf("RTC DS1307 ERROR: Failed to open %s I2C Bus\n", namebuf);
		#endif
		var_return = 1;
	}
			
	if (ioctl(file, I2C_SLAVE, I2CAddress) < 0 && var_return==0){
		#if DEBUG_LEVEL>0
			printf("RTC ERROR: I2C_SLAVE address %s  failed...\n", &I2CAddress);
		#endif
		var_return = 2;
	}
			
	if(var_return==0){
		res = i2c_smbus_read_byte_data(file, reg_hour);
		if (res < 0) {
			#if DEBUG_LEVEL>0
				printf("RTC DS1307 ERROR: hour i2c transaction failed\n");
			#endif
		} else {
			hour = res;
		}
						
		res = i2c_smbus_read_byte_data(file, reg_minute);
		if (res < 0) {
			#if DEBUG_LEVEL>0
				printf("RTC DS1307 ERROR: minute i2c transaction failed\n");
			#endif
		} else {
			minute = res;
		}  
		res = i2c_smbus_read_byte_data(file, reg_second);
		if (res < 0) {
			#if DEBUG_LEVEL>0
				printf("RTC DS1307 ERROR: second i2c transaction failed\n");
			#endif
		} else {
			second = res;
		}
				
				
				
		res = i2c_smbus_read_byte_data(file, reg_date);
		if (res < 0) {
			#if DEBUG_LEVEL>0
				printf("RTC DS1307 ERROR: hour i2c transaction failed\n");
			#endif
		} else {
			date = res;
		}
				
		res = i2c_smbus_read_byte_data(file, reg_month);
		if (res < 0) {
			#if DEBUG_LEVEL>0
				printf("RTC DS1307 ERROR: hour i2c transaction failed\n");
			#endif
		} else {
			month = res;
		}
				
		res = i2c_smbus_read_byte_data(file, reg_year);
		if (res < 0) {
			#if DEBUG_LEVEL>0
				printf("RTC DS1307 ERROR: hour i2c transaction failed\n");
			#endif
		} else {
			year = res;
		}
								

		hour 	&= 0b00111111;
		minute 	&= 0b01111111;
		second 	&= 0b01111111;
				
		date 	&= 0b00111111;
		month 	&= 0b00011111;
		year 	&= 0b11111111;
				
				
		if(second==0 && minute==0 && hour==0 && date==1 && month==1 && year==0 ){
			i2c_smbus_write_byte_data(file, reg_second, 0x00); //here write the 7bit of register 0x00 to start the RTC
			#if DEBUG_LEVEL>0
				printf("RTC DS1307: this chip is new! Now should start the count!\n");
			#endif
		}
			
	}
			
}
#endif // ENABLE_READING_I2C_RTC



//void writeTagAndValueIntoFIFOJson(char *tag, char *value, int handleFIFO){
void writeTagAndValueIntoFIFOJson(char *tag, char *value, FILE *file_pointer){
	char apex_ascii=34; 
	char str_apex[2];// = { "a", '\0' };
	str_apex[0]='\0';
	sprintf(str_apex,"%c",apex_ascii);
			
	//sprintf(data,"%c\n",apex_ascii); write(handleFIFO, data,(strlen(data))); //this print the apex "
			
			
	//here start to write the json
	fprintf(file_pointer, "%s", str_apex);//write(handleFIFO, str_apex,1); 
	fprintf(file_pointer, "%s", tag);//write(handleFIFO, tag,(strlen(tag))); 
	fprintf(file_pointer, "%s", str_apex);//write(handleFIFO, str_apex,1); 
	fprintf(file_pointer, "%s", ":");//write(handleFIFO, ":",1); 
	fprintf(file_pointer, "%s", str_apex);//write(handleFIFO, str_apex,1); 
	fprintf(file_pointer, "%s", value);//write(handleFIFO, value,(strlen(value))); 
	fprintf(file_pointer, "%s", str_apex);//write(handleFIFO, str_apex,1); 

}

//void writeTagIntoFIFOJson(char *tag, int handleFIFO){
void writeTagIntoFIFOJson(char *tag, FILE *file_pointer){
	char apex_ascii=34; 
	char str_apex[2];// = { "a", '\0' };
	str_apex[0]='\0';
	sprintf(str_apex,"%c",apex_ascii);
			
	//sprintf(data,"%c\n",apex_ascii); write(handleFIFO, data,(strlen(data))); //this print the apex "
			
			
	//here start to write the json			
	fprintf(file_pointer, "%s", str_apex);//write(handleFIFO, str_apex,1); 
	fprintf(file_pointer, "%s", tag);//write(handleFIFO, tag,(strlen(tag))); 
	fprintf(file_pointer, "%s", str_apex);//write(handleFIFO, str_apex,1); 
	fprintf(file_pointer, "%s", ":");//write(handleFIFO, ":",1); 
	

			
}
			
//write the FIFO in format Json in order to give all data to the GUI
void writeFifoJsonPeripheralLinked(peripheraldata *rootPeripheralData){

	//pointers used to manage the data of all linked peripheral
	peripheraldata *currentPeripheralData=0;
	
	//pointers used to manage the struct with the name of input
	peripheraldatanameinput *currentPeripheralDataNameInput=0; 
	
	//pointers used to manage the struct with the name of output
	peripheraldatanameoutput *currentPeripheralDataNameOutput=0; 
	
	FILE *handleFIFO=0;//int handleFIFO; 
	//char data[MAX_LEN_LINE_FILE];
	char tag[50]; tag[0]='\0';
	char st_temp1[30]; st_temp1[0]='\0';
	char strNum[10]; strNum[0]='\0';
	int i=0;
	int cont_status_link_input=0;
	int cont_status_link_output=0;
	int cont_input=0;
	int cont_output=0;
	char value[]="KO";
	char mpn[30]; mpn[0]='\0';
	FILE *file_pointer=0;
	
	//create the fifo to give the status of the peripheral to the GUI
	//mkfifo(FIFO_RFPI_PERIPHERAL_JSON, 0666); 
	//handleFIFO = open(FIFO_RFPI_PERIPHERAL_JSON, O_RDWR);  
	file_pointer  = fopen(FIFO_RFPI_PERIPHERAL_JSON,  "w+");
	if(file_pointer == NULL){
		#if DEBUG_LEVEL>0
			printf("Impossible to write into FIFO: %s\n",FIFO_RFPI_PERIPHERAL_JSON); fflush(stdout);
		#endif
	}else{
		handleFIFO=file_pointer;
		
		currentPeripheralData=rootPeripheralData;
			
		i=0;

		fprintf(file_pointer, "%s", "{\n");//write(handleFIFO, "{\n", 2);
		tag[0]='\0'; strcat(tag,"linked_peri_0"); writeTagIntoFIFOJson(tag, handleFIFO); 
		fprintf(file_pointer, "%s", "\n {\n");//write(handleFIFO, "\n {\n", 4);

		while( (currentPeripheralData)>0 && currentPeripheralData!=0 ){ //for LINUX_MINT

				//here start to write the json
				
				//write the first block the data of the peri
				if(i==0){
					//write(handleFIFO, "{\n", 2);
				}else{
					fprintf(file_pointer, "%s", " },\n");//write(handleFIFO, " },\n", 4);
					tag[0]='\0'; strcat(tag,"linked_peri_"); intToStr(i, strNum); strcat(tag,strNum); writeTagIntoFIFOJson(tag, handleFIFO); 
					fprintf(file_pointer, "%s", "\n {\n");//write(handleFIFO, "\n {\n", 4);
				}
				
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"name");writeTagAndValueIntoFIFOJson(tag, currentPeripheralData->Name, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"id_peri"); intToStr(currentPeripheralData->IDtype, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"id_position"); intToStr(i, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"hex_address");writeTagAndValueIntoFIFOJson(tag, currentPeripheralData->PeriAddress, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"num_input"); intToStr(currentPeripheralData->NumInput, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"num_output"); intToStr(currentPeripheralData->NumOutput, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"num_embedded_functions"); intToStr(currentPeripheralData->numSpecialFunction, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"fw_version"); intToStr(currentPeripheralData->fwVersion, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"strength_link"); intToStr(currentPeripheralData->strengthLink, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);
				fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);

				//write second block the inputs
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1); 
				strcpy(tag,"inputs"); writeTagIntoFIFOJson(tag, handleFIFO);
				fprintf(file_pointer, "%s", "\n");//write(handleFIFO, "\n", 1);
				
				tag[0]='\0';
				//intToStr(currentPeripheralData->NumInput, strNum); strcat(tag,strNum); strcat(tag," ");
				currentPeripheralDataNameInput=currentPeripheralData->rootNameInput;
				cont_input=0;
				cont_status_link_input = 0;
				fprintf(file_pointer, "%s", "   {\n");//write(handleFIFO, "   {\n", 5);
				if(currentPeripheralData->NumInput>0){
					fprintf(file_pointer, "%s", "   ");//write(handleFIFO, "   ", 3); 
					strcpy(tag,"in_0"); writeTagIntoFIFOJson(tag, handleFIFO); 
					fprintf(file_pointer, "%s", "\n     {\n");//write(handleFIFO, "\n     {\n", 8);
					while(currentPeripheralDataNameInput!=0){

						if(cont_input==0){
							//write(handleFIFO, "{\n   ", 5);
						}else{
							fprintf(file_pointer, "%s", "     },\n");//write(handleFIFO, "     },\n", 8);
							fprintf(file_pointer, "%s", "   ");//write(handleFIFO, "   ", 3); 
							strcpy(tag,"in_"); intToStr(cont_input, strNum); strcat(tag,strNum); writeTagIntoFIFOJson(tag, handleFIFO); 
							fprintf(file_pointer, "%s", "\n     {\n");//write(handleFIFO, "\n     {\n", 8);
						}
					
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"name"); writeTagAndValueIntoFIFOJson(tag, currentPeripheralDataNameInput->NameInput, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"id"); intToStr(cont_input, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
					
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"type"); writeTagAndValueIntoFIFOJson(tag, currentPeripheralDataNameInput->Type, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"raw_value"); intToStr(currentPeripheralDataNameInput->StatusInput, strNum);  writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
					
					
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"bit_resolution"); intToStr(currentPeripheralDataNameInput->BitResolution, strNum);  writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						
						//char value[]="KO";
						if(currentPeripheralDataNameInput->StatusCommunication != -1) strcpy(value ,"OK");
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"status_communication"); writeTagAndValueIntoFIFOJson(tag, value, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						
						//char mpn[30];//="MCP9701A";
						return_mpn(mpn, &currentPeripheralDataNameInput->id_shield_connected);
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"mpn_shield_connected"); writeTagAndValueIntoFIFOJson(tag, mpn, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5);
						strcpy(tag,"id_shield_connected"); intToStr(currentPeripheralDataNameInput->id_shield_connected, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);  
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"num_pin"); intToStr(currentPeripheralDataNameInput->num_pin_used_on_the_peri, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO); 
						
						fprintf(file_pointer, "%s", "\n");//write(handleFIFO, "\n", 1);
					
					
						if(currentPeripheralDataNameInput->StatusInput == -1)
							cont_status_link_input++;
						
						
						currentPeripheralDataNameInput=currentPeripheralDataNameInput->next;
						cont_input++;
					}
					fprintf(file_pointer, "%s", "     }\n");//write(handleFIFO, "     }\n", 7);
				}
				fprintf(file_pointer, "%s", "   },\n");//write(handleFIFO, "   },\n", 6);
				//write(handleFIFO, " ,\n", 4);
				
				
				//write third block with the outputs
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1);
				strcpy(tag,"outputs"); writeTagIntoFIFOJson(tag, handleFIFO); 
				fprintf(file_pointer, "%s", "\n");//write(handleFIFO, "\n", 1);
				
				tag[0]='\0';
				//intToStr(currentPeripheralData->NumOutput, strNum); strcat(tag,strNum); strcat(tag," ");
				currentPeripheralDataNameOutput=currentPeripheralData->rootNameOutput;
				cont_output=0;
				cont_status_link_output = 0;
				fprintf(file_pointer, "%s", "   {\n");//write(handleFIFO, "   {\n", 5);
				if(currentPeripheralData->NumOutput>0){
					fprintf(file_pointer, "%s", "   ");//write(handleFIFO, "   ", 3); 
					strcpy(tag,"out_0"); writeTagIntoFIFOJson(tag, handleFIFO); 
					fprintf(file_pointer, "%s", "\n     {\n");//write(handleFIFO, "\n     {\n", 8);
					while(currentPeripheralDataNameOutput!=0 && currentPeripheralData->NumOutput!=0){
						
						if(cont_output==0){
							//write(handleFIFO, "{\n   ", 5);
						}else{
							fprintf(file_pointer, "%s", "     },\n");//write(handleFIFO, "     },\n", 8);
							fprintf(file_pointer, "%s", "   ");//write(handleFIFO, "   ", 3); 
							strcpy(tag,"out_"); intToStr(cont_output, strNum); strcat(tag,strNum); writeTagIntoFIFOJson(tag, handleFIFO); 
							fprintf(file_pointer, "%s", "\n     {\n");//write(handleFIFO, "\n     {\n", 8);
						}
					
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"name"); writeTagAndValueIntoFIFOJson(tag, currentPeripheralDataNameOutput->NameOutput, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"id"); intToStr(cont_output, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
					
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"type"); writeTagAndValueIntoFIFOJson(tag, currentPeripheralDataNameOutput->Type, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"raw_value"); intToStr(currentPeripheralDataNameOutput->StatusOutput, strNum);  writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
					
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"bit_resolution"); intToStr(currentPeripheralDataNameOutput->BitResolution, strNum);  writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						
						char value[]="KO";
						if(currentPeripheralDataNameOutput->StatusCommunication != -1) strcpy(value ,"OK");
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"status_communication"); writeTagAndValueIntoFIFOJson(tag, value, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						
						//char mpn[30];//="LED";
						return_mpn(mpn, &currentPeripheralDataNameOutput->id_shield_connected);
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"mpn_shield_connected"); writeTagAndValueIntoFIFOJson(tag, mpn, handleFIFO); 
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"id_shield_connected"); intToStr(currentPeripheralDataNameOutput->id_shield_connected, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO);  
						fprintf(file_pointer, "%s", ",\n");//write(handleFIFO, ",\n", 2);
						fprintf(file_pointer, "%s", "     ");//write(handleFIFO, "     ", 5); 
						strcpy(tag,"num_pin"); intToStr(currentPeripheralDataNameOutput->num_pin_used_on_the_peri, strNum); writeTagAndValueIntoFIFOJson(tag, strNum, handleFIFO); 
						
						
						fprintf(file_pointer, "%s", "\n");//write(handleFIFO, "\n", 1);
						
										
						if(currentPeripheralDataNameOutput->StatusOutput == -1)
							cont_status_link_output++;
						
						
						currentPeripheralDataNameOutput=currentPeripheralDataNameOutput->next;
						cont_output++;
					}
					fprintf(file_pointer, "%s", "     }\n");//write(handleFIFO, "     }\n", 7);
				}
				fprintf(file_pointer, "%s", "   },\n");//write(handleFIFO, "   },\n", 6);
				//write(handleFIFO, " ],\n", 4);
				
				
				st_temp1[0]='\0';
				if(cont_status_link_input==cont_input && cont_status_link_output==cont_output){
					strcat(st_temp1,"offline");
				}else if(cont_status_link_input>0 || cont_status_link_output>0){
					strcat(st_temp1,"weakness");
				}else{
					strcat(st_temp1,"online");
				}
				
				
				fprintf(file_pointer, "%s", " ");//write(handleFIFO, " ", 1);
				strcpy(tag,"status_link"); writeTagAndValueIntoFIFOJson(tag, st_temp1, handleFIFO);
				fprintf(file_pointer, "%s", "\n");//write(handleFIFO, "\n", 1);
				

				
				i++;
				currentPeripheralData=currentPeripheralData->next;
		}
		fprintf(file_pointer, "%s", " }\n");//write(handleFIFO, " }\n", 3);
		
		//write(handleFIFO, "\n", 2); //close the tag linked_peri
		fprintf(file_pointer, "%s", "}\n");//write(handleFIFO, "}\n", 2);
		
	//close(handleFIFO); 
	}
	fclose(file_pointer);

}




//this function test all serial port under the path given by path_search
char* return_serial_port_path(char *path_search, char *serial_port_path, int *handleUART){

	char varTmpReturn[50]="null";

	sem_serial_port_USB = 0;
	
	#ifdef ENABLE_SEARCH_SERIAL_PORT_PATH
	char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF]; answerRFPI[0]='\0';
	unsigned int i,j,cont1;
	i=j=cont1=0;
	int numCharacters=0;
	unsigned int baud=9600;
	unsigned int varTmpExit=0;
	
	char port_path[12][10]={"ttyAMA0", "ttyS0", "ttyS1", "serial0", "ttyUSB0", "ttyUSB1", "ttyUSB2", "ttyUSB3", "ttyO0", "ttyO1", "ttyO2", "ttyO3"};
	char temp_path_serial_port[50]; temp_path_serial_port[0]='\0';

	
	
	#if DEBUG_LEVEL>0
		printf(" Searching the serial port path...\n");
	#endif
		
	for(j=0; j<12 && varTmpExit==0; j++){
		strcpy(temp_path_serial_port, path_search);
		strcat(temp_path_serial_port, "/");
		strcat(temp_path_serial_port, port_path[j]);
			
		baud=9600;

		#if DEBUG_LEVEL>0
			printf("\n\nTesting serial path = %s ", temp_path_serial_port); fflush(stdout); // Prints immediately to screen 
		#endif
		
		*handleUART = serialOpen (temp_path_serial_port, baud) ;
		if(*handleUART == -1)	{ //impossible to open the path
			serialClose (*handleUART) ; //closing the serial
			#if DEBUG_LEVEL>0
				printf("-> Impossible to open this path!\n"); fflush(stdout); // Prints immediately to screen 
			#endif
		}else{
			#if DEBUG_LEVEL>0
				printf("-> path opened! Going to test if the transceiver reply......\n"); fflush(stdout); // Prints immediately to screen 
			#endif
			serialClose (*handleUART) ; //closing the serial
			#if DEBUG_LEVEL>0
				printf(" Testing all baud rate of the serial port...\n");
			#endif
			
			cont1=0;
			do{
				*handleUART = serialOpen (temp_path_serial_port, baud) ;

				//serialPrintf(*handleUART, "C85" ) ; //sending the command to the Radio		
				serialPrintf(*handleUART, "C54" ) ; //sending the command to the Radio	
				//delay_ms(CMD_WAIT1);
				
				
				delay_ms(550);
				
				serialPrintf(*handleUART, "C54" ) ; //sending the command to the Radio	
				//serialPrintf(*handleUART, "C85" ) ; //sending the command to the Radio	
				
				delay_ms(220);
				//delay_ms(CMD_WAIT1);
				
				numCharacters=serialDataAvail (*handleUART) ;
				if(numCharacters>0){ 
					#if DEBUG_LEVEL>0
						if(numCharacters>=MAX_LEN_BUFFER_ANSWER_RF) printf("\n Too high quantity of data: %d \n", numCharacters);
						printf("\n Reply from serial port: ");
					#endif
					for(i=0;i<numCharacters && i<MAX_LEN_BUFFER_ANSWER_RF;i++){ 
						answerRFPI[i] =serialGetchar(*handleUART) ;
						#if DEBUG_LEVEL>0
							printf("%c",answerRFPI[i]);
						#endif
					}
					if( (answerRFPI[0]=='I' && answerRFPI[1]=='O' && answerRFPI[2]=='T') 
						||  (answerRFPI[0]=='G' && answerRFPI[1]=='3' && answerRFPI[2]=='P')
						||  (answerRFPI[0]=='*' && answerRFPI[1]=='I' && answerRFPI[2]=='O' && answerRFPI[3]=='T')
						||  (answerRFPI[0]=='I' && answerRFPI[1]=='O' && answerRFPI[2]=='T')
						) //this work for command C54
						varTmpExit=1;
						

					numCharacters = 0;
				}
				
				serialClose (*handleUART) ; //closing the serial port
				
				if(varTmpExit==0){
					if(baud==38400)
						baud=57600;
					else
						baud=baud*2;
				}
					cont1++;
			}while(baud<=115200 && varTmpExit==0);

			if(baud<=115200){
				#if DEBUG_LEVEL>0
					printf("\nTRANSCEIVER FOUND!! Baud rate used: %d\n", baud);fflush(stdout); // Prints immediately to screen 
				#endif

				strcpy(varTmpReturn, temp_path_serial_port); //to return the serial port path
				
				if(j>3 && j<8){//4 to 7 are the index of the USB serial port path
					sem_serial_port_USB = 1;
				}else{
					sem_serial_port_USB = 0;
				}
			
			}else{
				#if DEBUG_LEVEL>0
					printf("\nI DID NOT FIND THE TRANSCEIVER!\n");fflush(stdout); // Prints immediately to screen 
				#endif
			}

		}
	
	}
	
	#endif
	
	serial_port_path = (char*) malloc( strlen(varTmpReturn) );
	strcpy(serial_port_path,varTmpReturn);
	return serial_port_path;
}
