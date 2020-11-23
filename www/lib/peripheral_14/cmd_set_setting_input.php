<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		03/11/2017

Description: it send the command to set the input duty

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
//		Specific library for the Peri_12
//		include './lib/peri_12_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
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





$input_enabled0=$_GET['input_enabled0'];
$input_enabled1=$_GET['input_enabled1'];
$input_enabled2=$_GET['input_enabled2'];
$input_enabled3=$_GET['input_enabled3'];
$output_to_control0=$_GET['output_to_control0'];
$output_to_control1=$_GET['output_to_control1'];
$output_to_control2=$_GET['output_to_control2'];
$output_to_control3=$_GET['output_to_control3'];

/*
$ADC0_enabled=$_GET['ADC0_enabled'];
$ADC0_trigger_low=$_GET['ADC0_trigger_low'];
$ADC0_trigger_high=$_GET['ADC0_trigger_high'];
$ADC1_enabled=$_GET['ADC1_enabled'];
$ADC1_trigger_low=$_GET['ADC1_trigger_low'];
$ADC1_trigger_high=$_GET['ADC1_trigger_high'];

$ADC0_output_to_control=$_GET['ADC0_output_to_control'];
$ADC1_output_to_control=$_GET['ADC1_output_to_control'];
*/


if($input_enabled0==="1"){
	if($output_to_control0==="1") $input_enabled0 = "01";
	else if($output_to_control0==="2") $input_enabled0 = "02";
}else{
	$input_enabled0 = "00";
}
if($input_enabled1==="1"){
	if($output_to_control1==="1") $input_enabled1 = "01";
	else if($output_to_control1==="2") $input_enabled1 = "02";
}else{
	$input_enabled1 = "00";
}
if($input_enabled2==="1"){
	if($output_to_control2==="1") $input_enabled2 = "01";
	else if($output_to_control2==="2") $input_enabled2 = "02";
}else{
	$input_enabled2 = "00";
}
if($input_enabled3==="1"){
	if($output_to_control3==="1") $input_enabled3 = "01";
	else if($output_to_control3==="2") $input_enabled3 = "02";
}else{
	$input_enabled3 = "00";
}

/*
if($ADC0_enabled==="1"){
	if($ADC0_output_to_control==="1") $ADC0_enabled = "01";
	else if($ADC0_output_to_control==="2") $ADC0_enabled = "02";
}else{
	$ADC0_enabled = "00";
}

if($ADC1_enabled==="1"){
	if($ADC1_output_to_control==="1") $ADC1_enabled = "01";
	else if($ADC1_output_to_control==="2") $ADC1_enabled = "02";
}else{
	$ADC1_enabled = "00";
}

//dechex ( int $number ) //convert a number in a exadecimal string

$ADC0_trigger_low_INT = floatval($ADC0_trigger_low);
$ADC0_trigger_low_INT = (int)value_8bit_from_voltage_0to10V_peri_12($ADC0_trigger_low_INT);
if($ADC0_trigger_low_INT > 255)	$ADC0_trigger_low_INT = 255;
$ADC0_trigger_low_BYTE = dechex( $ADC0_trigger_low_INT );
if(strlen($ADC0_trigger_low_BYTE)<2) $ADC0_trigger_low_BYTE = "0" . $ADC0_trigger_low_BYTE;

$ADC0_trigger_high_INT = floatval($ADC0_trigger_high);
$ADC0_trigger_high_INT = (int)value_8bit_from_voltage_0to10V_peri_12($ADC0_trigger_high_INT);  
if($ADC0_trigger_high_INT > 255)	$ADC0_trigger_high_INT = 255; 
$ADC0_trigger_high_BYTE = dechex( $ADC0_trigger_high_INT ); 
if(strlen($ADC0_trigger_high_BYTE)<2) $ADC0_trigger_high_BYTE = "0" . $ADC0_trigger_high_BYTE;

$ADC1_trigger_low_INT = floatval($ADC1_trigger_low);
$ADC1_trigger_low_INT = (int)value_8bit_from_voltage_0to10V_peri_12($ADC1_trigger_low_INT);
if($ADC1_trigger_low_INT > 255)	$ADC1_trigger_low_INT = 255;
$ADC1_trigger_low_BYTE = dechex( $ADC1_trigger_low_INT );
if(strlen($ADC1_trigger_low_BYTE)<2) $ADC1_trigger_low_BYTE = "0" . $ADC1_trigger_low_BYTE;

$ADC1_trigger_high_INT = floatval($ADC1_trigger_high);
$ADC1_trigger_high_INT = (int)value_8bit_from_voltage_0to10V_peri_12($ADC1_trigger_high_INT);
if($ADC1_trigger_high_INT > 255)	$ADC1_trigger_high_INT = 255;
$ADC1_trigger_high_BYTE = dechex( $ADC1_trigger_high_INT );
if(strlen($ADC1_trigger_high_BYTE)<2) $ADC1_trigger_high_BYTE = "0" . $ADC1_trigger_high_BYTE;
*/





//building the string command and writing into fifo command:
$TAG0="DATA";
$TAG1="RF";
$TAG2=$address_peri;
//$TAG3="524266" . $id_hex_special_function .$input_enabled . $ADC0_enabled . $ADC0_trigger_low_BYTE . $ADC0_trigger_high_BYTE . $ADC1_enabled . $ADC1_trigger_low_BYTE . $ADC1_trigger_high_BYTE . "2E2E2E2E2E";
$TAG3="524266" . $id_hex_special_function .$input_enabled0 . $input_enabled1 . $input_enabled2 . $input_enabled3 . "2E2E2E2E2E2E2E2E";

$cmd_to_write_into_fifo = $TAG0." ".$TAG1." ".$TAG2." ".$TAG3." "; //the space at the end is important

$cmd_to_write_into_fifo = strtoupper ($cmd_to_write_into_fifo);//make the string upper case

writeFIFO(FIFO_GUI_CMD, $cmd_to_write_into_fifo);
	
	
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



