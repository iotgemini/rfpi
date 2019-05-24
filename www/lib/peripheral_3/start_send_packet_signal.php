<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		20/04/2015

Description: it start the procedure to send all signal data to the peripheral

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

$id_packet = "00";//$_GET['id_packet'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];
if($counter==='')
	$counter=0;
	
$cont_retry = $_GET['cont_retry'];
if($cont_retry==='')
	$cont_retry=0;
	

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br><p>Sending packets to the peripheral ....</p>';

echo '<script type="text/javascript">';
	
echo 'setTimeout("';
echo "location.href = './cmd_send_packets_signal.php?redirect_page=".$redirect_page."&address_peri=".$address_peri."&id_packet=".$id_packet."&num_packets=".$num_packets."&signal_coefficient=".$signal_coefficient."&counter=" . $counter . "&cont_retry=".$cont_retry."&data_signal=".$data_signal."';";
echo '", 10); ';
	
echo '</script>';

echo '</div>';
echo '</body></html>';

?>
