<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/12/2017

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
$input_enabled4=$_GET['input_enabled4'];
$input_enabled5=$_GET['input_enabled5'];
$input_enabled6=$_GET['input_enabled6'];
$input_enabled7=$_GET['input_enabled7'];


$byte_input_enabled = 0;

if($input_enabled0==="1"){
	$byte_input_enabled |= 1;
}
if($input_enabled1==="1"){
	$byte_input_enabled |= 2;
}
if($input_enabled2==="1"){
	$byte_input_enabled |= 4;
}
if($input_enabled3==="1"){
	$byte_input_enabled |= 8;
}
if($input_enabled4==="1"){
	$byte_input_enabled |= 16;
}
if($input_enabled5==="1"){
	$byte_input_enabled |= 32;
}
if($input_enabled6==="1"){
	$byte_input_enabled |= 64;
}
if($input_enabled7==="1"){
	$byte_input_enabled |= 128;
}

//dechex ( int $number ) //convert a number in a exadecimal string

if($byte_input_enabled > 255)	$byte_input_enabled = 255;
$byte_hex_input_enabled = dechex( $byte_input_enabled );
if(strlen($byte_hex_input_enabled)<2) $byte_hex_input_enabled = "0" . $byte_hex_input_enabled;




//building the string command and writing into fifo command:
$TAG0="DATA";
$TAG1="RF";
$TAG2=$address_peri;
$TAG3="524266" . $id_hex_special_function . $byte_hex_input_enabled . "2E2E2E2E2E2E2E2E2E2E2E";

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



