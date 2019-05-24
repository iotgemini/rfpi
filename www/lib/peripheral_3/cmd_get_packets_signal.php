<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/04/2015

Description: it send the command "DATA RF id_posizione 524275022E id_packet 2E2E2E2E2E2E2E2E2E2E "
				to make the Sensore-Attuatore to send back the packets of the data signal

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

$redirect_page = $_GET['redirect_page'];

$cont_retry = $_GET['cont_retry'];

$strCmd = "DATA RF ".$address_peri." 524275022E".$id_packet."2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important

writeFIFO(FIFO_GUI_CMD, $strCmd);

					
if($redirect_page)
	header('Location: ./'.$redirect_page.'.php?signal_name='.$signal_name.'&address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&cont_retry='.$cont_retry);
else
	header('Location: ./save_packet_into_file.php?signal_name='.$signal_name.'&address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&cont_retry='.$cont_retry);
	
?>



