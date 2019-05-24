<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/09/2015

Description: it check for the packets and then save it into the database X_db_infrared_signals.txt
				where X is the address_peri 

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

$max_cont_retry = 2;

$signal_name = $_GET['signal_name'];

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

$strTemp = $id_packet[0].$id_packet[1]; //id packet
$id_packet_dec = convert_2ChrHex_to_byte($strTemp);
					
if( ($counter < $max_counter)){ //if it is not in time out

	//header('Location: ./infrared_functions.php?address_peri='.$address_peri.'&counter=0');

	if(!file_exists(FIFO_RFPI_STATUS)){ 
			//if there is not fifo will just reload it self after the delay
			//echo "<br>NO FIFO";
	}else{
		
		//open the fifo to check the message into
		$data=readFIFO(FIFO_RFPI_STATUS);
		echo $data; echo '<br>';
				
		if($data==="OK"){ //if there is no answer about the packet then will send a command again
			//$strCmd = "DATA RF ".strval($address_peri)." 524275012E2E2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important
			//writeFIFO(FIFO_GUI_CMD, $strCmd);
			//echo "<br>FIFO=OK";
		}else{
		
				//get the position from the reply
				$maxLenLine=strlen($data);
				$j=0;
				$address_peri_from_reply="";
				$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$address_peri_from_reply=$address_peri_from_reply . $data[$j];
						$j++;
					}
				}
				
				//echo $address_peri_from_reply; echo '<br>';
				
				//get data radio
				$j=$count+1;
				$data_radio="";
				//$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$data_radio=$data_radio . $data[$j];
						$j++;
					}
				}
				//echo $data_radio; echo '<br>';
				
				
			if($data_radio[9]=='2' || $data_radio[9]=='0'){ //the RF-SDS reply and into the data says it went in time out
				$counter=$max_counter; //to show the error message
			}else{
			
				$id_packet_reply = $data_radio[10] . $data_radio[11]; 
				
				if($address_peri === $address_peri_from_reply && $id_packet === $id_packet_reply){ //if it is the packet wanted, thus can save it into the DB
					
					$strTemp = $num_packets[0].$num_packets[1]; //number of packets
					$num_packets_dec = convert_2ChrHex_to_byte($strTemp);
					
					echo $num_packets_dec;
	
					$strTemp = $signal_coefficient[0].$signal_coefficient[1]; //multiplication coefficient 
					$signal_coefficient_dec = convert_2ChrHex_to_byte($strTemp);
					
					//$strTemp = $id_packet[0].$id_packet[1]; //id packet
					//$id_packet_dec = convert_2ChrHex_to_byte($strTemp);
					
					//bulding the line to write into the file
					$txt = "";
					if($id_packet === "00"){ //first part of the line 
						if(file_exists("./db/".$address_peri_from_reply."_db_infrared_signals.txt")){
							$txt = $txt . "\n" ;//. chr(0xD);
						}
						$txt = $txt . $signal_name . " ";
						$txt = $txt . strval($num_packets_dec);
						$txt = $txt . " ";
						$txt = $txt . strval($signal_coefficient_dec);
						$txt = $txt . " ";
						for($i=0; $i<20; $i++)
							$txt = $txt . $data_radio[($i+12)];
							
						$txt = $txt . " ";
					}else{ //second part of the line the packets of the signal data
						for($i=0; $i<20; $i++)
							$txt = $txt . $data_radio[($i+12)];
							
						$txt = $txt . " ";
					}
					//if(file_exists("./".$address_peri_from_reply."_db_infrared_signals.txt")){
					$handle = fopen("./db/".$address_peri_from_reply."_db_infrared_signals.txt", 'a');
					//while(feof($handle)!==TRUE){ if($data=fgets($handle)){ }}
					fwrite($handle,$txt);
					fclose($handle);
					//}
					
					if($id_packet_dec < $num_packets_dec){ 
						
						$id_packet_dec++;
						
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
							
						header('Location: ./cmd_get_packets_signal.php?signal_name='.$signal_name.'&address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&id_packet='.$id_packet.'&cont_retry=0');
						//echo "<br>GET PACKET";
					}else{
						header('Location: ./infrared_functions.php?address_peri='.$address_peri);
						//echo "<br>INFRA FUNCTIONS";
					}
				
					
				}else{ //it retry to ask the packet
					//will make a retry
					header('Location: ./cmd_get_packets_signal.php?signal_name='.$signal_name.'&address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'&id_packet='.$id_packet.'&cont_retry=0');
					
				}
				
				
			}
			
			
		}

	}
	


	echo '<html>';
	echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
	echo '<body>';
	echo '<div class="div_home">';

	echo '<br><p>Reading packets and saving the Signal into the database ....</p>';
	echo '<br><p>';
	echo 'Retry '; echo ($cont_retry+1); echo ' of '; echo ($max_cont_retry+1);
	echo '<br>for the packet number '; echo $id_packet_dec;
	echo '</p>';

	echo '<script type="text/javascript">';
	
	echo 'setTimeout("';
	echo "location.href = './save_packet_into_file.php?signal_name=".$signal_name."&address_peri=".$address_peri."&id_packet=".$id_packet."&num_packets=".$num_packets."&signal_coefficient=".$signal_coefficient."&counter=" . $counter ."&cont_retry=" . $cont_retry . "';";
	echo '", 250); ';
		
	echo '</script>';

	echo '</div>';
	echo '</body></html>';


}else{ //it went in time out by the application and not by the peripheral
	
	if($cont_retry<$max_cont_retry){
		$cont_retry++;
		
		echo '<html>';
		echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
		echo '<body>';
		echo '<div class="div_home">';

		echo '<br><p>Reading packets and saving the Signal into the database ....</p>';
		echo '<br><p>';
		//echo strval($counter); echo ' / '; echo $max_counter;
		echo '</p>';

		echo '<script type="text/javascript">';
		
		echo 'setTimeout("';
		echo "location.href = './cmd_get_packets_signal.php?signal_name=".$signal_name."&address_peri=".$address_peri."&id_packet=".$id_packet."&num_packets=".$num_packets."&signal_coefficient=".$signal_coefficient."&counter=" . $counter . "&cont_retry=".$cont_retry."';";
		echo '", 10); ';
		
		echo '</script>';

		echo '</div>';
		echo '</body></html>';
	}else{
		echo '<html>';
		echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
		echo '<body>';
		echo '<div class="div_home">';

		echo '<br><p>THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!</p>';

		echo '<script type="text/javascript">';
		echo "setTimeout('";
		echo 'location.href = "./try_and_save_signal.php?signal_name='.$signal_name.'&address_peri='.$address_peri.'&id_packet='.$id_packet.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient.'";';
		echo "', 3000);";
		echo '</script>';

		echo '</div>';
		echo '</body></html>';
	}
	
}

?>



