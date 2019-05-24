<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		27/01/2017

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
//		Specific library for the peri10
		include './lib/peri10_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//DEFINES
$PATH_THIS_PAGE = "./read_fifo_thermostat_setting.php"; 	//it is the name of this page
$PATH_NEXT_PAGE = "./thermostat_functions.php"; 			//it is the page with the control panel fo the function
$PATH_PREVIOUS_PAGE = "./cmd_get_thermostat_setting.php"; 	//this page send the command
$PATH_HOME_PAGE = "/index.php"; 							//it is the home where is displayed all control panel for each peripheral


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_reading_settings="Reading settings ....";
$lang_retry="Retry ";
$lang_peri_not_reply="THE PERIPHERAL DID NOT REPLY!<br>APPLICATION IN TIME OUT!";
$lang_btn_stop="STOP";
//$lang_peri_address_wrong = "THE REPLY CONTAIN THE ADDRESS OF ANOTHER PERIPHERAL!";
if($_SESSION["language"]=="IT"){
	$lang_reading_settings="Sto leggendo ....";
	$lang_retry="Prova ";
	$lang_peri_not_reply="IL PERIFERICO NON HA RISPOSTO!<br>APPLICAZIONE FUORI TEMPO!";
	//$lang_peri_address_wrong = "LA RISPOSTA CONTIENE L&sbquo;INDIRIZZO DI UN ALTRO PERIFERICO!";
}else if($_SESSION["language"]=="FR"){	
	$lang_reading_settings="Je lis ....";
	$lang_retry="Test ";
	$lang_peri_not_reply="Le p&eacute;riph&eacute;rique ne r&eacute;pondait pas! <br> Application en timeout!";
	//$lang_peri_address_wrong = "LA RE&sbquo;PONSE CONTIENT L&sbquo;ADRESSE D&sbquo;UN AUTRE P&Eacute;RIPH&Eacute;RIQUE!";
}else if($_SESSION["language"]=="SP"){	
	$lang_reading_settings="Estoy leyendo ....";
	$lang_retry="Prueba ";
	$lang_peri_not_reply="IL PERIFERICO NON HA RISPOSTO!<br>APLICACI&Oacute;N FUERA DE TIEMPO!";
	//$lang_peri_address_wrong = "LA RESPUESTA CONTIENE LA DIRECCI&Oacute;N  DEL OTRO DISPOSITIVO PERIF&Eacute;RICO!";
}

//---------------------------------------------------------------------------------------//


$num_special_function = $_GET['num_special_function'];
$num_bytes_to_get = $_GET['num_bytes_to_get'];


//before return the time out, the page will wait this mS:  $max_counter*$delay_before_reload_this_page 
$delay_before_reload_this_page = 50;
$max_counter = $num_bytes_to_get/3; 

$max_cont_retry = 2;

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];
if($counter==='')
	$counter=0;
$counter++;

$cont_retry = $_GET['cont_retry'];


//variable to attach to links in order to keep the datas between pages
$all_datas_for_get = "";
$all_datas_for_get.= "address_peri=".$address_peri;
$all_datas_for_get.= "&position_id=".$position_id;
$all_datas_for_get.= "&counter=" . $counter ;
$all_datas_for_get.= "&cont_retry=" . $cont_retry ;
$all_datas_for_get.=  "&redirect_page=".$redirect_page;
$all_datas_for_get.=  "&num_bytes_to_get=".$num_bytes_to_get;
$all_datas_for_get.=  "&num_special_function=".$num_special_function;


$reload_this_page = 1;

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';	

echo '<br>';

//button STOP CMD
echo '<form name="peri10_btn_stop_reading_settings" action="./cmd_stop_cmd.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_stop.'" class="btn_functions">';		
echo '</form>';
	

//echo '<br><p>'.$lang_reading_settings.'</p>'; //Reading settings ....


if( ($counter < $max_counter)){ //if it is not in time out

	if(!file_exists(FIFO_RFPI_STATUS)){ 
			//if there is not fifo will just reload it self after the delay
	}else{
		
		//open the fifo to check the message into
		$data=readFIFO(FIFO_RFPI_STATUS);
		//echo $data; echo '<br>';
				
		if($data[0]==='O' && $data[1]==='K'){ //if there is no answer then it goes in timeout

		}else{
			
			if($data[0]==='N' && $data[1]==='O' && $data[2]==='T' && $data[3]==='X'){
				//the peri is unreachable
				$counter = $max_counter;
			}else{

				if($data[0]==='E' && $data[1]==='R' && $data[2]==='R' && $data[3]==='O' && $data[4]==='R'){
					$reload_this_page = 0;
					echo '<br><p style="color:darkred">'.$data.'</p>';//
			
				}else{

					//if there is a reply and all data has been got, then into the fifogetbytesu there is written:
					//		GET_BYTES_U 	/path_where_is_the_file_that_has_all_data
										
					//get the position of the address from the reply
					$maxLenLine=strlen($data);
					$j=0;
					$cmd_reply="";
					$count=0;
					while ($j<$maxLenLine) {
						if($data[$j]===" "){
							$count=$j;
							$j=$maxLenLine;
						}else{
							$cmd_reply=$cmd_reply . $data[$j];
							$j++;
						}
					}
					//echo $cmd_reply; echo '<br>';
					
					if($cmd_reply === "GET_BYTES_U"){

						//get the address
						$maxLenLine=strlen($data);
						$j=$count+1;
						$address_peri_from_reply="";
						//$count=0;
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
						
						//get the path of the file where are kept the data
						$j=$count+1;
						$path_data_file="";
						//$count=0;
						while ($j<$maxLenLine) {
							if($data[$j]===" "){
								$count=$j;
								$j=$maxLenLine;
							}else{
								$path_data_file=$path_data_file . $data[$j];
								$j++;
							}
						}
						//echo $path_data_file; echo '<br>';//to delete
									
						if($address_peri === $address_peri_from_reply ){ //
		
							$reload_this_page = 0;
							
							//going to the next page and giving the path of the file where are kept the data
							header('Location: '.$PATH_NEXT_PAGE.'?' //address_peri='.$address_peri.'&position_id='.$position_id
							.$all_datas_for_get
							.'&path_data_file='.$path_data_file
							);
							
							
						}else{ 											
							$reload_this_page = 0;
							echo '<br><p style="color:darkred">'.$lang_peri_address_wrong.'</p>';//THE REPLY CONTAIN THE ADDRESS OF ANOTHER PERIPHERAL! <br>

						}
						
					}
				
				}
				
			}
		
			
			
		}

	}

	
	
	if($reload_this_page == 1){
	
		echo '<br><p>'.$lang_reading_settings.'</p>'; //Reading settings ....
	
		echo '<script type="text/javascript">';
		echo 'setTimeout("';
		echo "location.href = '";
		echo $PATH_THIS_PAGE."?";
		echo $all_datas_for_get;
		echo "';";
		echo '", '.$delay_before_reload_this_page.'); ';
		echo '</script>';
	}


}else{ //it went in time out by the application and not by the peripheral
	
	$reload_this_page = 0;
	
	echo '<br><p style="color:darkred">'.$lang_peri_not_reply.'</p>'; //THE PERIPHERAL DID NOT REPLY! <br>APPLICATION IN TIME OUT!
	

}

echo '<br>';
echo '<br>';

//button HOME
	echo '<p align=left>';
	echo '<form name="home" action="/index.php" method=GET>';
	echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
	echo '</form>';
	echo '</p>';

echo '</div>';
echo '</body></html>';
		
?>



