<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		24/08/2017

Description: it is the library to build the control panel for the 3rd peripheral


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
define("DIRECTORY_IMG_PERI_3", "./lib/peripheral_3/img/"); 		//where all pictures are kept
define("DIRECTORY_CSS_PERI_3", "./lib/peripheral_3/css/"); 		//where all pictures are kept


function peripheral_3($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){

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
	
	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_3 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td align=center>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'ant_grey.png" class="img_ant" alt="No communication!"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'ant_green.png" class="img_ant" alt=""> ';
	}
	
	echo '<br><br>';
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

		if($l == 0 && $array_input_to_show[$l]==1){
			echo 'Digital Input: <br>';
			if($arrayStatusInput[$l]==1){
				echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'led_green.png" class="img_led" alt="ON"> ';
			}else if($arrayStatusInput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'led_red.png" class="img_led" alt="OFF"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'led_grey.png" class="img_led" alt="No communication!"> ';
			}
			echo '<br><br>';
		}else if($l == 1 && $array_input_to_show[$l]==1){
			echo 'Temperature: <br>';
			if($arrayStatusInput[$l]>=0){
				/*if( $arrayStatusInput[$l] > 128){
					$arrayStatusInput[$l] &= 0x007F;
					echo '-';
				}*/
				if( ($arrayStatusInput[$l] & 0x80) == 128){
					
					//$arrayStatusInput[$l] = ~ $arrayStatusInput[$l];
					$cont = $arrayStatusInput[$l];
					$temperature = 0;
					while($cont<256){
						$temperature --;
						$cont++;
					}
				}else{
					$temperature = $arrayStatusInput[$l];
				}
				echo strval(($temperature));
				echo ' &#176C'; //°C
			}else{
				echo 'No communication!';
			}
			echo '<br><br>';
		}else if($l == 2 && $array_input_to_show[$l]==1){
			echo 'Luminosity: <br>';
			if($arrayStatusInput[$l]>=0){
				$maxCount = $arrayStatusInput[$l]; //every 10 has to be moltiplied by 10 (logarithmic scale)
				$lux = 0;
				$toAdd = 1;
				$countEveryTen = 0;
				
				for($i=0; $i < $maxCount; $i++){
					$lux += $toAdd;
					$countEveryTen++;
					if($countEveryTen>9){
						$countEveryTen = 0;
						$toAdd = $toAdd * 10;
						$lux = $toAdd;
					}
				}
				//$lux -= $toAdd;
				
				echo $lux;
				echo ' LUX';
			}else{
				echo 'No communication!';
			}
			echo '<br><br>';
		}else if(($l == 3 || $l == 4) && $array_input_to_show[$l]==1){
			if($arrayStatusInput[$l]>1){ //it is analogue
				echo 'GPIO Analogue: <br><br>';
				echo strval(($arrayStatusInput[$l]));
			}else{
				echo 'GPIO Digital: <br>';
				if($arrayStatusInput[$l]==1){ //it is digital
					echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'led_green.png" class="img_led" alt="ON"> ';
				}else if($arrayStatusInput[$l]==0){
					echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'led_red.png" class="img_led" alt="OFF"> ';
				}else{
					echo 'No communication!';
				}
			}
			echo '<br><br>';
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
		
		
		if(($l == 0 || $l == 1 || $l == 2 || $l == 3) && $array_output_to_show[$l]==1){ //first output

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
			
			if($l == 2 || $l == 3){
				echo 'GPIO: <br>';
			}else
				echo 'Relay'. $l .': <br>';
			
			if($arrayStatusOutput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'switch_off.png" onclick="change' . $id . '_' . $l .'(1)"  class="img_switch_off" alt="Turn ON"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'switch_on.png" onclick="change' . $id . '_' . $l .'(0)"  class="img_switch_on" alt="Turn OFF"> ';
			}
			
			if($arrayStatusOutput[$l]==1){
				echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'led_green.png" class="img_led" alt="ON"> ';
			}else if($arrayStatusOutput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'led_red.png" class="img_led" alt="OFF"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_3 . 'led_grey.png" class="img_led" alt="No communication!"> ';
			}
			
		}
	
		echo '</form>';
		
		$l++;
	}
	echo '</td>';
	
	echo '<td>';
	
	//here the special functions
	
	//Button to page Infrared Functions
	if( $array_function_to_show[0] == 1){
		echo '<form name="peri3_btn_infrared_functions_'.$id.'" action="./lib/peripheral_3/infrared_functions.php" method=GET>';
		//echo '<input type=hidden name="btn_infrared_functions" value="main">';
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=submit value="INFRARED" class="btn_functions">';
		echo '</form>';
	}
	
	//Button to page GPIO Functions
	if( $array_function_to_show[1] == 1){
		echo '<form name="peri3_btn_gpio_functions_'.$id.'" action="./lib/peripheral_3/cmd_get_gpio_setting.php" method=GET>';
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=submit value="GPIO" class="btn_functions">';
		echo '</form>';
	}
	
	//Button to page UPDATE Functions
	if( $array_function_to_show[2] == 1){
		echo '<form name="peri3_btn_update_functions_'.$id.'" action="./lib/peripheral_3/cmd_get_update_setting.php" method=GET>';
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=submit value="UPDATE" class="btn_functions">';
		echo '</form>';
	}
	
	
	echo '</td>';
}


?>