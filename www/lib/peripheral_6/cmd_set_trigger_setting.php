<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/11/2015

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
//		Specific library for the Peri
		include './lib/peri_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$byte_function_number = "02";


$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];



$trigger_enabled=$_GET['trigger_enabled'];

if($trigger_enabled==="1"){
	$trigger_enabled = "01";
}else{
	$trigger_enabled = "00";
}


$trigger_temperature_low_INT=$_GET['trigger_temperature_low_INT'];

$int_trigger_temperature_low_INT = intval($trigger_temperature_low_INT, 10);
if($int_trigger_temperature_low_INT > 90){
	$int_trigger_temperature_low_INT = 90;
}
if($int_trigger_temperature_low_INT < -25){
	$int_trigger_temperature_low_INT = -25;
}
if($int_trigger_temperature_low_INT < 0){
	$int_trigger_temperature_low_INT = 256 - ($int_trigger_temperature_low_INT * -1);
}
$byte_trigger_temperature_low_INT = dechex( $int_trigger_temperature_low_INT );
if(strlen($byte_trigger_temperature_low_INT)<2) $byte_trigger_temperature_low_INT = "0" . $byte_trigger_temperature_low_INT;


$trigger_temperature_low_DEC=$_GET['trigger_temperature_low_DEC'];
$int_trigger_temperature_low_DEC = intval($trigger_temperature_low_DEC, 10);
if($int_trigger_temperature_low_DEC > 9){
	$int_trigger_temperature_low_DEC = 9;
}
if($int_trigger_temperature_low_DEC < 0){
	$int_trigger_temperature_low_DEC = 0;
}
$byte_trigger_temperature_low_DEC = dechex( $int_trigger_temperature_low_DEC );
if(strlen($byte_trigger_temperature_low_DEC)<2) $byte_trigger_temperature_low_DEC = "0" . $byte_trigger_temperature_low_DEC;


$trigger_temperature_high_INT=$_GET['trigger_temperature_high_INT'];
$int_trigger_temperature_high_INT = intval($trigger_temperature_high_INT, 10);
if($int_trigger_temperature_high_INT > 90){
	$int_trigger_temperature_high_INT = 90;
}
if($int_trigger_temperature_high_INT < -25){
	$int_trigger_temperature_high_INT = -25;
}
if($int_trigger_temperature_high_INT < 0){
	$int_trigger_temperature_high_INT = 256 - ($int_trigger_temperature_high_INT * -1);
}
$byte_trigger_temperature_high_INT = dechex( $int_trigger_temperature_high_INT );
if(strlen($byte_trigger_temperature_high_INT)<2) $byte_trigger_temperature_high_INT = "0" . $byte_trigger_temperature_high_INT;


$trigger_temperature_high_DEC=$_GET['trigger_temperature_high_DEC'];
$int_trigger_temperature_high_DEC = intval($trigger_temperature_high_DEC, 10);
if($int_trigger_temperature_high_DEC > 9){
	$int_trigger_temperature_high_DEC = 9;
}
if($int_trigger_temperature_high_DEC < 0){
	$int_trigger_temperature_high_DEC = 0;
}
$byte_trigger_temperature_high_DEC = dechex( $int_trigger_temperature_high_DEC );
if(strlen($byte_trigger_temperature_high_DEC)<2) $byte_trigger_temperature_high_DEC = "0" . $byte_trigger_temperature_high_DEC;




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
	$byte_temperature_offset_to_send_data_temperature = dechex( $int_temperature_offset_to_send_data_temperature );
	if(strlen($byte_temperature_offset_to_send_data_temperature)<2) $byte_temperature_offset_to_send_data_temperature = "0" . $byte_temperature_offset_to_send_data_temperature;

}



$strCmd = "DATA RF ".$address_peri." 524266". $byte_function_number . $trigger_enabled . $byte_trigger_temperature_low_INT . $byte_trigger_temperature_low_DEC . $byte_trigger_temperature_high_INT . $byte_trigger_temperature_high_DEC . $byte_temperature_offset_to_send_data_temperature . "2E2E2E2E2E2E "; //the space at the end is important

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

echo 'Setting sent!';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
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



