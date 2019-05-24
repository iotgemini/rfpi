<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/11/2015

Description: this is a panel where to setup the TRIGGER


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

$trigger_enabled=$_GET['trigger_enabled'];

$trigger_temperature_low_INT=$_GET['trigger_temperature_low_INT'];
$trigger_temperature_low_DEC=$_GET['trigger_temperature_low_DEC'];
$trigger_temperature_high_INT=$_GET['trigger_temperature_high_INT'];
$trigger_temperature_high_DEC=$_GET['trigger_temperature_high_DEC'];

//all number over 128 are negative!
if($trigger_temperature_low_INT > 127){
	$trigger_temperature_low_INT -= 256; //converting in negative
}
if($trigger_temperature_high_INT > 127){
	$trigger_temperature_high_INT -= 256; //converting in negative
}

$temperature_offset_to_send_data_temperature=$_GET['temperature_offset_to_send_data_temperature'];

$send_temperature_enabled = 0;
if(intval($temperature_offset_to_send_data_temperature, 10) > 0 ){
	$send_temperature_enabled = 1;
}

$current_temperature_INT = $_GET['current_temperature_INT'];
$current_temperature_DEC = $_GET['current_temperature_DEC'];

if($current_temperature_INT > 127){
	$current_temperature_INT -= 256; //converting in negative
}
if($current_temperature_DEC > 127){
	$current_temperature_DEC -= 256; //converting in negative
}

//$num_current_temperature_INT = intval($current_temperature_INT, 10);
$str_current_temperature_INT = (string) $current_temperature_INT;
$str_current_temperature_DEC = (string) $current_temperature_DEC;
$current_temperature= $str_current_temperature_INT . '.' . $str_current_temperature_DEC;



echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<br>';

//button Settings
/*echo '<p align=center>';
echo '<a href="./infrared_functions_settings.php?address_peri='.$address_peri.'" class="btn_cmd">Settings</a>';
echo '</p>';
*/

echo 'If the temperature is below the LOW Trigger the relay is ON.';
echo '<br>If the temperature is above the HIGH Trigger the relay is OFF.';
echo '<br><br>';

echo '<table class="table_temperature">';
echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" align=center>';
echo 'Current temperature = ';
echo $current_temperature;
echo '&#176C';
echo '</td>';
echo '</tr>';

echo '<form name="peri3_btn_gpio_functions" action="./cmd_set_trigger_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral">';

echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral" colspan=2>LOW Trigger</td>';    
echo '<td class="td_peripheral" colspan=2>HIGH Trigger</td>';
echo '<td class="td_peripheral">ENABLED</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" align=center>';
echo 'Integer part:<br>';
echo '<input type="text" name="trigger_temperature_low_INT" value="'; echo $trigger_temperature_low_INT; echo '" size="3" maxlength="3">';
echo '<br>(-25&#176C to +90&#176C)';
echo '</td>';   
echo '<td class="td_peripheral" align=center>';
echo 'Decimal part:<br>';
echo '. ';
echo '<input type="text" name="trigger_temperature_low_DEC" value="'; echo $trigger_temperature_low_DEC; echo '" size="1" maxlength="1">';
echo '<br>(0 to 9)';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo 'Integer part:<br>';
echo '<input type="text" name="trigger_temperature_high_INT" value="'; echo $trigger_temperature_high_INT; echo '" size="3" maxlength="3">';
echo '<br>(-25&#176C to +90&#176C)';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo 'Decimal part:<br>';
echo '. ';
echo '<input type="text" name="trigger_temperature_high_DEC" value="'; echo $trigger_temperature_high_DEC; echo '" size="1" maxlength="1">';
echo '<br>(0 to 9)';
echo '</td>';
echo '<td class="td_peripheral" align=center>';
if(intval($trigger_enabled, 10)==0){
	echo '<input type="checkbox" name="trigger_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="trigger_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>';
echo '</tr>';

echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral" colspan=4>Send the temperature data to the master when temperature change about:</td>';  
echo '<td class="td_peripheral" align=left>ENABLED</td>';   
echo '</tr>';

echo '<tr class="table_line_odd">';
echo '<td class="td_peripheral" colspan=4>';
echo '<input type="text" name="temperature_offset_to_send_data_temperature" value="'; echo $temperature_offset_to_send_data_temperature; echo '" size="3" maxlength="3">';
echo '&#176C &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp (1&#176C to 100&#176C. At least 1&#176C otherwise at 0&#176C it is disabled)';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
if($send_temperature_enabled == 0){
	echo '<input type="checkbox" name="send_temperature_enabled" value="0" onchange="if(send_temperature_enabled.value==0) send_temperature_enabled.value=1; else send_temperature_enabled.value=0;">';
}else{
	echo '<input type="checkbox" name="send_temperature_enabled" value="1" onchange="if(send_temperature_enabled.value==0) send_temperature_enabled.value=1; else send_temperature_enabled.value=0;" checked>';
}
echo '</td>';  
echo '</tr>';

echo '<tr class="table_title_field_line">';
echo '<td colspan=6 align=center>';
echo '<input type=submit value="APPLY" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';

//END: READING THE FILE WHERE ALL SIGNALS ARE STORED AND BULDING THE TABLE


//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
