<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		24/08/2017

Description: 	it is the library to build the control panel for the 5th peripheral 
				(Radio Control)


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

//		function peripheral_5($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri);
												
												
//--------------------------------END FUNCTIONS DESCRIPTIONS-----------------------------------//


//DEFINES of DIRECTORY
define("DIRECTORY_IMG_PERI_5", "./lib/peripheral_5/img/"); 		//where all pictures are kept
define("DIRECTORY_CSS_PERI_5", "./lib/peripheral_5/css/"); 		//where all pictures are kept



function peripheral_5($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){
	
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
									
	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_5 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'ant_grey.png" class="img_ant" alt="No communication!"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'ant_green.png" class="img_ant" alt=""> ';
	}
	
	echo '</td>';
	echo '<td>&nbsp';
	echo $name;
	echo '</td>';
	
	echo '</tr></table>';
	echo '</td>';  
				
	//print the name of the input and the status
	echo '<td>';
	$l=0;
	while ($l<$numInput) { 

		if($l==0){
			if($arrayStatusInput[$l]==1){
				echo 'Gate Functions';
			}else if($arrayStatusInput[$l]==0){
				echo 'Normal Functions';
			}else{
				echo 'No communication!';
			}
		}else {
			echo '<br>';
			if($arrayStatusInput[$l]==1){
				echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'led_green.png" class="img_led" alt="ON"> ';
			}else if($arrayStatusInput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'led_red.png" class="img_led" alt="OFF"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'led_grey.png" class="img_led" alt="No communication!"> ';
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
		echo 'function change' . $id . '(value){';
		echo 'document.set_' . $id . '_output.output_value.value=value;';
		echo 'document.set_' . $id . '_output.submit();';
		echo '}';
		echo '</script>';
		
		echo '<form name="set_' . $id . '_output" action="set_output.php" method=GET>';
		echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
		echo '<input type=hidden name="output_id" value="' . $l . '">';
		echo '<input type=hidden name="output_value" value="' . $arrayStatusOutput[$l] . '">';
		if($arrayStatusOutput[$l]==0){
			//echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'on.png" onclick="change' . $id . '(1)"  class="img_button_square_enable" alt="Turn ON"> ';
			echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'switch_off.png" onclick="change' . $id . '(1)"  class="img_switch_off" alt="Turn ON"> ';
			//echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'off.png"  class="img_button_square_disable" alt="Turn OFF"> ';
		}else{
			//echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'on.png" class="img_button_square_disable" alt="Turn ON"> ';
			//echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'off.png" onclick="change' . $id . '(0)"  class="img_button_square_enable" alt="Turn OFF"> ';
			echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'switch_on.png" onclick="change' . $id . '(0)"  class="img_switch_on" alt="Turn OFF"> ';
		}
		
		
		if($arrayStatusOutput[$l]==1){
			echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'led_green.png" class="img_led" alt="ON"> ';
		}else if($arrayStatusOutput[$l]==0){
			echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'led_red.png" class="img_led" alt="OFF"> ';
		}else{
			echo '<img src="' . DIRECTORY_IMG_PERI_5 . 'led_grey.png" class="img_led" alt="No communication!"> ';
		}
	
		echo '</form>';
		
		$l++;
	}
	echo '</td>';
	
	
	echo '<td>';
	
	//here the special functions
		
	//Button to page ADDRESSES Functions for Button 0
	echo '<form name="peri5_btn_timer_functions_'.$id.'" action="./lib/peripheral_5/cmd_get_addresses_setting.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	echo '<input type=hidden name="id_btn" value="0">';
	echo '<input type=submit value="ADD. BTN0" class="btn_functions">';
	echo '</form>';
	
	//Button to page ADDRESSES Functions for Button 1
	echo '<form name="peri5_btn_timer_functions_'.$id.'" action="./lib/peripheral_5/cmd_get_addresses_setting.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	echo '<input type=hidden name="id_btn" value="1">';
	echo '<input type=submit value="ADD. BTN1" class="btn_functions">';
	echo '</form>';
	
	
	echo '</td>';
}


?>