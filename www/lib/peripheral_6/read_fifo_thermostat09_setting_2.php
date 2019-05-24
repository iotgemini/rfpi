<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		27/11/2015

Description: it check if the peri answer with the settings of the THERMOSTAT

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
//		Specific library for the Peri
		include './lib/peri_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


$max_counter = 100;

$max_cont_retry = 2;

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];
if($counter==='')
	$counter=0;
$counter++;

$cont_retry = $_GET['cont_retry'];

$reload_this_page = 1;

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';		

echo '<br><p>Reading settings THERMOSTAT ....</p>';

/*echo '<br><p>';
echo 'Retry '; echo ($cont_retry+1); echo ' of '; echo ($max_cont_retry+1);
echo '</p>';
$data=readFIFO(FIFO_RFPI_STATUS);
echo $data; echo '<br>';
*/

if( ($counter < $max_counter)){ //if it is not in time out

	if(!file_exists(FIFO_RFPI_STATUS)){ 
			//if there is not fifo will just reload it self after the delay
	}else{
		
		//open the fifo to check the message into
		$data=readFIFO(FIFO_RFPI_STATUS);
		//echo $data; echo '<br>';
				
		if($data[0]==='O' && $data[1]==='K'){ //if there is no answer about the packet then will send a command again
			//$strCmd = "DATA RF ".strval($address_peri)." 524275012E2E2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important
			//writeFIFO(FIFO_GUI_CMD, $strCmd);
			//echo 'OK<br>';
		}else{
	
			if($data[0]==='N' && $data[1]==='O' && $data[2]==='T' && $data[3]==='X'){
				//the peri is unreachable
				$counter = $max_counter;
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
				
				//echo $data_radio; echo '<br>';//to delete
							
				if($address_peri === $address_peri_from_reply ){ //&& $id_packet === $id_packet_reply //if it is the packet wanted, thus can save it into the DB

					$thermostat_enabled = intval($data_radio[8] . $data_radio[9], 16); 
					$id_packet = intval($data_radio[10] . $data_radio[11], 16);
					
					//$str_temperature_09 = intval($data_radio[12] . $data_radio[13], 16); 
					$j=0;
					$str_temperature_09 = '';
					while ($j<10) {
						$str_temperature_09 .= '&temperature_' .$j. '='.$intval($data_radio[12+$j] . $data_radio[13+$j], 16);
						$j++;
					}
					
					header('Location: ./thermostat09_functions.php?address_peri='.$address_peri.'&position_id='.$position_id.'&thermostat_enabled='.$thermostat_enabled.'&id_packet='.$id_packet.$str_temperature_09);
					$reload_this_page = 0;
					
				}else{ //it retry 
					//will make a retry
					//header('Location: ./cmd_get_gpio_setting.php?address_peri='.$address_peri.'&position_id='.$position_id.'&cont_retry=0');
					
					$cont_retry++;
					if($cont_retry<$max_cont_retry+1){
						echo '<br><p>';
						echo 'Retry '; echo $cont_retry; echo ' of '; echo $max_cont_retry;
						echo '</p>';
						
						echo '<script type="text/javascript">';
						echo 'setTimeout("';
						echo "location.href = './cmd_get_thermostat09_setting.php?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter . "&cont_retry=".$cont_retry."&$redirect_page=".$redirect_page."';";
						echo '", 10); ';
						echo '</script>';
					}else{
						echo '<br><p style="color:darkred">THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!</p>';

						echo '<script type="text/javascript">';
						echo "setTimeout('";
						echo 'location.href = "./thermostat09_functions.php?address_peri='.$address_peri.'&position_id='.$position_id.'";';
						echo "', 3000);";
						echo '</script>';
					}
					
				}
				
			}
		
			
			
		}

	}

	if($reload_this_page == 1){
		echo '<script type="text/javascript">';
		echo 'setTimeout("';
		echo "location.href = './read_fifo_thermostat09_setting.php?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter ."&cont_retry=" . $cont_retry . "&redirect_page=".$redirect_page."';";
		echo '", 25); ';
		echo '</script>';
	}
}else{ //it went in time out by the application and not by the peripheral
	
	$cont_retry++;
	if($cont_retry<$max_cont_retry+1){
		//header('Location: ./cmd_get_timer_setting.php?address_peri='.$address_peri.'&counter='.$counter.'&cont_retry='.$cont_retry.'&redirect_page='.$redirect_page);
		echo '<br><p>';
		echo 'Retry '; echo $cont_retry; echo ' of '; echo $max_cont_retry;
		echo '</p>';
						
		echo '<script type="text/javascript">';
		echo 'setTimeout("';
		echo "location.href = './cmd_get_thermostat09_setting.php?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter . "&cont_retry=".$cont_retry."&$redirect_page=".$redirect_page."';";
		echo '", 3000); ';
		echo '</script>';

	}else{

		echo '<br><p style="color:darkred">THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!</p>';

		echo '<script type="text/javascript">';
		echo "setTimeout('";
		echo 'location.href = "./thermostat09_functions.php?address_peri='.$address_peri.'&position_id='.$position_id.'";';
		echo "', 3000);";
		echo '</script>';

	}
	
}

echo '</div>';
echo '</body></html>';
		
?>



