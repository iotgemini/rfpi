<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/01/2020

Description: this is a panel where to setup the Input Duty


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
$lang_decription_function="When the trigger occur on the selected input then the selected output would be set.";
$lang_select_input_output="Select the input and output:";
$lang_set_status="Select the status to set:";
$lang_title_input="INPUT";
$lang_title_input_trigger="INPUT TRIGGER";
$lang_title_output="OUTPUT";
$lang_title_output_status_to_set="STATUS TO SET";
$lang_title_function="FUNCTION";
$lang_title_input_function_status="STATUS FUNCTION";
$lang_rising_edge_input="on rising edge";
$lang_set_input_trigger="Select the input<br>trigger:";
$lang_DISABLED="DISABLED";
$lang_RISING_EDGE="RISING EDGE";
$lang_FALLING_EDGE="FALLING EDGE";
$lang_INVERT="INVERT";
$lang_ON="ON";
$lang_OFF="OFF";
if($_SESSION["language"]=="IT"){
	$lang_title_timer="TIMER";
	$lang_title_millisecond="Millisecondi";
	$lang_title_second="Secondi";
	$lang_title_minutes="Minuti";
	$lang_title_hours="Ore";
	$lang_title_enabled="ABILITATA";
	$lang_title_input="ENTRATA";
	$lang_title_input_trigger="TRIGGER DELL&apos;ENTRATA";
	$lang_title_output="USCITA";
	$lang_title_output_status_to_set="STATO DA IMPOSTARE";
	$lang_title_function="FUNZIONE";
	$lang_title_input_function_status="STATO FUNZIONE";
	$lang_relay="Rel&egrave;";
	$lang_decription_function="Quando si verifica il trigger sull&apos;ingresso selezionato, viene impostata l&apos;uscita selezionata.";
	$lang_select_input_output="Seleziona l&apos;entrata e l&apos;uscita:";
	$lang_set_status="Seleziona lo stato<br>da impostare:";
	$lang_set_input_trigger="Seleziona il trigger<br>per l&apos;input:";
	$lang_DISABLED="DISABILITATO";
	$lang_RISING_EDGE="FRONTE di SALITA";
	$lang_FALLING_EDGE="FRONTE di DISCESA";
	$lang_INVERT="INVERTI";
	$lang_ON="ACCESO";
	$lang_OFF="SPENTO";
}else if($_SESSION["language"]=="FR"){
	$lang_title_timer="MINUTEUR";
	$lang_title_millisecond="Millisecondes";
	$lang_title_second="Secondes";
	$lang_title_minutes="Minutes";
	$lang_title_hours="Heures";
	$lang_title_enabled="Acti&eacute;e";
	$lang_title_input="ENTR&Eacute;E";
	$lang_title_input_trigger="D&Eacute;CLENCHEUR D&apos;ENTR&Eacute;E";
	$lang_title_output="SORTIE";
	$lang_title_function="FONCTION";
	$lang_title_input_function_status="F&Eacute;TAT DES FONCTIONS";
	$lang_title_output_status_to_set="&Eacute;TAT &Agrave; D&Eacute;FINIR";
	$lang_relay="Rel&egrave;";
	$lang_decription_function="Lorsque le d&eacute;clenchement se produit sur l&apos;entr&eacute;e s&eacute;lectionn&eacute;e, la sortie s&eacute;lectionn&eacute;e est d&eacute;finie.";
	$lang_select_input_output="S&eacute;lectionnez la sortie:";
	$lang_set_status="S&eacute;lectionnez le statut &agrave; d&eacute;finir:";
	$lang_set_input_trigger="S&eacute;lectionnez le d&eacute;clencheur<br>d&apos;entr&eacute;e:";
	$lang_DISABLED="D&Eacute;SACTIV&Eacute;";
	$lang_RISING_EDGE="FRONT MONTANT";
	$lang_FALLING_EDGE="FRONT DESCENDANT";
	$lang_INVERT="INVERSER";
	$lang_ON="ON";
	$lang_OFF="OFF";
}else if($_SESSION["language"]=="SP"){
	$lang_title_timer="TIMER";
	$lang_title_millisecond="Milisegundos";
	$lang_title_second="Segundos";
	$lang_title_minutes="Acta";
	$lang_title_hours="Horas";
	$lang_title_enabled="ACTIVO";
	$lang_title_input="ENTRADA";
	$lang_title_input_trigger="GATILLO DE ENTRADA";
	$lang_title_output="SALIDA";
	$lang_title_function="FUNCION";
	$lang_title_input_function_status="FUNCION DE ESTADO";
	$lang_title_output_status_to_set="ESTADO A CONFIGURAR";
	$lang_relay="Rel&eacute;";
	$lang_decription_function="Cuando se produce el disparo en la entrada seleccionada, se establecer&aacute; la salida seleccionada.";
	$lang_select_input_output="Seleccione la salida:";
	$lang_set_status="Seleccione el estado<br>para establecer:";
	$lang_set_input_trigger="Seleccione el disparador<br>de entrada:";
	$lang_DISABLED="DISCAPACITADO";
	$lang_RISING_EDGE="FLANCO ASCENDENTE";
	$lang_FALLING_EDGE="FLANCO DESCENDENTE";
	$lang_INVERT="INVERTIR";
	$lang_ON="ON";
	$lang_OFF="OFF";
}

//---------------------------------------------------------------------------------------//




$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];
$status_rfpi=$_GET['status_rfpi'];
$data_rfpi=$_GET['data_rfpi'];

/*for($i=0;$i<32;$i+=2){
	echo $data_rfpi[$i].$data_rfpi[1+$i];
	echo " ";
}
echo "<br>";*/

//estract the data for Input Duty
$index = 8;
$l=0;
for($i=0;$i<10;$i++){
		$fun_input_ctrl_output[$i] = hexdec(($data_rfpi[$l+$index].$data_rfpi[1+$l+$index]));
		$l+=2;
		//echo $fun_input_ctrl_output[$i];
		//echo " ";
}
//meaning bits inside fun_input_ctrl_output from byte 0 to byte 7
		//	bit0 to bit2	=	ID of the input that control the output
		//	bit3 to bit5 	= 	ID of the output to control
		//	bit6 to	bit7	=	Status to set to the output: 0 = OFF, 1 = ON, 2 = TOGGLES, 
		//every 2bit into byte 8 and byte 9 there is the status for each of the 8 function "Input Duty"
		//example: byte 8 of the array fun_input_ctrl_output (so the array point to fun_input_ctrl_output[8])
		// bit0 to bit1		=	Input Trigger: 0 = Disabled, 1 = Rising edge, 2 = Falling edge, 3 = Both. This are relate to the fun_input_ctrl_output[0]
		// bit2 to bit3		=	Input Trigger: 0 = Disabled, 1 = Rising edge, 2 = Falling edge, 3 = Both. This are relate to the fun_input_ctrl_output[1]
		//	........ and so on
		





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

echo '<form name="set_functions" action="./cmd_set_settings_input.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_hex_special_function" value="'. $id_hex_special_function . '">';
echo '<input type=hidden name="redirect_page" value="'. $redirect_page . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';   
echo '<td class="td_peripheral">'.$lang_title_input_function_status.'</td>';
echo '<td class="td_peripheral">'.$lang_title_input.'</td>';
echo '<td class="td_peripheral">'.$lang_title_output.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_output_status_to_set.'</td>'; 
echo '</tr>';

for($i=0;$i<8;$i++){ //begin cycle to print the status of the 8 functions concerning input duty
	
	if(($i%2)==0) 
		echo '<tr class="table_line_even">';
	else
		echo '<tr class="table_line_odd">';
	
	echo '<td class="td_peripheral">'.$lang_title_function.' '.$i.':</td>';  

	echo '<td colspan=1 class="td_peripheral" align=left>';
	echo $lang_set_input_trigger;
	echo '<br>';
	echo '<select name="status_input_trigger'.$i.'">'; 
	$current_status_input_trigger = ($fun_input_ctrl_output[8]>>(2*$i))&0b00000011;
	if($i>3)
		$current_status_input_trigger = ($fun_input_ctrl_output[9]>>(2*($i-4)))&0b00000011;
	echo '				<option value="0"'; if($current_status_input_trigger==0) echo "selected"; echo '>'.$lang_DISABLED.'</option>'; 
	echo '				<option value="1"'; if($current_status_input_trigger==1) echo "selected"; echo '>'.$lang_RISING_EDGE.'</option>'; 
	echo '				<option value="2"'; if($current_status_input_trigger==2) echo "selected"; echo '>'.$lang_FALLING_EDGE.'</option>'; 
	echo '</select>'; 
	echo '</td>';   
	
	
	echo '<td colspan=1 class="td_peripheral" align=left>';
	if($sem_json_exist==1){
		$l=0;
		$counter=0;
		while ($l<$count_digital_input_json) {

			echo '<input type="radio" name="input_trigger'.$i.'" value="'.($counter).'" '; if((($fun_input_ctrl_output[$i]&0b00111000)>>3)==($counter)) echo 'checked'; echo '>';
			echo $array_shield_name_digital_inputs_json[$l] . ' ';
			echo "PIN" . $array_pin_digital_inputs_json[$l].' ';
			echo 'ID' . $counter;
			echo '<br>';

			$l++;
			$counter++;
		}
	}
	echo '</td>';   

	echo '<td colspan=1 class="td_peripheral" align=left>';
	if($sem_json_exist==1){
		$l=0;
		$counter=0;
		while ($l<$count_digital_output_json) {

			echo '<input type="radio" name="output_to_control'.$i.'" value="'.($counter).'" '; if(($fun_input_ctrl_output[$i]&0b00000111)==($counter)) echo 'checked'; echo '>';
			echo $array_shield_name_digital_outputs_json[$l] . ' ';
			echo "PIN" . $array_pin_digital_outputs_json[$l].' ';
			echo 'ID' . $counter;
			echo '<br>';

			$l++;
			$counter++;
		}
		$l=0;
		while ($l<$count_analogue_output_json) {

			echo '<input type="radio" name="output_to_control'.$i.'" value="'.($counter).'" '; if(($fun_input_ctrl_output[$i]&0b00000111)==($counter)) echo 'checked'; echo '>';
			echo $array_shield_name_analogue_outputs_json[$l] . ' ';
			echo "PIN" . $array_pin_analogue_outputs_json[$l].' ';
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
	echo '<select name="status_output_to_set'.$i.'">'; 
	$current_status_to_set = ($fun_input_ctrl_output[$i]&0b11000000)>>6;
	echo '				<option value="0"'; if($current_status_to_set==0) echo "selected"; echo '>'.$lang_OFF.'</option>'; 
	echo '				<option value="1"'; if($current_status_to_set==1) echo "selected"; echo '>'.$lang_ON.'</option>'; 
	echo '				<option value="2"'; if($current_status_to_set==2) echo "selected"; echo '>'.$lang_INVERT.'</option>'; 
	echo '</select>'; 
	echo '</td>';   
		
	echo '</tr>';

}//end cycle to print the status of the 8 functions concerning input duty


echo '<tr class="table_title_field_line">';
echo '<td colspan=5 align=center>';
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';


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
