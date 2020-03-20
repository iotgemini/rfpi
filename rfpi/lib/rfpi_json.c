/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					20/03/2020


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

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include "jsmn.c"



//this function build and send command to the transceiver, this the command:
//	R	B	s	EEPROM_POS	H_ID_shield	L_ID_shield	PIN_used	PIN_mask	PULL-UP_resistor	ID_function0	ID_function1	ID_function2	ID_function3	ID_function4	ID_function5	ID_function6
peripheraldata *send_to_transceiver_json_settings(peripheraldata *rootDataPeripheral,/*struct_json_settings *rootJsonSettings, */char *address_peri, char *json_path, int *handleUART){
	
	char strCmd[40]; //used to give command to the Transceiver
	char answerRFPI[MAX_LEN_BUFFER_ANSWER_RF];
	struct_shields_connected *tempStructShieldConn;
	int cont=0;
	struct_json_settings *rootJsonSettings=0;
	char strPathFile[MAX_LEN_PATH]; //used to keep the path of the file
	char strPathFile2[MAX_LEN_PATH]; //used to keep the path of the file
	unsigned char semAllOK = 1;
	
	rootJsonSettings=(struct_json_settings*)malloc(sizeof(struct_json_settings));
	rootJsonSettings->next=0;
	rootJsonSettings->cont=0;
	rootJsonSettings->semaphores=0;
	rootJsonSettings->rootShieldsConnected = 0;
	
	printf("SENDJSONSETTINGS | address_peri = %s | json_path = %s \n", address_peri, json_path );
	
	printf("\n********************************** JSON PARSED START ****************************************\n");
	parseJSON(rootJsonSettings, json_path, fill_up_struct_json_settings);
	printf("\n********************************** JSON PARSED END ****************************************\n");
	
	if(rootJsonSettings->rootShieldsConnected == 0){ //struct to allocate
		//it is empty, the JSON was bad
		semAllOK = 0;
	}else{
		rootJsonSettings->currentShieldsConnected = rootJsonSettings->rootShieldsConnected;
		
		strcpy(strCmd,"C03"); //cmd to set address of peripheral
		strcat(strCmd,address_peri);
		SerialCmdRFPi(handleUART, strCmd, answerRFPI, CMD_WAIT1);
			
		cont=0;
		while(rootJsonSettings->currentShieldsConnected != 0){ //build command and send until all data is sent
			//C30 is a command to load data to send 
			strcpy(strCmd,"C30RBsEHLUMR0123456"); //	R	B	s	EEPROM_POS	H_ID_shield	L_ID_shield		PIN_used	PIN_mask	PULL-UP_resistor	ID_function0	ID_function1	ID_function2	ID_function3	ID_function4	ID_function5	ID_function6
			
			strCmd[6] = cont; //EEPROM_POS: position into the Transceiver EEPROM where to save the data
			strCmd[7] = (unsigned char)(rootJsonSettings->currentShieldsConnected->ID >> 8); //H_ID_shield
			strCmd[8] = (unsigned char)(rootJsonSettings->currentShieldsConnected->ID); //L_ID_shield
			strCmd[9] = (unsigned char)(rootJsonSettings->currentShieldsConnected->PINUSED); //PIN_used
			strCmd[10] = (unsigned char)(rootJsonSettings->currentShieldsConnected->PINMASK); //PIN_mask
			strCmd[11] = (unsigned char)(rootJsonSettings->currentShieldsConnected->PULLUPRESISTOR); //PULL-UP_resistor
			strCmd[12] = (unsigned char)(rootJsonSettings->currentShieldsConnected->IDFUNCTION0); //ID_function0
			strCmd[13] = (unsigned char)(rootJsonSettings->currentShieldsConnected->IDFUNCTION1); //ID_function1
			strCmd[14] = (unsigned char)(rootJsonSettings->currentShieldsConnected->IDFUNCTION2); //ID_function2
			strCmd[15] = (unsigned char)(rootJsonSettings->currentShieldsConnected->IDFUNCTION3); //ID_function3
			strCmd[16] = (unsigned char)(rootJsonSettings->currentShieldsConnected->IDFUNCTION4); //ID_function4
			strCmd[17] = (unsigned char)(rootJsonSettings->currentShieldsConnected->IDFUNCTION5); //ID_function5
			strCmd[18] = (unsigned char)(rootJsonSettings->currentShieldsConnected->IDFUNCTION6); //ID_function6
			
			/*printf("\n\n    ID SHIELD = %d\n", (unsigned int)rootJsonSettings->currentShieldsConnected->ID);
			printf("        PIN USED = %d\n", (unsigned int)strCmd[9]);
			printf("        PIN MASK = %d\n", (unsigned int)strCmd[10]);
			printf("        PULL UP RESISTOR = %d\n", (unsigned int)strCmd[11]);
			printf("\n\n");
			*/
			
			//########### v1.1 ###########
			SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2, 0);
					
			printf(" status= %s\n",answerRFPI);
			if(answerRFPI[0]=='O' && answerRFPI[1]=='K'){
				strcpy(statusRFPI,"OK"); 
				printf("Status=OK\n");
			}else{
				strcpy(statusRFPI,"NOTX"); 
				printf("Status=NOTX\n");
				semAllOK = 0;
			} 
			
			delay_ms(50); //

			//reset the counter to give the time to be read the status by the GUI, after the status return to OK
			contStatusMsg=0;
				
			rootJsonSettings->currentShieldsConnected = rootJsonSettings->currentShieldsConnected->next; //next shield to load with command s
			cont++;
		}
		
		
		//C30 is a command to load data to send 
		strcpy(strCmd,"C30RBsEHLUMR0123456"); //	R	B	s	EEPROM_POS	H_ID_shield	L_ID_shield		PIN_used	PIN_mask	PULL-UP_resistor	ID_function0	ID_function1	ID_function2	ID_function3	ID_function4	ID_function5	ID_function6
			
		strCmd[6] = cont; //EEPROM_POS: position into the Transceiver EEPROM where to save the data
		strCmd[7] = 0; //H_ID_shield
		strCmd[8] = 0; //L_ID_shield
		strCmd[9] = 0; //PIN_used
		strCmd[10] = 0; //PIN_mask
		strCmd[11] = 0; //PULL-UP_resistor
		strCmd[12] = 0; //ID_function0
		strCmd[13] = 0; //ID_function1
		strCmd[14] = 0; //ID_function2
		strCmd[15] = 0; //ID_function3
		strCmd[16] = 0; //ID_function4
		strCmd[17] = 0; //ID_function5
		strCmd[18] = 0; //ID_function6
			
		while(cont < 8){ //it fill up the other position with null values

			strCmd[6] = cont; //EEPROM_POS: position into the Transceiver EEPROM where to save the data

			SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2, 0);
					
			printf(" status= %s\n",answerRFPI);
			if(answerRFPI[0]=='O' && answerRFPI[1]=='K'){
				strcpy(statusRFPI,"OK"); 
				printf("Status=OK\n");
			}else{
				strcpy(statusRFPI,"NOTX"); 
				printf("Status=NOTX\n");
				semAllOK = 0;
			} 
			
			delay_ms(50); //

			//reset the counter to give the time to be read the status by the GUI, after the status return to OK
			contStatusMsg=0;
				
			cont++;
		}
		
		
		//sending command to make the transceiver to reboot

		//C30 is a command to load data to send 
		strcpy(strCmd,"C30RBb0............");
		strCmd[6] = 0; //at 0 enable the reboot and make the transceiver to tune on the programming network
		
		//########### v1.1 ###########
		SendRadioDataAndGetReplyFromPeri(handleUART, strCmd, 19, answerRFPI, CMD_WAIT2, 0);
					
		printf(" status= %s\n",answerRFPI);
		if(answerRFPI[0]=='O' && answerRFPI[1]=='K'){
				strcpy(statusRFPI,"OK"); 
				printf("Status=OK\n");
		}else{
				strcpy(statusRFPI,"NOTX"); 
				printf("Status=NOTX\n");
				semAllOK = 0;
		} 
		//reset the counter to give the time to be read the status by the GUI, after the status return to OK
		contStatusMsg=0; 	
			
		
		//making free all memory
		rootJsonSettings->currentShieldsConnected = rootJsonSettings->rootShieldsConnected->next;
		free(rootJsonSettings->rootShieldsConnected);
		while(rootJsonSettings->currentShieldsConnected != 0){ //build command and send until all data is sent
			tempStructShieldConn = rootJsonSettings->currentShieldsConnected;
			rootJsonSettings->currentShieldsConnected = rootJsonSettings->currentShieldsConnected->next;
			free(tempStructShieldConn->NAME_SHIELD);
			free(tempStructShieldConn);
		}
		
		free(rootJsonSettings->ADDRESS);
		free(rootJsonSettings->NAME);
		free(rootJsonSettings->LINK_TYPE);
		//free(rootJsonSettings->NameFileJson);
		
		free(rootJsonSettings);
		
	}
	
	if(semAllOK == 1){
		//renaming old file list peripheral in backup
		
		
		//delete the peri that does not have any IO and reinstall it with the new configuration
		rootDataPeripheral=deletePeripheralByAddress(address_peri, rootDataPeripheral);
		
		delay_ms(1000); //gieves the time to the transceiver to reset itself and be ready for the new installation
		
		//reinstall it with the new configuration
		rootDataPeripheral=findNewPeripheral(handleUART, statusRFPI, rootDataPeripheral);
		
		/*free(rootDataPeripheral);
		rootDataPeripheral=0;
		//load in memory the struct data of all linked peripheral 
		rootDataPeripheral=loadLinkedPeripheral(handleUART);
			
		if(rootDataPeripheral==0){
				printf("WARNING: No linked peripherals! Or missing files. \n");
		}*/
			
			
		//unlink all FIFO, thus the GUI will wait for the data updated
				//unlink(FIFO_RFPI_RUN);
				//unlink(FIFO_GUI_CMD);
				//unlink(FIFO_RFPI_STATUS);
		
				//ask to All peripherals the status of all inputs and outputs and update the status into the struct data
				//askAndUpdateAllIOStatusPeri(handleUART, rootDataPeripheral);
				
		//asks to the peripheral the status of all inputs and outputs and update the status into the struct data
		//char tag_all[] = "ALL";
		askAndUpdateIOStatusPeri(handleUART, address_peri, rootDataPeripheral);
		
		
		//init the Transceiver module
		/*initRFberryNetwork(handleUART);
	
		//convert the network name in an address for the Transceiver module
		addressFromName(networkName, networkAddress);
		
		//just to see the output
		printf("NETWORK NAME: %s\n", networkName);
		printf("NETWORK ADDRESS: %s\n", networkAddress); 
		
		//ask to All peripherals the status of all inputs and outputs and update the status into the struct data
		askAndUpdateAllIOStatusPeri(handleUART, rootDataPeripheral);
				
		printPeripheralStructData(rootDataPeripheral);
		*/
		
		
		
		//rename (const char *oldname, const char *newname);
	/*	char path_json_conf[50];
		strcpy(path_json_conf,PATH_CONFIG_FILE); //path where all configurations are saved
		strcat(path_json_conf,address_peri);
		strcat(path_json_conf,".json");
		//rename(json_path, path_json_conf);
		*/
		
		
		//moving the json under the config path
		//strcpy(strPathFile,PATH_CONFIG_FILE);
		//strcat(strPathFile,"/new.json");
		strcpy(strPathFile,json_path);
		
		strcpy(strPathFile2,PATH_CONFIG_FILE);
		strcat(strPathFile2,address_peri);
		strcat(strPathFile2,".json");
		
		printf("COPY %s TO %s\n", strPathFile, strPathFile2); fflush(stdout); // Prints immediately to screen 
		
		unsigned char status= rename(strPathFile, strPathFile2); //( oldname , newname );
		if ( status == 0 ){
			puts ( "File JSON successfully copyed!!" );
		}else{
			printf("ERROR: Unable to copy the file %s\n", strPathFile);
			perror( "Error copying the file JSON" );
			semAllOK = 0;
		}
		fflush(stdout); // Prints immediately to screen 
		
	
		/*
		char buf[1000];
		size_t size;

		FILE* source = fopen(json_path, "rb");
		FILE* dest = fopen(path_json_conf, "wb");

		// clean and more secure
		// feof(FILE* stream) returns non-zero if the end of file indicator for stream is set

		while (size = fread(buf, 1, BUFSIZ, source)) {
			fwrite(buf, 1, size, dest);
		}

		fclose(source);
		fclose(dest);
		*/
		
		//reset the counter to give the time to be read the status by the GUI, after the status return to OK
		contStatusMsg=0; 
	}
	
	return rootDataPeripheral;
}


//this function allocate the memory and record all data from json file
void fill_up_struct_json_settings(struct_json_settings *rootJsonSettings, char *json_key, char* json_value){
	int l;
	
	struct_shields_connected *tempStructShieldConn;

	if(strcmp(json_key,"MODULE")==0 && rootJsonSettings->cont == 0){
		rootJsonSettings->cont = 1;
	}else if(rootJsonSettings->cont == 1 && 
			(
			strcmp(json_key,"SHIELD_0")==0
			|| strcmp(json_key,"SHIELD_1")==0 
			|| strcmp(json_key,"SHIELD_2")==0 
			|| strcmp(json_key,"SHIELD_3")==0 
			|| strcmp(json_key,"SHIELD_4")==0 
			|| strcmp(json_key,"SHIELD_5")==0 
			|| strcmp(json_key,"SHIELD_6")==0 
			|| strcmp(json_key,"SHIELD_7")==0	
			)){
				
		rootJsonSettings->cont = 2;
		
		if(rootJsonSettings->rootShieldsConnected == 0){ //struct to allocate
			rootJsonSettings->rootShieldsConnected = (struct_shields_connected*)malloc(sizeof(struct_shields_connected));
		}
		rootJsonSettings->currentShieldsConnected = rootJsonSettings->rootShieldsConnected;
		rootJsonSettings->currentShieldsConnected->next=0;
		rootJsonSettings->currentShieldsConnected->NAME_SHIELD=0;
		rootJsonSettings->currentShieldsConnected->PINUSED=0;
		rootJsonSettings->currentShieldsConnected->PINMASK=0;
		rootJsonSettings->currentShieldsConnected->PULLUPRESISTOR=0;
		rootJsonSettings->currentShieldsConnected->IDFUNCTION0=0;
		rootJsonSettings->currentShieldsConnected->IDFUNCTION1=0;
		rootJsonSettings->currentShieldsConnected->IDFUNCTION2=0;
		rootJsonSettings->currentShieldsConnected->IDFUNCTION3=0;
		rootJsonSettings->currentShieldsConnected->IDFUNCTION4=0;
		rootJsonSettings->currentShieldsConnected->IDFUNCTION5=0;
		rootJsonSettings->currentShieldsConnected->IDFUNCTION6=0;
		rootJsonSettings->currentShieldsConnected->semaphores=0;
		
		printf("  SHIELD_%d:\n", rootJsonSettings->cont-2); fflush(stdout); // Prints immediately to screen 
		
	}else if(	(rootJsonSettings->cont >= 2 && rootJsonSettings->cont < 11) &&
				  (strcmp(json_key,"SHIELD_1")==0 
				|| strcmp(json_key,"SHIELD_2")==0 
				|| strcmp(json_key,"SHIELD_3")==0 
				|| strcmp(json_key,"SHIELD_4")==0 
				|| strcmp(json_key,"SHIELD_5")==0 
				|| strcmp(json_key,"SHIELD_6")==0 
				|| strcmp(json_key,"SHIELD_7")==0)
				
				){
					
		rootJsonSettings->cont++;

		tempStructShieldConn = (struct_shields_connected*)malloc(sizeof(struct_shields_connected));
		tempStructShieldConn->next=0;
		tempStructShieldConn->NAME_SHIELD=0;
		tempStructShieldConn->PINUSED=0;
		tempStructShieldConn->PINMASK=0;
		tempStructShieldConn->PULLUPRESISTOR=0;
		tempStructShieldConn->IDFUNCTION0=0;
		tempStructShieldConn->IDFUNCTION1=0;
		tempStructShieldConn->IDFUNCTION2=0;
		tempStructShieldConn->IDFUNCTION3=0;
		tempStructShieldConn->IDFUNCTION4=0;
		tempStructShieldConn->IDFUNCTION5=0;
		tempStructShieldConn->IDFUNCTION6=0;
		tempStructShieldConn->semaphores=0;
		
		rootJsonSettings->currentShieldsConnected->next=tempStructShieldConn;
		
		rootJsonSettings->currentShieldsConnected = tempStructShieldConn;
		
		//free(tempStructShieldConn);
		
		printf("\n  SHIELD_%d:\n", rootJsonSettings->cont-2); fflush(stdout); // Prints immediately to screen 
		
	}else if(strcmp(json_key,"SHIELD_8")==0){
		rootJsonSettings->cont = 11;
	}
	
	if(rootJsonSettings->cont == 1){
		if(strcmp(json_key,"ADDRESS")==0 && (rootJsonSettings->semaphores & 0x0001)==0){
			rootJsonSettings->semaphores |= 0x0001;
			rootJsonSettings->ADDRESS=(char*)malloc((strlen(json_value)+1)*sizeof(char));
			strcpy(rootJsonSettings->ADDRESS,json_value);
			printf("ADDRESS=%s\n",rootJsonSettings->ADDRESS); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"NAME")==0 && (rootJsonSettings->semaphores & 0x0002)==0){
			rootJsonSettings->semaphores |= 0x0002;
			rootJsonSettings->NAME=(char*)malloc((strlen(json_value)+1)*sizeof(char));
			strcpy(rootJsonSettings->NAME,json_value);
			printf("NAME=%s\n",rootJsonSettings->NAME); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"FW_VERSION")==0 && (rootJsonSettings->semaphores & 0x0004)==0){
			rootJsonSettings->semaphores |= 0x0004;
			rootJsonSettings->FW_VERSION = atoi(json_value);
			printf("FW_VERSION=%d\n",rootJsonSettings->FW_VERSION);fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"LINK_TYPE")==0 && (rootJsonSettings->semaphores & 0x0008)==0){
			rootJsonSettings->semaphores |= 0x0008;
			rootJsonSettings->LINK_TYPE=(char*)malloc((strlen(json_value)+1)*sizeof(char));
			strcpy(rootJsonSettings->LINK_TYPE,json_value);
			printf("LINK_TYPE=%s\n",rootJsonSettings->LINK_TYPE);fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"NUM_CHANNEL")==0 && (rootJsonSettings->semaphores & 0x0010)==0){
			rootJsonSettings->semaphores |= 0x0010;
			rootJsonSettings->NUM_CHANNEL = atoi(json_value);
			printf("NUM_CHANNEL=%d\n",rootJsonSettings->NUM_CHANNEL);fflush(stdout); // Prints immediately to screen 
		}
		
	}else if(rootJsonSettings->cont >= 2 && rootJsonSettings->cont <= 10){
		
		if(strcmp(json_key,"NAME")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0001)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0001;
			//if(rootJsonSettings->currentShieldsConnected->NAME_SHIELD==0)
				//rootJsonSettings->currentShieldsConnected->NAME_SHIELD=(char*)malloc((strlen(json_value)+1)*sizeof(char));
			//else
			//	rootJsonSettings->currentShieldsConnected->NAME_SHIELD=(char*)realloc(rootJsonSettings->currentShieldsConnected->NAME_SHIELD, (strlen(json_value)+1)*sizeof(char));
			rootJsonSettings->currentShieldsConnected->NAME_SHIELD=(char*)malloc((strlen(json_value)+1)*sizeof(char));
			if (rootJsonSettings->currentShieldsConnected->NAME_SHIELD == 0){
				printf("ERROR: Out of memory\n");
			}else{
				strcpy(rootJsonSettings->currentShieldsConnected->NAME_SHIELD,json_value);
				printf("      NAME=%s\n", rootJsonSettings->currentShieldsConnected->NAME_SHIELD); fflush(stdout); // Prints immediately to screen 
			}
		}else if(strcmp(json_key,"ID")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0002)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0002;
			rootJsonSettings->currentShieldsConnected->ID = atoi(json_value);
			printf("      ID=%d\n", rootJsonSettings->currentShieldsConnected->ID); fflush(stdout); // Prints immediately to screen 
		//}else if(strcmp(json_key,"PIN")==0 /*&& (rootJsonSettings->currentShieldsConnected->semaphores & 0x0004)==0*/){
		//}else if( json_key[0]=='P' && json_key[1]=='I' && json_key[2]=='N' && json_key[3]=='_'){
		}else if(  strcmp(json_key,"PIN_0")==0 
				|| strcmp(json_key,"PIN_1")==0 
				|| strcmp(json_key,"PIN_2")==0 
				|| strcmp(json_key,"PIN_3")==0 
				|| strcmp(json_key,"PIN_4")==0 
				|| strcmp(json_key,"PIN_5")==0 
				|| strcmp(json_key,"PIN_6")==0
				|| strcmp(json_key,"PIN_7")==0
				){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0004;
			unsigned char numPin = (atoi(json_value));
			if(numPin < 3 || numPin > 10){
				rootJsonSettings->tempLastPin = 0; //this means the input value is wrong! thus it not assigs any PIN!
			}else{
				rootJsonSettings->tempLastPin = 1;
				for(l=3;l<numPin;l++){
					rootJsonSettings->tempLastPin = rootJsonSettings->tempLastPin * 2;
				}
			}
			rootJsonSettings->currentShieldsConnected->PINUSED |= rootJsonSettings->tempLastPin;
			printf("      PIN%s=", json_value); fflush(stdout); // Prints immediately to screen 
		//}else if(strcmp(json_key,"MASK")==0 /*&& (rootJsonSettings->currentShieldsConnected->semaphores & 0x0008)==0*/){
		//}else if( json_key[0]=='M' && json_key[1]=='A' && json_key[2]=='S' && json_key[3]=='K' && json_key[4]=='_' ){
		}else if(  strcmp(json_key,"MASK_0")==0 
				|| strcmp(json_key,"MASK_1")==0 
				|| strcmp(json_key,"MASK_2")==0 
				|| strcmp(json_key,"MASK_3")==0 
				|| strcmp(json_key,"MASK_4")==0 
				|| strcmp(json_key,"MASK_5")==0 
				|| strcmp(json_key,"MASK_6")==0
				|| strcmp(json_key,"MASK_7")==0
				){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0008;
			if(strcmp(json_value,"out")==0){
				rootJsonSettings->currentShieldsConnected->PINMASK |= rootJsonSettings->tempLastPin;
			}
			printf("%s", json_value); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"PULL_UP_RESISTOR")==0 /*&& (rootJsonSettings->currentShieldsConnected->semaphores & 0x0010)==0*/){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0010;
			if(strcmp(json_value,"yes")==0){
				rootJsonSettings->currentShieldsConnected->PULLUPRESISTOR |= rootJsonSettings->tempLastPin;
				printf(", Pull-Up=%s", json_value); fflush(stdout); // Prints immediately to screen 
			}
		}else if(strcmp(json_key,"ID_FUNCTION_0")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0020)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0020;
			rootJsonSettings->currentShieldsConnected->IDFUNCTION0 |= atoi(json_value); //
			printf("\n      ID_FUNCTION_0=%s", json_value); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"ID_FUNCTION_1")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0040)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0040;
			rootJsonSettings->currentShieldsConnected->IDFUNCTION1 |= atoi(json_value); //
			printf("\n      ID_FUNCTION_1=%s", json_value); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"ID_FUNCTION_2")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0080)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0080;
			rootJsonSettings->currentShieldsConnected->IDFUNCTION2 |= atoi(json_value); //
			printf("\n      ID_FUNCTION_2=%s", json_value); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"ID_FUNCTION_3")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0100)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0100;
			rootJsonSettings->currentShieldsConnected->IDFUNCTION3 |= atoi(json_value); //
			printf("\n      ID_FUNCTION_3=%s", json_value); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"ID_FUNCTION_4")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0200)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0200;
			rootJsonSettings->currentShieldsConnected->IDFUNCTION4 |= atoi(json_value); //
			printf("\n      ID_FUNCTION_4=%s", json_value); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"ID_FUNCTION_5")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0400)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0400;
			rootJsonSettings->currentShieldsConnected->IDFUNCTION5 |= atoi(json_value); //
			printf("\n      ID_FUNCTION_5=%s", json_value); fflush(stdout); // Prints immediately to screen 
		}else if(strcmp(json_key,"ID_FUNCTION_6")==0 && (rootJsonSettings->currentShieldsConnected->semaphores & 0x0800)==0){
			rootJsonSettings->currentShieldsConnected->semaphores |= 0x0800;
			rootJsonSettings->currentShieldsConnected->IDFUNCTION6 |= atoi(json_value); //
			printf("\n      ID_FUNCTION_6=%s", json_value); fflush(stdout); // Prints immediately to screen 
		}
		
	}
	
	
}


// Read files
void readfile(char* filepath, char* fileContent)
{
    FILE *f;
    char c='\0';
    int index=0;
	
    //f = fopen(JSON_FILE_PATH, "r");
	f = fopen(filepath, "r");
   // while(EOF != (c = fgetc(f)) && index<BUFFER_SIZE ){
	//while(EOF != (c = getc(f)) && index<BUFFER_SIZE ){
		//printf("ORIGINAL JSON\n"); fflush(stdout);
	while(!feof(f) && (c = fgetc(f)) != EOF){ 
		//c = getc(f);
		if(c != EOF ){
			fileContent[index] = c;
			//printf("%c",c); fflush(stdout);
			index++;
		}//printf("Up to here OK!\n"); fflush(stdout);
	}
    fileContent[index-1] = '\0';
	//printf("\n\n"); fflush(stdout);
}

// This is where the magic happens
int parseJSON(struct_json_settings *rootJsonSettings, char *filepath, void callback(struct_json_settings *, char *, char*)){

    char JSON_STRING[BUFFER_SIZE];

    char value[1024];
    char key[1024];

    readfile(filepath, JSON_STRING);

   int i;
   int r;

   jsmn_parser p;
   jsmntok_t t[MAX_TOKEN_COUNT];

   jsmn_init(&p);

    
	
   r = jsmn_parse(&p, JSON_STRING, strlen(JSON_STRING), t, sizeof(t)/(sizeof(t[0])));

   if (r < 0) {
       printf("Failed to parse JSON: %d\n", r);
       return 1;
   }

   /* Assume the top-level element is an object */
   if (r < 1 || t[0].type != JSMN_OBJECT) {
       printf("Object expected\n");
       return 1;
   }

   for (i = 1; i < r; i++){

       jsmntok_t json_value = t[i+1];
       jsmntok_t json_key = t[i];


       int string_length = json_value.end - json_value.start;
       int key_length = json_key.end - json_key.start;

       int idx;

       for (idx = 0; idx < string_length; idx++){
           value[idx] = JSON_STRING[json_value.start + idx ];
       }

       for (idx = 0; idx < key_length; idx++){
           key[idx] = JSON_STRING[json_key.start + idx];
       }

       value[string_length] = '\0';
       key[key_length] = '\0';

       callback(rootJsonSettings,key, value);

       i++;
   }

   return 0;
}

// Only prints the key and value
void mycallback(char *key, char* value){
    printf("%s : %s\n", key, value);
}


void mycallback_print(char *key, char* value){
    //printf("%s\n", key);
	 printf("%s : %s\n", key, value);
}


/*
int main()
{ 
    parseJSON(JSON_FILE_PATH, mycallback);
    return 0;
}*/
