<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		24/06/2016

Description: it send the command to set the parameters

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
		include './lib/peri9_lib.php';  

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

$sem_status_send_array_temperature_pyrometer=$_GET['sem_status_send_array_temperature_pyrometer'];
$time_between_samples=$_GET['time_between_samples'];


if($sem_status_send_array_temperature_pyrometer==="1"){
	if($sem_status_send_array_temperature_pyrometer==="1") $sem_status_send_array_temperature_pyrometer = "01";
	else if($sem_status_send_array_temperature_pyrometer==="2") $sem_status_send_array_temperature_pyrometer = "02";
}else{
	$sem_status_send_array_temperature_pyrometer = "00";
}

//dechex ( int $number ) //convert a number in a exadecimal string

$time_between_samples_INT = intval($time_between_samples);
//$time_between_samples_INT = (int)value_8bit_from_voltage_0to10V_peri9($time_between_samples_INT);
if($time_between_samples_INT > 255)	$time_between_samples_INT = 255;
$time_between_samples_BYTE = dechex( $time_between_samples_INT );
if(strlen($time_between_samples_BYTE)<2) $time_between_samples_BYTE = "0" . $time_between_samples_BYTE;


$strCmd = "DATA RF ".$address_peri." 52426601". $sem_status_send_array_temperature_pyrometer . $time_between_samples_BYTE . "2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important


$strCmd = strtoupper ($strCmd); //make the string upper case
//echo $strCmd ;
writeFIFO(FIFO_GUI_CMD, $strCmd);
	
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



