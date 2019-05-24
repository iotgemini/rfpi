<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		11/07/2016

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

$sem_status_send_data_after_stop=$_GET['sem_status_send_data_after_stop'];
$num_data_send=$_GET['num_data_send'];

save_num_data_peri9((int)$sem_status_send_data_after_stop, (int)$num_data_send, $address_peri);

if($sem_status_send_data_after_stop==="1"){
	if($sem_status_send_data_after_stop==="1") $sem_status_send_data_after_stop = "01";
	else if($sem_status_send_data_after_stop==="2") $sem_status_send_data_after_stop = "02";
}else{
	$sem_status_send_data_after_stop = "00";
}

//dechex ( int $number ) //convert a number in a exadecimal string

$num_data_send_INT = (intval($num_data_send) >> 8) & 255;
if($num_data_send_INT > 255)	$num_data_send_INT = 255;
$num_data_send_BYTE_H = dechex( $num_data_send_INT );
if(strlen($num_data_send_BYTE_H)<2) $num_data_send_BYTE_H = "0" . $num_data_send_BYTE_H;

$num_data_send_INT = intval($num_data_send) & 255;
if($num_data_send_INT > 255)	$num_data_send_INT = 255;
$num_data_send_BYTE_L = dechex( $num_data_send_INT );
if(strlen($num_data_send_BYTE_L)<2) $num_data_send_BYTE_L = "0" . $num_data_send_BYTE_L;


$strCmd = "DATA RF ".$address_peri." 52426602". $sem_status_send_data_after_stop . $num_data_send_BYTE_H . $num_data_send_BYTE_L . "2E2E2E2E2E2E2E2E2E "; //the space at the end is important


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



