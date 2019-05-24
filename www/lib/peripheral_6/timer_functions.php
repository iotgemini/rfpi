<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/11/2015

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
//		Specific library for the Peri
		include './lib/peri_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

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
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<br>';

//button Settings
/*echo '<p align=center>';
echo '<a href="./infrared_functions_settings.php?address_peri='.$address_peri.'" class="btn_cmd">Settings</a>';
echo '</p>';
*/

echo 'After the time specified here the relay will be turned OFF';
echo '<br><br>';

echo '<form name="peri3_btn_gpio_functions" action="./cmd_set_timer_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral">';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';  
echo '<td class="td_peripheral">Milli Second</td>';   
echo '<td class="td_peripheral">Second</td>';  
echo '<td class="td_peripheral">Minutes</td>';
echo '<td class="td_peripheral">Hours</td>';  
echo '<td class="td_peripheral">ENABLED</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">TIMER</td>';  
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
