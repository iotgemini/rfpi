<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/07/2019

Description: it is the library to build the control panel for the 100th peripheral


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



//-------------------------------BEGIN FUNCTIONS DESCRIPTIONS----------------------------------//

//		function peripheral_100($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput);
												
												
//--------------------------------END FUNCTIONS DESCRIPTIONS-----------------------------------//


//---------------------------------BEGIN INCLUDE-------------------------------------------//
//		Specific library for the Peri_100
		include './lib/peripheral_100/peri_100_lib.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//





function peripheral_100($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){


	//---------------------------------------------------------------------------------------//
	//		Strings for languages

	$lang_btn_load_json = "Load Json";
	$lang_btn_build_json = "Build Json";
	$lang_btn_trigger = "Temperature";
	$lang_btn_input = "Input";
	$lang_temperature="Temperature: ";
	$lang_btn_thermostat="Thermostat";
	$lang_btn_rgb="Set RGB";
	$lang_msg_turn_on="Turn ON";
	$lang_msg_turn_off="Turn OFF";
	if($_SESSION["language"]=="IT"){
		$lang_btn_load_json = "Carica Json";
		$lang_btn_build_json = "Costruisci Json";
		$lang_btn_trigger = "Temperatura";
		$lang_btn_input = "Entrate";
		$lang_temperature="Temperatura: ";
		$lang_btn_thermostat="Termostato";
	}else if($_SESSION["language"]=="FR"){
		$lang_btn_load_json = "Charge Json";
		$lang_btn_build_json = "Costruire Json";
		$lang_btn_trigger = "Temp&eacute;rature";
		$lang_btn_input = "Entr&eacute;e";
		$lang_temperature="Temp&eacute;rature: ";
		$lang_btn_thermostat="Thermostat";
	}else if($_SESSION["language"]=="SP"){
		$lang_btn_load_json = "Carga Json";
		$lang_btn_build_json = "Costruir Json";
		$lang_btn_trigger = "Temperatura";
		$lang_btn_input = "Entrada";
		$lang_temperature="Temperature: ";
		$lang_btn_thermostat="Termostato";
	}

	//---------------------------------------------------------------------------------------//

	$array_input_to_show = array();
	$array_output_to_show = array();
	$array_function_to_show = array();
	$array_input_formula_to_show = array();
	create_array_from_config_file($address_peri, $idperipheral, 
									$array_input_to_show, 
									$array_output_to_show, 
									$array_function_to_show, 
									$array_input_formula_to_show
									);
									
	
	
	
/************************************* BEGIN: DECODE JSON FILE *************************************/
	
	
	$count_digital_input_json = 0;
	$count_digital_output_json = 0;
	$count_analogue_input_json = 0;
	$count_analogue_output_json = 0;
	
	$sem_RGB_Shield_connected = 0;
	
	
	
	$path_conf_json = CONF_PATH . $address_peri . ".json";
	$sem_json_exist=0;

	if (file_exists($path_conf_json) && ($numInput!=0 || $numOutput!=0) ) {
		$sem_json_exist=1;
	}
	
	if ( $sem_json_exist==1 ) {	
	// Define recursive function to extract nested values
	function printValues($array_conf_json) {
		global $count;
		global $values;
		
		// Check input is an array
		if(!is_array($array_conf_json)){
			die("ERROR: Input is not an array");
		}
		
		/*
		Loop through array, if value is itself an array recursively call the
		function else add the value found to the output items array,
		and increment counter by 1 for each value found
		*/
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
	
	
	/*echo $array_conf_json["MODULE"]["SHIELD_0"]["PINOUT"]["MASK_0"];
	
	if($array_conf_json["MODULE"]["SHIELD_5"]["PINOUT"]["MASK_0"]) 
		echo " ESISTE!"; 
	else 
		echo " NON ESISTE!";
	*/
	
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
			
		}else if($id_shield==2){ //DIGITAL INPUT
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
			
		}else if($id_shield==3){ //ANALOGUE INPUT
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
			
		}else if($id_shield==5){ //ANALOGUE INPUT DHT11
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

	//echo '$count_analogue_input_json='.$count_analogue_input_json;
	//echo '<br>';
	//echo 'numInput='.$numInput;
	//echo '<br>';
	//echo 'numOutput='.$numOutput;
	
/************************************* END: DECODE JSON FILE *************************************/
	
	
	
	
	
	
	
	
	//echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_100 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td align=center>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'ant_grey.png" class="img_ant" alt="'.DEFINE_lang_msg_no_communication.'"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'ant_green.png" class="img_ant" alt="'.$lang_msg_connected.'"> ';
	}
	echo '<br>';
	echo '<br>';
	
	//echo '</td>';
	//echo '<td>&nbsp';
	echo $name;
	echo '</td>';
	
	echo '</tr></table>';
	echo '</td>';  
				
				
				

/**************************************** BEGIN: PRINTING INPUTS	****************************************/
	//print the name of the input and the status
	echo '<td>';
	
	if($sem_json_exist==1){
	//begin to print the digital input
	$l=0;
	$counter=0;
	while ($l<$count_digital_input_json) { 

			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo $array_shield_name_digital_inputs_json[$l] . ' ';
			echo "PIN" . $array_pin_digital_inputs_json[$l].' ';
			echo 'ID' . $counter;
			echo '<br>';
			/*if($arrayStatusInput[$counter]==-1){
				echo ' = &#63;';
			}else{	
				echo ' = ';
				echo $arrayStatusInput[$counter];
			}*/
			echo '<h2>';
			if($arrayStatusInput[$counter]==1){
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
				//echo ' = 1';
			}else if($arrayStatusInput[$counter]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
				//echo ' = 0';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
				//echo ' = &#63;';
			}
			echo '</h2>';
			echo '</div>';
	

		$l++;
		$counter++;
		
	}
	//printing the analogue input
	$l=0;
	while ($l<$count_analogue_input_json) { 

			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo $array_shield_name_analogue_inputs_json[$l] . ' ';
			echo "PIN" . $array_pin_analogue_inputs_json[$l].' ';
			echo 'ID' . $counter;
			
			echo '<br>';
			
			if($arrayStatusInput[$counter]==-1){
				echo '&#63;';
			}else{	
				//echo ' = ';
				if($array_shield_mpn_analogue_inputs_json[$l]==="MCP9701A"){
					$temperature = temperature_MCP9701_from_ADC_raw_value_peri_100($arrayStatusInput[$counter]);
					echo '<h2>';
					echo number_format((float)strval($temperature), 1, '.', '');
					echo '&nbsp&#176C&nbsp'; //°C
					echo '</h2>';
				}else if($array_shield_mpn_analogue_inputs_json[$l]==="DHT11"){
					echo '<h2>';
					echo umidity_DHT11_from_raw_value_peri_100($arrayStatusInput[$counter]);
					echo ' %<br>';
					echo temperature_DHT11_from_raw_value_peri_100($arrayStatusInput[$counter]);
					echo '&nbsp&#176C&nbsp'; //°C
					echo '</h2>';
				}else{
					echo '<h2>';
					echo $arrayStatusInput[$counter];
					echo '</h2>';
				}
			}
			
			//echo '<br>';
			//echo '(' . $array_pin_analogue_inputs_json[$l].' ';
			//echo 'ID' . $counter . ')';
			
			echo '</div>';

		$l++;
		$counter++;
		
	}
	}//END json exist
	
	echo '</td>';
/**************************************** END: PRINTING INPUS	****************************************/



/**************************************** BEGIN: PRINTING OUTPUS	****************************************/
	
				
	//print the name of the output and the status
	echo '<td>';
	
	if($sem_json_exist==1){
	$l=0;
	$counter=0;
	while ($l<$count_digital_output_json) {
		

			echo '<script type="text/JavaScript">';
			echo 'function change' . $id . '_' . $counter . '(value){';
			echo 'document.set_' . $id . '_output_' . $counter . '.output_value.value=value;';
			echo 'document.set_' . $id . '_output_' . $counter . '.submit();';
			echo '}';
			echo '</script>';
		
			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo $array_shield_name_digital_outputs_json[$l] . ' ';
			echo "PIN" . $array_pin_digital_outputs_json[$l].' ';
			echo 'ID' . $counter;
			
			echo '<form name="set_' . $id . '_output_' . $counter . '" action="set_output.php" method=GET>';

			echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
			echo '<input type=hidden name="output_id" value="' . $counter . '">';

			echo '<input type=hidden name="output_value" value="' . $arrayStatusOutput[$counter] . '">';
			if($arrayStatusOutput[$counter]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'switch_off.png" onclick="change' . $id . '_' . $counter . '(1)"  class="img_switch_off" alt="'.$lang_msg_turn_on.'"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'switch_on.png" onclick="change' . $id . '_' . $counter . '(0)"  class="img_switch_on" alt="'.$lang_msg_turn_off.'"> ';
			}

			if($arrayStatusOutput[$counter]==1){
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
			}else if($arrayStatusOutput[$counter]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
			}

			echo '</form>';
			echo '</div>';

		$l++;
		$counter++;
	}
	
	
	//printing analogue outputs
	$l=0;
	while ($l<$count_analogue_output_json && $sem_RGB_Shield_connected == 0) {
		

			echo '<script type="text/JavaScript">';
			echo 'function change' . $id . '_' . $counter . '(value){';
			echo 'document.set_' . $id . '_output_' . $counter . '.output_value.value=value;';
			echo 'document.set_' . $id . '_output_' . $counter . '.submit();';
			echo '}';
			echo '</script>';
		
			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo $array_shield_name_analogue_outputs_json[$l] . ' ';
			echo "PIN" . $array_pin_analogue_outputs_json[$l].' ';
			echo 'ID' . $counter;
			
			echo '<form name="set_' . $id . '_output_' . $counter . '" action="set_output.php" method=GET>';

			echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
			echo '<input type=hidden name="output_id" value="' . $counter . '">';

			if($sem_RGB_Shield_connected == 0){
				echo '<input type=text name="output_value" value="' . $arrayStatusOutput [$counter] . '" class="text_value_io">';
				echo '<input type=submit value="Set Output">';
			}

			echo '</form>';
			echo '</div>';

		$l++;
		$counter++;
	}
	
	
	if($sem_RGB_Shield_connected == 1){	//if the first output is analogue and there are other 2 output analogue then is the RGB shield connected
		echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
		echo '<form name="peri_100_btn_rgb_functions_'.$id.'" action="./lib/peripheral_100/cmd_send_rgb_data.php" method=GET>';
		$value_hex_RED_LED =  convert_byte_to_2ChrHex($arrayStatusOutput[$numOutput-3]); //"00";
		$value_hex_GREEN_LED = convert_byte_to_2ChrHex($arrayStatusOutput[$numOutput-1]); //"00";
		$value_hex_BLUE_LED = convert_byte_to_2ChrHex($arrayStatusOutput[$numOutput-2]); //"00";
		
		echo '<h2>RGB<br>';
		echo '  <input type="color" name="favcolor" value="#' . $value_hex_RED_LED . $value_hex_GREEN_LED . $value_hex_BLUE_LED .'" onchange="change_RGB_'.$id.'(0)">';
		echo '</h2>';
		echo 'RED PIN3 ID' . $count_digital_input_json . '<br>';
		echo 'BLUE PIN6 ID' . ($count_digital_input_json+1) . '<br>';
		echo 'GREEN PIN9 ID' . ($count_digital_input_json+2) ;

		$byte_to_convert=$numOutput-3;
		$id_hex_special_function = convert_byte_to_2ChrHex($byte_to_convert); //"00"; //hexadecimal format. example 0x02 as to be written as "02"
		//echo $id_hex_special_function;
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=hidden name="id_hex_special_function" value="'.$id_hex_special_function.'">'; 
		echo '<input type=hidden name="TAG0" value="DATA">'; 				//Command
		echo '<input type=hidden name="TAG1" value="RF">'; 					//second parameter
		echo '<input type=hidden name="TAG2" value="'.$address_peri.'">';	//third parameter
		
		$str_TAG3 = "52426F" . $id_hex_special_function . "0018" . $value_hex_RED_LED . $value_hex_GREEN_LED . $value_hex_BLUE_LED . "2E2E2E2E2E2E2E"; 
		echo '<input type=hidden name="TAG3" value="'.$str_TAG3.'">';		//fourth parameter
		echo '<input type=hidden name="page_to_show_data" value="login.php">';
		//echo '<input type=text name="VALUECOLOR" value="'.$address_peri.'"><br>';	
		echo '<input type=hidden name="REDCOLOR" value="'.$value_hex_RED_LED.'">';	
		echo '<input type=hidden name="GREENCOLOR" value="'.$value_hex_GREEN_LED.'">';	
		echo '<input type=hidden name="BLUECOLOR" value="'.$value_hex_BLUE_LED.'">';
		
		//echo '<input type=button value="'.$lang_btn_rgb.'" onclick="change_RGB_'.$id.'(0)" class="btn_functions">';
		
		echo '<script type="text/JavaScript">';
		echo 'function change_RGB_'.$id.'(value){';
		echo 'var selected_color = document.peri_100_btn_rgb_functions_'.$id.'.favcolor.value;';
		echo 'selected_color = selected_color.toUpperCase();';
		echo 'var value_hex_RED_LED = selected_color[1]+selected_color[2];';
		echo 'var value_hex_GREEN_LED = selected_color[3]+selected_color[4];';
		echo 'var value_hex_BLUE_LED = selected_color[5]+selected_color[6];';
		//echo 'document.peri_100_btn_rgb_functions_'.$id.'.VALUECOLOR.value = selected_color;';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.REDCOLOR.value = value_hex_RED_LED;';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.GREENCOLOR.value = value_hex_GREEN_LED;';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.BLUECOLOR.value = value_hex_BLUE_LED;';
		echo 'var value_TAG3 = "52426F' . $id_hex_special_function . '0018"+value_hex_RED_LED+value_hex_GREEN_LED+value_hex_BLUE_LED+"2E2E2E2E2E2E2E";';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.TAG3.value = value_TAG3;';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.submit();';
		echo '}';
		echo '</script>';
		echo '</form>';
		echo '</div>';
	}
	}//END json exist
	
	echo '</td>';
	
	
/**************************************** END: PRINTING OUTPUS	****************************************/


	//special functions
	echo '<td>';
	
	echo '<br>';
	
	//here the special functions
	
	if($sem_json_exist==0){ //if($numInput==0 && $numOutput==0) { 
	if($array_function_to_show[0]==1){
	
	//Button to load json configurations
	echo '<form name="peri_100_btn_load_json_'.$id.'" action="./lib/peripheral_100/select_file_to_upload.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	$id_hex_special_function = "00"; //hexadecimal format. example 0x02 as to be written as "02"
	echo '<input type=hidden name="id_hex_special_function" value="'.$id_hex_special_function.'">'; 
	echo '<input type=hidden name="TAG0" value="SENDJSONSETTINGS">'; 				//Command
	echo '<input type=hidden name="TAG1" value="'.$address_peri.'">'; 					//second parameter
	echo '<input type=hidden name="TAG2" value="'.FIFO_PATH.'">';	//third parameter
	$str_TAG3 = "NULL"; 
	echo '<input type=hidden name="TAG3" value="'.$str_TAG3.'">';		//fourth parameter
	echo '<input type=hidden name="page_to_show_data" value="show_settings_fifo_timer.php">';
	echo '<input type=submit value="'.$lang_btn_load_json.'" class="btn_functions">';
	echo '</form>';
	
	//Button to build json configurations
	echo '<a href="http://www.iotgemini.com/conf" target="_blank" class="btn_functions">'.$lang_btn_build_json.'</a>';
	echo '<br><br>';
	
	
	}
	}
	
	
	
	/*if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
	
	
  echo '<form name="upload_json" method="POST" action="./lib/peripheral_100/upload.php" enctype="multipart/form-data">';
  echo '  <div>';
  echo '    <span>Upload a File:</span>';
  echo '    <input type="file" name="uploadedFile" />';
  echo '  </div>';
  echo '  <input type="submit" name="uploadBtn" value="Upload" />';
  echo '</form>';
	*/
	
	echo '</td>';
}

?>