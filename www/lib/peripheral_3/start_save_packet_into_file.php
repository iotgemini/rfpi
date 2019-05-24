<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/09/2015

Description: it start the procedure of communication with the peripheral to get all data
			 of the signal just acquired

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


$signal_name = $_GET['signal_name'];

$address_peri = $_GET['address_peri'];

$num_packets = $_GET['num_packets'];
$signal_coefficient = $_GET['signal_coefficient'];

$id_packet = $_GET['id_packet'];


if(preg_match('/\s/',$signal_name)){
	//error: there is a spce into the name of the signal
	echo "<html><body>Error: there is a spce into the name of the signal!</body></html>";
	header( 'Location: get_signal_name.php?error=space&address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&id_packet='.$id_packet ) ;
}else{
	$counter=0;

	echo '<html>';
	echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
	echo '<body>';
	echo '<div class="div_home">';

	echo '<br><p>Reading packets and saving the Signal into the database ....</p>';

	echo '<script type="text/javascript">';
	
	echo 'setTimeout("';
	echo "location.href = './cmd_get_packets_signal.php?signal_name=".$signal_name."&address_peri=".$address_peri."&id_packet=".$id_packet."&num_packets=".$num_packets."&signal_coefficient=".$signal_coefficient."&counter=" . $counter . "&cont_retry=0';";
	echo '", 10); ';
	
	echo '</script>';

	echo '</div>';
	echo '</body></html>';
}
?>



