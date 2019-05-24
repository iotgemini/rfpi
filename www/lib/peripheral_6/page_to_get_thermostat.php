<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		27/11/2015

Description: this is a panel where to go to set the THERMOSTAT function


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

	//Button to page 0-9 hours Functions
	$id_packet = 0;
	echo '<form name="peri6_btn_thermostat09_functions_'.$position_id.'" action="./cmd_get_thermostat09_setting.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$position_id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	echo '<input type=hidden name="id_packet" value="'.$id_packet.'">';
	echo '<input type=submit value="Set 0-9 hours" class="btn_functions">';
	echo '</form>';
	
	
	//Button to page 10-19 hours Functions
	$id_packet = 1;
	echo '<form name="peri6_btn_thermostat1019_functions_'.$position_id.'" action="./cmd_get_thermostat09_setting.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$position_id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	echo '<input type=hidden name="id_packet" value="'.$id_packet.'">';
	echo '<input type=submit value="Set 10-19 hours" class="btn_functions">';
	echo '</form>';
	
	//Button to page 20-23 hours Functions
	$id_packet = 2;
	echo '<form name="peri6_btn_thermostat2023_functions_'.$position_id.'" action="./cmd_get_thermostat09_setting.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$position_id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	echo '<input type=hidden name="id_packet" value="'.$id_packet.'">';
	echo '<input type=submit value="Set 20-23 hours + Enable + Time" class="btn_functions">';
	echo '</form>';

echo '<br>';
	
//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
