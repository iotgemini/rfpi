<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/04/2015

Description: it send the command "DATA RF id_posizione 52426601012E2E2E2E2E2E2E2E2E2E2E"
				to make the Sensore-Attuatore to start the acquisition of a infrared signal

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



echo '<link rel="stylesheet" href="./css/peripheral.css" type="text/css" >';

echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'settings.css" type="text/css" >';

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


//button Test Command Acquired
echo '<p align=center>';
echo '<a href="./cmd_transmit_signal.php?redirect_page=try_and_save_signal&address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'" class="btn_cmd">Test Command Acquired</a>';
echo '</p>';

//button Save Command Acquired
echo '<p align=center>';
echo '<a href="./get_signal_name.php?address_peri='.$address_peri.'&id_packet=00&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'" class="btn_cmd">Save Command Acquired</a>';
echo '</p>';

//button Return to the Inrared Function
echo '<p align=center>';
echo '<a href="./infrared_functions.php?address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'" class="btn_cmd">Return to the Functions</a>';
echo '</p>';


	
//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';


echo '</div>';

echo '</body></html>';

?>



