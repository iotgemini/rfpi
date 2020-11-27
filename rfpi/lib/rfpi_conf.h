/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					27/11/2020


Description: configuration library for the  RFPI

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

//#define ENABLE_OLD_SYSTEM_COMPATIBILITY		//uncomment this to enable old system compatibility

#ifdef	ENABLE_OLD_SYSTEM_COMPATIBILITY
	#define PLATFORM 1							//do not touch this, touch the define after the else
#else
	#define PLATFORM 3							//choose the platform where this software will be used
	#define ENABLE_SEARCH_SERIAL_PORT_PATH		//this disable the search of the port where is connected the radio
	#define ENABLE_RADIO_DATA_CHECKSUM	//if defined enable the control of the checksum that is stored on the 16th byte of the radio data. This used for peri 100
#endif
//the platform can have the following vlaues:
// PLATFORM_RPI_1_2 						1   			//if the platform is the Raspberry Pi 1 or 2
// PLATFORM_RPI_3 							3				//the 3 indicate pi3
// PLATFORM_RPI_2_JESSY_MAY_2016 			4 				//the 4 indicate the Raspbian image Jessy May 2016 mounted on Raspberry Pi 2
// PLATFORM_GWRPI_3_JESSY_MAY_2016 			6 				//the 6 indicate the Raspbian image Jessy May 2016 mounted on Raspberry Pi 3 on the board GWRPI
// PLATFORM_BBB 							2				//if the platform is the Beaglebone Black
// PLATFORM_OPZ 							5				//if the platform is the OrangePi Zero //also for the ARMBIAN_BIONIC_4.14.y 
// PLATFORM_PC_DEBIAN						7				//if the platform is the Beaglebone Black


//#define SERIAL_PORT_FTDI_USB	//uncomment to enable only usb communication
#ifndef SERIAL_PORT_FTDI_USB
	//#define LED_YES	//to turn off leds then comment this define
#endif

#define PATH_TO_SEARCH_SERIAL_PORT			"/dev"

#define PATH_RFPI_SW	 					"/etc/rfpi"		//"/etc/rfpi_path"

//#define ENABLE_READING_I2C_RTC 	
#define RTC_MODEL 		0
//RTC_MODEL can assume these values:
// NO_RTC 			0
// RTC_M41T62 		1
// RTC_DS1307 		2



