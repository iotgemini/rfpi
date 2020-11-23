<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/11/2020

Description: it send the command to set the settings of the counter

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





//GETTING CUSTOM INFORMATIONS
//dechex ( int $number )

$counter=$_GET['counter'];
$counter = intval($counter, 10);
if($counter > 9999){
	$counter = 9999;
}else if($counter < 0){
	$counter = 0;
}

$int_counter_byte2 = ($counter >> 16) & 0xFF;
$int_counter_byte1 = ($counter >> 8) & 0xFF;
$int_counter_byte0 = $counter & 0xFF;

$hex_counter_byte2 = dechex( $int_counter_byte2 );
$hex_counter_byte1 = dechex( $int_counter_byte1 );
$hex_counter_byte0 = dechex( $int_counter_byte0 );

if(strlen($hex_counter_byte2)<2) $hex_counter_byte2 = "0" . $hex_counter_byte2;
if(strlen($hex_counter_byte1)<2) $hex_counter_byte1 = "0" . $hex_counter_byte1;
if(strlen($hex_counter_byte0)<2) $hex_counter_byte0 = "0" . $hex_counter_byte0;



$preset=$_GET['preset'];
$preset = intval($preset, 10);
if($preset > 9999){
	$preset = 9999;
}else if($preset < 0){
	$preset = 0;
}

$int_preset_byte2 = ($preset >> 16) & 0xFF;
$int_preset_byte1 = ($preset >> 8) & 0xFF;
$int_preset_byte0 = $preset & 0xFF;

$hex_preset_byte2 = dechex( $int_preset_byte2 );
$hex_preset_byte1 = dechex( $int_preset_byte1 );
$hex_preset_byte0 = dechex( $int_preset_byte0 );

if(strlen($hex_preset_byte2)<2) $hex_preset_byte2 = "0" . $hex_preset_byte2;
if(strlen($hex_preset_byte1)<2) $hex_preset_byte1 = "0" . $hex_preset_byte1;
if(strlen($hex_preset_byte0)<2) $hex_preset_byte0 = "0" . $hex_preset_byte0;

//building the string command and writing into fifo command:
$TAG0="DATA";
$TAG1="RF";
$TAG2=$address_peri;
$TAG3="524266" . $id_hex_special_function . $hex_counter_byte2 . $hex_counter_byte1 . $hex_counter_byte0 . $hex_preset_byte2 . $hex_preset_byte1 . $hex_preset_byte0 .  "2E2E2E2E2E2E";

$cmd_to_write_into_fifo = $TAG0." ".$TAG1." ".$TAG2." ".$TAG3." "; //the space at the end is important

$cmd_to_write_into_fifo = strtoupper ($cmd_to_write_into_fifo);//make the string upper case

writeFIFO(FIFO_GUI_CMD, $cmd_to_write_into_fifo);
usleep( 400 * 1000 );
writeFIFO(FIFO_GUI_CMD, "REFRESH PERI STATUS ".$address_peri." ");	
	
//redirecting to home
echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

//echo $cmd_to_write_into_fifo;

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



