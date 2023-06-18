<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		28/05/2023

Description: this is a panel where to setup the THRESHOLD to control an output with an analogue input


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
		
//		Specific library for the Peri_100
		include './peri_100_lib.php';  
		
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
$lang_decription_function="When the value of the input goes above or below the thresholds then the selected output would be set.";
$lang_select_threshold_output="Select the threshold and output:";
$lang_set_status="Select the status to set:";
$lang_title_input="INPUT";
$lang_title_input_trigger="THRESHOLD TRIGGER";
$lang_title_output="OUTPUT";
$lang_title_output_status_to_set="STATUS TO SET";
$lang_title_function="FUNCTION";
$lang_title_threshold_function_status="THRESHOLDS VALUES";
$lang_rising_edge_threshold="on rising edge";
$lang_set_threshold_trigger="Select the threshold<br>trigger:";
$lang_DISABLED="DISABLED";
$lang_RISING_EDGE="RISING EDGE";
$lang_FALLING_EDGE="FALLING EDGE";
$lang_INVERT="INVERT";
$lang_ON="ON";
$lang_OFF="OFF";
$lang_ON_OFF="ON-OFF";
$lang_OFF_ON="OFF-ON";
if($_SESSION["language"]=="IT"){
	$lang_title_timer="TIMER";
	$lang_title_millisecond="Millisecondi";
	$lang_title_second="Secondi";
	$lang_title_minutes="Minuti";
	$lang_title_hours="Ore";
	$lang_title_enabled="ABILITATA";
	$lang_title_input="ENTRATA";
	$lang_title_threshold_trigger="TRIGGER DELL&apos;ENTRATA";
	$lang_title_output="USCITA";
	$lang_title_output_status_to_set="STATO DA IMPOSTARE";
	$lang_title_function="FUNZIONE";
	$lang_title_threshold_function_status="VALORI SOGLIE";
	$lang_relay="Rel&egrave;";
	$lang_decription_function="Quando il valore dell&rsquo;ingresso supera o scende al di sotto delle soglie, viene impostata l'uscita selezionata.";
	$lang_select_threshold_output="Seleziona l&apos;entrata e l&apos;uscita:";
	$lang_set_status="Seleziona lo stato<br>da impostare:";
	$lang_set_threshold_trigger="Seleziona il trigger<br>per l&apos;threshold:";
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
	$lang_title_threshold_trigger="D&Eacute;CLENCHEUR D&apos;ENTR&Eacute;E";
	$lang_title_output="SORTIE";
	$lang_title_function="FONCTION";
	$lang_title_threshold_function_status="VALEURS SEUILS";
	$lang_title_output_status_to_set="&Eacute;TAT &Agrave; D&Eacute;FINIR";
	$lang_relay="Rel&egrave;";
	$lang_decription_function="Lorsque la valeur de l&rsquo;entr&eacute;e passe au-dessus ou au-dessous des seuils, la sortie s&eacute;lectionn&eacute;e est d&eacute;finie.";
	$lang_select_threshold_output="S&eacute;lectionnez la sortie:";
	$lang_set_status="S&eacute;lectionnez le statut &agrave; d&eacute;finir:";
	$lang_set_threshold_trigger="S&eacute;lectionnez le d&eacute;clencheur<br>d&apos;entr&eacute;e:";
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
	$lang_title_threshold_trigger="GATILLO DE ENTRADA";
	$lang_title_output="SALIDA";
	$lang_title_function="FUNCION";
	$lang_title_threshold_function_status="VALORES UMBRALES";
	$lang_title_output_status_to_set="ESTADO A CONFIGURAR";
	$lang_relay="Rel&eacute;";
	$lang_decription_function="Cuando el valor de la entrada est&aacute; por encima o por debajo de los umbrales, se establecer&aacute; la salida seleccionada.";
	$lang_select_threshold_output="Seleccione la salida:";
	$lang_set_status="Seleccione el estado<br>para establecer:";
	$lang_set_threshold_trigger="Seleccione el disparador<br>de entrada:";
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
$data0 = $_GET['data0'];
$data1 = $_GET['data1'];
$data2 = $_GET['data2'];
$data3 = $_GET['data3'];


/*for($i=0;$i<32;$i+=2){
	echo $data_rfpi[$i].$data_rfpi[1+$i];
	echo " ";
}
echo "<br>";*/

$MCU_Volts_raw_value = $data0;

//estract the data for threshold Duty
$index = 8;
$l=0;
for($i=0;$i<10;$i++){
		$fun_threshold_ctrl_output[$i] = hexdec(($data_rfpi[$l+$index].$data_rfpi[1+$l+$index]));
		$l+=2;
		//echo $fun_threshold_ctrl_output[$i];
		//echo " ";
}
// Byte and Bits meaning inside fun_threshold_ctrl_output[] from byte 0 to byte 9
// ------------------- BYTE0	=	ID INPUT-OUTPUT FOR THRESHOLD0 -----------------------
//		B0:bit0 (LSB) 	to	 B0:bit2 (MSB)	=	ID of the input that control the output
//		B0:bit3 (LSB) 	to	 B0:bit5 (MSB)	=	ID of the output to control
//		B0:bit6 (LSB) 	to	 B0:bit7 (MSB)	=	Way to control output:
//											___________________________________________________________________
//											| VALUE bit6-bit7		< LOW THRESHOLD0		> HIGH THRESHOLD0 |
//											|_________________________________________________________________|
//											|		0x0 			SET OUT LOW 			SET OUT HIGH	  |
//											|		0x1 			SET OUT HIGH 			SET OUT LOW		  |
//											|		0x2				FUNCTION DISABLED		FUNCTION DISABLED |
//											|_________________________________________________________________|
// ---------------------------------------------------------------------------------------
// ------------------- BYTE1+BYTE2	=	HIGH THRESHOLD0 ----------------------------------
//		B1:bit0 (LSB),	B1:bit1,	......	B2:bit6,	B2:bit7 (MSB)
// ---------------------------------------------------------------------------------------
// ------------------- BYTE3+BYTE4	=	LOW THRESHOLD0 ----------------------------------
//		B3:bit0 (LSB),	B3:bit1,	......	B4:bit6,	B4:bit7 (MSB)
// ---------------------------------------------------------------------------------------

// ------------------- BYTE5	=	ID INPUT-OUTPUT FOR THRESHOLD1 -----------------------
//		B5:bit0 (LSB) 	to	 B5:bit2 (MSB)	=	ID of the input that control the output
//		B5:bit3 (LSB) 	to	 B5:bit5 (MSB)	=	ID of the output to control
//		B5:bit6 (LSB) 	to	 B5:bit7 (MSB)	=	Way to control output:
//											___________________________________________________________________
//											| VALUE bit6-bit7		< LOW THRESHOLD1		> HIGH THRESHOLD1 |
//											|_________________________________________________________________|
//											|		0x0 			SET OUT LOW 			SET OUT HIGH	  |
//											|		0x1 			SET OUT HIGH 			SET OUT LOW		  |
//											|		0x2				FUNCTION DISABLED		FUNCTION DISABLED |
//											|_________________________________________________________________|
// ---------------------------------------------------------------------------------------
// ------------------- BYTE6+BYTE7	=	HIGH THRESHOLD1 ----------------------------------
//		B6:bit0 (LSB),	B6:bit1,	......	B7:bit6,	B7:bit7 (MSB)
// ---------------------------------------------------------------------------------------
// ------------------- BYTE8+BYTE9	=	LOW THRESHOLD1 -----------------------------------
//		B8:bit0 (LSB),	B8:bit1,	......	B9:bit6,	B9:bit7 (MSB)
// ---------------------------------------------------------------------------------------




/************************************* BEGIN: DECODE JSON FILE *************************************/
	
	
	$count_digital_input_json = 0;
	$count_digital_output_json = 0;
	$count_analogue_input_json = 0;
	$count_analogue_output_json = 0;
	
	$sem_RGB_Shield_connected = 0;
	
	//$array_pin_digital_input_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	//$array_pin_digital_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	//$array_pin_analogue_input_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	//$array_pin_analogue_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		
	$array_pin_digital_input_json = [0,0,0,0,0,0,0,0,0];
	$array_pin_digital_outputs_json = [0,0,0,0,0,0,0,0,0];
	$array_pin_analogue_input_json = [0,0,0,0,0,0,0,0,0];
	$array_pin_analogue_outputs_json = [0,0,0,0,0,0,0,0,0];
		
		
	$array_shield_name_digital_input_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_name_analogue_input_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_name_digital_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_name_analogue_outputs_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
		
		
	$array_shield_mpn_digital_input_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
	$array_shield_mpn_analogue_input_json = ["NULL","NULL","NULL","NULL","NULL","NULL","NULL","NULL"];
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
									
									$array_pin_digital_input_json,
									$array_pin_digital_outputs_json,
									$array_pin_analogue_input_json,
									$array_pin_analogue_outputs_json,
									
									$array_shield_name_digital_input_json,
									$array_shield_name_digital_outputs_json,
									$array_shield_name_analogue_input_json,
									$array_shield_name_analogue_outputs_json,
									
									$array_shield_mpn_digital_input_json,
									$array_shield_mpn_digital_outputs_json,
									$array_shield_mpn_analogue_inputs_json,
									$array_shield_mpn_analogue_outputs_json,
									
									$array_id_digital_outputs_json,
									$array_id_analogue_outputs_json,
									
									//variables to run the function
									$address_peri,
									$path_conf_json,
									$numthreshold,
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

echo '<form name="set_functions" action="./cmd_set_settings_threshold.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_hex_special_function" value="'. $id_hex_special_function . '">';
echo '<input type=hidden name="redirect_page" value="'. $redirect_page . '">';

echo '<input type=hidden name="data0" value="'.$data0.'">'; 
echo '<input type=hidden name="data1" value="'.$data1.'">'; 
echo '<input type=hidden name="data2" value="'.$data2.'">'; 
echo '<input type=hidden name="data3" value="'.$data3.'">'; 

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';  
echo '<td class="td_peripheral">'.$lang_title_input.'</td>'; 
echo '<td class="td_peripheral">'.$lang_title_threshold_function_status.'</td>';
echo '<td class="td_peripheral">'.$lang_title_output.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_output_status_to_set.'</td>'; 
echo '</tr>';

for($i=0;$i<2;$i++){ //begin cycle to print the status of the 2 functions concerning threshold duty
	
	if(($i%2)==0) 
		echo '<tr class="table_line_even">';
	else
		echo '<tr class="table_line_odd">';
	
	echo '<td class="td_peripheral">'.$lang_title_function.' '.$i.':</td>';  

	
	//I going to write into INPUT TEXT the actual values of the THRESHOLDS
	$value_threshold_low = $fun_threshold_ctrl_output[($i*5)+2] << 8;
	$value_threshold_low |= $fun_threshold_ctrl_output[($i*5)+1];
	$value_threshold_high = $fun_threshold_ctrl_output[($i*5)+4] << 8;
	$value_threshold_high |= $fun_threshold_ctrl_output[($i*5)+3]; 


	$functionToRunToShowRightThresholdValue ="";
	echo '<td colspan=1 class="td_peripheral" align=left>';
	if($sem_json_exist==1){
		$l=0;
		$counter=0;
		while ($l<$count_analogue_input_json) {

			echo '<script>'; 
			echo 'function SetUnit'.$l.$i.'(shield_name) {';
			echo 'var UnitMeasureToShow = "";';
			echo 'if(shield_name==="MCP9701A" || shield_name==="mcp9701a"){';
			echo '	UnitMeasureToShow = "°C";';
			$varTemp = temperature_MCP9701_from_ADC_raw_value_peri_100($value_threshold_low,$MCU_Volts_raw_value);
			$varTemp = number_format((float)strval($varTemp), 1, '.', ''); 
			echo '	document.getElementById("threshold_low'.$i.'").value = '.$varTemp.';';
			$varTemp = temperature_MCP9701_from_ADC_raw_value_peri_100($value_threshold_high,$MCU_Volts_raw_value);
			$varTemp = number_format((float)strval($varTemp), 1, '.', ''); 
			echo '	document.getElementById("threshold_high'.$i.'").value = '.$varTemp.';';
			echo '}else if(shield_name==="DHT11" || shield_name==="dht11" || shield_name==="DHT22" || shield_name==="dht22"){';
					if($i==0){ //the first threshold control for DHT11 is for umidity
						echo '	UnitMeasureToShow = "%";';
						$varTemp = tempORhumid_DHT11_from_threshold_value_peri_100($value_threshold_low);
						echo '	document.getElementById("threshold_low'.$i.'").value = '.$varTemp.';';
						$varTemp = tempORhumid_DHT11_from_threshold_value_peri_100($value_threshold_high);
						echo '	document.getElementById("threshold_high'.$i.'").value = '.$varTemp.';';
					}else{ //the second threshold control for DHT11 is for temperature
						echo '	UnitMeasureToShow = "°C";';
						$varTemp = tempORhumid_DHT11_from_threshold_value_peri_100($value_threshold_low);
						//$varTemp = number_format((float)strval($varTemp), 1, '.', '');
						echo '	document.getElementById("threshold_low'.$i.'").value = '.$varTemp.';';
						$varTemp = tempORhumid_DHT11_from_threshold_value_peri_100($value_threshold_high);
						//$varTemp = number_format((float)strval($varTemp), 1, '.', '');
						echo '	document.getElementById("threshold_high'.$i.'").value = '.$varTemp.';';
					}
			echo '}else if(shield_name==="ADC0V5V" || shield_name==="adc0v5v"){';
			echo '	UnitMeasureToShow = "mV";';
			$varTemp = str_voltage_0to5V_from_10bit_value_peri_100($value_threshold_low,$MCU_Volts_raw_value)*1000;
			echo '	document.getElementById("threshold_low'.$i.'").value = '.$varTemp.';';
			$varTemp = str_voltage_0to5V_from_10bit_value_peri_100($value_threshold_high,$MCU_Volts_raw_value)*1000;
			echo '	document.getElementById("threshold_high'.$i.'").value = '.$varTemp.';';
			echo '}';
			
			echo 'document.getElementById("unit_threshold_low'.$i.'").value = UnitMeasureToShow;';
			echo 'document.getElementById("unit_threshold_high'.$i.'").value = UnitMeasureToShow;';

			echo '}'; 
			echo '</script>';

			echo '<input type=hidden name="input_shield_name'.$l.$i.'" id="input_shield_name'.$l.$i.'" value="'.$array_shield_mpn_analogue_inputs_json[$l].'">';
			echo '<input type="radio" name="input_analogue'.$i.'" id="input_analogue'.$i.'" onclick=SetUnit'.$l.$i.'("'.$array_shield_mpn_analogue_inputs_json[$l].'"); value="'.($counter).'" '; if(($fun_threshold_ctrl_output[($i*5)]&0b00000111)==($counter)) echo 'checked'; echo '>';
			if(($fun_threshold_ctrl_output[($i*5)]&0b00000111)==($counter)){
				echo '<script>'; 
				$functionToRunToShowRightThresholdValue = 'SetUnit'.$l.$i.'("'.$array_shield_mpn_analogue_inputs_json[$l].'");';
				echo '</script>'; 
			}
			echo $array_shield_name_analogue_input_json[$l] . ' ';
			echo "PIN" . $array_pin_analogue_input_json[$l].' ';
			echo 'ID' . $counter;
			echo '<br>';

			$l++;
			$counter++;
		}
	} 
	echo '</td>';   
	

	echo '<td colspan=1 class="td_peripheral" align=left>';

	echo 'LOW THRESHOLD = <input type="text" name="threshold_low'.$i.'" id="threshold_low'.$i.'" value="'.($value_threshold_low).'" '; echo 'size="5" maxlength="5">';
	echo '<input type="text" disabled name="unit_threshold_low'.$i.'" id="unit_threshold_low'.$i.'" value="" '; echo 'size="2" maxlength="2">';
	echo '<br>';
	echo 'HIGH THRESHOLD = <input type="text" name="threshold_high'.$i.'" id="threshold_high'.$i.'" value="'.($value_threshold_high).'" '; echo 'size="5" maxlength="5">';
	echo '<input type="text" disabled name="unit_threshold_high'.$i.'" id="unit_threshold_high'.$i.'" value="" '; echo 'size="2" maxlength="2">';
	
	echo '<script>'; 
	echo $functionToRunToShowRightThresholdValue;
	echo '</script>'; 
				
	echo '</td>'; 
	
	
	
	echo '<td colspan=1 class="td_peripheral" align=left>';
	if($sem_json_exist==1){
		$l=0;
		$counter=0;
		while ($l<$count_digital_output_json) {

			echo '<input type="radio" name="output_to_control'.$i.'" value="'.($counter).'" '; if((($fun_threshold_ctrl_output[($i*5)]&0b00111000)>>3)==($counter)) echo 'checked'; echo '>';
			echo $array_shield_name_digital_outputs_json[$l] . ' ';
			echo "PIN" . $array_pin_digital_outputs_json[$l].' ';
			echo 'ID' . $counter;
			echo '<br>';

			$l++;
			$counter++;
		}
		$l=0;
		while ($l<$count_analogue_output_json) {

			echo '<input type="radio" name="output_to_control'.$i.'" value="'.($counter).'" '; if((($fun_threshold_ctrl_output[($i*5)]&0b00111000)>>3)==($counter)) echo 'checked'; echo '>';
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
	$current_status_to_set = ($fun_threshold_ctrl_output[($i*5)]&0b11000000)>>6;
	echo '				<option value="1"'; if($current_status_to_set==1) echo "selected"; echo '>'.$lang_OFF_ON.'</option>'; 
	echo '				<option value="2"'; if($current_status_to_set==2) echo "selected"; echo '>'.$lang_ON_OFF.'</option>'; 
	echo '				<option value="3"'; if($current_status_to_set==3) echo "selected"; echo '>'.$lang_DISABLED.'</option>'; 
	echo '</select>'; 
	echo '</td>';   
		
	echo '</tr>';

}//end cycle to print the status of the 8 functions concerning threshold duty

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
