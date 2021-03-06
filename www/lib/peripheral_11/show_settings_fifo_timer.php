<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		03/11/2017

Description: this is a panel where to setup the TIMER


 *    RFberryPi is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    RFberryPi is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with RFberryPi.  If not, see <http://www.gnu.org/licenses/>.
 
******************************************************************************************/


//---------------------------------BEGIN INCLUDE-------------------------------------------//
//		Specific library for the Peri_12
		include './peri_12_lib.php';  

//		library with all useful functions to use RFberry Pi
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
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];
$status_rfpi=$_GET['status_rfpi'];
$data_rfpi=$_GET['data_rfpi'];




$index = 8;
$byte_timer_enabled = intval( $data_rfpi[0+$index] . $data_rfpi[1+$index] , 16); //byte 0
$timer_ms = intval( $data_rfpi[2+$index] . $data_rfpi[3+$index] , 16); //byte 1
$timer_ms = $timer_ms << 8;
$timer_ms += intval( $data_rfpi[4+$index] . $data_rfpi[5+$index] , 16); //byte 2
$timer_SS = intval( $data_rfpi[6+$index] . $data_rfpi[7+$index] , 16); //byte 3
$timer_MM = intval( $data_rfpi[8+$index] . $data_rfpi[9+$index] , 16); //byte 4
$timer_HH = intval( $data_rfpi[10+$index] . $data_rfpi[11+$index] , 16); //byte 5


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

echo '<form name="set_functions" action="./cmd_set_settings_timer.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_hex_special_function" value="'. $id_hex_special_function . '">';
echo '<input type=hidden name="redirect_page" value="'. $redirect_page . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';  
echo '<td class="td_peripheral">'.$lang_title_millisecond.'</td>';   
echo '<td class="td_peripheral">'.$lang_title_second.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_minutes.'</td>';
echo '<td class="td_peripheral">'.$lang_title_hours.'</td>';  
//echo '<td class="td_peripheral">'.$lang_title_output.'</td>';  
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


echo '<td class="td_peripheral" align=center>';
/*if(intval($timer_enabled, 10)==0){
	echo '<input type="checkbox" name="timer_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="timer_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}*/
for($y=1,$c=0;$c<8;$y=$y*2,$c++){

	if(($byte_timer_enabled & $y)==0){
		echo '<input type="checkbox" name="timer_enabled'.$c.'" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
	}else{
		echo '<input type="checkbox" name="timer_enabled'.$c.'" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
	}
	echo "Q".$c;
	echo "<br>";

}
echo '</td>';

echo '</tr>';


echo '<tr class="table_title_field_line">';
echo '<td colspan=6 align=center>';
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
