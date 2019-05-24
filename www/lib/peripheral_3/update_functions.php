<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		07/12/2015

Description: this is a panel where to setup the UPDATE


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
//		Specific library for the Peri3
		include './lib/peri3_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//



$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];


$trigger_update_temperature=$_GET['trigger_update_temperature'];
$trigger_update_lux=$_GET['trigger_update_lux'];
$temperature_ADC=$_GET['temperature_ADC'];
$lux_ADC=$_GET['lux_ADC'];



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


echo '<form name="peri3_btn_update_functions" action="./cmd_set_update_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral">';

echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral">Trigger</td>';    
echo '<td class="td_peripheral">ENABLED</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" align=center>';
echo 'Keep updated the temperature';
echo '</td>';   
echo '<td class="td_peripheral" align=center>';
if(intval($trigger_update_temperature, 10)==0){
	echo '<input type="checkbox" name="trigger_update_temperature" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="trigger_update_temperature" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>';
echo '</tr>';

echo '<tr class="table_line_odd">';
echo '<td class="td_peripheral" align=center>';
echo 'Keep updated the luminosity';
echo '</td>';   
echo '<td class="td_peripheral" align=center>';
if(intval($trigger_update_lux, 10)==0){
	echo '<input type="checkbox" name="trigger_update_lux" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="trigger_update_lux" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
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
