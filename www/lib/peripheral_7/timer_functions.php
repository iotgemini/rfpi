<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		16/05/2016

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
//		Specific library for the Peri7
		include './lib/peri7_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_title_timer="TIMER";
$lang_title_millisecond="Milliseconds";
$lang_title_second="Second";
$lang_title_minutes="Minutes";
$lang_title_hours="Hours";
$lang_title_enabled="ENABLED";
$lang_title_output="Output";
$lang_relay="Relay";
$lang_decription_function="After the time specified here the relay will be turned OFF.";
if($_SESSION["language"]=="IT"){
	$lang_title_timer="TIMER";
	$lang_title_millisecond="Millisecondi";
	$lang_title_second="Secondi";
	$lang_title_minutes="Minuti";
	$lang_title_hours="Ore";
	$lang_title_enabled="ABILITATA";
	$lang_title_output="Uscita";
	$lang_relay="Rel&egrave;";
	$lang_decription_function="Dopo il tempo specificato qui il rel&egrave; si spegner&agrave;.";
}else if($_SESSION["language"]=="FR"){
	$lang_title_timer="MINUTEUR";
	$lang_title_millisecond="Millisecondes";
	$lang_title_second="Secondes";
	$lang_title_minutes="Minutes";
	$lang_title_hours="Heures";
	$lang_title_enabled="Acti&eacute;e";
	$lang_title_output="Sortie";
	$lang_relay="Rel&egrave;";
	$lang_decription_function="Apr&egrave;s le temps sp&eacute;cifi&eacute; ici, le relais s'&eacute;teint.";
}else if($_SESSION["language"]=="SP"){
	$lang_title_timer="TIMER";
	$lang_title_millisecond="Milisegundos";
	$lang_title_second="Segundos";
	$lang_title_minutes="Acta";
	$lang_title_hours="Horas";
	$lang_title_enabled="ACTIVO";
	$lang_title_output="Salida";
	$lang_relay="Rel&eacute;";
	$lang_decription_function="Despu&eacute;s del tiempo especificado aquí, el rel&eacute; se apagar&aacute;.";
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$timer_enabled=$_GET['timer_enabled'];

$timer_ms=$_GET['timer_ms'];
$timer_SS=$_GET['timer_SS'];
$timer_MM=$_GET['timer_MM'];
$timer_HH=$_GET['timer_HH'];

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

echo '<form name="peri3_btn_gpio_functions" action="./cmd_set_timer_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';  
echo '<td class="td_peripheral">'.$lang_title_millisecond.'</td>';   
echo '<td class="td_peripheral">'.$lang_title_second.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_minutes.'</td>';
echo '<td class="td_peripheral">'.$lang_title_hours.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_output.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">'.$lang_title_timer.'</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="timer_ms" value="'; echo $timer_ms; echo '" size="4" maxlength="4">';
echo '<br> MAX = 1000';
echo '</td>';   
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="timer_SS" value="'; echo $timer_SS; echo '" size="2" maxlength="2">';
echo '<br> MAX = 60';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="timer_MM" value="'; echo $timer_MM; echo '" size="2" maxlength="2">';
echo '<br> MAX = 60';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="timer_HH" value="'; echo $timer_HH; echo '" size="2" maxlength="2">';
echo '<br> MAX = 255';
echo '</td>';

echo '<td class="td_peripheral">';
if(intval($timer_enabled, 10)==2){
	echo '<input type="radio" name="output_to_control" value="1" >&nbspRelay';
	echo '<br><input type="radio" name="output_to_control" value="2" checked="checked">&nbspGPIO1';
}else{
	echo '<input type="radio" name="output_to_control" value="1" checked="checked">&nbsp'.$lang_relay;
	echo '<br><input type="radio" name="output_to_control" value="2">&nbspGPIO1';
}

echo '</td>'; 

echo '<td class="td_peripheral" align=center>';
if(intval($timer_enabled, 10)==0){
	echo '<input type="checkbox" name="timer_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="timer_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>';
echo '</tr>';

/*echo '<tr class="table_line_odd">';
echo '<td class="td_peripheral">GPIO1</td>';  
echo '<td class="td_peripheral" align=left>';
echo '<input type="radio" name="gpio1_setting" value="0"/ '; if($gpio1_setting==0) echo 'checked'; echo '>Digital Output<br>';
echo '<input type="radio" name="gpio1_setting" value="1"/ '; if($gpio1_setting==1) echo 'checked'; echo '>Digital Input<br>';
echo '<input type="radio" name="gpio1_setting" value="2"/ '; if($gpio1_setting==2) echo 'checked'; echo '>Analogue Input';
echo '</td>';   
echo '</tr>';
*/

echo '<tr class="table_title_field_line">';
echo '<td colspan=7 align=center>';
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';


//echo '<br>';
//echo 'After the time specified here the relay will be turned OFF';
echo $lang_decription_function;
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
