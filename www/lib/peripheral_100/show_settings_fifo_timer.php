<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/11/2019

Description: this is a panel where to setup the TIMER


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


//---------------------------------BEGIN INCLUDE-------------------------------------------//

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//		Specific library for the json file
		include './lib/json_lib.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_title_timer="TIMER";
$lang_title_millisecond="Milliseconds";
$lang_title_second="Second";
$lang_title_minutes="Minutes";
$lang_title_hours="Hours";
$lang_title_enabled="ENABLED";
$lang_title_output="Output";
$lang_relay="Relay";
$lang_decription_function="After the time specified here the output will be turned OFF/ON.";
$lang_select_output="Select the output:";
$lang_set_status="Select the status<br>to set:";
if($_SESSION["language"]=="IT"){
	$lang_title_timer="TIMER";
	$lang_title_millisecond="Millisecondi";
	$lang_title_second="Secondi";
	$lang_title_minutes="Minuti";
	$lang_title_hours="Ore";
	$lang_title_enabled="ABILITATA";
	$lang_title_output="Uscita";
	$lang_relay="Rel&egrave;";
	$lang_decription_function="Dopo il tempo specificato qui l&apos;ustita verr&aacute; accesa/spenta.";
	$lang_select_output="Seleziona l&apos;uscita:";
	$lang_set_status="Seleziona lo stato<br>da impostare:";
}else if($_SESSION["language"]=="FR"){
	$lang_title_timer="MINUTEUR";
	$lang_title_millisecond="Millisecondes";
	$lang_title_second="Secondes";
	$lang_title_minutes="Minutes";
	$lang_title_hours="Heures";
	$lang_title_enabled="Acti&eacute;e";
	$lang_title_output="Sortie";
	$lang_relay="Rel&egrave;";
	$lang_decription_function="Apr&egrave;s le temps sp&eacute;cifi&eacute; ici, l&apos;odita sera activ&eacute;/désactiv&eacute;.";
	$lang_select_output="S&eacute;lectionnez la sortie:";
	$lang_set_status="S&eacute;lectionnez le statut<br>&agrave; d&eacute;finir:";
}else if($_SESSION["language"]=="SP"){
	$lang_title_timer="TIMER";
	$lang_title_millisecond="Milisegundos";
	$lang_title_second="Segundos";
	$lang_title_minutes="Acta";
	$lang_title_hours="Horas";
	$lang_title_enabled="ACTIVO";
	$lang_title_output="Salida";
	$lang_relay="Rel&eacute;";
	$lang_decription_function="Despu&eacute;s del tiempo especificado aquí, la odita se encender&aacute;/apagar&aacute;.";
	$lang_select_output="Seleccione la salida:";
	$lang_set_status="Seleziona lo stato<br>da impostare:";
}

//---------------------------------------------------------------------------------------//




$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];
$status_rfpi=$_GET['status_rfpi'];
$data_rfpi=$_GET['data_rfpi'];



$index = 8;
$timer_enabled = intval( $data_rfpi[0+$index] . $data_rfpi[1+$index] , 16); //byte 0
$timer_ms = intval( $data_rfpi[2+$index] . $data_rfpi[3+$index] , 16); //byte 1
$timer_ms = $timer_ms << 8;
$timer_ms += intval( $data_rfpi[4+$index] . $data_rfpi[5+$index] , 16); //byte 2
$timer_SS = intval( $data_rfpi[6+$index] . $data_rfpi[7+$index] , 16); //byte 3
$timer_MM = intval( $data_rfpi[8+$index] . $data_rfpi[9+$index] , 16); //byte 4
$timer_HH = intval( $data_rfpi[10+$index] . $data_rfpi[11+$index] , 16); //byte 5






/************************************* BEGIN: DECODE JSON FILE *************************************/
	
	
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
	
	
	
	$path_conf_json = CONF_PATH . $address_peri . ".json";
	$sem_json_exist=0;
	
	$numOutput = 1; //this just to say there is an output thus it will go forward in reading the json file
	
	//here the array are filled up with the data taken from the json
	decode_iotgemini_json(
									//variables that are filled up
									$sem_json_exist, 
									$sem_RGB_Shield_connected,
									
									$count_digital_input_json, 
									$count_digital_output_json, 
									$count_analogue_input_json, 
									$count_analogue_output_json, 
									
									$array_pin_digital_inputs_json,
									$array_pin_digital_outputs_json,
									$array_pin_analogue_inputs_json,
									$array_pin_analogue_outputs_json,
									
									$array_shield_name_digital_inputs_json,
									$array_shield_name_digital_outputs_json,
									$array_shield_name_analogue_inputs_json,
									$array_shield_name_analogue_outputs_json,
									
									$array_shield_mpn_digital_inputs_json,
									$array_shield_mpn_digital_outputs_json,
									$array_shield_mpn_analogue_inputs_json,
									$array_shield_mpn_analogue_outputs_json,
									
									$array_id_digital_outputs_json,
									$array_id_analogue_outputs_json,
									
									//variables to run the function
									$address_peri,
									$path_conf_json,
									$numInput,
									$numOutput
									
								);
	
/************************************* END: DECODE JSON FILE *************************************/





echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<br>';

echo '<form name="set_functions" action="./cmd_set_settings_timer.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_hex_special_function" value="'. $id_hex_special_function . '">';
echo '<input type=hidden name="redirect_page" value="'. $redirect_page . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';  
//echo '<td class="td_peripheral">'.$lang_title_millisecond.'</td>';   
echo '<td class="td_peripheral">'.$lang_title_second.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_minutes.'</td>';
echo '<td class="td_peripheral">'.$lang_title_hours.'</td>';  
//echo '<td class="td_peripheral">'.$lang_title_output.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">'.$lang_title_timer.'</td>';  
/*echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="timer_ms" value="'; echo $timer_ms; echo '" size="4" maxlength="4">';
echo '<br> MAX = 1000';
echo '</td>';  */ 
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="timer_SS" value="'; echo $timer_SS; echo '" size="2" maxlength="2">';
echo '<br> MAX = 60';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="timer_MM" value="'; echo $timer_MM; echo '" size="2" maxlength="2">';
echo '<br> MAX = 60';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="timer_HH" value="'; echo $timer_HH; echo '" size="2" maxlength="2">';
echo '<br> MAX = 255';
echo '</td>';

/*
echo '<td class="td_peripheral">';
if(intval($timer_enabled, 10)==2){
	echo '<input type="radio" name="output_to_control" value="1" >&nbspRelay';
	echo '<br><input type="radio" name="output_to_control" value="2" checked="checked">&nbspGPIO1';
}else{
	echo '<input type="radio" name="output_to_control" value="1" checked="checked">&nbsp'.$lang_relay;
	echo '<br><input type="radio" name="output_to_control" value="2">&nbspGPIO1';
}

echo '</td>'; 
*/

echo '<td class="td_peripheral" align=center>';
if(intval($timer_enabled, 10)==0){
	echo '<input type="checkbox" name="timer_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="timer_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>';
echo '</tr>';

echo '<tr class="table_line_odd">';
echo '<td class="td_peripheral">'.$lang_select_output.'</td>';  
echo '<td colspan=3 class="td_peripheral" align=left>';
//echo '<input type="radio" name="output_selected" value="0"/ '; if($output_selected==0) echo 'checked'; echo '>Digital Output<br>';
//echo '<input type="radio" name="output_selected" value="1"/ '; if($output_selected==1) echo 'checked'; echo '>Digital Input<br>';
//echo '<input type="radio" name="output_selected" value="2"/ '; if($output_selected==2) echo 'checked'; echo '>Analogue Input';

/*if(intval($timer_enabled, 10)==2){
	echo '<input type="radio" name="output_to_control" value="1" >&nbspOutput0';
	echo '<br><input type="radio" name="output_to_control" value="2" checked="checked">&nbspOutput1';
}else{
	echo '<input type="radio" name="output_to_control" value="1" checked="checked">&nbspOutput0';
	echo '<br><input type="radio" name="output_to_control" value="2">&nbspOutput1';
}*/

if($sem_json_exist==1){
	$l=0;
	$counter=0;
	while ($l<$count_digital_output_json) {

		echo '<input type="radio" name="output_to_control" value="'.($counter+1).'" '; if((intval($timer_enabled, 10)&0b00111111)==($counter+1)) echo 'checked'; echo '>';
		echo $array_shield_name_digital_outputs_json[$l] . ' ';
		echo "PIN" . $array_pin_digital_outputs_json[$l].' ';
		echo 'ID' . $counter;
		echo '<br>';

		$l++;
		$counter++;
	}
}


echo '</td>';   

echo '<td colspan=1 class="td_peripheral" align=left>';
echo $lang_set_status;
echo '<br>';
echo '<select name="status_to_set">'; 
echo '				<option value="0"'; if((intval($timer_enabled, 10)&0b10000000)==0) echo "selected"; echo '>OFF</option>'; 
echo '				<option value="1"'; if((intval($timer_enabled, 10)&0b10000000)==0b10000000) echo "selected"; echo '>ON</option>'; 
//echo '				<option value="2">INVERT</option>'; 
echo '</select>'; 
echo '</td>';   
	
echo '</tr>';




echo '<tr class="table_title_field_line">';
echo '<td colspan=5 align=center>';
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';


//echo '<br>';
//echo 'After the time specified here the relay will be turned OFF';
echo $lang_decription_function;
echo '<br><br>';



//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
