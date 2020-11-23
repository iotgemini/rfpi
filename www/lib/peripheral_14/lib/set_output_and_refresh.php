<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/11/2020

Description: it will create the FIFO with the output to set
example FIFO: peri=0 out=0 1

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


//---------------------------------------------------------------------------------------//
//		library with all useful functions to use RFPI
		include './../../../lib/rfberrypi.php';
//---------------------------------------------------------------------------------------//



$peripheral_id=$_GET['peripheral_id'];
$output_id=$_GET['output_id'];
$output_value=$_GET['output_value'];
$address_peri=$_GET['address_peri'];

writeFIFO(FIFO_GUI_CMD, "PERIOUT " . $peripheral_id . " ".$output_id." ".$output_value." ");
usleep( 400 * 1000 );
writeFIFO(FIFO_GUI_CMD, "REFRESH PERI STATUS ".$address_peri." ");


//header( 'Location: home.php' ) ;


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


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';	

	$next_page_name = "index.php";
	
	echo '<script type="text/javascript">';
	echo "setTimeout('";
	//echo 'location.href = "/' . $next_page_name . '?address_peri='.$address_peri.'&position_id='.$position_id.'&status_rfpi='.$status_rfpi."&".$parameters_retry_page.'";';
	echo 'location.href = "/' . $next_page_name . '";';
	echo "', 1000);";
	echo '</script>';
	
echo '<br><p>'.$lang_reading_settings.'</p>'; //Reading settings ....
//ob_flush();
//flush();

echo '</div>';
echo '</body></html>';


?>