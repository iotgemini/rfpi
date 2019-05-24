<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		20/04/2015

Description: it check if it is not busy the routine rfpi.c then will give the next packet

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




$max_counter =8;

$max_cont_retry = 3;

$data_signal = $_GET['data_signal'];

$address_peri = $_GET['address_peri'];

$num_packets = $_GET['num_packets'];

$signal_coefficient = $_GET['signal_coefficient'];

$id_packet = $_GET['id_packet'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];
if($counter==='')
	$counter=0;
$counter++;

$cont_retry = $_GET['cont_retry'];
if($cont_retry==='')
	$cont_retry=0;

$strTemp = $id_packet[0].$id_packet[1]; //id packet
$id_packet_dec = convert_2ChrHex_to_byte($strTemp);
					
if( ($counter < $max_counter)){ //if it is not in time out

	//header('Location: ./infrared_functions.php?address_peri='.$address_peri.'&counter=0');

	if(!existsFIFO(FIFO_RFPI_STATUS)){ 
			//if there is not fifo will just reload it self after the delay
			
			echo '<html>';
			echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
			echo '<body>';
			echo '<div class="div_home">';

			echo '<br><p>Waiting rfpi.c in sending packets to the peripheral ....</p>';

			echo '<script type="text/javascript">';
			
			echo 'setTimeout("';
			echo "location.href = './send_packet_signal.php?address_peri=".$address_peri."&id_packet=".$id_packet."&num_packets=".$num_packets."&signal_coefficient=".$signal_coefficient."&counter=" . $counter ."&cont_retry=" . $cont_retry . "&data_signal=".$data_signal."&redirect_page=".$redirect_page."';";
			echo '", 10); ';
			
			echo '</script>';

			echo '</div>';
			echo '</body></html>';
	}else{
		
		//open the fifo to check the message into
		$data=readFIFO(FIFO_RFPI_STATUS);
		echo $data; echo '<br>';
				
		if($data==="OK"){ //if there is OK then it can send the next packet
		
			$id_packet_dec++;
			
			$strTemp = $num_packets[0].$num_packets[1]; //id packet
			$num_packets_dec = convert_2ChrHex_to_byte($strTemp);

			if($id_packet_dec < $num_packets_dec){ 

				$id_packet = "00";
						
				//converting in ASCII hexadecimal
				$byte = $id_packet_dec & 0x0F; 
				if($byte>9){
					$id_packet[1] = chr ( ($byte+65-10));
				}else{
					$id_packet[1] = chr ( ($byte+48));
				}
				$byte = $id_packet_dec >> 4;
						
				if($byte>9)
					$id_packet[0] = chr ( ($byte+65-10));
				else
					$id_packet[0] = chr ( ($byte+48));
				
				//if($id_packet_dec<2)				
				header('Location: ./cmd_send_packets_signal.php?address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&id_packet='.$id_packet.'&cont_retry=0&data_signal='.$data_signal.'&redirect_page='.$redirect_page);
			}else{
				if($redirect_page===''){
					$redirect_page = "infrared_functions";
					header('Location: ./cmd_transmit_signal.php?address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&id_packet='.$id_packet.'&cont_retry=0&redirect_page='.$redirect_page);
				}else if($redirect_page === "cmd_save_signal_in_mem0" || $redirect_page === "cmd_save_signal_in_mem1"){
					$redirect_page2 = "infrared_functions";
					header('Location: ./'.$redirect_page.'.php?address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&id_packet='.$id_packet.'&cont_retry=0&redirect_page='.$redirect_page2);
				}
			}
		}else{ //if there is written other things as "NOTX" then it will retry
			$cont_retry++;
			//if($cont_retry==0)	
			if($cont_retry<$max_cont_retry){
				echo '<html>';
				echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
				echo '<body>';
				echo '<div class="div_home">';

				echo '<br><p>Sending packets to the peripheral ....</p>';
				echo '<br><p>';
				echo 'Retry '; echo $cont_retry; echo ' of '; echo $max_cont_retry;
				echo '<br>for the packet number '; echo $id_packet_dec;
				echo '</p>';

				echo '<script type="text/javascript">';
				
				echo 'setTimeout("';
				echo "location.href = './cmd_send_packets_signal.php?address_peri=".$address_peri."&id_packet=".$id_packet."&num_packets=".$num_packets."&signal_coefficient=".$signal_coefficient."&cont_retry=" . $cont_retry . "&data_signal=".$data_signal."&redirect_page=".$redirect_page."';";
				echo '", 250); ';
				
				echo '</script>';

				echo '</div>';
				echo '</body></html>';
			}else{ //the peri is unreachable
				echo '<html>';
				echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
				echo '<body>';
				echo '<div class="div_home">';

				echo '<br><p>THE PERIPHERAL IS UNREACHABLE! <br>APPLICATION IN TIME OUT!</p>';

				echo '<script type="text/javascript">';
				echo "setTimeout('";
				if($redirect_page==='')
					echo 'location.href = "./infrared_functions.php?address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&data_signal='.$data_signal.'&redirect_page='.$redirect_page.'";';
				else
					echo 'location.href = "./'.$redirect_page.'.php?address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&data_signal='.$data_signal.'&redirect_page='.$redirect_page.'";';
				
				echo "', 3000);";
				echo '</script>';

				echo '</div>';
				echo '</body></html>';
			}
		}

	}
	
}else{ //it went in time out by the application and not by the peripheral
		echo '<html>';
		echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
		echo '<body>';
		echo '<div class="div_home">';

		echo '<br><p>THE RFPI.C DID NOT REPLY! <br>APPLICATION IN TIME OUT!</p>';

		echo '<script type="text/javascript">';
		echo "setTimeout('";
		if($redirect_page==='')
			echo 'location.href = "./infrared_functions.php?address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&data_signal='.$data_signal.'&redirect_page='.$redirect_page.'";';
		else
			echo 'location.href = "./'.$redirect_page.'.php?address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&data_signal='.$data_signal.'&redirect_page='.$redirect_page.'";';
		
		echo "', 3000);";
		echo '</script>';

		echo '</div>';
		echo '</body></html>';
	
}

?>



