<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		09/03/2017

Description: it send the parameters with all temperatures for the thermostat

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
$lang_sending_settings="Sending settings ....";
$lang_btn_stop="STOP";
if($_SESSION["language"]=="IT"){
	$lang_setting_sent="Impostazioni inviate!";
	$lang_sending_settings="Sto inviando ....";
}else if($_SESSION["language"]=="FR"){	
	$lang_setting_sent="Param&egrave;tres envoy&eacute;s!";
	$lang_sending_settings="J&#39;envoie des donn&eacute;es ....";
}else if($_SESSION["language"]=="SP"){	
	$lang_setting_sent="Configuraci&oacute;n enviados!";
	$lang_sending_settings="Estoy enviando datos ....";
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];


$num_special_function=$_GET['num_special_function'];
$lenght_array_data_file=$_GET['lenght_array_data_file'];
//$lenght_array_data_file=10;

if(file_exists(FIFO_SEND_BYTES_U)!==TRUE){ 
	//the file does not exist!
}else{
	unlink ( FIFO_SEND_BYTES_U );
}

//posix_mkfifo(FIFO_SEND_BYTES_U, 0666);
$myfile = fopen(FIFO_SEND_BYTES_U, "w") or die("<br>Unable to create data file!");
$array_data_to_sed[$lenght_array_data_file];
for($cont_day=0;$cont_day<7;$cont_day++){
	for($cont_hour=0;$cont_hour<24;$cont_hour++){
		$array_data_to_sed[$cont_day+$cont_hour]=$_GET['temperature_day'.$cont_day.'_hour'.$cont_hour];
		$txt = $array_data_to_sed[$cont_day+$cont_hour] . "\n";
		fwrite($myfile, $txt);
		//echo "<br>".$array_data_to_sed[$cont_day+$cont_hour];
		//if($cont_day==6 && $cont_hour==23)
		//	echo "<br>".$array_data_to_sed[$cont_day+$cont_hour]."<br>";
	}
}
$byte168=$_GET['byte168'];
$txt = $byte168 . "\n";
fwrite($myfile, $txt);
$byte169=$_GET['byte169'];
$txt = $byte169 . "\n";
fwrite($myfile, $txt);
fclose($myfile);


/*if($IN1_enabled==="1"){
	if($IN1_output_to_control==="1") $IN1_enabled = "01";
	else if($IN1_output_to_control==="2") $IN1_enabled = "02";
}else{
	$IN1_enabled = "00";
}*/

//dechex ( int $number ) //convert a number in a exadecimal string

/*$IN1_trigger_high_INT = floatval($IN1_trigger_high);
$IN1_trigger_high_INT = (int)value_8bit_from_voltage_0to10V_peri10($IN1_trigger_high_INT);
if($IN1_trigger_high_INT > 255)	$IN1_trigger_high_INT = 255;
$IN1_trigger_high_BYTE = dechex( $IN1_trigger_high_INT );
if(strlen($IN1_trigger_high_BYTE)<2) $IN1_trigger_high_BYTE = "0" . $IN1_trigger_high_BYTE;
*/

//$strCmd = "DATA RF ".$address_peri." 52426603". $input_enabled . $IN0_enabled . $IN0_trigger_low_BYTE . $IN0_trigger_high_BYTE . $IN1_enabled . $IN1_trigger_low_BYTE . $IN1_trigger_high_BYTE . "2E2E2E2E2E "; //the space at the end is important

$strCmd = "SEND_BYTES_F ".$position_id." ".$num_special_function." ".$lenght_array_data_file." "; //the space at the end is important

$strCmd = strtoupper ($strCmd); //make the string upper case
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

//button STOP CMD
echo '<form name="peri10_btn_stop_reading_settings" action="./cmd_stop_cmd.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_stop.'" class="btn_functions">';		
echo '</form>';

//echo '<br>';
echo '<br>';


echo $lang_sending_settings;

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';


//redirect to page 
echo '<script type="text/javascript">';
echo 'setTimeout("'; 
	echo "location.href = './sending_thermostat_setting.php?lenght_array_data_file=".$lenght_array_data_file."';";
echo '", 1500);'; 
echo '</script>';

echo '</div>';
echo '</body></html>';


?>



