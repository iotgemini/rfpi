<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/04/2015

Description: it send the command "DATA RF id_posizione 5242660401 num_pacchetti signal_coefficient  2E2E2E2E2E2E2E2E2E "
				to make the Sensore-Attuatore to transmit the infrared signal saved into its RAM

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
//$num_packets = "44";
//$signal_coefficient = "0A";

$redirect_page = $_GET['redirect_page'];

$strCmd = "DATA RF ".$address_peri." 5242660401".$num_packets.$signal_coefficient."2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important

writeFIFO(FIFO_GUI_CMD, $strCmd);

if($redirect_page)
	header('Location: ./'.$redirect_page.'.php?address_peri='.$address_peri.'&counter=0&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient);
else
	header('Location: ./infrared_functions.php?address_peri='.$address_peri.'&counter=0&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient);
	
?>



