<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/04/2020

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


//-------------------------------BEGIN INCLUDE--------------------------------------------//

//		library with all useful functions to use RFPI
//		include './../../lib/rfberrypi.php';  

//-------------------------------END INCLUDE----------------------------------------------//


//-------------------------------BEGIN FUNCTIONS DESCRIPTIONS-----------------------------//


//	function printValues($array_conf_json);	// Define recursive function to extract nested values


//-------------------------------END FUNCTIONS DESCRIPTIONS-------------------------------//

//-------------------------------BEGIN DEFINE---------------------------------------------//

//	define("DIRECTORY_IMG_PERI_100", "/img/peripheral/"); 		//where all default pictures for any peripheral are kept

//-------------------------------END DEFINE-----------------------------------------------//

//-------------------------------BEGIN INCLUDE CSS----------------------------------------//

//	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_100 . 'peripheral.css" type="text/css" >';

//-------------------------------END INCLUDE CSS------------------------------------------//




// Define recursive function to extract nested values
function printValues($array_conf_json) {
	global $count;
	global $values;
			
	// Check input is an array
	if(!is_array($array_conf_json)){
		die("ERROR: Input is not an array");
	}
			
			
	//Loop through array, if value is itself an array recursively call the
	//function else add the value found to the output items array,
	//and increment counter by 1 for each value found
			
	foreach($array_conf_json as $key=>$value){
		if(is_array($value)){
			printValues($value);
		} else{
			$values[] = $value;
			$count++;
		}
	}
			
	// Return total count and values found in array
	return array('total' => $count, 'values' => $values);
}


//here the array are filled up with the data taken from the json
function decode_iotgemini_json(
									//variables that are filled up
									&$sem_json_exist, 
									&$sem_RGB_Shield_connected,
									
									&$count_digital_input_json, 
									&$count_digital_output_json, 
									&$count_analogue_input_json, 
									&$count_analogue_output_json, 
									
									&$array_pin_digital_inputs_json,
									&$array_pin_digital_outputs_json,
									&$array_pin_analogue_inputs_json,
									&$array_pin_analogue_outputs_json,
									
									&$array_shield_name_digital_inputs_json,
									&$array_shield_name_digital_outputs_json,
									&$array_shield_name_analogue_inputs_json,
									&$array_shield_name_analogue_outputs_json,
									
									&$array_shield_mpn_digital_inputs_json,
									&$array_shield_mpn_digital_outputs_json,
									&$array_shield_mpn_analogue_inputs_json,
									&$array_shield_mpn_analogue_outputs_json,
									
									&$array_id_digital_outputs_json,
									&$array_id_analogue_outputs_json,
									
									//variables to run the function
									$address_peri,
									$path_conf_json,
									$numInput,
									$numOutput
									
								) {
	
		
	$count_digital_input_json = 0;
	$count_digital_output_json = 0;
	$count_analogue_input_json = 0;
	$count_analogue_output_json = 0;
	
	$sem_RGB_Shield_connected = 0;
	
	
	//$array_pin_digital_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	//$array_pin_digital_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	//$array_pin_analogue_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	//$array_pin_analogue_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		
	$array_pin_digital_inputs_json = [0,0,0,0,0,0,0,0,0];
	$array_pin_digital_outputs_json = [0,0,0,0,0,0,0,0,0];
	$array_pin_analogue_inputs_json = [0,0,0,0,0,0,0,0,0];
	$array_pin_analogue_outputs_json = [0,0,0,0,0,0,0,0,0];
		
		
	$array_shield_name_digital_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_name_analogue_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_name_digital_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_name_analogue_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		
		
	$array_shield_mpn_digital_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_mpn_analogue_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_mpn_digital_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_mpn_analogue_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		
			
	$array_id_digital_outputs_json = [0,0,0,0,0,0,0,0,0];
	$array_id_analogue_outputs_json = [0,0,0,0,0,0,0,0,0];
	
	
	
	
	//$path_conf_json = CONF_PATH . $address_peri . ".json";
	$sem_json_exist=0;

	if (file_exists($path_conf_json) && ($numInput!=0 || $numOutput!=0) ) {
		$sem_json_exist=1; 
	}
	
	if ( $sem_json_exist==1 ) {	
		
		 
		
		$json = "";
		//$path_conf_json = CONF_PATH . $address_peri . ".json";
		if(file_exists($path_conf_json)){
			$myfile = fopen($path_conf_json, 'r');
			while(feof($myfile)!==TRUE){
				$json .= fgets($myfile);
			}
			fclose($myfile);
		
		}else{
			//$_SESSION["language"]="EN";
		}
		
		
		
		// Decode JSON data into PHP associative array format
		$array_conf_json = json_decode($json, true);
		 
		//var_dump($array_conf_json);
		
		
		 
		// Call the function and print all the values
		//$result = printValues($array_conf_json);
		//echo "<h3>" . $result["total"] . " value(s) found: </h3>";
		//echo implode("<br>", $result["values"]); 
		//echo "<hr>";
		 
		// Print a single value
		//echo $array_conf_json["book"]["author"] . "<br>";  // Output: J. K. Rowling
		//echo $array_conf_json["book"]["characters"][0] . "<br>";  // Output: Harry Potter
		//echo $array_conf_json["book"]["price"]["hardcover"] . "<br>";  // Output: $20.32
		
		
		//echo $array_conf_json["MODULE"]["SHIELD_0"]["PINOUT"]["MASK_0"];
		
		//if($array_conf_json["MODULE"]["SHIELD_5"]["PINOUT"]["MASK_0"]) 
		//	echo " ESISTE!"; 
		//else 
		//	echo " NON ESISTE!";

		
		//$array_pin_digital_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		//$array_pin_digital_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		//$array_pin_analogue_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		//$array_pin_analogue_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		
		/*$array_pin_digital_inputs_json = [0,0,0,0,0,0,0,0,0];
		$array_pin_digital_outputs_json = [0,0,0,0,0,0,0,0,0];
		$array_pin_analogue_inputs_json = [0,0,0,0,0,0,0,0,0];
		$array_pin_analogue_outputs_json = [0,0,0,0,0,0,0,0,0];
		
		
		$array_shield_name_digital_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		$array_shield_name_analogue_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		$array_shield_name_digital_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		$array_shield_name_analogue_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		
		
		$array_shield_mpn_digital_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		$array_shield_mpn_analogue_inputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		$array_shield_mpn_digital_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		$array_shield_mpn_analogue_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		
			
		$array_id_digital_outputs_json = [0,0,0,0,0,0,0,0,0];
		$array_id_analogue_outputs_json = [0,0,0,0,0,0,0,0,0];
		*/
		
		$varExit=0;
		$var_shields_count=0;
		//echo $array_conf_json["MODULE"].length;
		for($i=0; $i<100 && $varExit==0; $i++){
			if($array_conf_json["MODULE"]["SHIELD_" . $i]){
				$var_shields_count++;
			}else{
				$varExit=1;
				//echo " NON ESISTE!";
			}
			
		}
		 
		//echo " NUM SHIELDS=".$var_shields_count;


		//going to get the values for each shield
		for($i=0; $i<$var_shields_count; $i++){
			$id_shield = $array_conf_json["MODULE"]["SHIELD_" . $i][ID];
			
			
			
			//parsing data by ID
			if($id_shield==1){ //DIGITAL OUTPUT
				//$array_pin_digital_outputs_json [$count_digital_output_json] = "PIN" . $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				
				$num_pin = $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				$varExit = 0;
				$j=0;
				for($j=0; $j<$count_digital_output_json && $varExit==0 ; $j++){
					if($num_pin < $array_pin_digital_outputs_json[$j]){ //then move all data forward and copy the num of the pin
						$varExit=1;
						for($h=$j; $h<$count_digital_output_json; $h++){
							$array_pin_digital_outputs_json[$h+1] = $array_pin_digital_outputs_json[$h];
							$array_shield_name_digital_outputs_json [$h+1] = $array_shield_name_digital_outputs_json [$h];
							$array_shield_mpn_digital_outputs_json[$h+1] = $array_shield_mpn_digital_outputs_json[$h];
							//$array_id_digital_outputs_json[$h+1] = $array_id_digital_outputs_json[$h];
						}
						$array_pin_digital_outputs_json[$j] = $num_pin;
						$array_shield_name_digital_outputs_json [$j] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
						$array_shield_mpn_digital_outputs_json[$j] = $array_conf_json["MODULE"]["SHIELD_" . $i][MPN];
						//$array_id_digital_outputs_json[$j] = $j;
					}
				}
				if($varExit==0){
					$array_pin_digital_outputs_json[$count_digital_output_json] = $num_pin;
					$array_shield_name_digital_outputs_json [$count_digital_output_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
					$array_shield_mpn_digital_outputs_json[$count_digital_output_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][MPN];
					//$array_id_digital_outputs_json[$count_digital_output_json] = $count_digital_output_json;
				}
				
				
				$count_digital_output_json++;
				
			}else if($id_shield==2 || $id_shield==7){ //DIGITAL INPUT
				//$array_pin_digital_inputs_json [$count_digital_input_json]  = "PIN" . $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				//$array_shield_name_digital_inputs_json [$count_digital_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
				
				$num_pin = $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				$varExit = 0;
				$j=0;
				for($j=0; $j<$count_digital_input_json && $varExit==0 ; $j++){
					if($num_pin < $array_pin_digital_inputs_json[$j]){ //then move all data forward and copy the num of the pin
						$varExit=1;
						for($h=$j; $h<$count_digital_input_json; $h++){
							$array_pin_digital_inputs_json[$h+1] = $array_pin_digital_inputs_json[$h];
							$array_shield_name_digital_inputs_json [$h+1] = $array_shield_name_digital_inputs_json [$h];
							$array_shield_mpn_digital_inputs_json[$h+1] = $array_shield_mpn_digital_inputs_json[$h];
							//$array_id_digital_outputs_json[$h+1] = $array_id_digital_outputs_json[$h];
						}
						$array_pin_digital_inputs_json[$j] = $num_pin;
						$array_shield_name_digital_inputs_json [$j] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
						$array_shield_mpn_digital_inputs_json[$j] = $array_conf_json["MODULE"]["SHIELD_" . $i][MPN];
						//$array_id_digital_outputs_json[$j] = $j;
					}
				}
				if($varExit==0){
					$array_pin_digital_inputs_json[$count_digital_input_json] = $num_pin;
					$array_shield_name_digital_inputs_json [$count_digital_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
					$array_shield_mpn_digital_inputs_json[$count_digital_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][MPN];
					//$array_id_digital_outputs_json[$count_digital_input_json] = $count_digital_input_json;
				}
				
				$count_digital_input_json++;
				
			}else if($id_shield==3 || $id_shield==6){ //ANALOGUE INPUT
				//$array_pin_analogue_inputs_json [$count_analogue_input_json] = "PIN" . $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				//$array_shield_name_analogue_inputs_json [$count_analogue_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
				
				$num_pin = $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				$varExit = 0;
				$j=0;
				for($j=0; $j<$count_analogue_input_json && $varExit==0 ; $j++){
					if($num_pin < $array_pin_analogue_inputs_json[$j]){ //then move all data forward and copy the num of the pin
						$varExit=1;
						for($h=$j; $h<$count_analogue_input_json; $h++){
							$array_pin_analogue_inputs_json[$h+1] = $array_pin_analogue_inputs_json[$h];
							$array_shield_name_analogue_inputs_json [$h+1] = $array_shield_name_analogue_inputs_json [$h];
							$array_shield_mpn_analogue_inputs_json[$h+1] = $array_shield_mpn_analogue_inputs_json[$h];
							//$array_id_digital_outputs_json[$h+1] = $array_id_digital_outputs_json[$h];
						}
						$array_pin_analogue_inputs_json[$j] = $num_pin;
						$array_shield_name_analogue_inputs_json [$j] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
						$array_shield_mpn_analogue_inputs_json[$j] = $array_conf_json["MODULE"]["SHIELD_" . $i][MPN];
						//$array_id_digital_outputs_json[$j] = $j;
					}
				}
				if($varExit==0){
					$array_pin_analogue_inputs_json[$count_analogue_input_json] = $num_pin;
					$array_shield_name_analogue_inputs_json [$count_analogue_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
					$array_shield_mpn_analogue_inputs_json[$count_analogue_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][MPN];
					//$array_id_digital_outputs_json[$count_analogue_input_json] = $count_analogue_input_json;
				}
				
				$count_analogue_input_json++;
				
			}else if($id_shield==4){ //RGB SHIELD
				$sem_RGB_Shield_connected = 1;
				$array_pin_analogue_outputs_json [$count_analogue_output_json] = "PIN" . $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				$array_shield_name_analogue_outputs_json [$count_analogue_output_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
				//$array_id_analogue_outputs_json [$count_analogue_output_json] = $count_analogue_input_json + $count_analogue_output_json;
				$count_analogue_output_json++;
				$array_pin_analogue_outputs_json [$count_analogue_output_json] = "PIN" . $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_1];
				$array_shield_name_analogue_outputs_json [$count_analogue_output_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
				//$array_id_analogue_outputs_json [$count_analogue_output_json] = $count_analogue_input_json + $count_analogue_output_json;
				$count_analogue_output_json++;
				$array_pin_analogue_outputs_json [$count_analogue_output_json] = "PIN" . $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_2];
				$array_shield_name_analogue_outputs_json [$count_analogue_output_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
				//$array_id_analogue_outputs_json [$count_analogue_output_json] = $count_analogue_input_json + $count_analogue_output_json;
				$count_analogue_output_json++;
				
			}else if($id_shield==5 || $id_shield==8){ //ANALOGUE INPUT DHT11 and for $id_shield==8 is the DHT22 sensor
				//$array_pin_analogue_outputs_json [$count_analogue_input_json] = "PIN" . $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				//$array_shield_name_analogue_inputs_json [$count_analogue_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
				//$array_id_analogue_outputs_json [$count_analogue_input_json] = $count_analogue_input_json + $count_analogue_input_json;
				
				$num_pin = $array_conf_json["MODULE"]["SHIELD_" . $i][PINOUT][PIN_0];
				$varExit = 0;
				$j=0;
				for($j=0; $j<$count_analogue_input_json && $varExit==0 ; $j++){
					if($num_pin < $array_pin_analogue_inputs_json[$j]){ //then move all data forward and copy the num of the pin
						$varExit=1;
						for($h=$j; $h<$count_analogue_input_json; $h++){
							$array_pin_analogue_inputs_json[$h+1] = $array_pin_analogue_inputs_json[$h];
							$array_shield_name_analogue_inputs_json [$h+1] = $array_shield_name_analogue_inputs_json [$h];
							$array_shield_mpn_analogue_inputs_json[$h+1] = $array_shield_mpn_analogue_inputs_json[$h];
						}
						$array_pin_analogue_inputs_json[$j] = $num_pin;
						$array_shield_name_analogue_inputs_json [$j] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
						$array_shield_mpn_analogue_inputs_json[$j] = $array_conf_json["MODULE"]["SHIELD_" . $i][MPN];
					}
				}
				if($varExit==0){
					$array_pin_analogue_inputs_json[$count_analogue_input_json] = $num_pin;
					$array_shield_name_analogue_inputs_json [$count_analogue_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][NAME];
					$array_shield_mpn_analogue_inputs_json[$count_analogue_input_json] = $array_conf_json["MODULE"]["SHIELD_" . $i][MPN];
				}
				
				$count_analogue_input_json++;
			}
		}
	
	}//END json exist

	
}




?>
