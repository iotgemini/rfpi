<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/05/2015

Description: it just ask to the user to type a name for the signal acquired

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

$address_peri = $_GET['address_peri'];

$num_packets = $_GET['num_packets'];
$signal_coefficient = $_GET['signal_coefficient'];

$id_packet = $_GET['id_packet'];
	
$error=$_GET["error"];

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

if($error==="space"){
	echo '<p style="color:darkred">';
	echo 'You typed a space! Please do not time spaces!';
	echo '</p>';
}

echo '<br>';

echo '<p>';
echo '<form name="save" action="./start_save_packet_into_file.php" method=GET>';
echo 'Type the signal name: <input type=text name="signal_name" value="">(DO NOT TYPE SPACES!)';
echo '<input type=hidden name="address_peri" value="' . $address_peri . '">';
echo '<input type=hidden name="id_packet" value="' . $id_packet . '">';
echo '<input type=hidden name="num_packets" value="' . $num_packets . '">';
echo '<input type=hidden name="signal_coefficient" value="' . $signal_coefficient . '">';
echo '</form>';
echo '</p>';

echo '<p>';
echo '<form name="home" action="./index.php" method=GET>';
echo '<input type=hidden name="counter" value="1">';
echo '<input type=submit value="home" class="btn_pag">';
echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
echo '<input type=button onclick="document.save.submit();" value="Save" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';
echo '</body></html>';
?>