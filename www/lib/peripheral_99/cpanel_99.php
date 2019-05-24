<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/04/2019

Description: it is the library to build the control panel for the 99th peripheral


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

//		function peripheral_99($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput);
												
												
//--------------------------------END FUNCTIONS DESCRIPTIONS-----------------------------------//


//---------------------------------BEGIN INCLUDE-------------------------------------------//
//		Specific library for the Peri_99
		include './lib/peripheral_99/peri_99_lib.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//





function peripheral_99($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){


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
									
	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_99 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td align=center>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_99 . 'ant_grey.png" class="img_ant" alt="'.DEFINE_lang_msg_no_communication.'"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_99 . 'ant_green.png" class="img_ant" alt="'.$lang_msg_connected.'"> ';
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
		echo 'UNFORTUNATELY THIS TRANSCEIVER IS NOT ORIGINAL!';
	echo '</td>';
	
				
	//print the name of the output and the status
	echo '<td>';
	echo '<h1>:(</h1>';
	echo '</td>';
	
	//special function
	echo '<td>';
	
	
	
	echo '</td>';
}


?>