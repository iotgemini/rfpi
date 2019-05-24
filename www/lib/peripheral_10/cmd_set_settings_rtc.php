<?php
/******************************************************************************************
Powered by:			Emanuele Aimone
Programmer: 		Emanuele Aimone
Last Update: 		24/08/2017

Description: it send the command to set the settings

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
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];

$rtc_SS=$_GET['rtc_SS'];	//seconds
$rtc_MM=$_GET['rtc_MM'];	//minutes
$rtc_HH=$_GET['rtc_HH'];	//hours
$rtc_mday=$_GET['rtc_mday'];//month day
$rtc_mon=$_GET['rtc_mon'];	//month 
$rtc_year=$_GET['rtc_year'];//year



//Checking and formatting data:
//dechex ( int $number )3

//SECONDS:
$int_rtc_SS = intval($rtc_SS, 10);
if($int_rtc_SS > 60){
	$int_rtc_SS = 60;
}
if($int_rtc_SS < 0){
	$int_rtc_SS = 0;
}
$byte_rtc_SS = dechex( $int_rtc_SS );
if(strlen($byte_rtc_SS)<2) $byte_rtc_SS = "0" . $byte_rtc_SS;

//MINUTES:
$int_rtc_MM = intval($rtc_MM, 10);
if($int_rtc_MM > 60){
	$int_rtc_MM = 60;
}
if($int_rtc_MM < 0){
	$int_rtc_MM = 0;
}
$byte_rtc_MM = dechex( $int_rtc_MM );
if(strlen($byte_rtc_MM)<2) $byte_rtc_MM = "0" . $byte_rtc_MM;

//HOURS:
$int_rtc_HH = intval($rtc_HH, 10);
if($int_rtc_HH > 255){
	$int_rtc_HH = 255;
}
if($int_rtc_HH < 0){
	$int_rtc_HH = 0;
}
$byte_rtc_HH = dechex( $int_rtc_HH );
if(strlen($byte_rtc_HH)<2) $byte_rtc_HH = "0" . $byte_rtc_HH;

//MONTH DAY:
$int_rtc_mday = intval($rtc_mday, 10);
if($int_rtc_mday > 255){
	$int_rtc_mday = 255;
}
if($int_rtc_mday < 0){
	$int_rtc_mday = 0;
}
$byte_rtc_mday = dechex( $int_rtc_mday );
if(strlen($byte_rtc_mday)<2) $byte_rtc_mday = "0" . $byte_rtc_mday;

//MONTH:
$int_rtc_mon = intval($rtc_mon, 10);
if($int_rtc_mon > 255){
	$int_rtc_mon = 255;
}
if($int_rtc_mon < 0){
	$int_rtc_mon = 0;
}
$byte_rtc_mon = dechex( $int_rtc_mon );
if(strlen($byte_rtc_mon)<2) $byte_rtc_mon = "0" . $byte_rtc_mon;

//YEAR:
$int_rtc_year = intval($rtc_year, 10);
if($int_rtc_year > 255){
	$int_rtc_year = 255;
}
if($int_rtc_year < 0){
	$int_rtc_year = 0;
}
$byte_rtc_year = dechex( $int_rtc_year );
if(strlen($byte_rtc_year)<2) $byte_rtc_year = "0" . $byte_rtc_year;



//building the string command and writing into fifo command:
$TAG0="DATA";
$TAG1="RF";
$TAG2=$address_peri;
$TAG3="524266" . $id_hex_special_function . $byte_rtc_SS . $byte_timer_SS . $byte_rtc_MM . $byte_rtc_HH . $byte_rtc_mday . $byte_rtc_mon . $byte_rtc_year .  "2E2E2E2E2E";

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



