<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		24/08/2017

Description: it is the library to build the control panel for the 9th peripheral


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

//		function peripheral_9($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri);
												
												
//--------------------------------END FUNCTIONS DESCRIPTIONS-----------------------------------//


//---------------------------------BEGIN INCLUDE-------------------------------------------//
//		Specific library for the Peri
		include './lib/peripheral_9/lib/peri9_lib.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


	



function peripheral_9($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){
	
	//---------------------------------------------------------------------------------------//
	//		Strings for languages
	


	$settings_temperature = "Settings";
	$get_temperature = "Refresh";
	$lang_btn_input = "Input";
	$lang_temperature="Air: ";
	$lang_the_last="last";
	$show_data = "Show Data";
	$set_num_data = "Set Num Data";
	if($_SESSION["language"]=="IT"){
		$settings_temperature = "Impostazioni";
		$get_temperature = "Aggiorna";
		$lang_btn_input = "Entrate";
		$lang_temperature="Aria: ";
		$lang_the_last="ultimo";
	}else if($_SESSION["language"]=="FR"){
		$settings_temperature = "Param&egrave;tres";
		$get_temperature = "Rafra&Icirc;chir";
		$lang_btn_input = "Entr&eacute;e";
		$lang_temperature="Air: ";
		$lang_the_last="dernier";
	}else if($_SESSION["language"]=="SP"){
		$settings_temperature = "Ajustes";
		$get_temperature = "Refrescar";
		$lang_btn_input = "Entrada";
		$lang_temperature="Aire: ";
		$lang_the_last="ultimo";
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
									
	$file_data_path2 = "./lib/peripheral_9/data/" . $address_peri . "_data.txt"; 
	
	
	
	
	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_9 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td align=center>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_9 . 'ant_grey.png" class="img_ant" alt="'.DEFINE_lang_msg_no_communication.'"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_9 . 'ant_green.png" class="img_ant" alt="'.$lang_msg_connected.'"> ';
	}
	echo '<br>';
	echo '<br>';
	
	//echo '</td>';
	//echo '<td>&nbsp';
	echo $name;
	echo '</td>';
	
	echo '</tr></table>';
	echo '</td>';  
	
	
		


		
		
		
	echo '<td colspan=2>'; //echo '<td>';
	$l=0;
	//$num_input_used_on_peri=1; 
	$num_input_used_on_peri=4; 
	while ($l<$numInput) { 

		if($l==0){ // && $array_input_to_show[$l]==1){
		

		}else if($l==1 && $array_input_to_show[$l]==1){
			echo '&nbsp'.$lang_temperature;
			if($arrayStatusInput[$l]>=0){
				$temperature = (int)($arrayStatusInput[$l]);
				$temperature = temperature_MCP9701_from_8bit_value_peri9($temperature);
				
				if( ($arrayStatusInput[$l] & 0x80) == 128){
					//echo '-';
					//$temperature = $temperature - 128;
					//$cont = $arrayStatusInput[$l];
					//$temperature = 0;
					//while($cont<256){
					//	$temperature --;
					//	$cont++;
					//}
				}//else{
					//$temperature = $arrayStatusInput[$l];
				//}
				echo number_format((float)strval($temperature), 1, '.', '');
				echo '&nbsp&#176C&nbsp'; //°C
			}else{
				echo DEFINE_lang_msg_no_communication;
			}
			echo '<br>';echo '<br>';
			
		}else if($num_input_used_on_peri == 4){
			if( ($l==2 || $l==3 || $l==4 || $l==5 || $l==6) && $array_input_to_show[$l]==1){ 
				if($arrayStatusInput[$l]>=0){ 
					echo return_str_with_formula_name($array_input_formula_to_show[$l-2]);
					echo "= ";
					echo return_str_with_formula_set($arrayStatusInput[$l], $array_input_formula_to_show[$l-2], 1);
				}else{
					echo ' '.DEFINE_lang_msg_no_communication.' ';
				}
				echo '<br>';
			}
		}
		$l++;
	}

	
	echo '<br>';
	//print the last data saved into the file
	if($num_input_used_on_peri == 4){
		//print_only_the_last_4data_from_the_file_peri9($address_peri, $array_input_formula_to_show, $file_data_path2);
	}else if((int)$num_input_used_on_peri !== -1){
		print_the_file_data($address_peri,$array_input_formula_to_show[0],$file_data_path2);
	}
	

	echo '</td>';


	echo '<td>';
	
	//here the special functions
		
	//Button to page Temperature Pyrometer Functions
	if($array_function_to_show[0]==1){
		echo '<form name="peri9_btn_temperature_functions_'.$id.'" action="./lib/peripheral_9/cmd_get_data.php" method=GET>';
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=submit value="'.$settings_temperature.'" class="btn_functions">';
		echo '</form>';
	}
	
	//echo '<br>';
	
	
	if($array_function_to_show[1]==1 && $num_input_used_on_peri==1){
		//Button to get the temperature of the pyrometer
		echo '<form name="peri9_btn_trigger_functions_'.$id.'" action="./lib/peripheral_9/cmd_get_data.php" method=GET>';
		echo '<input type=hidden name="redirect_page" value="../../index.php">';
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=submit value="'.$get_temperature.'" class="btn_functions">';
		echo '</form>';
		
	}
	
	if($array_function_to_show[2]==1  && $num_input_used_on_peri==4 || $num_input_used_on_peri==-1){
		//Button to show the data recorded
		echo '<form name="peri9_btn_trigger_functions_'.$id.'" action="./lib/peripheral_9/show_data.php" method=GET>';
		//echo '<input type=hidden name="redirect_page" value="../../index.php">';
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="idperipheral" value="'.$idperipheral.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=submit value="'.$show_data.'" class="btn_functions">';
		echo '</form>';
	}
	
	if($array_function_to_show[3]==1  && $num_input_used_on_peri==4){
		//Button to show the data recorded
		echo '<form name="peri9_btn_trigger_functions_'.$id.'" action="./lib/peripheral_9/cmd_get_num_data.php" method=GET>';
		//echo '<input type=hidden name="redirect_page" value="../../index.php">';
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=submit value="'.$set_num_data.'" class="btn_functions">';
		echo '</form>';
	}
	

	
	
	echo '</td>'; 

	
}

?>