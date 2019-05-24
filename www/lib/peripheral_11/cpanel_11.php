<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/12/2017

Description: it is the library to build the control panel for the 11th peripheral


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

//		function peripheral_11($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput);
												
												
//--------------------------------END FUNCTIONS DESCRIPTIONS-----------------------------------//


//---------------------------------BEGIN INCLUDE-------------------------------------------//
//		Specific library for the Peri_11
		include './lib/peripheral_11/peri_11_lib.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//





function peripheral_11($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){


	//---------------------------------------------------------------------------------------//
	//		Strings for languages

	$lang_btn_timer = "Timer";
	$lang_btn_trigger = "Temperature";
	$lang_btn_input = "Input";
	$lang_temperature="Temperature: ";
	$lang_btn_thermostat="Thermostat";
	if($_SESSION["language"]=="IT"){
		$lang_btn_timer = "Temporizzatore";
		$lang_btn_trigger = "Temperatura";
		$lang_btn_input = "Entrate";
		$lang_temperature="Temperatura: ";
		$lang_btn_thermostat="Termostato";
	}else if($_SESSION["language"]=="FR"){
		$lang_btn_timer = "Minuteur";
		$lang_btn_trigger = "Temp&eacute;rature";
		$lang_btn_input = "Entr&eacute;e";
		$lang_temperature="Temp&eacute;rature: ";
		$lang_btn_thermostat="Thermostat";
	}else if($_SESSION["language"]=="SP"){
		$lang_btn_timer = "Temporizador";
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
									
	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_11 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td align=center>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'ant_grey.png" class="img_ant" alt="'.DEFINE_lang_msg_no_communication.'"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'ant_green.png" class="img_ant" alt="'.$lang_msg_connected.'"> ';
	}
	echo '<br>';
	echo '<br>';
	
	//echo '</td>';
	//echo '<td>&nbsp';
	echo $name;
	echo '</td>';
	
	echo '</tr></table>';
	echo '</td>';  
				
	//print the name of the input and the status
	echo '<td>';
	$l=0;
	while ($l<$numInput) { 

		if(($l==0) && $array_input_to_show[$l]==1){
			
			for($y=1,$c=0;$c<8;$y=$y*2,$c++){
				echo 'IN' . $c;
				if($arrayStatusInput[$l] < 0){
					//echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
					echo '= &#63;';
				}else if(($arrayStatusInput[$l] & $y) == $y){
					//echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
					echo '= 1';
				}else if(($arrayStatusInput[$l] & $y) == 0){
					//echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
					echo '= 0';
				}
				echo '<br>';
			}
	
		}
		$l++;
		
	}
	echo '</td>';
	
				
	//print the name of the output and the status
	echo '<td>';
	$l=0;
	while ($l<$numOutput) {
		//echo $arrayNameOutput [$l];
		//echo ':';

		echo '<script type="text/JavaScript">';
		echo 'function change' . $id . '_' . $l . '(value){';
		echo 'document.set_' . $id . '_output_' . $l . '.output_value.value=value;';
		echo 'document.set_' . $id . '_output_' . $l . '.submit();';
		echo '}';
		echo '</script>';
			
		if(($l==0) && $array_output_to_show[$l]==1){
			
			echo '<form name="set_' . $id . '_output_' . $l . '" action="set_output.php" method=GET>';
			echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
			echo '<input type=hidden name="output_id" value="' . $l . '">';
			echo '<input type=hidden name="output_value" value="' . $arrayStatusOutput[$l] . '">';
		
			echo '<table>';	
			for($y=1,$c=0;$c<8;$y=$y*2,$c++){
				if($c==0){
					echo '<tr>';	
				}
				if($c==4){
					echo '</tr>';
					echo '<tr>';	
				}
			
				echo '<td>';
				echo '<br>';
				//prtinting the status led
				if($arrayStatusOutput[$l] < 0){ //there is no communication with the peripheral
					echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
				}else if(($arrayStatusOutput[$l] & $y)==$y){ //the output is set
					echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
				}else if(($arrayStatusOutput[$l] & $y)==0){ //the output is NOT set
					echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
				}
				
				echo '<br>';
				//printing the button used to switch the status
				if(($arrayStatusOutput[$l] & $y)==$y){
					$value_to_set = ($arrayStatusOutput[$l]&(~$y));
					echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'switch_on.png" onclick="change' . $id . '_' . $l . '(' . $value_to_set . ')"  class="img_switch_off" alt="'.$lang_msg_turn_on.'"> ';
				}else{
					$value_to_set = ($arrayStatusOutput[$l]|$y);
					echo '<img src="' . DIRECTORY_IMG_PERI_11 . 'switch_off.png" onclick="change' . $id . '_' . $l . '(' . $value_to_set . ')"  class="img_switch_on" alt="'.$lang_msg_turn_off.'"> ';
				}

				echo '</td>';
				
			}
			echo '</tr>';
			echo '</table>';	

			echo '</form>';
		}
		
		$l++;
	}
	echo '</td>';
	
	
	
	echo '<td>';
	
	//here the special functions
		
	//Button to page TIMER Relay Functions
	if($array_function_to_show[0]==1){
	echo '<form name="peri_11_btn_timer1_functions_'.$id.'" action="./lib/peripheral_11/lib/cmd_get_settings.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	$id_hex_special_function = "00"; //hexadecimal format. example 0x00 as to be written as "00"
	echo '<input type=hidden name="id_hex_special_function" value="'.$id_hex_special_function.'">'; 
	echo '<input type=hidden name="TAG0" value="DATA">'; 				//Command
	echo '<input type=hidden name="TAG1" value="RF">'; 					//second parameter
	echo '<input type=hidden name="TAG2" value="'.$address_peri.'">';	//third parameter
	$str_TAG3 = "524275" . $id_hex_special_function . "2E2E2E2E2E2E2E2E2E2E2E2E"; 
	echo '<input type=hidden name="TAG3" value="'.$str_TAG3.'">';		//fourth parameter
	echo '<input type=hidden name="page_to_show_data" value="show_settings_fifo_timer.php">';
	echo '<input type=submit value="'.$lang_btn_timer.'" class="btn_functions">';
	echo '</form>';
	}
		
	//Button to page Input Functions
	if($array_function_to_show[0]==1){
	echo '<form name="peri_11_btn_timer2_functions_'.$id.'" action="./lib/peripheral_11/lib/cmd_get_settings.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	$id_hex_special_function = "01"; //hexadecimal format. example 0x01 as to be written as "01"
	echo '<input type=hidden name="id_hex_special_function" value="'.$id_hex_special_function.'">'; 
	echo '<input type=hidden name="TAG0" value="DATA">'; 				//Command
	echo '<input type=hidden name="TAG1" value="RF">'; 					//second parameter
	echo '<input type=hidden name="TAG2" value="'.$address_peri.'">';	//third parameter
	$str_TAG3 = "524275" . $id_hex_special_function . "2E2E2E2E2E2E2E2E2E2E2E2E"; 
	echo '<input type=hidden name="TAG3" value="'.$str_TAG3.'">';		//fourth parameter
	echo '<input type=hidden name="page_to_show_data" value="show_settings_fifo_input.php">';
	echo '<input type=submit value="'.$lang_btn_input.'" class="btn_functions">';
	echo '</form>';
	}
	

	
	echo '</td>';
}


?>