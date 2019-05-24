<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		24/08/2017

Description: it is the library to build the control panel for the second peripheral


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

//		function peripheral_1($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri);
												
												
//-------------------------------END FUNCTIONS DESCRIPTIONS----------------------------------//



//DEFINES of DIRECTORY
define("DIRECTORY_IMG_PERI_2", "./lib/peripheral_2/img/"); 		//where all pictures are kept
define("DIRECTORY_CSS_PERI_2", "./lib/peripheral_2/css/"); 		//where all pictures are kept


function peripheral_2($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){
	
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
									
	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_2 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'ant_grey.png" class="img_ant" alt="No communication!"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'ant_green.png" class="img_ant" alt=""> ';
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

		if($arrayStatusInput[$l]>=0){
			echo 'Value ADC (0 to 255): <br><br>';
			echo $arrayStatusInput[$l];
			/*echo 'Current: <br><br>';
			if($arrayStatusInput[$l]<44){
				echo "WARNING: Under 4mA";
			}else{
				echo (($arrayStatusInput[$l]-44)*27.4);
				echo " mA";
			}*/
		}else{
			echo 'No communication!';
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
		
		
		if($l == 0){ //first output

			echo '<script type="text/JavaScript">';
			echo 'function change' . $id . '_' . $l .'(value){';
			echo 'document.set_' . $id . '_' . $l .'_output.output_value.value=value;';
			echo 'document.set_' . $id . '_' . $l .'_output.submit();';
			echo '}';
			echo '</script>';
			
			echo '<form name="set_' . $id . '_' . $l .'_output" action="set_output.php" method=GET>';
			echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
			echo '<input type=hidden name="output_id" value="' . $l . '">';
		
			echo '<input type=hidden name="output_value" value="' . $arrayStatusOutput[$l] . '">';
			
			echo 'Relay: <br>';
			
			if($arrayStatusOutput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'switch_off.png" onclick="change' . $id . '_' . $l .'(1)"  class="img_switch_off" alt="Turn ON"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'switch_on.png" onclick="change' . $id . '_' . $l .'(0)"  class="img_switch_on" alt="Turn OFF"> ';
			}
			
			if($arrayStatusOutput[$l]==1){
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'led_green.png" class="img_led" alt="ON"> ';
			}else if($arrayStatusOutput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'led_red.png" class="img_led" alt="OFF"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'led_grey.png" class="img_led" alt="No communication!"> ';
			}
			
		}else if($l == 1){ //second output
			echo '<form name="set_' . $id . '_' . $l .'_output" action="set_output.php" method=GET>';
			echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
			echo '<input type=hidden name="output_id" value="' . $l . '">';
			
			/*if($arrayStatusOutput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'switch_off.png" onclick="change' . $id . '(1)"  class="img_switch_off" alt="Turn ON"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'switch_on.png" onclick="change' . $id . '(0)"  class="img_switch_on" alt="Turn OFF"> ';
			}*/
			
			//echo '<input type=button onclick="change' . $id . '(1)" value="Set Output" class="btn_set" alt="Set Output">';
			
			echo '<br> Value DAC (0 to 255): <br>';
			
			echo '<input type=text name="output_value" value="' . $arrayStatusOutput [$l] . '" class="text_value_io">';
			echo '<input type=submit value="Set Output" class="btn_set2">';
					
			
			/*if($arrayStatusOutput[$l]==1){
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'led_green.png" class="img_led" alt="ON"> ';
			}else if($arrayStatusOutput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'led_red.png" class="img_led" alt="OFF"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_2 . 'led_grey.png" class="img_led" alt="No communication!"> ';
			}*/
			
		}
	
		echo '</form>';
		
		$l++;
	}
	echo '</td>';
	
	echo '<td>';
	
	//here the special functions
	
	echo '</td>';
}


?>