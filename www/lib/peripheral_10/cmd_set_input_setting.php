<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		08/05/2016

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
//		Specific library for the Peri10
		include './lib/peri10_lib.php';  

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

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$input_enabled=$_GET['input_enabled'];
$output_to_control=$_GET['output_to_control'];


$IN0_enabled=$_GET['IN0_enabled'];
$IN0_trigger_low=$_GET['IN0_trigger_low'];
$IN0_trigger_high=$_GET['IN0_trigger_high'];
$IN1_enabled=$_GET['IN1_enabled'];
$IN1_trigger_low=$_GET['IN1_trigger_low'];
$IN1_trigger_high=$_GET['IN1_trigger_high'];

$IN0_output_to_control=$_GET['IN0_output_to_control'];
$IN1_output_to_control=$_GET['IN1_output_to_control'];


if($input_enabled==="1"){
	if($output_to_control==="1") $input_enabled = "01";
	else if($output_to_control==="2") $input_enabled = "02";
}else{
	$input_enabled = "00";
}

if($IN0_enabled==="1"){
	if($IN0_output_to_control==="1") $IN0_enabled = "01";
	else if($IN0_output_to_control==="2") $IN0_enabled = "02";
}else{
	$IN0_enabled = "00";
}

if($IN1_enabled==="1"){
	if($IN1_output_to_control==="1") $IN1_enabled = "01";
	else if($IN1_output_to_control==="2") $IN1_enabled = "02";
}else{
	$IN1_enabled = "00";
}

//dechex ( int $number ) //convert a number in a exadecimal string


$IN0_trigger_low_INT = floatval($IN0_trigger_low);
$IN0_trigger_low_INT = (int)value_8bit_from_voltage_0to10V_peri10($IN0_trigger_low_INT);
if($IN0_trigger_low_INT > 255)	$IN0_trigger_low_INT = 255;
$IN0_trigger_low_BYTE = dechex( $IN0_trigger_low_INT );
if(strlen($IN0_trigger_low_BYTE)<2) $IN0_trigger_low_BYTE = "0" . $IN0_trigger_low_BYTE;

$IN0_trigger_high_INT = floatval($IN0_trigger_high);
$IN0_trigger_high_INT = (int)value_8bit_from_voltage_0to10V_peri10($IN0_trigger_high_INT);  
if($IN0_trigger_high_INT > 255)	$IN0_trigger_high_INT = 255; 
$IN0_trigger_high_BYTE = dechex( $IN0_trigger_high_INT ); 
if(strlen($IN0_trigger_high_BYTE)<2) $IN0_trigger_high_BYTE = "0" . $IN0_trigger_high_BYTE;

$IN1_trigger_low_INT = floatval($IN1_trigger_low);
$IN1_trigger_low_INT = (int)value_8bit_from_voltage_0to10V_peri10($IN1_trigger_low_INT);
if($IN1_trigger_low_INT > 255)	$IN1_trigger_low_INT = 255;
$IN1_trigger_low_BYTE = dechex( $IN1_trigger_low_INT );
if(strlen($IN1_trigger_low_BYTE)<2) $IN1_trigger_low_BYTE = "0" . $IN1_trigger_low_BYTE;

$IN1_trigger_high_INT = floatval($IN1_trigger_high);
$IN1_trigger_high_INT = (int)value_8bit_from_voltage_0to10V_peri10($IN1_trigger_high_INT);
if($IN1_trigger_high_INT > 255)	$IN1_trigger_high_INT = 255;
$IN1_trigger_high_BYTE = dechex( $IN1_trigger_high_INT );
if(strlen($IN1_trigger_high_BYTE)<2) $IN1_trigger_high_BYTE = "0" . $IN1_trigger_high_BYTE;

$strCmd = "DATA RF ".$address_peri." 52426603". $input_enabled . $IN0_enabled . $IN0_trigger_low_BYTE . $IN0_trigger_high_BYTE . $IN1_enabled . $IN1_trigger_low_BYTE . $IN1_trigger_high_BYTE . "2E2E2E2E2E "; //the space at the end is important


$strCmd = strtoupper ($strCmd); //make the string upper case
//echo $strCmd ;
writeFIFO(FIFO_GUI_CMD, $strCmd);

//header('Location: ./xxxxxxx.php?address_peri='.$address_peri.'&address_peri='.$address_peri.'&counter='.$counter.'&cont_retry='.$cont_retry.'&redirect_page='.$redirect_page);
//header('Location: ../.././index.php');


//header('Location: ../.././read_fifo_timer_after_apply_setting.php?position_id='.$position_id.'&redirect_page=./lib/peripheral_3/reinstall_after_gpio_setting');
	
	
	
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


//redirect to page 
echo '<script type="text/javascript">';
echo 'setTimeout("'; 
	echo "location.href = '../.././index.php';";
echo '", 1500);'; 
echo '</script>';

echo '</div>';
echo '</body></html>';


?>



