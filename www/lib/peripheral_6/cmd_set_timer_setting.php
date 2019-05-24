<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/11/2015

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
//		Specific library for the Peri
		include './lib/peri_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$timer_enabled=$_GET['timer_enabled'];

if($timer_enabled==="1"){
	$timer_enabled = "01";
}else{
	$timer_enabled = "00";
}

//dechex ( int $number )

$timer_ms=$_GET['timer_ms'];
$int_timer_ms = intval($timer_ms, 10);
if($int_timer_ms > 1000){
	$int_timer_ms = 1000;
}
if($int_timer_ms < 0){
	$int_timer_ms = 0;
}
//echo $int_timer_ms;
$int_byte_L_timer_ms = $int_timer_ms >> 8;
$int_byte_L_timer_ms = $int_byte_L_timer_ms << 8;
$int_byte_L_timer_ms = $int_timer_ms - $int_byte_L_timer_ms; 
$byte_L_timer_ms = dechex( $int_byte_L_timer_ms );
if(strlen($byte_L_timer_ms)<2) $byte_L_timer_ms = "0" . $byte_L_timer_ms;
//echo $byte_L_timer_ms;
$byte_H_timer_ms = dechex( $int_timer_ms >> 8 );
if(strlen($byte_H_timer_ms)<2) $byte_H_timer_ms = "0" . $byte_H_timer_ms;
//echo $byte_H_timer_ms;


$timer_SS=$_GET['timer_SS'];
$int_timer_SS = intval($timer_SS, 10);
if($int_timer_SS > 60){
	$int_timer_SS = 60;
}
if($int_timer_SS < 0){
	$int_timer_SS = 0;
}
$byte_timer_SS = dechex( $int_timer_SS );
if(strlen($byte_timer_SS)<2) $byte_timer_SS = "0" . $byte_timer_SS;


$timer_MM=$_GET['timer_MM'];
$int_timer_MM = intval($timer_MM, 10);
if($int_timer_MM > 60){
	$int_timer_MM = 60;
}
if($int_timer_MM < 0){
	$int_timer_MM = 0;
}
$byte_timer_MM = dechex( $int_timer_MM );
if(strlen($byte_timer_MM)<2) $byte_timer_MM = "0" . $byte_timer_MM;


$timer_HH=$_GET['timer_HH'];
$int_timer_HH = intval($timer_HH, 10);
if($int_timer_HH > 255){
	$int_timer_HH = 255;
}
if($int_timer_HH < 0){
	$int_timer_HH = 0;
}
$byte_timer_HH = dechex( $int_timer_HH );
if(strlen($byte_timer_HH)<2) $byte_timer_HH = "0" . $byte_timer_HH;


$strCmd = "DATA RF ".$address_peri." 52426601". $timer_enabled . $byte_H_timer_ms . $byte_L_timer_ms . $byte_timer_SS . $byte_timer_MM . $byte_timer_HH . "2E2E2E2E2E2E "; //the space at the end is important

//echo $strCmd ;
$strCmd = strtoupper ($strCmd);//make the string upper case

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



