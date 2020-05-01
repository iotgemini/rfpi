/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					01/05/2020


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

//for debug:
//printf("\n\n###### - UP TO HERE OK! - ######\n\n");fflush(stdout); // Prints immediately to screen 

//the platform can have the following vlaues:
#define PLATFORM_RPI_1_2 						1   			//if the platform is the Raspberry Pi 1 or 2
#define PLATFORM_RPI_3 							3				//the 3 indicate pi3
#define PLATFORM_RPI_2_JESSY_MAY_2016 			4 				//the 4 indicate the Raspbian image Jessy May 2016 mounted on Raspberry Pi 2
#define PLATFORM_GWRPI_3_JESSY_MAY_2016 		6 				//the 6 indicate the Raspbian image Jessy May 2016 mounted on Raspberry Pi 3 on the board GWRPI
								
#define PLATFORM_BBB 							2				//if the platform is the Beaglebone Black

#define PLATFORM_OPZ 							5				//if the platform is the OrangePi Zero
																//also for the ARMBIAN_BIONIC_4.14.y 

#define PLATFORM_PC_DEBIAN						7				//if the platform is the Beaglebone Black


#define PLATFORM 3	//choose the platform where this software will be used



#define ENABLE_SEARCH_SERIAL_PORT_PATH					
#define PATH_TO_SEARCH_SERIAL_PORT				"/dev"

//#define SERIAL_PORT_FTDI_USB	//deccoment to enable usb communication

#define PATH_RFPI_SW	 			"/etc/rfpi"
//#define PATH_RFPI_SW	 			"/etc/rfpi_usb"

#ifndef SERIAL_PORT_FTDI_USB
	#define LED_YES	//to turn off leds then comment this define
#endif

//define the RTC used
#define NO_RTC 			0
#define RTC_M41T62 		1
#define RTC_DS1307 		2

//#define RTC_MODEL 		NO_RTC	


#if PLATFORM == 1
	#define SERIAL_PORT_PATH		"/dev/ttyAMA0"
	#define PLATFORM_RPI			1
#elif PLATFORM == 2
	//#define SERIAL_PORT_PATH 		"/dev/ttyO1"
	#define SERIAL_PORT_PATH 		"/dev/ttyS1"
	#define PLATFORM_RPI			0
#elif PLATFORM == 3
	#ifdef SERIAL_PORT_FTDI_USB
		#define SERIAL_PORT_PATH		"/dev/ttyUSB0"
	#else
		#define SERIAL_PORT_PATH		"/dev/ttyS0"
	#endif
	#define PLATFORM_RPI			3
#elif PLATFORM == 4
	#define SERIAL_PORT_PATH		"/dev/serial0"
	#define PLATFORM_RPI			4
#elif PLATFORM == 5
	#define SERIAL_PORT_PATH		"/dev/ttyS1"
	#define PLATFORM_RPI			0
#elif PLATFORM == 6
	#define SERIAL_PORT_PATH		"/dev/serial0"
	#define PLATFORM_RPI			6
#elif PLATFORM == 7
	#define SERIAL_PORT_PATH		"/dev/ttyUSB0"
	#define PLATFORM_RPI			0
#endif




//#define		BAUD_RATE_SERIAL_PORT	115200
//#define		BAUD_RATE_SERIAL_PORT	57600
#define		BAUD_RATE_SERIAL_PORT	9600

//#define 	SEND_COMAND_TO_SET_OPERATING_BAUDRATE 	SerialCmdRFPi(handleUART, "C557", answerRFPI, CMD_WAIT1)	//set a baud rate of 115200 (no compatible with Black Transceiver)
//#define 	SEND_COMAND_TO_SET_OPERATING_BAUDRATE 	SerialCmdRFPi(handleUART, "C556", answerRFPI, CMD_WAIT1) 	//set a baud rate of 57600


#define DEBUG_LEVEL 	2	//enable the debug setting this parameter with a value above 0

#define ENABLE_RADIO_DATA_CHECKSUM	//if defined enable the control of the checksum that is stored on the 16th byte of the radio data. This used for peri 100


#define SERVER "http://rfpi/"

int var_dummy1,var_dummy2;

//GPIO Raspberry Pi
#if PLATFORM == 6
	#define PIN_LED_DS1 				var_dummy1 //RPI_GPIO_P1_16	//the pin where is connected the led DS1
	#define PIN_LED_DS2 				var_dummy2 //RPI_GPIO_P1_18 	//the pin where is connected the led DS2 (running led)
	#define PIN_RESET 					RPI_GPIO_P1_18//RPI_GPIO_P1_22 	//the pin where is connected the reset
#else
	#define PIN_LED_DS1 				RPI_GPIO_P1_16	//the pin where is connected the led DS1
	#define PIN_LED_DS2 				RPI_GPIO_P1_18 	//the pin where is connected the led DS2 (running led)
	#define PIN_RESET 					RPI_GPIO_P1_22 	//the pin where is connected the reset
#endif

//GPIO Beaglebone Black
#define BBB_PIN_LED_DS2 			51   // EHRPWM1B //the pin where is connected the led DS2 (running led)
#define BBB_PIN_RESET	 			50   // EHRPWM1B //the pin where is connected the reset of the radio shield

//GPIO OrangePi Zero
#define OPZ_PIN_LED_DS2 			18   // the pin where is connected the led DS2 (running led)
#define OPZ_PIN_RESET	 			19   // the pin where is connected the reset of the radio shield
#define OPZ_PIN_BUSY	 			7    // the pin where is connected the busy of the radio shield




#define PATH_CONFIG_FILE 			PATH_RFPI_SW "/config/"							//the path where are kept all configuration files
#define FILE_NETWORK_NAME 			PATH_RFPI_SW "/config/network_name.txt" 		//it keep the network name
#define FILE_LIST_PERIPHERAL 		PATH_RFPI_SW "/config/list_peripheral.txt" 		//it keep the list of all peripherals linked
#define FILE_ERROR_HISTORY	 		PATH_RFPI_SW "/config/error_history.txt" 		//it keep the list of all peripherals linked

#define PATH_FIFO	 				PATH_RFPI_SW "/fifo"
#define FIFO_RFPI_RUN 				PATH_RFPI_SW "/fifo/fiforfpirun" 		//used to communicate to the GUI the rfpi is operating
#define FIFO_GUI_CMD 				PATH_RFPI_SW "/fifo/fifoguicmd" 		//the web (GUI) will write into the data to se and send
#define FIFO_GUI_CMD_SYNC			PATH_RFPI_SW "/fifo/fifocmdsync" 		//when the command is ready into fifoguicmd then the GUI write '1' into fifocmdsync, that make the rfpi to execute the command. After the reading from rfpi then rfpi write '0' inside fifocmdsync.
#define FIFO_RFPI_STATUS 			PATH_RFPI_SW "/fifo/fiforfpistatus" 	//used to communicate to the GUI the status and answer of the RFberry Pi
#define FIFO_RFPI_PERIPHERAL 		PATH_RFPI_SW "/fifo/fifoperipheral" 	//used to communicate to the GUI the all data of the all peripherals installed
#define FIFO_RFPI_PERIPHERAL_SYNC	PATH_RFPI_SW "/fifo/fifoperipheralsync" 		//
#define FIFO_RFPI_PERIPHERAL_JSON	PATH_RFPI_SW "/fifo/fifoperipheraljson" 	//
#define FIFO_RFPI_NET_NAME			PATH_RFPI_SW "/fifo/fifonetname" 		//used to communicate to the GUI the network name
#define FIFO_GET_BYTES_U 			PATH_RFPI_SW "/fifo/fifogetbytesu"	//used to get GET_BYTES_U
#define FIFO_SEND_BYTES_F 			PATH_RFPI_SW "/fifo/fifosendbytesf"	//used to get SEND_BYTES_F
#define FIFO_RTC		 			PATH_RFPI_SW "/fifo/fifortc"			//used to keep the time

#define ADDRESS_RFPI 				"AAFF" 	//it is the address of the Transceiver in hexadecimal with format ASCII

#define MAX_BUF_FIFO_GUI_CMD 		512		//the max length of the string to read from a FIFO
#define MAX_LEN_LINE_FILE 			500 	//used to define the maximum length in number of characters for each line into the file of configuration
#define MAX_LEN_NAME 				50 		//it is the maximum length in number of characters for each name (peripheral and input or output)
#define MAX_LEN_NET_NAME			128		//it is the max length of the network name
#define MAX_LEN_PATH 				255 	//it is the maximum length in number of characters for the path included the name of the file
#define MAX_LEN_BUFFER_ANSWER_RF	47 		//into the answer there are 23bytes + the \0. Example: OK*0001RBu1............

#define CMD_WAIT1					30//120//420 	//it is a delay needed after each command sent through the uart to the Transceiver
#define CMD_WAIT2					100//50 //1200 	//it is a longer delay used to wait answer after radio frequency transmission

#define MAX_NUM_RETRY				25//8//3	 	//if the peripheral does not answer then the rfpi.c try to get the data for this number of times
#define MIN_NUM_RETRY				8//8//3	 		//if the peripheral does not answer then the rfpi.c try to get the data for this number of times

//#define BLINK_LED_DELAY				0 //25 //50		//it is the time in ms between the ON and OFF of the LED
//#define ERROR_BLINK_LED_DELAY		200	//500	//it is the time in ms between the ON and OFF of the LED


#define DELAY_AFTER_PARSED_DATA_GUI		5		//mS. It is then multiplied by EXECUTION_DELAY by a cycle 

#define EXECUTION_DELAY					10		//This is multiplied by DELAY_AFTER_PARSED_DATA_GUI
												//It is the delay before to update the fifo with the status, this will also give the semiperiod of the blinking led
												
//This is the time that the last message into FIFO_RFPI_STATUS would be hold, then ParseFIFOdataGUI(...) will write inside "OK"
#define TIME_HOLD_MSG_FIFO_RFPI_STATUS	DELAY_AFTER_PARSED_DATA_GUI*10000		
												


//LIST OF ERROR

#define  ERROR001		"ERROR001 = Impossible to initialise the GPIO!"
#define  ERROR002		"ERROR002 = No serial communication with the RADIO!"
#define  ERROR003		"ERROR003 = No network name!"
#define  ERROR004		"ERROR004 = Missing file descriptor! Created new empty file: "
#define  ERROR005		"ERROR005 =	Error in parsing the command GET_BYTES_U!"
#define  ERROR006		"ERROR006 =	Error in creating the data file for the function GET_BYTES_U!"
#define  ERROR007		"ERROR007 =	Error in parsing the command SEND_BYTES_F!"
#define  ERROR008		"ERROR008 =	Impossible to access to the file data for the command SEND_BYTES_F!"
#define  ERROR009		"ERROR009 =	Error in reading the file for the command SEND_BYTES_F! Data is less than expected!"


//LIST OF MESSAGE THAT ARE WRITTEN INTO FIFO

//fifo rfpi run
#define  MSG_FIFO_RFPI_RUN_TRUE		"TRUE"
#define  MSG_FIFO_RFPI_RUN_BUSY		"BUSY"

//FIFO status rfpi
#define  MSG_FIFO_RFPI_STATUS_OK					"OK"
#define  MSG_FIFO_RFPI_STATUS_EXECUTING				"EXECUTING"
#define  MSG_FIFO_RFPI_STATUS_NOTX					"NOTX"		
#define  MSG_FIFO_RFPI_STATUS_NOPERI				"NOPERI"		//used above all when it find a new peripheral
#define  MSG_FIFO_RFPI_STATUS_NOTYPE				"NOTYPE" 		//used above all when it find a new peripheral
#define  MSG_FIFO_RFPI_STATUS_NONAME				"NONAME" 		//used above all when it find a new peripheral
#define  MSG_FIFO_RFPI_STATUS_SENDING				"SENDING"
#define  MSG_FIFO_RFPI_STATUS_READING				"READING"		//used above all  into command GET_BYTES_U
#define  MSG_FIFO_RFPI_STATUS_STOPPED				"STOPPED"		//used above all  into command GET_BYTES_U



//DEFINE THE DATA POSITION INTO THE ARRY OF 16 BYTES OF RADIO TXRX FOR Input and Output
#define  POS_IO_R				0	//it is the 'R'
#define  POS_IO_B				1	//it is the 'B'
#define  POS_IO_CMD				2	//it is the command 'i' or 'p'
#define  POS_IO_ID				3	//it is the ID of the IO
#define  POS_IO_STATUS_8BIT		4	//it is the status of the Input or Output on 8bit
#define  POS_IO_RESOLUTION		5	//it is the resolution in bit of the Input or Output on 8bit
#define  POS_IO_DATA0			6	//it is the
//.........
#define  POS_IO_ID_SHIELD		13	//it is the ID of the shield connected to the pin
#define  POS_IO_NUM_PIN			14	//it is the number of the pin
#define  POS_IO_CHECKSUM		15	//it is the byte containing checksum

//the struct where is kept the list of inputs name and status for each peripheral
typedef struct peripheralnameinput{
	char *NameInput;
	char *Type;
	signed long StatusInput;
	int BitResolution;
	int StatusCommunication;
	int id_shield_connected;
	int num_pin_used_on_the_peri;
	struct peripheralnameinput	*next;
}peripheraldatanameinput;

//the struct where is kept the list of outputs name and status for each peripheral
typedef struct peripheralnameoutput{
	char *NameOutput;
	char *Type;
	signed long StatusOutput;
	int BitResolution;
	int StatusCommunication;
	int id_shield_connected;
	int num_pin_used_on_the_peri;
	struct peripheralnameoutput	*next;
}peripheraldatanameoutput;

//this is the main struct where is kept all description and status of the peripheral
typedef struct peripheral{
	unsigned int IDtype; //identify the peripheral
	char *Name; //name of the peripheral
	
	char *NameFileDescriptor; //it keep the name of the file descriptor
	
	char NetAddress[5]; //it keep the 4 characters (they are 4 hexadecimal) which represent the address of the network 
	char PeriAddress[5]; //it keep the 4 characters (they are 4 hexadecimal) which represent the address of the peripheral 
	  
	int NumInput; //number of inputs on the peripheral
	int NumOutput; //number of outputs on the peripheral
	int numSpecialFunction; //number of the special functions
	int fwVersion; //it is the version of the firmware of the peripheral
	
	int strengthLink;	//this callculate the strength of the link, that is calculated so: strengthLink = (100/contTotalNumInOut) * (contTotalNumInOut - contInOutOFFline);
	  
	struct peripheralnameinput *rootNameInput; //it is like an array containing all name of the inputs
	struct peripheralnameoutput *rootNameOutput; //it is like an array containing all name of the outputs
	
	struct peripheral	*next;
} peripheraldata;


//global variables used into the functions
char statusRFPI[200]; 				//used into: ParseFIFOdataGUI
long contStatusMsg; 					//used into: ParseFIFOdataGUI
char statusInit[200]; 				//it contain the initialisation status of rfpi.c
char networkName[MAX_LEN_NET_NAME]; //it will keep the name of the network
char networkAddress[5]; 			//it will contain the address of the network generated from the networkName
int handleUART; 					//keep the handleUART of the serial port
unsigned char lastStatusBlinkingLed; //used to change the status of the blinking led every BLINK_LED_DELAY
unsigned int decontBlinkDelay;	 //used  to change the status of the blinking led every BLINK_LED_DELAY

//used for the RTC
unsigned char RTC_hour_bcd, RTC_minute_bcd, RTC_second_bcd;
unsigned char RTC_date_bcd, RTC_month_bcd, RTC_year_bcd;
	
//used as temporary variables into function peripheraldata *ParseFIFOdataGUI(int *handleUART, peripheraldata *rootPeripheralData)
unsigned char array10BytesToSend[10];

//variable used for the serial communication
char *serial_port_path_str;
unsigned char sem_serial_port_USB;
char path_to_search_serial_port[]=PATH_TO_SEARCH_SERIAL_PORT;
unsigned char sem_serial_communication_via_usb;  //if the communication is via USB then no gpio will control leds. This would be updated by function return_serial_port_path(....)
//unsigned char sem_ctrl_led; //this enable or disable the control of the leds by the gpio. If the transceiver is connected via USB then no led are connected to the gpio

unsigned char sem_init_gpio_rpi_ok;	//if the gpio are initialised then cna control the gpio

char last_status_blinking_led; //used to keep the last status of the binking led

//delay milliseconds
void delay_ms(unsigned int millis);

//it will init the serial communication between Transceiver and Raspberry Pi. 
//First it will find the baud rate set on the Transceiver and then keep open 
//the Raspberry Pi serial with the same baud rate.
extern unsigned int InitSerialCommunication(int *handleUART, char *serial_port_path);

//it will init the serial communication between Transceiver and Raspberry Pi. 
//It chang the baudrate of the transceiver with the one defined by BAUD_RATE_SERIAL_PORT
extern unsigned int InitSerialCommunicationWithDefaultBaudRate(int *handleUART, char *serial_port_path);
		
//it reset the Transceiver and turn on the LED called DS2
extern void ResetRFPI(void);

//it will set the baud rate on the Transceiver and the serial port on the Raspberry Pi
extern void SetBaud(int *handleUART);

//it will return the command for the Transceiver to set the baud rate given
extern char* GetCmdToSetBaud(unsigned int *baud);

//it create a fifo with written inside the data given
extern int fifoWriter(char *fifoname, char *data);

//it read the fifo and then it will delete
extern int fifoReader(char *data, char *fifoname);

//convert a int with value between 0 and 255 to a hexadecimal, example: 255 return FF
extern void byteToHexStr(char *strHex, int num);

//it just print on the terminal the data into all struct used for the RFberry Pi
extern void printPeripheralStructData(peripheraldata *rootPeripheralData);

//it read all files necessary to construct the struct containing the data of all peripheral linked
extern peripheraldata *loadLinkedPeripheral(int *handleUART);

//write the FIFO in order to give all data to the GUI
extern void writeFifoPeripheralLinked(peripheraldata *rootPeripheralData);

//convert a number in string
extern char* intToStr(int i, char *b);

//read from file the network name, if the network name is not set it will return '\0' 
extern int readNetworkNameFromFile(char *networkName);

//it will send the command to the RFberry module 
extern void initRFberryNetwork(int *handleUART);

//it will set the default network used to search a new peripheral
extern void setProgramModeNetwork(int *handleUART);

//it will set the address for the current network
extern void setCurrentNetwork(int *handleUART);

//it init a default network and then send the data to set the current network to the new peripheral
extern peripheraldata *findNewPeripheral(int *handleUART, char *statusRFPI, peripheraldata *rootPeripheralData);

//it update the status into the struct data of the peripheral linked
//extern void updateStructPeriOut(peripheraldata *rootPeripheralData, int *IDposition, int *IDoutput, int *valueOutput, int *id_shield_connected, int *num_pin_used_on_the_peri);
//extern void updateStructPeriOut(peripheraldata *rootPeripheralData, int *IDposition, int *IDoutput, int *valueOutput);

//it calculate and return the address for the network. Example name="SDS" return="00EA"
extern void addressFromName(char *name, char *address);

//it delete the peripheral with the specified address
peripheraldata *deletePeripheralByAddress(char *addressPeri, peripheraldata *rootPeripheralData);
	
//it delete the peripheral in the postion positionId, the file descriptor will remain
extern peripheraldata *deletePeripheral(int positionId, peripheraldata *rootPeripheralData);

//send trough the serial port a specified number of characters to the Transceiver module.
//extern void SerialCmdRFPI(int *handleUART, unsigned char *strCmd, int numCharacters, unsigned char *answerRFPI, int delayMs);

//send trough the serial port a specified number of characters to the Transceiver module and then send the command C31 and wait the answer from the peipheral.
extern void SendRadioDataAndGetReplyFromPeri(int *handleUART, unsigned char *arrayData, int numCharacters, char *answerRFPI, int maxTimeOutMs, unsigned char mustReply);

//load the data on the Transceiver. This data then will be ready to be sended with the command C31
extern void loadRadioData(int *handleUART, unsigned char *arrayData, int numCharacters, char *answerRFPI, int maxTimeOutMs);

//send trough the serial port a command which will be a string (string has to not contain special characters, for special characters use the function: SerialCmdRFPI)
//it will exit from the function when receive the "*" or receive "OK*" from the Transceiver module or when reach the maxTimeOutMs
extern void SerialCmdRFPi(int *handleUART, unsigned char *strCmd, char *answerRFPI, int maxTimeOutMs);

//it parse the data coming from the GUI. It will write the FIFO RFPI STATUS. Thus into the FIFO RFPI STATUS there will be written the response after have parsed the data from the GUI.
extern peripheraldata *ParseFIFOdataGUI(int *handleUART, peripheraldata *rootPeripheralData, int *cmd_executed);
//Here the commands to write into FIFO_GUI_CMD that are parsed by the fuction ParseFIFOdataGUI(...):
//	FIND 			NEW 		PERI 			NULL				//it start the procedure to find a new peripheral that is waitng to be installed into the network
//	DELETE 			ADDRESS 	xxxx 			NULL				//it delete all files and data of the address written in place of xxxx
//	DELETE 			PERI 		x 				NULL				//it delete all files and data of the ID position written in place of xxxx
//	PERIOUT			xa 			xb 				xc					//it set the output of the peripherla with ID position = xa, ID output = xb, value to set = xc
//	PERIOUT			xxxx		xb 				xc					//it set the output of the peripherla with address = xxxx, ID output = xb, value to set = xc 
//	STATUS 			RFPI 		GOT 			NULL				//it says to the rfpi that the status has been readed, thus rfpi can write into FIFO_RFPI_STATUS the word "OK"
//	NAME 			NET 		xxx... 			NULL				//it save the network name and create a numerical address. Name of the network is xxx...
//	NAME 			PERI 		xxx...			xxxx  				//it save the name of aperipheral given by xxx... with address given by xxxx
//	REFRESH			PERI 		STATUS			ALL  				//it refressh all input/output status for all peripherals
//	REFRESH			PERI 		STATUS			xxxx  				//it refressh all input/output status only for the peripheral with address xxxx
//	DATA			RF 			xxxx			strHex16bytes  		//it send the 16bytes to a peripheral with address xxxx. Example of strHex16bytes: 524275010000000300002E2E2E2E2E2E
//	RTC				SET			NULL			NULL  				//it set RTC if it is installed on the gateway
//	GET_BYTES_U		id_position	num_function	num_bytes_to_get  	//it get a series of data from the peripheral give by the ID position id_position
//	SEND_BYTES_F	id_position	num_function	num_bytes_to_send  	//it sends a series of data to the peripheral give by the ID position id_position
//	SENDJSONSETTINGS		address_peri		json_file	NULL  		//it sends a json configuration to a periheral with address = address_peri. The function send_to_transceiver_json_settings(...) is kept into file rfpi_json.c



//it check the data into the buffer of the UART, return the data on the string given
extern int checkDataIntoUART(int *handleUART, unsigned char *dataRFPI, int lenght_buffer_dataRFPI);

//it parse the data given. In case of data from peripheral, it will update the struct data
extern peripheraldata *parseDataFromUART(unsigned char *dataRFPI, int *numBytesDataRFPI, peripheraldata *rootPeripheralData, int *cmd_executed);

//asks to All peripherals the status of all inputs and outputs and update the status into the struct data
//extern void askAndUpdateAllIOStatusPeri(int *handleUART, peripheraldata *rootPeripheralData);

//asks to the peripheral the status of all inputs and outputs and update the status into the struct data
extern void askAndUpdateIOStatusPeri(int *handleUART, unsigned char *peripheralAddress, peripheraldata *rootPeripheralData);

//get from the peripheral the status of the input/output and it return also the bit resolution, if the bit resolution is over the 8bit then the value is kept into the bytes after the bit resolution byte
//extern int getIOStatusPeri(int *handleUART, unsigned char *peripheralAddress, unsigned int ID_IO, char type_IO, unsigned char *array_status);
extern signed long get_IO_Peri_Status(int *handleUART, peripheraldata *currentPeripheralData, unsigned int ID_IO, char type_IO, unsigned char *array_status);
	
//asks to the peripheral the status of the input
//extern int askInputStatusPeri(int *handleUART, unsigned char *peripheralAddress, unsigned int IDinput, int *id_shield_connected, int *num_pin_used_on_the_peri);

//asks to the peripheral the status of the output
//extern int askOutputStatusPeri(int *handleUART, unsigned char *peripheralAddress, unsigned int IDoutput, int *id_shield_connected, int *num_pin_used_on_the_peri);

// Implementation of itoa(). Convert a number into a string
extern char* itoaRFPI(int num, char* str, int base);

//convert 2 Character ASCII in a byte. Example "FF" become 0xFF
unsigned char convert_2ChrHex_to_byte(unsigned char *chr);

//convert a byte in 2 Character ASCII in hexadecimal format. Example 0xFF become "FF"
char* convert_byte_to_2ChrHex(unsigned char byte, char *str2chr);

//it init the RFPI
extern peripheraldata *InitRFPI(peripheraldata *rootPeripheralData, char *serial_port_path);

//it blink the led and gives a delay to stay under the transmission limit
extern void blinkLed();

//read the RTC on the i2c bus and update a FIFO called fifortc
#ifdef RTC_MODEL
unsigned char read_RTC();
#endif // RTC_MODEL

//set the RTC on the i2c bus. The str_time has the format: hh:mm:ss
#ifdef RTC_MODEL
unsigned char set_RTC(unsigned char *str_time, unsigned char *str_data);
#endif // RTC_MODEL

//get the bytes for the function u. Will write into answerRFPI the reply from the peri 
int get_bytes_u_Peri(int *handleUART, unsigned char *peripheralAddress, unsigned int num_function, unsigned int num_packet_to_get, unsigned char *answerRFPI);

//send the bytes for the function u. Will write into answerRFPI the reply from the peri 
int send_bytes_f_Peri(int *handleUART, unsigned char *peripheralAddress, unsigned int num_function, unsigned int num_packet_to_write, unsigned char *array10BytesToSend, unsigned char *answerRFPI);

//this function make to start the DS1307 when it is new!
#ifdef RTC_MODEL
void start_DS1307_if_new(void);
#endif // RTC_MODEL

//write the FIFO in format Json in order to give all data to the GUI
void writeFifoJsonPeripheralLinked(peripheraldata *rootPeripheralData);

//this function test all serial port under the path given by path_search
char* return_serial_port_path(char *path_search, char *serial_port_path, int *handleUART);

//this function return the MPN from the id, this is used for peripheral 100
//char* return_mpn(char *mpn, int *id_shield);
	
