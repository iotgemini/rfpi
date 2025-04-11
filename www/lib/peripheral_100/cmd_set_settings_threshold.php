<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		11/04/2025

Description: it send the command to set the settings of the THRESHOLD to control output from analogue Input 

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
		
//		Specific library for the Peri_100
		include './peri_100_lib.php'; 
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_setting_sent="Setting sent!";
if($_SESSION["language"]=="IT"){
	$lang_setting_sent="Impostazioni inviate!";
}else if($_SESSION["language"]=="FR"){	
	$lang_setting_sent="Param&egrave;tres envoy&eacute;s!";
}else if($_SESSION["language"]=="SP"){	
	$lang_setting_sent="Configuraci&oacute;n enviados!";
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];

$data0 = $_GET['data0'];
$data1 = $_GET['data1'];
$data2 = $_GET['data2'];
$data3 = $_GET['data3'];

$MCU_Volts_raw_value = $data0;


//GETTING CUSTOM INFORMATIONS

$fun_input_ctrl_output[8];
$byte_fun_input_ctrl_output[16];
for($i=0;$i<8;$i++)
	$fun_input_ctrl_output[$i]=0;
for($i=0;$i<16;$i++)
	$byte_fun_input_ctrl_output[$i]=' ';
$fun8=0;
$fun9=0;
$byte_fun8=0;
$byte_fun9=0;
$l=0;
for($i=0;$i<2;$i++){
	$threshold_low[$i] = $_GET['threshold_low'.$i];
	$threshold_high[$i] = $_GET['threshold_high'.$i];
	$input_analogue[$i] = $_GET['input_analogue'.$i];
	$output_to_control[$i] = $_GET['output_to_control'.$i];
	$status_output_to_set[$i] = $_GET['status_output_to_set'.$i];
	$input_shield_name[$i] = $_GET['input_shield_name'.$input_analogue[$i].$i];
	
/*	echo 'threshold_low['.$i.'] = '.$threshold_low[$i].'<br>';
	echo 'threshold_high['.$i.'] = '.$threshold_high[$i].'<br>';
	echo 'input_analogue['.$i.'] = '.$input_analogue[$i].'<br>';
	echo 'output_to_control['.$i.'] = '.$output_to_control[$i].'<br>';
	echo 'status_output_to_set['.$i.'] = '.$status_output_to_set[$i].'<br>';
	echo 'input_shield_name['.$i.'] = '.$input_shield_name[$i].'<br>';*/
	
	//echo $input_shield_name[$i];echo ' <br>';

	if($input_shield_name[$i]==="MCP9701A" || $input_shield_name[$i]==="mcp9701a"){
		$threshold_low[$i] = the_ADC10bit_value_from_temperature_MCP9701_peri_100($threshold_low[$i], $MCU_Volts_raw_value); 	//return 10bits on 2bytes of the ADC raw value of the temperature
		$threshold_high[$i] = the_ADC10bit_value_from_temperature_MCP9701_peri_100($threshold_high[$i], $MCU_Volts_raw_value); 	//return 10bits on 2bytes of the ADC raw value of the temperature
	}else if($input_shield_name[$i]==="DHT11" || $input_shield_name[$i]==="dht11" || $input_shield_name[$i]==="DHT22" || $input_shield_name[$i]==="dht22"){
		if($i==0){
			$threshold_low[$i] = threshold_value_from_tempORhumid_DHT11_peri_100($threshold_low[$i]);
			$threshold_high[$i] = threshold_value_from_tempORhumid_DHT11_peri_100($threshold_high[$i]);
		}else{
			$threshold_low[$i] = threshold_value_from_tempORhumid_DHT11_peri_100($threshold_low[$i]);
			$threshold_high[$i] = threshold_value_from_tempORhumid_DHT11_peri_100($threshold_high[$i]);
		}
	}else if($input_shield_name[$i]==="ADC0V5V" || $input_shield_name[$i]==="adc0v5v"){
		$threshold_low[$i] = ADC10bit_from_voltage_0to5V_peri_100($threshold_low[$i],$MCU_Volts_raw_value);
		$threshold_high[$i] = ADC10bit_from_voltage_0to5V_peri_100($threshold_high[$i],$MCU_Volts_raw_value);
	}else{
		
	}
	//echo $_GET['threshold_low'.$i]; echo ' = '; echo $threshold_low[$i]; echo '<br>';
	//echo $_GET['threshold_high'.$i]; echo ' = '; echo $threshold_high[$i]; echo '<br><br>';
			
				
	//filling the first byte
	$fun_input_ctrl_output[($i*5)] = (intval($input_analogue[$i], 10)) & 0x07; //id input to use to control the output with THRESHOLDS
	$fun_input_ctrl_output[($i*5)] |= ((intval($output_to_control[$i], 10) & 0x07)<<3); //id output to control
	$fun_input_ctrl_output[($i*5)] |= ((intval($status_output_to_set[$i], 10) & 0x03)<<6); //way to control the selected output (OFF-ON, ON-OFF, DISABLED)

	
	//adjusting the byte0 to be sendable
	$byte_temp = dechex( $fun_input_ctrl_output[$i*5] );
	if(strlen($byte_temp)<2) $byte_temp = "0" . $byte_temp;
	$byte_fun_input_ctrl_output[$l] = $byte_temp[0];
	$byte_fun_input_ctrl_output[$l+1] = $byte_temp[1];
	$l+=2;
	
	
	//filling Byte1+Byte2 for LOW THRESHOLD
	$fun_input_ctrl_output[($i*5)+1] = $threshold_low[$i] & 0xFF; //LSB
	$fun_input_ctrl_output[($i*5)+2] = ($threshold_low[$i] >> 8) & 0xFF; //MSB

	//adjusting the byte1 to be sendable
	$byte_temp = dechex( $fun_input_ctrl_output[($i*5)+1] );
	if(strlen($byte_temp)<2) $byte_temp = "0" . $byte_temp;
	$byte_fun_input_ctrl_output[$l] = $byte_temp[0];
	$byte_fun_input_ctrl_output[$l+1] = $byte_temp[1];
	$l+=2;
	//adjusting the byte2 to be sendable
	$byte_temp = dechex( $fun_input_ctrl_output[($i*5)+2] );
	if(strlen($byte_temp)<2) $byte_temp = "0" . $byte_temp;
	$byte_fun_input_ctrl_output[$l] = $byte_temp[0];
	$byte_fun_input_ctrl_output[$l+1] = $byte_temp[1];
	$l+=2;
	
	
	//filling Byte3+Byte4 for HIGH THRESHOLD
	$fun_input_ctrl_output[($i*5)+3] = $threshold_high[$i] & 0xFF; //LSB
	$fun_input_ctrl_output[($i*5)+4] = ($threshold_high[$i] >> 8) & 0xFF; //MSB

	//adjusting the byte3 to be sendable
	$byte_temp = dechex( $fun_input_ctrl_output[($i*5)+3] );
	if(strlen($byte_temp)<2) $byte_temp = "0" . $byte_temp;
	$byte_fun_input_ctrl_output[$l] = $byte_temp[0];
	$byte_fun_input_ctrl_output[$l+1] = $byte_temp[1];
	$l+=2;
	//adjusting the byte4 to be sendable
	$byte_temp = dechex( $fun_input_ctrl_output[($i*5)+4] );
	if(strlen($byte_temp)<2) $byte_temp = "0" . $byte_temp;
	$byte_fun_input_ctrl_output[$l] = $byte_temp[0];
	$byte_fun_input_ctrl_output[$l+1] = $byte_temp[1];
	$l+=2;

}
for($i=0;$i<20;$i++){ 
	if($byte_fun_input_ctrl_output[$i]==='a') $byte_fun_input_ctrl_output[$i] = 'A';
	else if($byte_fun_input_ctrl_output[$i]==='b') $byte_fun_input_ctrl_output[$i] = 'B';
	else if($byte_fun_input_ctrl_output[$i]==='c') $byte_fun_input_ctrl_output[$i] = 'C';
	else if($byte_fun_input_ctrl_output[$i]==='d') $byte_fun_input_ctrl_output[$i] = 'D';
	else if($byte_fun_input_ctrl_output[$i]==='e') $byte_fun_input_ctrl_output[$i] = 'E';
	else if($byte_fun_input_ctrl_output[$i]==='f') $byte_fun_input_ctrl_output[$i] = 'F';
	//echo '['.$i.']='. $byte_fun_input_ctrl_output[$i] .'<br>';
} 

//building the string command and writing into fifo command:
$TAG0="DATA";
$TAG1="RF";
$TAG2=$address_peri;
$TAG3="524266" . $id_hex_special_function; for($i=0;$i<20;$i++){$TAG3=$TAG3 . $byte_fun_input_ctrl_output[$i];} $TAG3=$TAG3 . "2E2E";

$cmd_to_write_into_fifo = $TAG0." ".$TAG1." ".$TAG2." ".$TAG3." "; //the space at the end is important

$cmd_to_write_into_fifo = strtoupper ($cmd_to_write_into_fifo);//make the string upper case

writeFIFO(FIFO_GUI_CMD, $cmd_to_write_into_fifo);
	
//echo $cmd_to_write_into_fifo;
	
	
//redirecting to home
echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';
echo '<br>';

//echo 'Setting sent!';
echo $lang_setting_sent;

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';


//redirecting to next page
$next_page = "../.././index.php";
if($redirect_page!=""){
	$next_page = $redirect_page;
}

echo '<script type="text/javascript">';
echo 'setTimeout("'; 
	echo "location.href = '". $next_page . "';";
echo '", 1500);';
echo '</script>';

echo '</div>';
echo '</body></html>';

?>



