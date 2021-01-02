<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/01/2021

Description: it is the library to build the control panel for the peripheral


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

//		function peripheral_14($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput);
												
												
//--------------------------------END FUNCTIONS DESCRIPTIONS-----------------------------------//


//---------------------------------BEGIN INCLUDE-------------------------------------------//
//		Specific library for the Peri
	include './lib/peripheral_14/peri_lib.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//





function peripheral_14($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){


	//---------------------------------------------------------------------------------------//
	//		Strings for languages

	$lang_msg_counter = "Counter: ";
	$lang_msg_preset = "Preset: ";
	$lang_title_divider="Divider: ";
	$lang_msg_count_mode = "Count Mode: ";
	$lang_msg_increase_count = " increase";
	$lang_msg_decrease_count = " decrease";
	$lang_msg_relay = "Relay:";
	$lang_btn_counter = "Set Counter";
	if($_SESSION["language"]=="IT"){
		$lang_msg_counter = "Conteggio: ";
		$lang_msg_preset = "Preset: ";
		$lang_title_divider="Divisore: ";
		$lang_msg_count_mode = "Modalit&agrave; conteggio: ";
		$lang_msg_increase_count = " incremento";
		$lang_msg_decrease_count = " decremento";
		$lang_msg_relay = "Rel&egrave;:";
		$lang_btn_counter = "Imposta Conteggio";
	}else if($_SESSION["language"]=="FR"){
		$lang_msg_counter = "Compter: ";
		$lang_msg_preset = "Pr&eacute;r&eacute;gl&eacute;: ";
		$lang_title_divider="Diviseur: ";
		$lang_msg_count_mode = "Mode de comptage: ";
		$lang_msg_increase_count = " augmenter";
		$lang_msg_decrease_count = " diminution";
		$lang_msg_relay = "Relays:";
		$lang_btn_counter = "D&eacute;finir le nombre";
	}else if($_SESSION["language"]=="SP"){
		$lang_msg_counter = "Contar: ";
		$lang_msg_preset = "Preestablecida: ";
		$lang_title_divider="Divisor: ";
		$lang_msg_count_mode = "Modo de conteo: ";
		$lang_msg_increase_count = " incrementar";
		$lang_msg_decrease_count = " disminuci&oacute;n";
		$lang_msg_relay = "Rel&eacute;:";
		$lang_btn_counter = "Establecer recuento";
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
									
	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_14 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td align=center>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'ant_grey.png" class="img_ant" alt="'.DEFINE_lang_msg_no_communication.'"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'ant_green.png" class="img_ant" alt="'.$lang_msg_connected.'"> ';
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
			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo 'Input ID = ' . $l . '<br><br>';
			echo $lang_msg_count_mode;
			echo '<div style="background-color:black;color:red;font-size:30px">';
			if($arrayStatusInput[$l]==1){
				//echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
				echo $lang_msg_increase_count;
			}else if($arrayStatusInput[$l]==0){
				//echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
				echo $lang_msg_decrease_count;
			}else{
				//echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
				echo DEFINE_lang_msg_no_communication;
			}
			echo '</div>';
			echo '<br></div>';
	
			
		}else if($l==1 && $array_input_to_show[$l]==1){
			//echo '<br>&nbsp';
			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo 'Input ID = ' . $l . '<br><br>';
			echo $lang_msg_counter;
			echo '<div style="background-color:black;color:red;font-size:30px">';
			if($arrayStatusInput[$l]>=0){
				$counter = (int)($arrayStatusInput[$l]);
				$divider = (int)($arrayStatusInput[3]);
				echo intval(($counter * 100) / $divider);
			}else{
				echo DEFINE_lang_msg_no_communication;
			}
			echo '</div>';
			echo '<br></div>';
		
		}else if($l==2 && $array_input_to_show[$l]==1){
			//echo '<br>&nbsp';
			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo 'Input ID = ' . $l . '<br><br>';
			echo $lang_msg_preset;
			echo '<div style="background-color:black;color:red;font-size:30px">';
			if($arrayStatusInput[$l]>=0){
				$preset = (int)($arrayStatusInput[$l]);
				echo $preset;
			}else{
				echo DEFINE_lang_msg_no_communication;
			}
			echo '</div>';
			echo '<br></div>';

		}else if($l==3 && $array_input_to_show[$l]==1){
			//echo '<br>&nbsp';
			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo 'Input ID = ' . $l . '<br><br>';
			echo $lang_title_divider;
			echo '<div style="background-color:black;color:red;font-size:30px">';
			if($arrayStatusInput[$l]>=0){
				$divider = (int)($arrayStatusInput[$l]);
				$divider_int = intval($divider / 100);
				$divider_dec = $divider - ($divider_int * 100 );
				echo $divider_int . '.' . $divider_dec;
			}else{
				echo DEFINE_lang_msg_no_communication;
			}
			echo '</div>';
			echo '<br></div>';

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
		
		
		//first output
		if(($l==0) && $array_input_to_show[$l]==1){
			
			echo '<script type="text/JavaScript">';
			echo 'function change' . $id . '_' . $l . '(value){';
			echo 'document.set_' . $id . '_output_' . $l . '.output_value.value=value;';
			echo 'document.set_' . $id . '_output_' . $l . '.submit();';
			echo '}';
			echo '</script>';
			
			echo '<form name="set_' . $id . '_output_' . $l . '" action="set_output.php" method=GET>';
			echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
			echo '<input type=hidden name="output_id" value="' . $l . '">';
			echo '<input type=hidden name="output_value" value="' . $arrayStatusOutput[$l] . '">';

				echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
				echo 'Output ID = ' . $l . '<br><br>';
				echo $lang_msg_relay;
				echo "<br>";
				if($arrayStatusOutput[$l]==0){
					//echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'on.png" onclick="change' . $id . '_' . $l . '(1)"  class="img_button_square_enable" alt="'.$lang_msg_turn_on.'"> ';
					echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'switch_off.png" onclick="change' . $id . '_' . $l . '(1)"  class="img_switch_off" alt="'.$lang_msg_turn_on.'"> ';
					//echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'off.png"  class="img_button_square_disable" alt="'.$lang_msg_turn_off.'"> ';
				}else{
					//echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'on.png" class="img_button_square_disable" alt="'.$lang_msg_turn_on.'"> ';
					//echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'off.png" onclick="change' . $id . '_' . $l . '(0)"  class="img_button_square_enable" alt="'.$lang_msg_turn_off.'"> ';
					echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'switch_on.png" onclick="change' . $id . '_' . $l . '(0)"  class="img_switch_on" alt="'.$lang_msg_turn_off.'"> ';
				}
				
				
				if($arrayStatusOutput[$l]==1){
					echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
				}else if($arrayStatusOutput[$l]==0){
					echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
				}else{
					echo '<img src="' . DIRECTORY_IMG_PERI_14 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
				}
				echo '<br><br>';
				echo '</div>';
			
			echo '</form>';
			
		//second output
		}else if(($l==1) && $array_input_to_show[$l]==1){
			echo '<div style="border:1px solid #000;border-radius:4px;padding: 2px;">'; //background: #d2f4f3;">';
			echo 'Output ID = ' . $l . '<br><br>';		
			
			echo '<form name="peri_14_btn_reset_'.$id.'" action="./lib/peripheral_14/lib/set_output_and_refresh.php" method=GET>';
				echo '<input type=hidden name="position_id" value="'.$id.'">';
				echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
				echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
				echo '<input type=hidden name="output_id" value="1">';
				echo '<input type=hidden name="output_value" value="' . $arrayStatusOutput[$l] . '">';
		
				echo '<input type=submit value="RESET" class="btn_functions">';
			echo '</form>';

			echo '</div>';

		}

		$l++;
	}
	echo '</td>';
	
	
	echo '<td>';
	
	echo '<br>';
	
	//here the special functions
		
	//Button to page TIMER Relay1 Functions
	if($array_function_to_show[0]==1){
	echo '<form name="peri_14_btn_timer1_functions_'.$id.'" action="./lib/peripheral_14/lib/cmd_get_settings.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	$id_hex_special_function = "01"; //hexadecimal format. example 0x02 as to be written as "02"
	echo '<input type=hidden name="id_hex_special_function" value="'.$id_hex_special_function.'">'; 
	echo '<input type=hidden name="TAG0" value="DATA">'; 				//Command
	echo '<input type=hidden name="TAG1" value="RF">'; 					//second parameter
	echo '<input type=hidden name="TAG2" value="'.$address_peri.'">';	//third parameter
	$str_TAG3 = "524275" . $id_hex_special_function . "2E2E2E2E2E2E2E2E2E2E2E2E"; 
	echo '<input type=hidden name="TAG3" value="'.$str_TAG3.'">';		//fourth parameter
	echo '<input type=hidden name="page_to_show_data" value="show_settings_fifo_counter.php">';
	echo '<input type=submit value="'.$lang_btn_counter.'" class="btn_functions">';
	echo '</form>';
	}
	
	
	echo '</td>';
}


?>