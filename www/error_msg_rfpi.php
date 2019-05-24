<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/05/2019

Description: it lists the error encountered

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
 
 
$str_ERROR001 = "ERROR001: Impossible to initialise the GPIO!";
if($_SESSION["language"]=="IT"){
	$str_ERROR001 = "ERROR001: Impossibile inizializzare i GPIO!";
}else if($_SESSION["language"]=="FR"){	
	$str_ERROR001 = "ERROR001: Impossible d&rsquo;initialiser le GPIO!";
}else if($_SESSION["language"]=="SP"){	
	$str_ERROR001 = "ERROR001: Imposible inicializar el GPIO!";
}

$str_ERROR002 = "ERROR002: No serial communication with radio transceiver!";
if($_SESSION["language"]=="IT"){
	$str_ERROR002 = "ERROR002: Nessuna comunicazione seriale con radio modulo!";
}else if($_SESSION["language"]=="FR"){	
	$str_ERROR002 = "ERROR002: Aucune communication s&eacute;rie avec &eacute;metteur-r&eacute;cepteur radio!";
}else if($_SESSION["language"]=="SP"){	
	$str_ERROR002 = "ERROR002: No hay comunicaci&oacute;n serie con el transceptor de radio!";
}

$str_ERROR003 = "The network name is not set!";
if($_SESSION["language"]=="IT"){
	$str_ERROR003 = "Il nome della rete non &egrave; impostato!";
}else if($_SESSION["language"]=="FR"){	
	$str_ERROR003 = "Le nom du r&eacute;seau n&rsquo;est pas r&eacute;gl&eacute;!";
}else if($_SESSION["language"]=="SP"){	
	$str_ERROR003 = "El nombre de la red no est&aacute; configurado!";
}

 
echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

$data=$_GET['data'];

$display_btn_home = 1;

if($data==="TRUE"){
	echo '<p style="color:green">TRUE</p>';
}else if(substr($data,0,8)==="ERROR001"){
	echo '<p style="color:red">' . $str_ERROR001 . '</p>';
}else if(substr($data,0,8)==="ERROR002"){
	echo '<p style="color:red">' . $str_ERROR002 . '</p>';
}else if(substr($data,0,8)==="ERROR003"){
	$display_btn_home = 0;
	echo '<p style="color:red">' . $str_ERROR003 . '</p>';
	echo '<script type="text/JavaScript">';
    ?> setTimeout("location.href = 'get_network_name.php?data=ERROR003';", 3000); <?php
    echo '</script>';
}else if(substr($data,0,5)==="ERROR"){ //all others errors
	echo '<p style="color:red">';
	echo $data;
	echo '</p>';
	
	echo '<script type="text/JavaScript">';
    ?> setTimeout("location.href = 'home.php?data=<?php 
	echo urlencode(substr($data,0,8)); 
	?>';", 3000); <?php
    echo '</script>';
}else{
	/*echo 'Just a moment! ;)';
	echo '<script type="text/JavaScript">';
    ?> setTimeout("location.href = 'index.php';", 100); <?php
    echo '</script>';
	*/
	//header("Location: http://support.rfberrypi.com");
	header("Location: index.php");
	
}

$counter=$_GET['counter'];
$counter++;
//button Try Again
/*echo '<form name="try_again" action="./index.php" method=GET>';
echo '<input type=hidden name="counter" value="' . $counter . '">';
echo '<input type=submit value="'.$lang_btn_retry.'" class="btn_pag">';
echo '</form>';*/

//button HOME
if($display_btn_home == 1){
	echo '<p align=left>';
	echo '<form name="home" action="/home.php" method=GET>';
	echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
	echo '</form>';
	echo '</p>';
}	
	
echo '</div>';
echo '</body></html>';	
		
?>