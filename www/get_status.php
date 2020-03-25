<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/03/2020

Description: it will read the FIFO of the status of the RFberry Pi
example FIFO: NOTX
means: unsuccessful transmission

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
		include './lib/rfberrypi.php';  
//---------------------------------------------------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_tx_ok="Successful transmission!";
$lang_peri_found="Peripheral found!";
$lang_peri_not_found="Peripheral not found!";
$lang_output_not_set="Output not set!<br>Impossible to communicate with the peripheral!";
if($_SESSION["language"]=="IT"){
	$lang_tx_ok="Trasmissione avvenuta!";
	$lang_peri_found="Periferico trovato!";
	$lang_peri_not_found="Periferico non trovato!";
	$lang_output_not_set="Uscita non impostata!<br>Impossibile comunicare con il periferico!";
}else if($_SESSION["language"]=="FR"){	
	$lang_tx_ok="Transmission r&eacute;ussie!";
	$lang_peri_found="P&eacute;riph&eacute;rique trouv&eacute;!";
	$lang_peri_not_found="P&eacute;riph&eacute;rique non trouv&eacute;!";
	$lang_output_not_set="Sortie non r&eacute;gl&eacute;e!<br>Impossible de communiquer avec le p&eacute;riph&eacute;rique!";
}else if($_SESSION["language"]=="SP"){	
	$lang_tx_ok="Transmisi&oacute;n con &eacute;xito!";
	$lang_peri_found="Perif&eacute;rica encontr&oacute;!";
	$lang_peri_not_found="Perif&eacute;rica que no se encuentra!";
	$lang_output_not_set="La salida no establece!<br>Imposible comunicarse con el perif&eacute;rico!";
}

//---------------------------------------------------------------------------------------//

usleep( 100 * 1000 );//delay to leave the time for the execution of the command

$action=$_GET['action'];

$status="?";

//open the fifo to check the message into
if(file_exists(FIFO_RFPI_STATUS)){ 
	$handle = fopen(FIFO_RFPI_STATUS, 'r');
	if(feof($handle)!==TRUE){ 
		$status=fgets($handle, 40);
	}
	fclose($handle);
	//@unlink(FIFO_RFPI_STATUS);
}
//echo $status;

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

if($status==="OK" && $action==="PERI_OUT"){
	echo '<p style="color:green">'.$lang_tx_ok.'</p>';
	ob_flush(); //it will send to the client what has been executed and then proceed with next instruction
	flush();
	echo '<script type="text/javascript">';
		echo 'setTimeout("'; 
			echo "location.href = './index.php';";
		echo '", 10);';
	echo '</script>';
}else if($status==="OK" && $action==="FIND_NEW"){
	echo '<p style="color:green">'.$lang_peri_found.'</p>';
	ob_flush(); //it will send to the client what has been executed and then proceed with next instruction
	flush();
	echo '<script type="text/javascript">';
		echo 'setTimeout("'; 
			echo "location.href = './index.php';";
		echo '", 500);';
	echo '</script>';
}else if($status==="NOPERI" && $action==="FIND_NEW"){
	echo '<p style="color:red">'.$lang_peri_not_found.'</p>';
	ob_flush(); //it will send to the client what has been executed and then proceed with next instruction
	flush();
	echo '<script type="text/javascript">';
		echo 'setTimeout("'; 
			echo "location.href = './index.php';";
		echo '", 9000);';
	echo '</script>';
}else if($status==="NOTX" && $action==="PERI_OUT"){
	echo '<p style="color:red">'.$lang_output_not_set.'</p>';
	ob_flush(); //it will send to the client what has been executed and then proceed with next instruction
	flush();
	echo '<script type="text/javascript">';
		echo 'setTimeout("'; 
			echo "location.href = './index.php';";
		echo '", 9000);';
	echo '</script>';
}else if($status!=="OK"){
	//if the status has not change yet
	htmlMsgWaitanswerFromRFPI(); //it just shows a message: Waiting answer from RFPI......
	flush();
	echo '<script type="text/javascript">';
		echo 'setTimeout("'; 
			echo "location.href = './get_status.php?action=" . $action . "';";
		echo '", 500);'; 
	echo '</script>';
}

echo '<form name="try_again" action="./index.php" method=GET>';
echo '<input type=hidden name="counter" value="1">';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';

echo '</div>';
echo '</body></html>';	

?>