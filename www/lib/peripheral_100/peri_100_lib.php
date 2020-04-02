<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/04/2020

Description: it is the library with all useful function to use RFPI


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

//	function temperature_MCP9701_from_8bit_value_peri_100($ADC_8bit_value); 	//return the temperature in °C

//	function the_8bit_value_from_temperature_MCP9701_peri_100($tempearure); 	//return the byte of the temperature

//	function voltage_0to10V_from_8bit_value_peri_100($ADC_8bit_value);		//return the value between 0V and 10V from the byte readed by the ADC

//	function str_voltage_0to10V_from_8bit_value_peri_100($ADC_8bit_value);	//return the string with the value between 0V and 10V from the byte readed by the ADC

//	function value_8bit_from_voltage_0to10V_peri_100($voltage_0to10_value);	//return a byte from a value between 0V and 10V


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
//define("DIRECTORY_IMG_PERI_100", "./lib/peripheral_100/img/"); 		//where all pictures are kept
//define("DIRECTORY_CSS_PERI_100", "./lib/peripheral_100/css/"); 		//where all pictures are kept

define("DIRECTORY_IMG_PERI_100", "/img/peripheral/"); 		//where all default pictures for any peripheral are kept
//define("DIRECTORY_CSS_PERI_100", "/lib/peripheral_100/css/"); 					//where all default style for any peripheral are kept
define("DIRECTORY_CSS_PERI_100", "/css/"); 					//where all default style for any peripheral are kept



define("MAX_VOLTAGE_ADC_INPUT", 3.3);

//-------------------------------END DEFINE----------------------------------//

//-------------------------------BEGIN INCLUDE CSS----------------------------------//

//include the CSS
//echo '<link rel="stylesheet" href="./css/peripheral.css" type="text/css" >';
//echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_100 . 'slider.css" type="text/css" >';
echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_100 . 'peripheral.css" type="text/css" >';

//-------------------------------END INCLUDE CSS----------------------------------//


function voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value){
	$voltage = $ADC_10bit_value; 
	$voltage = (($ADC_10bit_value * 5 ) / 1024); 
	//$voltage = ceil($voltage);
	return $voltage;
}

function str_voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value){
	$voltage="";
	//$voltage=number_format((float)strval(voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value)), 0, '.', ''); 
	$voltage_int = voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value);
	$voltage_int = $voltage_int * 100;
	$voltage_int = ceil($voltage_int);
	$voltage_int = $voltage_int / 100;
	$voltage = number_format($voltage_int, 2, '.', ''); 
	return $voltage;
}


function temperature_DHT11_from_raw_value_peri_100($raw_value){
	$temperature = $raw_value & 0x0000FFFF;
	
	$temperature_int = $temperature >> 8;
	$temperature_dec = $temperature & 0x00FF;
	
	if( ($temperature & 0x8000) == 0x8000 ){
		$temperature_int &= 0x7F;
		return "? " . $temperature_int . "." . $temperature_dec;
	}else{
		return $temperature_int . "." . $temperature_dec;
	}
}

function umidity_DHT11_from_raw_value_peri_100($raw_value){
	$umidity = $raw_value >> 16;

	$umidity_int = $umidity >> 8;
	$umidity_dec = $umidity & 0x00FF;
	
	if( ($umidity & 0x8000) == 0x8000 ){
		$umidity_int &= 0x7F;
		return "? " . $umidity_int;// . "." . $umidity_dec;
	}else{
		return $umidity_int;// . "." . $umidity_dec;
	}
}


function temperature_MCP9701_from_ADC_raw_value_peri_100($ADC_value){
	$tempearure = (((($ADC_value)*0.0048828125) - 0.4) / 0.0195);
	//$tempearure = ceil($tempearure);
	//$tempearure = round($tempearure);
	return $tempearure;
}


function voltage_0to10V_from_8bit_value_peri_100($ADC_8bit_value){
	$voltage = $ADC_8bit_value; 
	$voltage = (($ADC_8bit_value * MAX_VOLTAGE_ADC_INPUT ) / 256); 
	//$voltage = ceil($voltage);
	return $voltage;
}

function str_voltage_0to10V_from_8bit_value_peri_100($ADC_8bit_value){
	$voltage="";
	//$voltage=number_format((float)strval(voltage_0to10V_from_8bit_value_peri_100($ADC_8bit_value)), 0, '.', ''); 
	$voltage_int = voltage_0to10V_from_8bit_value_peri_100($ADC_8bit_value);
	$voltage_int = $voltage_int * 100;
	$voltage_int = ceil($voltage_int);
	$voltage_int = $voltage_int / 100;
	$voltage = number_format($voltage_int, 2, '.', ''); 
	return $voltage;
}

function value_8bit_from_voltage_0to10V_peri_100($voltage_0to10_value){
	$value_8bit = floatval($voltage_0to10_value); 
	$value_8bit = (($voltage_0to10_value * 256) / MAX_VOLTAGE_ADC_INPUT) ; 
	//$value_8bit = ceil($value_8bit);
	//$value_8bit = round($value_8bit);
	$value_8bit = number_format($value_8bit, 0, '.', ''); 
	return $value_8bit;
}



function temperature_pyrometer_peri_100($ADC_8bit_value){
	$temperature = (($ADC_8bit_value * (1000-250) ) / 255)+250;
	
	return $temperature;
}


function str_temperature_pyrometer_peri_100($ADC_8bit_value){
	$temperature = temperature_pyrometer_peri_100($ADC_8bit_value);
				
	$str_temperature="";
	$temperature = $temperature * 100;
	$temperature = ceil($temperature);
	$temperature = $temperature / 100;
	$str_temperature = number_format($temperature, 0, '.', ''); 
	
	$str_to_return = "<br>PYROMETER: ";
	$str_to_return .= $str_temperature;
	$str_to_return .= '&nbsp&#176C&nbsp'; //°C
	
	return $str_to_return;
}


/*
http://stackoverflow.com/questions/1619265/how-to-round-up-a-number-to-nearest-10
floor() will go down.

ceil() will go up.

round() will go to nearest by default.

Divide by 10, do the ceil, then multiply by 10 to reduce the significant digits.

$number = ceil($input / 10) * 10;
*/



?>
