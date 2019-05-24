<?php
/******************************************************************************************
Powered by:			ATIONE
Programmer: 		Emanuele Aimone
Last Update: 		19/06/2017

Description: it is the library with all useful function to use Peri


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

//	function temperature_MCP9701_from_8bit_value_peri8($ADC_8bit_value); 	//return the temperature in °C

//	function the_8bit_value_from_temperature_MCP9701_peri8($tempearure); 	//return the byte of the temperature

//	function voltage_0to10V_from_8bit_value_peri8($ADC_8bit_value);			//return the value between 0V and 10V from the byte readed by the ADC

//	function str_voltage_0to10V_from_8bit_value_peri8($ADC_8bit_value);		//return the string with the value between 0V and 10V from the byte readed by the ADC

//	function value_8bit_from_voltage_0to10V_peri8($voltage_0to10_value);	//return a byte from a value between 0V and 10V


//-------------------------------END FUNCTIONS DESCRIPTIONS----------------------------------//

//-------------------------------BEGIN DEFINE----------------------------------//

//define("DEFAULT_PATH", "NO"); 			//this force the library rfberrypi.php to take the following paths
//define("DIRECTORY_IMG", "/img/"); 		//redefined here for the library rfberrypi.php
//define("DIRECTORY_CSS", "/css/"); 		//redefined here for the library rfberrypi.php

//DEFINES of DIRECTORY
//define("DIRECTORY_IMG_PERI_8", "./lib/peripheral_8/img/"); 		//where all pictures are kept
define("DIRECTORY_CSS_PERI_8", "./lib/peripheral_8/css/"); 		//where all pictures are kept

define("DIRECTORY_IMG_PERI_8", "/img/peripheral/"); 		//where all default pictures for any peripheral are kept
//define("DIRECTORY_CSS_PERI_8", "/css/"); 					//where all default style for any peripheral are kept


define("MAX_TEMPERATURE_MCP9701", 128);
define("MIN_TEMPERATURE_MCP9701", 0);

define("MAX_NUM_OF_DATA_PERI8", 1000);
define("NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI8", 25);
define("PATH_FILE_DATA", "./lib/peripheral_8/data/"); 		//where all pictures are kept


//-------------------------------END DEFINE----------------------------------//

//-------------------------------BEGIN INCLUDE CSS----------------------------------//

//include the CSS
//echo '<link rel="stylesheet" href="./css/peripheral.css" type="text/css" >';
//echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'settings.css" type="text/css" >';

//-------------------------------END INCLUDE CSS----------------------------------//


function temperature_MCP9701_from_8bit_value_peri8($ADC_8bit_value){
	$tempearure = (($ADC_8bit_value * MAX_TEMPERATURE_MCP9701) / 256)-MIN_TEMPERATURE_MCP9701; 
	//$tempearure = ceil($tempearure);
	//$tempearure = round($tempearure);
	return $tempearure;
}

function the_8bit_value_from_temperature_MCP9701_peri8($tempearure){
	$value_8bit = (($tempearure * 256) / MAX_TEMPERATURE_MCP9701)+MIN_TEMPERATURE_MCP9701; 
	//$value_8bit = ceil($value_8bit);
	return $value_8bit;
}

function voltage_0to10V_from_8bit_value_peri8($ADC_8bit_value){
	$voltage = $ADC_8bit_value; 
	$voltage = (($ADC_8bit_value * 10 ) / 256); 
	//$voltage = ceil($voltage);
	return $voltage;
}

function str_voltage_0to10V_from_8bit_value_peri8($ADC_8bit_value){
	$voltage="";
	//$voltage=number_format((float)strval(voltage_0to10V_from_8bit_value_peri8($ADC_8bit_value)), 0, '.', ''); 
	$voltage_int = voltage_0to10V_from_8bit_value_peri8($ADC_8bit_value);
	$voltage_int = $voltage_int * 100;
	$voltage_int = ceil($voltage_int);
	$voltage_int = $voltage_int / 100;
	$voltage = number_format($voltage_int, 2, '.', ''); 
	return $voltage;
}

function value_8bit_from_voltage_0to10V_peri8($voltage_0to10_value){
	$value_8bit = floatval($voltage_0to10_value); 
	$value_8bit = (($voltage_0to10_value * 256) / 10) ; 
	//$value_8bit = ceil($value_8bit);
	//$value_8bit = round($value_8bit);
	$value_8bit = number_format($value_8bit, 0, '.', ''); 
	return $value_8bit;
}


function read_data_peri8($address){
	
		$file_path = PATH_FILE_DATA . $address ."_data.txt";
		//$file_path = ".././data/". $address ."_data.txt";
		$array_data = array();
		$cont_lines=0;
		$cont_data_found=0;
		array_push($array_data, strval($cont_data_found));
		if(file_exists($file_path)===TRUE){ //echo "ciao";
			$handle_file = fopen($file_path, 'r');
			while(feof($handle_file)!==TRUE){ // && $cont_lines<30){ 
				$line_file=fgets($handle_file, NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI8);
				
				if (strpos($line_file,'DATA') !== false) {

					$position1 = strpos($line_file,'=') + 1;
					$position2 = strpos($line_file,'|');
					
					$str_sub_temp = substr($line_file, $position1, ($position2 - $position1));
					
					array_push($array_data, $str_sub_temp);
					
					//$array_data[$cont_lines] = (int)str_replace(' ', '', $str_sub_temp);
					
					$cont_data_found++;
				}
				
				$cont_lines++;
			}
			fclose($handle_file);
		}

	$array_data[0] = strval($cont_data_found);
	
	return $array_data;
	//return $cont_temperature_found;
}

function save_data_peri8($array_data,$lenght_array,$address){
	
	$file_path = "./data/". $address ."_data.txt";
	
	$array_data_file = array();
	$cont_lines=0;
	$cont_data_found=0;
	
	//array_push($array_data_file, strval($cont_data_found));
	if(file_exists($file_path)===TRUE){ //echo "ciao";
		$handle_file = fopen($file_path, 'r');
		while(feof($handle_file)!==TRUE){ // && $cont_lines<MAX_NUM_OF_DATA_PERI8){ 
			$line_file=fgets($handle_file, NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI8);
			array_push($array_data_file, $line_file);
			$data_to_write_into_file .= $line_file;
			$cont_lines++;
		}
		fclose($myfile);	
	}	
	
	
	
	if($cont_lines > MAX_NUM_OF_DATA_PERI8){
		$data_to_write_into_file="";
		$i = $cont_lines - MAX_NUM_OF_DATA_PERI8;
		$j=0;
		while($j<MAX_NUM_OF_DATA_PERI8){
			$data_to_write_into_file .= $array_data_file[$i];
			$i++;
			$j++;
		}
	}
	
		
	
	
	$myfile = fopen($file_path , "wr");
	$i=0;
	//$data_to_write_into_file="";
	while($i<$lenght_array){
		if((int)$array_data[$i]>0){
				//fwrite($myfile, "DATA=" . $array_data[$i] . "|\n");
				//$myfile = file_put_contents($file_path, "DATA=" . $array_data[$i] . "|\n".PHP_EOL , FILE_APPEND);
				$data_to_write_into_file .= "DATA=" . $array_data[$i] . "|\n";
		}
		$i++;
	}
	$myfile = file_put_contents($file_path, $data_to_write_into_file.PHP_EOL , FILE_APPEND);
		
	fclose($myfile);

	
	
	
}



?>
