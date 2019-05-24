<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/05/2015

Description: this is a panel where to setup the GPIO as output/input/analogue


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
//		Specific library for the Peri3: Sensore-Attuatore
		include './lib/peri3_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$gpio0_setting=$_GET['gpio0_setting'];
$gpio1_setting=$_GET['gpio1_setting'];

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


echo '<form name="peri3_btn_gpio_functions" action="./yesno_apply_gpio_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral">';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral">GPIO</td>';  
echo '<td class="td_peripheral">Settings</td>';   
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">GPIO0</td>';  
echo '<td class="td_peripheral" align=left>';
echo '<input type="radio" name="gpio0_setting" value="0"/ '; if($gpio0_setting==0) echo 'checked'; echo '>Digital Output<br>';
echo '<input type="radio" name="gpio0_setting" value="1"/ '; if($gpio0_setting==1) echo 'checked'; echo '>Digital Input<br>';
echo '<input type="radio" name="gpio0_setting" value="2"/ '; if($gpio0_setting==2) echo 'checked'; echo '>Analogue Input';
echo '</td>';   
echo '</tr>';

echo '<tr class="table_line_odd">';
echo '<td class="td_peripheral">GPIO1</td>';  
echo '<td class="td_peripheral" align=left>';
echo '<input type="radio" name="gpio1_setting" value="0"/ '; if($gpio1_setting==0) echo 'checked'; echo '>Digital Output<br>';
echo '<input type="radio" name="gpio1_setting" value="1"/ '; if($gpio1_setting==1) echo 'checked'; echo '>Digital Input<br>';
echo '<input type="radio" name="gpio1_setting" value="2"/ '; if($gpio1_setting==2) echo 'checked'; echo '>Analogue Input';
echo '</td>';   
echo '</tr>';

echo '<tr class="table_title_field_line">';
echo '<td colspan=2 align=center>';
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
