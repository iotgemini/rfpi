<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/12/2017

Description: it check if the peri answer with the settings 

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

//		library with all useful functions to use RFPI
		include './../../../lib/rfberrypi.php'; 
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

//echo " " . FIFO_RFPI_STATUS;

$TAG0=$_GET['TAG0'];
$TAG1=$_GET['TAG1'];
$TAG2=$_GET['TAG2'];
$TAG3=$_GET['TAG3'];

$page_to_show_data=$_GET['page_to_show_data'];

if($page_to_show_data!=""){
	$next_page_name = $page_to_show_data; 
}else{	
	$next_page_name = "show_data_fifo.php";
}

$retry_page_name = "read_fifo_data.php";

$max_counter = 30;
$max_cont_retry = 2;


$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];
if($counter==='')
	$counter=0;

$counter++;

$cont_retry = $_GET['cont_retry'];
if($cont_retry==="") $cont_retry=0;

$reload_this_page = 1;


$status_rfpi = $_GET['status_rfpi'];
if($status_rfpi!=""){
	
}else{	
	$status_rfpi = "UNKNOWN";
}



$parameters_retry_page = "address_peri=".$address_peri."&position_id=".$position_id . "&redirect_page=".$redirect_page."&page_to_show_data=" . $page_to_show_data . "&TAG0=" . $TAG0 . "&TAG1=" . $TAG1 . "&TAG2=" . $TAG2 . "&TAG3=" . $TAG3 . "&id_hex_special_function=" . $id_hex_special_function ; 


function data_got_then_give_to_next_page($status_rfpi,$data_rfpi,$next_page_name,$address_peri,$position_id,$redirect_page, $parameters_retry_page){
	//redirecting to next page
	if($redirect_page!=""){
		$next_page_name = $redirect_page;
	}
	//echo $next_page_name;
	
	header('Location: ./../' . $next_page_name . '?status_rfpi='.$status_rfpi 
		.'&data_rfpi='.$data_rfpi
		. '&' . $parameters_retry_page
	);	
			
}




function the_peri_not_reply($status_rfpi,$lang_peri_not_reply,$next_page_name,$address_peri,$position_id,$parameters_retry_page){
	
//	echo $status_rfpi;
	//$next_page_name = "../.././index.php";
	$next_page_name = "index.php";
	
	echo '<br><p style="color:darkred">'.$lang_peri_not_reply.'</p>';//THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!
	echo '<script type="text/javascript">';
	echo "setTimeout('";
	echo 'location.href = "/' . $next_page_name . '?address_peri='.$address_peri.'&position_id='.$position_id.'&status_rfpi='.$status_rfpi."&".$parameters_retry_page.'";';
	echo "', 3000);";
	echo '</script>';

}


function reload_this_page($reload_this_page,$retry_page_name,$counter,$cont_retry,$status_rfpi,$parameters_retry_page){
	if($reload_this_page == 1){
		echo '<script type="text/javascript">';
		echo 'setTimeout("';
		echo "location.href = './" . $retry_page_name . "?counter=" . $counter . "&cont_retry=" . $cont_retry . "&status_rfpi=" . $status_rfpi . "&" . $parameters_retry_page . "';";
		echo '", 50); ';
		echo '</script>';
	}
}


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';		

echo '<br><p>'.$lang_reading_settings.'</p>'; //Reading settings ....
//ob_flush();
//flush();



if( ($counter < $max_counter)){ //if it is not in time out

	$counter++;
	
	if(!file_exists(FIFO_RFPI_STATUS)){ 
		//if there is not fifo will just reload it self after the delay
		reload_this_page($reload_this_page,$retry_page_name,$counter,$cont_retry,$status_rfpi,$parameters_retry_page); 
	}else{
		
		//open the fifo to check the message into
		$data = "XX XXXX 524275XX2E2E2E2E2E2E2E2E2E2E2E2E ";
		$data=readFIFO(FIFO_RFPI_STATUS);
		//echo "data=" . $data; echo '<br>';
		
		
		//echo "<br>adress=".$address_peri;
		if($data[0]===$address_peri[0] && $data[1]===$address_peri[1] && $data[2]===$address_peri[2] && $data[3]===$address_peri[3] ){

				//get the position from the reply
				$maxLenLine=strlen($data);
				$j=0;
				$address_peri_from_data="";
				$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$address_peri_from_data=$address_peri_from_data . $data[$j];
						$j++;
					}
				}
				//echo $address_peri_from_data; echo '<br>';
				
				//get data 
				$j=$count+1;
				$data_rfpi="";
				//$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$data_rfpi=$data_rfpi . $data[$j];
						$j++;
					}
				}
				//echo $data_rfpi; echo '<br>';//to delete
				
				$status_rfpi = "GOT";			
				data_got_then_give_to_next_page($status_rfpi,$data_rfpi,$next_page_name,$address_peri,$position_id,$redirect_page,$parameters_retry_page);				
				$reload_this_page = 0;
				
		}else
				
		if($data[0]==='N' && $data[1]==='O' && $data[2]==='T' && $data[3]==='X'){
			//the peri is unreachable
			$counter = $max_counter;
			$cont_retry = $max_cont_retry;
			$status_rfpi = "NOTX";

			the_peri_not_reply($status_rfpi,$lang_peri_not_reply,$next_page_name,$address_peri,$position_id,$parameters_retry_page); //THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!
		}else
			
		if($data[0]==='O' && $data[1]==='K'){ 
			reload_this_page($reload_this_page,$retry_page_name,$counter,$cont_retry,$status_rfpi,$parameters_retry_page);
		}else{
			//if there is not written anything then will try to read up to $max_counter
			reload_this_page($reload_this_page,$retry_page_name,$counter,$cont_retry,$status_rfpi,$parameters_retry_page);
		}
		
			
			
		

	}

	
	
}else{ //it went in time out by the application and not by the peripheral
	the_peri_not_reply($status_rfpi,$lang_peri_not_reply,$next_page_name,$address_peri,$position_id,$parameters_retry_page); //THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!
}

echo '</div>';
echo '</body></html>';

//ob_end_flush();

		
?>



