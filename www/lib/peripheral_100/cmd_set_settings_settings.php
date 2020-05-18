<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		09/05/2020

Description: it send the command to set the settings of the TIMER

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

$sem_Led_TX_keep_OFF=$_GET['sem_Led_TX_keep_OFF'];
$ADC_compensation=$_GET['ADC_compensation'];

$int_sem_Led_TX_keep_OFF = 0xFF;
if($sem_Led_TX_keep_OFF==="1"){
	$int_sem_Led_TX_keep_OFF &= ~1;
}
if($ADC_compensation==="1"){
	$int_sem_Led_TX_keep_OFF &= ~2;
}

$str_hex_Led_TX_keep_OFF = dechex( $int_sem_Led_TX_keep_OFF );
if(strlen($str_hex_Led_TX_keep_OFF)<2) $str_hex_Led_TX_keep_OFF = "0" . $str_hex_Led_TX_keep_OFF;

//building the string command and writing into fifo command:
$TAG0="DATA";
$TAG1="RF";
$TAG2=$address_peri;
$TAG3="524266" . $id_hex_special_function . $str_hex_Led_TX_keep_OFF . "2E2E2E2E2E2E2E2E2E2E2E";

$cmd_to_write_into_fifo = $TAG0." ".$TAG1." ".$TAG2." ".$TAG3." "; //the space at the end is important

$cmd_to_write_into_fifo = strtoupper ($cmd_to_write_into_fifo);//make the string upper case
//echo $cmd_to_write_into_fifo;

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



