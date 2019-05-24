<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/08/2017

Description: it send the command to set the settings of the TRIGGER

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


$byte_function_number = "02";


$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];



$trigger_enabled=$_GET['trigger_enabled'];


$output_to_control=$_GET['output_to_control'];

if($trigger_enabled==="1"){
	if($output_to_control==="1") $trigger_enabled = "01";
	else if($output_to_control==="2") $trigger_enabled = "02";
}else{
	$trigger_enabled = "00";
}


$trigger_temperature_low_INT=$_GET['trigger_temperature_low_INT'];

//$int_trigger_temperature_low_INT = intval($trigger_temperature_low_INT, 10);
$int_trigger_temperature_low_INT = floatval($trigger_temperature_low_INT);
$int_trigger_temperature_low_INT = (int)the_8bit_value_from_temperature_MCP9701_peri10($int_trigger_temperature_low_INT);
$trigger_temperature_low_BYTE = dechex( $int_trigger_temperature_low_INT );
if(strlen($trigger_temperature_low_BYTE)<2) $trigger_temperature_low_BYTE = "0" . $trigger_temperature_low_BYTE;

//echo $int_trigger_temperature_low_INT . ' = ' . $trigger_temperature_low_BYTE .'<br>';


$trigger_temperature_high_INT=$_GET['trigger_temperature_high_INT'];
$int_trigger_temperature_high_INT = floatval($trigger_temperature_high_INT);
$int_trigger_temperature_high_INT = (int)the_8bit_value_from_temperature_MCP9701_peri10($int_trigger_temperature_high_INT);
$trigger_temperature_high_BYTE = dechex( $int_trigger_temperature_high_INT );
if(strlen($trigger_temperature_high_BYTE)<2) $trigger_temperature_high_BYTE = "0" . $trigger_temperature_high_BYTE;

//echo $int_trigger_temperature_high_INT . ' = ' . $trigger_temperature_high_BYTE .'<br>';

$send_temperature_enabled = $_GET['send_temperature_enabled'];
$temperature_offset_to_send_data_temperature = "00";
$byte_temperature_offset_to_send_data_temperature = "00";
if($send_temperature_enabled==="1"){

	$temperature_offset_to_send_data_temperature=$_GET['temperature_offset_to_send_data_temperature'];
	$int_temperature_offset_to_send_data_temperature = intval($temperature_offset_to_send_data_temperature, 10);
	if($int_temperature_offset_to_send_data_temperature > 100){
		$int_temperature_offset_to_send_data_temperature = 100;
	}
	if($int_temperature_offset_to_send_data_temperature < 0){
		$int_temperature_offset_to_send_data_temperature = 0;
	}
	
	$int_temperature_offset_to_send_data_temperature = (int)the_8bit_value_from_temperature_MCP9701_peri10($int_temperature_offset_to_send_data_temperature);
	
	$byte_temperature_offset_to_send_data_temperature = dechex( $int_temperature_offset_to_send_data_temperature );
	if(strlen($byte_temperature_offset_to_send_data_temperature)<2) $byte_temperature_offset_to_send_data_temperature = "0" . $byte_temperature_offset_to_send_data_temperature;

}
//echo $int_temperature_offset_to_send_data_temperature . ' = ' . $byte_temperature_offset_to_send_data_temperature .'<br>';


$strCmd = "DATA RF ".$address_peri." 524266". $byte_function_number . $trigger_enabled . $trigger_temperature_low_BYTE . "00" . $trigger_temperature_high_BYTE . "00" . $byte_temperature_offset_to_send_data_temperature . "2E2E2E2E2E2E "; //the space at the end is important

$strCmd = strtoupper ($strCmd);//make the string upper case

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



