<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/11/2020

Description: this is a panel where to setup the Counter


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

$lang_title_counter="COUNTER";
$lang_title_preset="PRESET";
$lang_title_parameters="Parameters";
$lang_decription_function="";
if($_SESSION["language"]=="IT"){
	$lang_title_counter="CONTEGGIO";
	$lang_title_preset="PRESET";
	$lang_title_parameters="Parametri";
	$lang_decription_function="";
}else if($_SESSION["language"]=="FR"){
	$lang_title_counter="COMPTEUR";
	$lang_title_preset="PRESET";
	$lang_title_parameters="Param&egrave;tres";
	$lang_decription_function="";
}else if($_SESSION["language"]=="SP"){
	$lang_title_counter="CONTADORA";
	$lang_title_preset="PRESET";
	$lang_title_parameters="Par&aacute;metros";
	$lang_decription_function="";
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];
$status_rfpi=$_GET['status_rfpi'];
$data_rfpi=$_GET['data_rfpi'];




$index = 8;
$counter_byte2 = intval( $data_rfpi[0+$index] . $data_rfpi[1+$index] , 16); //byte 0
$counter_byte1 = intval( $data_rfpi[2+$index] . $data_rfpi[3+$index] , 16); //byte 1
$counter_byte0 = intval( $data_rfpi[4+$index] . $data_rfpi[5+$index] , 16); //byte 2
$preset_byte2 = intval( $data_rfpi[6+$index] . $data_rfpi[7+$index] , 16); //byte 3
$preset_byte1 = intval( $data_rfpi[8+$index] . $data_rfpi[9+$index] , 16); //byte 4
$preset_byte0 = intval( $data_rfpi[10+$index] . $data_rfpi[11+$index] , 16); //byte 5

$counter = $counter_byte2 << 16;
$counter += $counter_byte1 << 8;
$counter += $counter_byte0;

$preset = $preset_byte2 << 16;
$preset += $preset_byte1 << 8;
$preset += $preset_byte0;


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

echo '<form name="set_functions" action="./cmd_set_settings_counter.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_hex_special_function" value="'. $id_hex_special_function . '">';
echo '<input type=hidden name="redirect_page" value="'. $redirect_page . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';  
echo '<td class="td_peripheral">'.$lang_title_counter.'</td>';   
echo '<td class="td_peripheral">'.$lang_title_preset.'</td>';  

echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">'.$lang_title_parameters.'</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="counter" value="'; echo $counter; echo '" size="4" maxlength="4">';
echo '<br> MAX = 9999';
echo '</td>';   
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="preset" value="'; echo $preset; echo '" size="4" maxlength="4">';
echo '<br> MAX = 9999';
echo '</td>';  

echo '</tr>';


echo '<tr class="table_title_field_line">';
echo '<td colspan=3 align=center>';
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
