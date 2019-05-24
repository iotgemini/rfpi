/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					28/04/2019


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


#define JSON_FILE_PATH 		PATH_RFPI_SW "/fifo/config.json"
#define BUFFER_SIZE 5000
#define MAX_TOKEN_COUNT 128


//the struct where is kept the list of the shields connected to the transceiver
typedef struct shields_connected{
	char *NAME_SHIELD; //name of the shield
	unsigned int ID;
	unsigned char PINUSED;
	unsigned char PINMASK;
	unsigned char PULLUPRESISTOR;
	
	unsigned char IDFUNCTION0;
	unsigned char IDFUNCTION1;
	unsigned char IDFUNCTION2;
	unsigned char IDFUNCTION3;
	unsigned char IDFUNCTION4;
	unsigned char IDFUNCTION5;
	unsigned char IDFUNCTION6;

	unsigned int semaphores;	//bit0: NAME_SHIELD
								//bit1: ID
								//bit2: PINUSED
								//bit3: PINMASK
								//bit4: PULLUPRESISTOR
								//bit5: IDFUNCTION0
								//bit6: IDFUNCTION1
								//bit7: IDFUNCTION2
								//bit8: IDFUNCTION3
								//bit9: IDFUNCTION4
								//bit10: IDFUNCTION5
								//bit11: IDFUNCTION6
	
	struct shields_connected *next;
}struct_shields_connected;


//this is the main struct where is kept all description and status of the peripheral


//this is allocated when the json file is read
typedef struct json_settings{
	
	char 			*ADDRESS; //address of the peripheral
	char 			*NAME; //name of the peripheral
	unsigned int 	FW_VERSION;
	char 			*LINK_TYPE;
	unsigned int 	NUM_CHANNEL;
	
	unsigned int semaphores;	//bit0: ADDRESS
								//bit1: NAME
								//bit2: FW_VERSION
								//bit3: LINK_TYPE
								//bit4: NUM_CHANNEL
	
	//char *NameFileJson; //it keep the name of the file json
	
	/*int NumInput; //number of inputs on the peripheral
	int NumOutput; //number of outputs on the peripheral
	int numSpecialFunction; //number of the special functions
*/
	
	struct shields_connected *rootShieldsConnected; //it is like an array containing all settings of the connected shields
	struct shields_connected *currentShieldsConnected; //
	
	struct json_settings *next;
	
	unsigned int cont;
	unsigned char tempLastPin;
	
} struct_json_settings;


//global variables used into the functions
//struct_json_settings *rootJsonSettings;



//this function build and send command to the transceiver, this the command:
//	R	B	s	EEPROM_POS	H_ID_shield	L_ID_shield	PIN_used	PIN_mask	PULL-UP_resistor	ID_function0	ID_function1	ID_function2	ID_function3	ID_function4	ID_function5	ID_function6
peripheraldata *send_to_transceiver_json_settings(peripheraldata *rootPeripheralData,/*struct_json_settings *rootJsonSettings,*/ char *address_peri, char *json_path, int *handleUART);
	
	
//this function allocate the memory and record all data from json file
void fill_up_struct_json_settings(struct_json_settings *rootJsonSettings, char *json_key, char* json_value);

// Read files
void readfile(char* filepath, char* fileContent);

// This is where the magic happens
int parseJSON(struct_json_settings *rootJsonSettings, char *filepath, void callback(struct_json_settings *, char *, char*));

// Only prints the key and value
void mycallback(char *key, char* value);
	