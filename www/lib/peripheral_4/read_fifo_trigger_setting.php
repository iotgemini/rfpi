<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		18/05/2016

Description: it check if the peri answer with the settings of the TIMER

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
//		Specific library for the Peri4
		include './lib/peri4_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_reading_settings="Reading settings ....";
$lang_retry="Retry ";
$lang_peri_not_reply="THE PERIPHERAL DID NOT REPLY!<br>APPLICATION IN TIME OUT!";
if($_SESSION["language"]=="IT"){
	$lang_reading_settings="Sto leggendo ....";
	$lang_retry="Prova ";
	$lang_peri_not_reply="IL PERIFERICO NON HA RISPOSTO!<br>APPLICAZIONE FUORI TEMPO!";
}else if($_SESSION["language"]=="FR"){	
	$lang_reading_settings="Je lis ....";
	$lang_retry="Test ";
	$lang_peri_not_reply="Le périphérique ne répondait pas! <br> Application en timeout!";
}else if($_SESSION["language"]=="SP"){	
	$lang_reading_settings="Estoy leyendo ....";
	$lang_retry="Prueba ";
	$lang_peri_not_reply="IL PERIFERICO NON HA RISPOSTO!<br>APLICACI&Oacute;N FUERA DE TIEMPO!";
}

//---------------------------------------------------------------------------------------//



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

echo '<br><p>'.$lang_reading_settings.'</p>'; //Reading settings ....


/*echo '<br><p>';
echo $lang_retry; //'Retry '; 
echo ($cont_retry+1); echo ' of '; echo ($max_cont_retry+1);
echo '</p>';
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
							
				if($address_peri === $address_peri_from_reply /*&& $id_packet === $id_packet_reply*/){ //if it is the packet wanted, thus can save it into the DB

					$trigger_enabled = intval($data_radio[8] . $data_radio[9], 16); 
					$trigger_temperature_low_INT = intval($data_radio[10] . $data_radio[11], 16);
					$trigger_temperature_low_DEC = intval ($data_radio[12] . $data_radio[13], 16); 
					$trigger_temperature_high_INT = intval ($data_radio[14] . $data_radio[15], 16); 
					$trigger_temperature_high_DEC = intval ($data_radio[16] . $data_radio[17], 16);
					$temperature_offset_to_send_data_temperature = intval ($data_radio[18] . $data_radio[19], 16);	
					$current_temperature_INT = intval ($data_radio[20] . $data_radio[21], 16);	
					$current_temperature_DEC = intval ($data_radio[22] . $data_radio[23], 16);
					
					header('Location: ./trigger_functions.php?address_peri='.$address_peri.'&position_id='.$position_id.'&trigger_enabled='.$trigger_enabled.'&trigger_temperature_low_INT='.$trigger_temperature_low_INT.'&trigger_temperature_low_DEC='.$trigger_temperature_low_DEC.'&trigger_temperature_high_INT='.$trigger_temperature_high_INT.'&trigger_temperature_high_DEC='.$trigger_temperature_high_DEC.'&temperature_offset_to_send_data_temperature='.$temperature_offset_to_send_data_temperature.'&current_temperature_INT='.$current_temperature_INT.'&current_temperature_DEC='.$current_temperature_DEC);
					$reload_this_page = 0;
					
				}else{ //it retry 
					//will make a retry
					//header('Location: ./cmd_get_gpio_setting.php?address_peri='.$address_peri.'&position_id='.$position_id.'&cont_retry=0');
					
					$cont_retry++;
					if($cont_retry<$max_cont_retry+1){
						echo '<br><p>';
						echo $lang_retry; //'Retry '; 
						echo $cont_retry; echo ' &frasl; '; echo $max_cont_retry;
						echo '</p>';
						
						echo '<script type="text/javascript">';
						echo 'setTimeout("';
						echo "location.href = './cmd_get_trigger_setting.php?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter . "&cont_retry=".$cont_retry."&$redirect_page=".$redirect_page."';";
						echo '", 10); ';
						echo '</script>';
					}else{
						echo '<br><p style="color:darkred">'.$lang_peri_not_reply.'</p>';//THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!

						echo '<script type="text/javascript">';
						echo "setTimeout('";
						echo 'location.href = "./trigger_functions.php?address_peri='.$address_peri.'&position_id='.$position_id.'";';
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
		echo "location.href = './read_fifo_trigger_setting.php?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter ."&cont_retry=" . $cont_retry . "&redirect_page=".$redirect_page."';";
		echo '", 25); ';
		echo '</script>';
	}
}else{ //it went in time out by the application and not by the peripheral
	
	$cont_retry++;
	if($cont_retry<$max_cont_retry+1){
		//header('Location: ./cmd_get_trigger_setting.php?address_peri='.$address_peri.'&counter='.$counter.'&cont_retry='.$cont_retry.'&redirect_page='.$redirect_page);
		echo '<br><p>';
		echo $lang_retry; //'Retry ';  
		echo $cont_retry; echo ' of '; echo $max_cont_retry;
		echo '</p>';
						
		echo '<script type="text/javascript">';
		echo 'setTimeout("';
		echo "location.href = './cmd_get_trigger_setting.php?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter . "&cont_retry=".$cont_retry."&$redirect_page=".$redirect_page."';";
		echo '", 3000); ';
		echo '</script>';

	}else{

		echo '<br><p style="color:darkred">'.$lang_peri_not_reply.'</p>';//THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!

		echo '<script type="text/javascript">';
		echo "setTimeout('";
		echo 'location.href = "./trigger_functions.php?address_peri='.$address_peri.'&position_id='.$position_id.'";';
		echo "', 3000);";
		echo '</script>';

	}
	
}

echo '</div>';
echo '</body></html>';
		
?>



