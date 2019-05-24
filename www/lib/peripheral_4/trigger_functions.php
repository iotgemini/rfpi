<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		19/05/2016

Description: this is a panel where to setup the TIMER


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
//		Specific library for the Peri4
		include './lib/peri4_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_description_low_trigger="If the temperature is below the LOW Trigger the relay is ON.";
$lang_description_high_trigger="If the temperature is above the HIGH Trigger the relay is OFF.";
$lang_title_enabled="ENABLED";
$lang_title_low_trigger="LOW Trigger";
$lang_title_high_trigger="HIGH Trigger";
$lang_relay="Relay";
$lang_title_send_temperature="Send the temperature data to the Web Server<br> when temperature change about of:";
$lang_current_temperature = "Current temperature = ";
if($_SESSION["language"]=="IT"){
	$lang_description_low_trigger="Se la temperatura &egrave; inferiore alla Soglia Inferiore allora il rel&egrave; &egrave; acceso.";
	$lang_description_high_trigger="Se la temperatura &egrave; superiore alla Soglia Superiore allora il rel&egrave; &egrave; spento.";
	$lang_title_enabled="ABILITATA";
	$lang_title_low_trigger="Soglia Inferiore";
	$lang_title_high_trigger="Soglia Superiore";
	$lang_relay="Rel&egrave;";
	$lang_title_send_temperature="Invia la temperatura al Server Web<br> quando cambia di circa:";
	$lang_current_temperature = "Temperatura corrente = ";
}else if($_SESSION["language"]=="FR"){	
	$lang_description_low_trigger="Si la temp&eacute;rature est inf&eacute;rieure au seuil inf&eacute;rieur, alors le relais est activ&eacute;.";
	$lang_description_high_trigger="Si la temp&eacute;rature est sup&eacute;rieure au seuil sup&eacute;rieur, alors le relais est d&eacute;sactiv&eacute;.";
	$lang_title_enabled="Activ&eacute;e";
	$lang_title_low_trigger="Seuil Inf&eacute;rieur";
	$lang_title_high_trigger="Seuil Sup&eacute;rieur";
	$lang_relay="Rel&egrave;";
	$lang_title_send_temperature="Envoyer &agrave; un Serveur Web<br> lorsque les changements de temp&eacute;rature d'environ:";
	$lang_current_temperature = "Temp&eacute;rature actuelle = ";
}else if($_SESSION["language"]=="SP"){	
	$lang_description_low_trigger="Si la temperatura es menor que el umbral inferior, el rel&eacute; est&aacute; activado.";
	$lang_description_high_trigger="Si la temperatura es mayor que el umbral superior, el rel&eacute; est&aacute; desactivado.";
	$lang_title_enabled="ACTIVO";
	$lang_title_low_trigger="Umbral m&aacute;s bajo";
	$lang_title_high_trigger="Umbral superior";
	$lang_relay="Rel&eacute;";
	$lang_title_send_temperature="Enviar al Servidor Web<br> cuando la temperatura cambia en aproximadamente:";
	$lang_current_temperature = "Temperatura actual = ";
}

//---------------------------------------------------------------------------------------//



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
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<br>';



echo '<table class="table_temperature">';


echo '<form name="peri3_btn_gpio_functions" action="./cmd_set_trigger_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral" colspan=2>'.$lang_title_low_trigger.'</td>';    
echo '<td class="td_peripheral" colspan=2>'.$lang_title_high_trigger.'</td>';
echo '<td class="td_peripheral">'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" align=center>';
echo 'Integer part:<br>';
echo '<input type="text" name="trigger_temperature_low_INT" value="'; echo $trigger_temperature_low_INT; echo '" size="3" maxlength="3">';
echo '<br>(-25&#176C - +90&#176C)';
echo '</td>';   
echo '<td class="td_peripheral" align=center>';
echo 'Decimal part:<br>';
echo '. ';
echo '<input type="text" name="trigger_temperature_low_DEC" value="'; echo $trigger_temperature_low_DEC; echo '" size="1" maxlength="1">';
echo '<br>(0 - 9)';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo 'Integer part:<br>';
echo '<input type="text" name="trigger_temperature_high_INT" value="'; echo $trigger_temperature_high_INT; echo '" size="3" maxlength="3">';
echo '<br>(-25&#176C - +90&#176C)';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo 'Decimal part:<br>';
echo '. ';
echo '<input type="text" name="trigger_temperature_high_DEC" value="'; echo $trigger_temperature_high_DEC; echo '" size="1" maxlength="1">';
echo '<br>(0 - 9)';
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
echo '<td class="td_peripheral" colspan=4>'.$lang_title_send_temperature.'</td>'; //Send the temperature data to the master<br> when temperature change about of: 
echo '<td class="td_peripheral" align=left>'.$lang_title_enabled.'</td>';   
echo '</tr>';

echo '<tr class="table_line_odd">';
echo '<td class="td_peripheral" colspan=4>';
echo '<input type="text" name="temperature_offset_to_send_data_temperature" value="'; echo $temperature_offset_to_send_data_temperature; echo '" size="3" maxlength="3">';
echo '&#176C';
//echo ' &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp (1&#176C to 100&#176C. At least 1&#176C otherwise at 0&#176C it is disabled)';
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
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	


echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" align=center colspan=5>';
//echo 'Current temperature = ';
echo $lang_current_temperature;
echo $current_temperature;
echo '&#176C';
echo '</td>';
echo '</tr>';


echo '</table>';

echo '</form>';


echo '<br>';
//echo 'If the temperature is below the LOW Trigger the relay is ON.';
echo $lang_description_low_trigger;
echo '<br>';
//If the temperature is above the HIGH Trigger the relay is OFF.';
echo $lang_description_high_trigger;
echo '<br><br>';


//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
