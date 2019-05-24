<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		20/11/2015

Description: it send the command to set the settings of the ADDRESSES

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
//		Specific library for the Peri5
		include './lib/peri5_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$error_addresses = 0;
$error_general = 0;

$network_address_peri_to_control=$_GET['network_address_peri_to_control'];
if(strlen($network_address_peri_to_control)<4) $error_addresses |= 1;

$device_address_peri_to_control=$_GET['device_address_peri_to_control'];
if(strlen($device_address_peri_to_control)<4) $error_addresses |= 2;

$master_address_peri_to_control=$_GET['master_address_peri_to_control'];
if(strlen($master_address_peri_to_control)<4) $error_addresses |= 4;

$id_output_to_control=$_GET['id_output_to_control'];
$int_id_output_to_control = intval($id_output_to_control, 10);
if($int_id_output_to_control > 255){
	$error_general |= 2;
}else if($int_id_output_to_control < 0){
	$int_id_output_to_control = 0;
}
$byte_id_output_to_control = dechex( $int_id_output_to_control );
if(strlen($byte_id_output_to_control)<2) $byte_id_output_to_control = "0" . $byte_id_output_to_control ;

//echo $byte_id_output_to_control;

$id_btn=$_GET['id_btn'];
$id_btn_hex = "FF";
if($id_btn==="0"){
	$id_btn_hex = "00";
}else if($id_btn==="1"){
	$id_btn_hex = "01";
}else{
	$error_general |= 1;
}

//echo $error_general; echo '<br>'; echo $error_addresses;

$link_back = '';
$link_back .= './addresses_functions.php?address_peri='.$address_peri.'&position_id='.$position_id.'&id_btn='.$id_btn.'&network_address_peri_to_control='.$network_address_peri_to_control.'&device_address_peri_to_control='.$device_address_peri_to_control.'&master_address_peri_to_control='.$master_address_peri_to_control;


/*
$int_byte_L_id_output_to_control = $int_id_output_to_control >> 8;
$int_byte_L_id_output_to_control = $int_byte_L_id_output_to_control << 8;
$int_byte_L_id_output_to_control = $int_id_output_to_control - $int_byte_L_id_output_to_control; 
$byte_L_id_output_to_control = dechex( $int_byte_L_id_output_to_control );
if(strlen($byte_L_id_output_to_control)<2) $byte_L_id_output_to_control = "0" . $byte_L_id_output_to_control;
echo $byte_L_id_output_to_control;
$byte_H_id_output_to_control = dechex( $int_id_output_to_control >> 8 );
if(strlen($byte_H_id_output_to_control)<2) $byte_H_id_output_to_control = "0" . $byte_H_id_output_to_control;
echo $byte_H_id_output_to_control;
*/


$strCmd = "DATA RF ".$address_peri." 524266". $id_btn_hex . $id_btn_hex . $network_address_peri_to_control . $device_address_peri_to_control . $master_address_peri_to_control . $byte_id_output_to_control ."2E2E2E2E "; //the space at the end is important

//echo $strCmd ;
$strCmd = strtoupper ($strCmd);//make the string upper case

if(($error_addresses&0xFF) == 0x00 && ($error_general&0xFF) == 0x00){
	writeFIFO(FIFO_GUI_CMD, $strCmd);
}

//header('Location: ./xxxxxxx.php?address_peri='.$address_peri.'&address_peri='.$address_peri.'&counter='.$counter.'&cont_retry='.$cont_retry.'&redirect_page='.$redirect_page);
//header('Location: ../.././index.php');
	
echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';
echo '<br>';

if(($error_addresses&0xFF) == 0x00 && ($error_general&0xFF) == 0x00){
	echo 'Setting sent!';
}else{
	echo '<p color=red>';
	if( ($error_addresses&0x01) == 0x01){
		echo '<H2>Network Address is invalid!</H2>';
	}
	if( ($error_addresses&0x02) == 0x02){
		echo '<H2>Peripheral  Address is invalid!</H2>';
	}
	if( ($error_addresses&0x04) == 0x04){
		echo '<H2>Master Address is invalid!</H2>';
	}
	if( ($error_general&0x01) == 0x01){
		echo '<H2>Error in the button selection!</H2>';
	}
	if( ($error_general&0x02) == 0x02){
		echo '<H2>ID OUTPUT Higher thant 255!</H2>';
	}
	echo '<H2>Impossible to set the addresses!</H2>';
	echo '</p>';
}


/*if($error_addresses != 0){
	//button BACK
	echo '<p align=left>';
	echo '<form name="back" action="'.$link_back.'" method=GET>';
	echo '<input type=submit value="<< Back" class="btn_pag">';
	echo '</form>';
	echo '</p>';
}*/

//button HOME
echo '<p align=center>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

//redirect to page 
echo '<script type="text/javascript">';
if(($error_addresses&0xFF) == 0x00 && ($error_general&0xFF) == 0x00){
	echo 'setTimeout("'; 
		echo "location.href = '../.././index.php';";
	echo '", 1500);'; 
}else{
	/*echo 'setTimeout("'; 
		echo 'location.href = "'.$link_back.'"';
	echo '", 5500);'; */
}
echo '</script>';

echo '</div>';
echo '</body></html>';


?>



