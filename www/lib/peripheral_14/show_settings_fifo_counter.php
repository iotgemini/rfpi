<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/01/2021

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
$lang_title_divider="DIVIDER";
$lang_title_num_impulses="NUM IMPULSES";
$lang_title_parameters="Parameters";
$lang_decription_function="";
if($_SESSION["language"]=="IT"){
	$lang_title_counter="CONTEGGIO";
	$lang_title_preset="PRESET";
	$lang_title_divider="DIVISORE";
	$lang_title_num_impulses="NUM IMPULSI";
	$lang_title_parameters="Parametri";
	$lang_decription_function="";
}else if($_SESSION["language"]=="FR"){
	$lang_title_counter="COMPTEUR";
	$lang_title_preset="PRESET";
	$lang_title_divider="DIVISEUR";
	$lang_title_num_impulses="NUM IMPULSES";
	$lang_title_parameters="Param&egrave;tres";
	$lang_decription_function="";
}else if($_SESSION["language"]=="SP"){
	$lang_title_counter="CONTADORA";
	$lang_title_preset="PRESET";
	$lang_title_divider="DIVISOR";
	$lang_title_num_impulses="NUM IMPULSOS";
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
$divider_byte2 = intval( $data_rfpi[12+$index] . $data_rfpi[13+$index] , 16); //byte 6
$divider_byte1 = intval( $data_rfpi[14+$index] . $data_rfpi[15+$index] , 16); //byte 7
$divider_byte0 = intval( $data_rfpi[16+$index] . $data_rfpi[17+$index] , 16); //byte 8

$counter = $counter_byte2 << 16;
$counter += $counter_byte1 << 8;
$counter += $counter_byte0;

$preset = $preset_byte2 << 16;
$preset += $preset_byte1 << 8;
$preset += $preset_byte0;

$divider = $divider_byte2 << 16;
$divider += $divider_byte1 << 8;
$divider += $divider_byte0;

$divider_int = intval($divider / 100);
$divider_dec = $divider - ($divider_int * 100);

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
echo '<td class="td_peripheral">'.$lang_title_num_impulses.'</td>';   
echo '<td class="td_peripheral">'.$lang_title_preset.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_divider.'</td>'; 

echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">'.$lang_title_parameters.'</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="counter" value="'; 
//echo intval(($counter*100)/$divider); 
echo $counter; 
echo '" size="4" maxlength="4" onchange="calcCounter();" id="num_impulses">';
echo '<br> MAX = 9999';
echo '</td>';   
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="preset" value="'; echo $preset; echo '" size="4" maxlength="4">';
echo '<br> MAX = 9999';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="divider_int" value="'; echo $divider_int; echo '" size="2" maxlength="2" onchange="calcCounter();" id="id_divider_int">';
echo '.';
echo '<input type="text" name="divider_dec" value="'; echo $divider_dec; echo '" size="2" maxlength="2" onchange="calcCounter();" id="id_divider_dec">';
echo '<br> MAX = 99.99';
echo '</td>'; 
echo '</tr>';

echo '<tr class="table_title_field_line">';
echo '<td colspan=4 align=center>';
echo $lang_title_counter.' = '.$lang_title_num_impulses.' / '.$lang_title_divider.' = ';
echo '<div style="background-color:black;color:red;font-size:30px" id="div_counter">';
echo '</div>';	
echo '</td>';
echo '</tr>';

echo '<tr class="table_title_field_line">';
echo '<td colspan=4 align=center>';
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';


//echo '<br>';
//echo 'After the time specified here the relay will be turned OFF';
echo $lang_decription_function;
echo '<br><br>';


echo '<script type="text/JavaScript">';
echo 'function calcCounter() {';
echo 'var n_imp = document.getElementById("num_impulses").value;';
echo 'var divider_int = document.getElementById("id_divider_int").value;';
echo 'var divider_dec = document.getElementById("id_divider_dec").value;';
echo 'var div_counter = document.getElementById("div_counter");';
echo 'var divider = Number(divider_int) * 100;';
echo 'if( Number(divider_dec) < 10 ) divider_dec = Number(divider_dec) * 10;';
echo 'divider = divider + Number(divider_dec);';
echo 'var counter = Number(n_imp) * 100;';
echo 'counter = counter / divider;';
echo 'counter = parseInt( counter);';
echo 'var str_counter = counter.toString();';
echo 'div_counter.innerHTML = str_counter;';
echo '}';
echo 'calcCounter();';
echo '</script>';


//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
