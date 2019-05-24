<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		14/04/2015

Description: wait for the end of the signal acquisition

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

$max_counter = 200;

$address_peri=$_GET['address_peri'];
$counter=$_GET['counter'];

if($counter==='')
	$counter=0;
$counter++;


if( !($counter > $max_counter)){ //if it is not in time out

	//header('Location: ./infrared_functions.php?address_peri='.$address_peri.'&counter=0');

	if(!file_exists(FIFO_RFPI_STATUS)){ 
			//if there is not fifo will just reload it self after the delay
	}else{
		
		//open the fifo to check the message into
		$data=readFIFO(FIFO_RFPI_STATUS);
		//echo $data; echo '<br>';
				
		if($data==="OK"){ //if there is no answer will send a command again
			$strCmd = "DATA RF ".strval($address_peri)." 524275012E2E2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important
			writeFIFO(FIFO_GUI_CMD, $strCmd);
			//echo 'OK<br>';
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
				
				echo $address_peri_from_reply; echo '<br>';
				
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
				echo $data_radio; echo '<br>';
				
				
			if($data_radio[11]=='2' || $data_radio[11]=='0'){ //the RF-SDS reply and into the data says it went in time out
				$counter=$max_counter; //to show the error message
			}else{
			
				$num_packets = $data_radio[12].$data_radio[13]; //number of packets
				$signal_coefficient = $data_radio[14].$data_radio[15]; //multiplication coefficient 
				
				header('Location: ./try_and_save_signal.php?address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient);

				//here the data displayed, to see this comment the previous line: header('Location: ................
				$strTemp = $data_radio[12].$data_radio[13]; //number of packets
				$num_packets = convert_2ChrHex_to_byte($strTemp);
				
				$strTemp = $data_radio[14].$data_radio[15]; //multiplication coefficient 
				$signal_coefficient = convert_2ChrHex_to_byte($strTemp);
				
				echo 'Num Packets='; echo $num_packets;
				echo ' Coefficient='; echo $signal_coefficient;
				
				echo '<br>';
			}
			
			/*if($data[13]=='2' || $data[13]=='0'){ //the RF-SDS reply and into the data says it went in time out
				$counter=$max_counter; //to show the error message
			}else{
			
				$num_packets = $data[14].$data[15]; //number of packets
				$signal_coefficient = $data[16].$data[17]; //multiplication coefficient 
				
				header('Location: ./try_and_save_signal.php?address_peri='.$address_peri.'&num_packets='.$num_packets.'&signal_coefficient='.$signal_coefficient);

				//here the data displayed, to see this comment the previous line: header('Location: ................
				$strTemp = $data[14].$data[15]; //number of packets
				$num_packets = convert_2ChrHex_to_byte($strTemp);
				
				$strTemp = $data[16].$data[17]; //multiplication coefficient 
				$signal_coefficient = convert_2ChrHex_to_byte($strTemp);
				
				echo 'Num Packets='; echo $num_packets;
				echo ' Coefficient='; echo $signal_coefficient;
				
				echo '<br>';
			}*/
			
		}

	}
	


	echo '<html>';
	echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
	echo '<body>';
	echo '<div class="div_home">';

	echo '<br><p>Waiting for the END of the Signal Acquisition ....</p>';
	echo '<br><p>';
	echo ($counter/2); echo ' / '; echo ($max_counter/2);
	echo '</p>';

	echo '<script type="text/javascript">';
	?> setTimeout("<?
	echo "location.href = './wait_end_record_signal.php?address_peri=".$address_peri."&counter=" . $counter . "';";
	?>", 250); <?
	echo '</script>';

	echo '</div>';
	echo '</body></html>';


}else{ //it went in time out by the application and not by the peripheral
	
	echo '<html>';
	echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
	echo '<body>';
	echo '<div class="div_home">';

	echo '<br><p>THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!</p>';

	echo '<script type="text/javascript">';
	?> setTimeout("<?
	echo "location.href = './infrared_functions.php?address_peri=".$address_peri."&counter=" . $counter . "';";
	?>", 4000); <?
	echo '</script>';

	echo '</div>';
	echo '</body></html>';
	
}

?>



