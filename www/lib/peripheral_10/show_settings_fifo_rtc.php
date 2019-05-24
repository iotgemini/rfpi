<?php
/******************************************************************************************
Powered by:			Emanuele Aimone
Programmer: 		Emanuele Aimone
Last Update: 		24/08/2017

Description: it shows the data got from the fifo

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


//---------------------------------------------------------------------------------------//
//		Specific library for the Peri10
		include './lib/peri10_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';		
//---------------------------------------------------------------------------------------//



//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_title_rtc="RTC";
$lang_title_12h_24h="12H/24H";

$lang_title_date="Date GG/MM/YY";
$lang_title_second="Second";
$lang_title_minutes="Minutes";
$lang_title_hours="Hours";
$lang_decription_function="After the time specified here the relay will be turned OFF.";
if($_SESSION["language"]=="IT"){
	$lang_title_date="Data GG/MM/YY";
	$lang_title_second="Secondi";
	$lang_title_minutes="Minuti";
	$lang_title_hours="Ore";
	$lang_decription_function="Dopo il tempo specificato qui il rel&egrave; si spegner&agrave;.";
}else if($_SESSION["language"]=="FR"){
	$lang_title_date="Date GG/MM/YY";
	$lang_title_second="Secondes";
	$lang_title_minutes="Minutes";
	$lang_title_hours="Heures";
	$lang_decription_function="Apr&egrave;s le temps sp&eacute;cifi&eacute; ici, le relais s'&eacute;teint.";
}else if($_SESSION["language"]=="SP"){
	$lang_title_date="Fecha GG/MM/YY";
	$lang_title_second="Segundos";
	$lang_title_minutes="Acta";
	$lang_title_hours="Horas";
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
$rtc_SS = intval( $data_rfpi[0+$index] . $data_rfpi[1+$index] , 16); //byte 0
$rtc_MM = intval( $data_rfpi[2+$index] . $data_rfpi[3+$index] , 16); //byte 1
$rtc_HH = intval( $data_rfpi[4+$index] . $data_rfpi[5+$index] , 16); //byte 2
$rtc_mday = intval( $data_rfpi[6+$index] . $data_rfpi[7+$index] , 16); //byte 3
$rtc_mon = intval( $data_rfpi[8+$index] . $data_rfpi[9+$index] , 16); //byte 4
$rtc_year = intval( $data_rfpi[10+$index] . $data_rfpi[11+$index] , 16); //byte 5
$rtc_wday = intval( $data_rfpi[12+$index] . $data_rfpi[13+$index] , 16); //byte 6. //value from 1 to 7 is the number of the day into the week
$rtc_am_pm = intval( $data_rfpi[14+$index] . $data_rfpi[15+$index] , 16); //byte 7 //true if it is AM and false if it is PM
$rtc_12h_24h = intval( $data_rfpi[16+$index] . $data_rfpi[17+$index] , 16); //byte 8
//echo $rtc_wday;


//FORMATTING DATA:
$rtc_year = $rtc_year & 0b00011111;


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<head>';

echo '</head>';

echo '<body>';
echo '<div class="div_home">';

//button HOME
echo '<p align=left>';
echo '<form name="home1" action="/index.php" method=GET>';
echo '<input type=submit value="HOME" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<br>'; 

echo '<p>'; 
	
	
echo '<form name="set_functions" action="./cmd_set_settings_rtc.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_hex_special_function" value="'. $id_hex_special_function . '">';
echo '<input type=hidden name="redirect_page" value="'. $redirect_page . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';   
echo '<td class="td_peripheral">'.$lang_title_hours.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_minutes.'</td>';
echo '<td class="td_peripheral">'.$lang_title_second.'</td>'; 
echo '<td class="td_peripheral">'.$lang_title_date.'</td>';   
//echo '<td class="td_peripheral">'.$lang_title_12h_24h.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">'.$lang_title_rtc.'</td>';    

echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="rtc_HH" value="'; echo $rtc_HH; echo '" size="2" maxlength="2">';
echo '</td>';

echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="rtc_MM" value="'; echo $rtc_MM; echo '" size="2" maxlength="2">';
echo '</td>';  

echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="rtc_SS" value="'; echo $rtc_SS; echo '" size="2" maxlength="2">';
echo '</td>';  

echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="rtc_mday" value="'; echo $rtc_mday; echo '" size="2" maxlength="2">';
echo '<input type="text" name="rtc_mon" value="'; echo $rtc_mon; echo '" size="2" maxlength="2">';
echo '<input type="text" name="rtc_year" value="'; echo $rtc_year; echo '" size="2" maxlength="2">';
echo '</td>'; 
/*
echo '<td class="td_peripheral">';
if(intval($rtc_12h_24h, 10)===0){
	echo '<input type="radio" name="12h_24h" value="1" >&nbsp12H';
	echo '<br><input type="radio" name="12h_24h" value="2" checked="checked">&nbsp24H';
}else{
	echo '<input type="radio" name="12h_24h" value="1" checked="checked">&nbsp12H';
	echo '<br><input type="radio" name="12h_24h" value="2">&nbsp24H';
}*/

echo '</td>'; 

echo '</tr>';

echo '<tr class="table_title_field_line">';
echo '<td colspan=7 align=center>';
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';


	
echo '</p>';

echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home2" action="/index.php" method=GET>';
echo '<input type=submit value="HOME" class="btn_pag">';
echo '</form>';
echo '</p>';


//it sends to the client what has been executed and then proceed with next instruction
ob_flush(); 
flush();

echo '</div>';

echo '</body></html>';
?>


    





