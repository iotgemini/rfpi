<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/11/2020

Description: it is the library with useful functions


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
 
******************************************************************************************/


//-------------------------------BEGIN INCLUDE----------------------------------//

	

//-------------------------------END INCLUDE----------------------------------//


//-------------------------------BEGIN FUNCTIONS DESCRIPTIONS----------------------------------//



//-------------------------------END FUNCTIONS DESCRIPTIONS----------------------------------//

//-------------------------------BEGIN DEFINE----------------------------------//


//DEFINES of message to write into the FIFO GUI CMD
define("GET_MULTIPLE_DATA_U",  "GET_BYTES_U "); 	//command written into FIFO GUI CMD used to get the data 
													// this command has to complete with position_id, number of the function that return the data
													// and the number of byte to get
													//example:
													//		GET_BYTES_U		1	4	250
		


//define("DEFAULT_PATH", "NO"); 			//this force the library rfberrypi.php to take the following paths
//define("DIRECTORY_IMG", "/img/"); 		//redefined here for the library rfberrypi.php
//define("DIRECTORY_CSS", "/css/"); 		//redefined here for the library rfberrypi.php

//DEFINES of DIRECTORY
//define("DIRECTORY_IMG_PERI_14", "./lib/peripheral_14/img/"); 		//where all pictures are kept
//define("DIRECTORY_CSS_PERI_14", "./lib/peripheral_14/css/"); 		//where all pictures are kept

define("DIRECTORY_IMG_PERI_14", "/img/peripheral/"); 		//where all default pictures for any peripheral are kept
define("DIRECTORY_CSS_PERI_14", "/css/"); 					//where all default style for any peripheral are kept


define("MAX_TEMPERATURE_MCP9701", 128);
define("MIN_TEMPERATURE_MCP9701", 0);

define("MAX_VOLTAGE_ADC_INPUT", 3.3);

//-------------------------------END DEFINE----------------------------------//

//-------------------------------BEGIN INCLUDE CSS----------------------------------//

//include the CSS
//echo '<link rel="stylesheet" href="./css/peripheral.css" type="text/css" >';
//echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'settings.css" type="text/css" >';

//-------------------------------END INCLUDE CSS----------------------------------//




?>
