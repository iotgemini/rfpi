<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		28/05/2023

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

//	function mcu_volt_peri_100($ADC_10bit_value);														//return the volts of the power supply applied to the MCU

//	function str_mcu_volt_peri_100($ADC_10bit_value);													//return a string that contain the volts of the power supply applied to the MCU

//	function voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value);									//return the value in Volt

//	function ADC10bit_from_voltage_0to5V_peri_100($milliVolt,$MCU_Volts_raw_value);						//return the ADC value on 10bits from the value in milli Volt

//	function str_voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value);								//return a string that contain the value in Volt of the ADC 10bit resolution

//	function temperature_DHT11_from_raw_value_peri_100($raw_value);										//return a string with the value of the temperature converted from the raw value read from the sensor DHT11

//	function humidity_DHT11_from_raw_value_peri_100($raw_value);										//return a string with the value of the humidity converted from the raw value read from the sensor DHT11

//	function tempORhumid_DHT11_from_threshold_value_peri_100($raw_value);								//return the value of the Temperature OR Humidity set for the threshold functions

//	function threshold_value_from_tempORhumid_DHT11_peri_100($temperature);								//return the value to set into the peri for the threshold functions from the Temperature OR Humidity value choosen by user

//	function temperature_MCP9701_from_ADC_raw_value_peri_100($ADC_value,$MCU_Volts_raw_value);			//return the temperature from ADC value where is connected the MCP9701 sensor
	
//	function temperature_MCP9701_from_8bit_value_peri_100($ADC_8bit_value); 							//return the temperature in °C

//	function the_ADC10bit_value_from_temperature_MCP9701_peri_100($temperature, $MCU_Volts_raw_value); 	//return 10bits on 2bytes of the ADC raw value of the temperature

//	function voltage_0to10V_from_8bit_value_peri_100($ADC_8bit_value);									//return the value between 0V and 10V from the byte readed by the ADC

//	function str_voltage_0to10V_from_8bit_value_peri_100($ADC_8bit_value);								//return the string with the value between 0V and 10V from the byte readed by the ADC

//	function value_8bit_from_voltage_0to10V_peri_100($voltage_0to10_value);								//return a byte from a value between 0V and 10V


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


function mcu_volt_peri_100($ADC_10bit_value){
	$voltage =  (3.3 * 1024) / $ADC_10bit_value; 
	//$voltage = ceil($voltage);
	return $voltage;
}

function str_mcu_volt_peri_100($ADC_10bit_value){
	$voltage="";
	$voltage_int = mcu_volt_peri_100($ADC_10bit_value);
	$voltage_int = $voltage_int * 100;
	$voltage_int = ceil($voltage_int);
	$voltage_int = $voltage_int / 100;
	$voltage = number_format($voltage_int, 2, '.', ''); 
	return $voltage;
}

function voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value,$MCU_Volts_raw_value){ 
	$vref_adc = mcu_volt_peri_100($MCU_Volts_raw_value);
	$voltage = (($ADC_10bit_value * $vref_adc ) / 1024); 
	//$voltage = ceil($voltage);
	return $voltage;
}

function ADC10bit_from_voltage_0to5V_peri_100($milliVolt,$MCU_Volts_raw_value){			
	$vref_adc = mcu_volt_peri_100($MCU_Volts_raw_value);
	$milliVolt = $milliVolt / 1000;
	$ADC_10bit_value = ($milliVolt * 1024)/$vref_adc;
	return $ADC_10bit_value;
}

function str_voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value,$MCU_Volts_raw_value){
	$voltage="";
	//$voltage=number_format((float)strval(voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value)), 0, '.', ''); 
	$voltage_int = voltage_0to5V_from_10bit_value_peri_100($ADC_10bit_value,$MCU_Volts_raw_value);
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
	return $temperature_int . "." . $temperature_dec;

}

function humidity_DHT11_from_raw_value_peri_100($raw_value){
	$humidity = $raw_value >> 16;
	$humidity_int = $humidity >> 8;
	$humidity_dec = $humidity & 0x00FF;
	return $humidity_int;// . "." . $humidity_dec;
}

function temperature_DHT22_from_raw_value_peri_100($raw_value){
	$temperature = $raw_value & 0x0000FFFF;
	return $temperature/10 ;
}

function humidity_DHT22_from_raw_value_peri_100($raw_value){
	$humidity = $raw_value >> 16;
	return $humidity/10 ;
}

function tempORhumid_DHT11_from_threshold_value_peri_100($raw_value){
	$temperature = $raw_value & 0xFFFF;
	$temperature_int = $temperature >> 8;
	$temperature_dec = $temperature & 0x00FF;
	return $temperature_int . "." . $temperature_dec;

}

function threshold_value_from_tempORhumid_DHT11_peri_100($temperature){
	$temperature_int = intval($temperature, 10);
	$temperature_dec = intval(($temperature * 100), 10); 
	$temperature_dec_sott_int = intval(($temperature_int * 100), 10); 
	$temperature_dec = $temperature_dec - $temperature_dec_sott_int;
	$raw_value = ($temperature_int << 8) | $temperature_dec;
	return $raw_value;

}

function temperature_MCP9701_from_ADC_raw_value_peri_100($ADC_value,$MCU_Volts_raw_value){
	$vref_adc = mcu_volt_peri_100($MCU_Volts_raw_value); //5V
	$volt_for_one_bit = $vref_adc / 1024;
	$temperature = (((($ADC_value)*$volt_for_one_bit) - 0.4) / 0.0195);
	//$temperature = ceil($temperature);
	//$temperature = round($temperature);
	return $temperature;
}


function the_ADC10bit_value_from_temperature_MCP9701_peri_100($temperature, $MCU_Volts_raw_value){ 	//return 10bits on 2bytes of the ADC raw value of the temperature
	$vref_adc = mcu_volt_peri_100($MCU_Volts_raw_value); //5V
	$volt_for_one_bit = $vref_adc / 1024;
	$ADC10bitsValue = (($temperature * 0.0195)+0.4) / $volt_for_one_bit;
	
	return $ADC10bitsValue;
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
