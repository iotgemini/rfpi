<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		11/07/2016

Description: it check if the peri answer with the settings of the INPUT

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
		include './lib/peri9_lib.php';  

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



$next_page_name = "functions_num_data.php";

$retry_page_name = "read_fifo_num_data.php";

$max_counter = 20;

$max_cont_retry = 2;

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];
if($counter==='')
	$counter=0;
$counter++;


$cont_retry = $_GET['cont_retry'];
if($cont_retry==="") $cont_retry=0;

$reload_this_page = 1;

function data_got_then_give_to_next_page($data_radio,$next_page_name,$address_peri,$position_id,$redirect_page){
	
					$sem_status_send_data_after_stop = intval($data_radio[8] . $data_radio[9], 16); 
					$num_data_to_send_H = intval($data_radio[10] . $data_radio[11], 16); 
					$num_data_to_send_L = intval($data_radio[12] . $data_radio[13], 16);
					$temp1 = intval($data_radio[14] . $data_radio[15], 16);
					$temp2 = intval($data_radio[16] . $data_radio[17], 16); 
					$temp3 = intval($data_radio[18] . $data_radio[19], 16);
					$temp4 = intval($data_radio[20] . $data_radio[21], 16);
					$temp5 = intval($data_radio[22] . $data_radio[23], 16);
					$temp6 = intval($data_radio[24] . $data_radio[25], 16);
					$temp7 = intval($data_radio[26] . $data_radio[27], 16);
					$temp8 = intval($data_radio[28] . $data_radio[29], 16);
					$temp9 = intval($data_radio[30] . $data_radio[31], 16);
					//$temp10 = intval($data_radio[32] . $data_radio[33], 16);
					
					//saving into file the temperature
					$array_data = array($num_data_to_send_L,$temp1,$temp2,$temp3,$temp4,$temp5,$temp6,$temp7,$temp8,$temp9);	
					save_data_peri9($array_data,10,$address_peri);
					
					//redirecting to next page
					if($redirect_page!=""){
						$next_page_name = $redirect_page;
					}
					echo $next_page_name;
					header('Location: ./' . $next_page_name . '?address_peri='.$address_peri.'&position_id='.$position_id
					//header('Location: ./../../index.php?address_peri='.$address_peri.'&position_id='.$position_id
					.'&sem_status_send_data_after_stop='.$sem_status_send_data_after_stop
					.'&num_data_to_send_H='.$num_data_to_send_H
					.'&num_data_to_send_L='.$num_data_to_send_L
					.'&temp1='.$temp1
					.'&temp2='.$temp2
					.'&temp3='.$temp3
					.'&temp4='.$temp4
					.'&temp5='.$temp5
					.'&temp6='.$temp6
					.'&temp7='.$temp7
					.'&temp8='.$temp8
					.'&temp9='.$temp9
					//.'&temp10='.$temp10
					);	
}




function the_peri_not_reply($lang_peri_not_reply,$next_page_name,$address_peri,$position_id){
	
	echo '<br><p style="color:darkred">'.$lang_peri_not_reply.'</p>';//THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!
	echo '<script type="text/javascript">';
	echo "setTimeout('";
	echo 'location.href = "./' . $next_page_name . '?address_peri='.$address_peri.'&position_id='.$position_id.'";';
	echo "', 3000);";
	echo '</script>';
	
}



echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';		

echo '<br><p>'.$lang_reading_settings.'</p>'; //Reading settings ....
//ob_flush();
//flush();




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
							
				if($address_peri === $address_peri_from_reply ){ //if it is the packet wanted, thus can save it into the DB
//&& $id_packet === $id_packet_reply
					data_got_then_give_to_next_page($data_radio,$next_page_name,$address_peri,$position_id,$redirect_page);
										
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
						echo "location.href = './" . $retry_page_name . "?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter . "&cont_retry=".$cont_retry."&$redirect_page=".$redirect_page."';";
						echo '", 10); ';
						echo '</script>';
					}else{
						the_peri_not_reply($lang_peri_not_reply,$next_page_name,$address_peri,$position_id); //THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!

					}
					
				}
				
			}
		
			
			
		}

	}

	if($reload_this_page == 1){
		echo '<script type="text/javascript">';
		echo 'setTimeout("';
		echo "location.href = './" . $retry_page_name . "?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter ."&cont_retry=" . $cont_retry . "&redirect_page=".$redirect_page."';";
		echo '", 25); ';
		echo '</script>';
		
		
	}
	
}else{ //it went in time out by the application and not by the peripheral
	
	$cont_retry++;
	if($cont_retry<$max_cont_retry+1){
		//header('Location: ./' . $retry_page_name . '?address_peri='.$address_peri.'&counter='.$counter.'&cont_retry='.$cont_retry.'&redirect_page='.$redirect_page);
		echo '<br><p>';
		echo $lang_retry; //'Retry ';  
		echo $cont_retry; echo ' of '; echo $max_cont_retry;
		echo '</p>';
						
		echo '<script type="text/javascript">';
		echo 'setTimeout("';
		echo "location.href = './" . $retry_page_name . "?address_peri=".$address_peri."&position_id=".$position_id."&counter=" . $counter . "&cont_retry=".$cont_retry. "&redirect_page=".$redirect_page."';";
		echo '", 3000); ';
		echo '</script>';

	}else{
		the_peri_not_reply($lang_peri_not_reply,$next_page_name,$address_peri,$position_id); //THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!

	}
	
}

echo '</div>';
echo '</body></html>';

//ob_end_flush();

		
?>



