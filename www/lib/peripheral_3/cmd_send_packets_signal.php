<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		20/04/2015

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

$data_signal = $_GET['data_signal'];

$address_peri = $_GET['address_peri'];

$num_packets = $_GET['num_packets'];

$signal_coefficient = $_GET['signal_coefficient'];

$id_packet = $_GET['id_packet'];

$redirect_page = $_GET['redirect_page'];

$cont_retry = $_GET['cont_retry'];
if($cont_retry==='')
	$cont_retry=0;
	
//converting the id_packet into dec
$strTemp = $id_packet[0] . $id_packet[1];
$id_packet_dec = convert_2ChrHex_to_byte($strTemp) ;

//getting 10 data for the packet
$data_packet = "E2E2E2E2E2E2E2E2E2E2";
for($i=0; $i<20 && ($i+($id_packet_dec*20))<strlen($data_signal); $i++)
	$data_packet[$i] = $data_signal[$i+($id_packet_dec*20)];

//building the command for the rfpi.c routine
$strCmd = "DATA RF ".$address_peri." 5242660301".$id_packet.$data_packet." "; //the space at the end is important
//echo $strCmd;
writeFIFO(FIFO_GUI_CMD, $strCmd);

//if($id_packet_dec<7)	
if($redirect_page && $redirect_page !== "cmd_save_signal_in_mem0" && $redirect_page !== "cmd_save_signal_in_mem1")
	header('Location: ./'.$redirect_page.'.php?address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&cont_retry='.$cont_retry.'&data_signal='.$data_signal);
else
	header('Location: ./send_packet_signal.php?redirect_page='.$redirect_page.'&address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&cont_retry='.$cont_retry.'&data_signal='.$data_signal);
	
?>



