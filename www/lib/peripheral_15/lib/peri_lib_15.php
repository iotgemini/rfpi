<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/04/2025

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

//	function temperature_MCP9701_from_8bit_value_peri_15($ADC_8bit_value); 	//return the temperature in �C

//	function the_8bit_value_from_temperature_MCP9701_peri_15($tempearure); 	//return the byte of the temperature

//	function voltage_0to10V_from_8bit_value_peri_15($ADC_8bit_value);			//return the value between 0V and 10V from the byte readed by the ADC

//	function str_voltage_0to10V_from_8bit_value_peri_15($ADC_8bit_value);		//return the string with the value between 0V and 10V from the byte readed by the ADC

//	function value_8bit_from_voltage_0to10V_peri_15($voltage_0to10_value);	//return a byte from a value between 0V and 10V


//-------------------------------END FUNCTIONS DESCRIPTIONS----------------------------------//

//-------------------------------BEGIN DEFINE----------------------------------//

//define("DEFAULT_PATH", "NO"); 			//this force the library rfberrypi.php to take the following paths
//define("DIRECTORY_IMG", "/img/"); 		//redefined here for the library rfberrypi.php
//define("DIRECTORY_CSS", "/css/"); 		//redefined here for the library rfberrypi.php

//DEFINES of DIRECTORY
//define("DIRECTORY_IMG_PERI_15", "./lib/peripheral_15/img/"); 		//where all pictures are kept
//define("DIRECTORY_CSS_PERI_15", "./lib/peripheral_15/css/"); 		//where all pictures are kept

define("DIRECTORY_IMG_PERI_15", "/img/peripheral/"); 		//where all default pictures for any peripheral are kept
define("DIRECTORY_CSS_PERI_15", "/css/"); 					//where all default style for any peripheral are kept

define("MAX_TEMPERATURE_MCP9701", 128); //ok for old FW1
define("MIN_TEMPERATURE_MCP9701", 0); //ok for old FW1

//define("MAX_TEMPERATURE_MCP9701", 125); //FW2
//define("MIN_TEMPERATURE_MCP9701", -10); //FW2

define("MAX_VOLTAGE_ADC_INPUT", 5);

//this selct the formula to use. 1=FW_v1, 2=FW_v2
//define("FORMULA_TO_USE_BASED_ON_FW_VERSION", 1);

//-------------------------------END DEFINE----------------------------------//

//-------------------------------BEGIN INCLUDE CSS----------------------------------//

//include the CSS
//echo '<link rel="stylesheet" href="./css/peripheral.css" type="text/css" >';
//echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'settings.css" type="text/css" >';

//-------------------------------END INCLUDE CSS----------------------------------//

function mcu_volt_peri_15($ADC_10bit_value){
	$voltage =  (3.3 * 1024) / $ADC_10bit_value; 
	//$voltage = ceil($voltage);
	return $voltage;
}

function temperature_MCP9701_from_ADC_raw_value_peri_15($ADC_value,$MCU_Volts_raw_value){
	$vref_adc = mcu_volt_peri_15($MCU_Volts_raw_value); //~5V
	$volt_for_one_bit = $vref_adc / 1024;
	$temperature = (((($ADC_value)*$volt_for_one_bit) - 0.4) / 0.0195);
	//$temperature = ceil($temperature);
	//$temperature = round($temperature);
	return $temperature;
}

function voltage_0to5V_from_10bit_value_peri_15_ref($ADC_10bit_value,$MCU_Volts_raw_value){ 
	$vref_adc = mcu_volt_peri_15($MCU_Volts_raw_value);
	$voltage = (($ADC_10bit_value * $vref_adc ) / 1024); 
	//$voltage = ceil($voltage);
	return $voltage;
}


function ADC10bit_from_voltage_0to5V_peri_15($milliVolt,$MCU_Volts_raw_value){			
	$vref_adc = mcu_volt_peri_15($MCU_Volts_raw_value);
	$milliVolt = $milliVolt / 1000;
	$ADC_10bit_value = ($milliVolt * 1024)/$vref_adc;
	return $ADC_10bit_value;
}

function str_voltage_0to5V_from_10bit_value_peri_15_ref($ADC_10bit_value,$MCU_Volts_raw_value){
	$voltage="";
	//$voltage=number_format((float)strval(voltage_0to5V_from_10bit_value_peri_15($ADC_10bit_value)), 0, '.', ''); 
	$voltage_int = voltage_0to5V_from_10bit_value_peri_15_ref($ADC_10bit_value,$MCU_Volts_raw_value);
	$voltage_int = $voltage_int * 100;
	$voltage_int = ceil($voltage_int);
	$voltage_int = $voltage_int / 100;
	$voltage = number_format($voltage_int, 2, '.', ''); 
	return $voltage;
}



function temperature_MCP9701_from_8bit_value_peri_15($ADC_8bit_value){
	$tempearure = (($ADC_8bit_value * MAX_TEMPERATURE_MCP9701) / 256)-MIN_TEMPERATURE_MCP9701; //ok for old FW1
//$tempearure =$ADC_8bit_value;
	//$tempearure = ((($ADC_8bit_value-20) * (MAX_TEMPERATURE_MCP9701+10)) / (144-20))-MIN_TEMPERATURE_MCP9701; //ok for old FW
	//$tempearure = (((($ADC_8bit_value)*0.01953125) - 0.4) / 0.0195);
		//$tempearure = $ADC_8bit_value-20; //new for FW2 but bo!
	//$tempearure = ceil($tempearure);
	//$tempearure = round($tempearure);
	return $tempearure;
}

function temperature_MCP9701_from_8bit_value_peri_15_FW2($ADC_8bit_value){
	//$volt_for_one_bit = 5 / 1024;
	//$tempearure = (((($ADC_10bit_value)*volt_for_one_bit) - 0.4) / 0.0195);
	$volt_for_one_bit = 5 / 256;
	$tempearure = (((($ADC_8bit_value)*$volt_for_one_bit) - 0.4) / 0.0195);
	//$tempearure = ceil($tempearure);
	//$tempearure = round($tempearure);
	
	return $tempearure;
}

function temperature_MCP9701_from_10bit_value_peri_15_FW2($ADC_10bit_value){
	$volt_for_one_bit = 5 / 1024;	
	$tempearure = (((($ADC_10bit_value)*volt_for_one_bit) - 0.4) / 0.0195);
	//$tempearure = ceil($tempearure);
	//$tempearure = round($tempearure);
	
	return $tempearure;
}

function the_8bit_value_from_temperature_MCP9701_peri_15($tempearure){
	$value_8bit = (($tempearure * 256) / MAX_TEMPERATURE_MCP9701)+MIN_TEMPERATURE_MCP9701; 
//$value_8bit =$tempearure;
	//$value_8bit = ceil($value_8bit);
	return $value_8bit;
}

function the_8bit_value_from_temperature_MCP9701_peri_15_FW2($tempearure){
	$volt_for_one_bit = 5 / 256;
	$value_8bit =  (($tempearure * 0.0195) + 0.4) / $volt_for_one_bit; 
	//$value_8bit = ceil($value_8bit);
	return $value_8bit;
}

function voltage_0to10V_from_8bit_value_peri_15($ADC_8bit_value){
	$voltage = $ADC_8bit_value; 
	$voltage = (($ADC_8bit_value * MAX_VOLTAGE_ADC_INPUT ) / 256); 
	//$voltage = ceil($voltage);
	return $voltage;
}

function str_voltage_0to10V_from_8bit_value_peri_15($ADC_8bit_value){
	$voltage="";
	//$voltage=number_format((float)strval(voltage_0to10V_from_8bit_value_peri_15($ADC_8bit_value)), 0, '.', ''); 
	$voltage_int = voltage_0to10V_from_8bit_value_peri_15($ADC_8bit_value);
	$voltage_int = $voltage_int * 100;
	$voltage_int = ceil($voltage_int);
	$voltage_int = $voltage_int / 100;
	$voltage = number_format($voltage_int, 2, '.', ''); 
	return $voltage;
}


function value_8bit_from_voltage_0to10V_peri_15($voltage_0to10_value){
	$value_8bit = floatval($voltage_0to10_value); 
	$value_8bit = (($voltage_0to10_value * 256) / MAX_VOLTAGE_ADC_INPUT) ; 
	//$value_8bit = ceil($value_8bit);
	//$value_8bit = round($value_8bit);
	$value_8bit = number_format($value_8bit, 0, '.', ''); 
	return $value_8bit;
}



function voltage_0to5V_from_10bit_value_peri_15($ADC_10bit_value){
	$voltage = $ADC_10bit_value; 
	$voltage = (($ADC_10bit_value * MAX_VOLTAGE_ADC_INPUT ) / 1024); 
	//$voltage = ceil($voltage);
	return $voltage;
}

function str_voltage_0to5V_from_10bit_value_peri_15($ADC_10bit_value){
	$voltage="";
	$voltage_int = voltage_0to5V_from_10bit_value_peri_15($ADC_10bit_value);
	$voltage_int = $voltage_int * 100;
	$voltage_int = ceil($voltage_int);
	$voltage_int = $voltage_int / 100;
	$voltage = number_format($voltage_int, 2, '.', ''); 
	return $voltage;
}



/*function temperature_pyrometer_peri_15($ADC_8bit_value){
	$temperature = (($ADC_8bit_value * (1000-250) ) / 255)+250;
		
	return $temperature;
}


function str_temperature_pyrometer_peri_15($ADC_8bit_value){
	$temperature = temperature_pyrometer_peri_15($ADC_8bit_value);
				
	$str_temperature="";
	$temperature = $temperature * 100;
	$temperature = ceil($temperature);
	$temperature = $temperature / 100;
	$str_temperature = number_format($temperature, 0, '.', ''); 
	
	$str_to_return = "<br>PYROMETER: ";
	$str_to_return .= $str_temperature;
	$str_to_return .= '&nbsp&#176C&nbsp'; //�C
	
	return $str_to_return;
}
*/

/*
http://stackoverflow.com/questions/1619265/how-to-round-up-a-number-to-nearest-10
floor() will go down.

ceil() will go up.

round() will go to nearest by default.

Divide by 10, do the ceil, then multiply by 10 to reduce the significant digits.

$number = ceil($input / 10) * 10;
*/



?>
