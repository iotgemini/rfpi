<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		07/05/2015

Description: it ask if apply the setting to the GPIO, because the peri will be deleted and reinstalled

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

$redirect_page = $_GET['redirect_page'];

$gpio0_setting = $_GET['gpio0_setting'];
$gpio1_setting = $_GET['gpio1_setting'];

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';
//echo '<br>';

echo '<form name="save" action="./cmd_set_gpio_setting.php" method=GET>';
echo '<p style="color:darkred;font-size:20px">';
echo 'WARNING!<br>';
echo 'The peripheral will be deleted and reinstalled!<br>';
echo 'Do you want continue?<br>';
echo '</p>';
echo '<input type=hidden name="position_id" value="' . $position_id . '">';
echo '<input type=hidden name="address_peri" value="' . $address_peri . '">';
echo '<input type=hidden name="gpio0_setting" value="' . $gpio0_setting . '">';
echo '<input type=hidden name="gpio1_setting" value="' . $gpio1_setting . '">';
echo '<input type=hidden name="redirect_page" value="' . $redirect_page . '">';
echo '</form>';


echo '<p>';
echo '<form name="home" action="../.././index.php" method=GET>';
echo '<input type=hidden name="counter" value="1">';
echo '<input type=submit value="NO" class="btn_pag">';
echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
echo '<input type=button onclick="document.save.submit();" value="YES" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';
echo '</body></html>';
?>