<?php

/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/05/2016

Description: form where to type the name of the network to set

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

$lang_net_name="Type the network name: ";
$lang_net_reprogrammed="Warning!<br>After the change of the network name, <br>all linked peripherals must be reprogrammed!";
if($_SESSION["language"]=="IT"){
	$lang_net_name="Digita il nome di rete: ";
	$lang_net_reprogrammed="Attenzione!<br>Dopo il cambio del nome della rete, <br> tutte le periferiche collegate devono essere riprogrammate!";
}else if($_SESSION["language"]=="FR"){	
	$lang_net_name="Tapez le nom du r&eacute;seau: ";
	$lang_net_reprogrammed="Avertissement!<br>Apr&egrave;s le changement de nom du r&eacute;seau, <br> tous les p&eacute;riph&eacute;riques li&eacute;s doivent être reprogramm&eacute;es!";
}else if($_SESSION["language"]=="SP"){	
	$lang_net_name="Escriba el nombre de la red: ";
	$lang_net_reprogrammed="Advertencia!<br>Tras el cambio del nombre de la red, <br> todos los perif&eacute;ricos relacionados deben ser reprogramadas!";
}

//---------------------------------------------------------------------------------------//


$data=$_GET['data'];

$page_to_redirect=$_GET["page_to_redirect"];

$error=$_GET["error"];

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

if($error==="space"){
	echo '<p style="color:darkred">';
	echo 'You typed a space! Please do not time spaces!';
	echo '</p>';
}

echo '<br>';


echo '<p>';
echo '<form name="save" action="./save_network_name.php" method=GET>';
echo $lang_net_name . '<input type=text name="name" value="">'; //(DO NOT TYPE SPACES!)
echo '<input type=hidden name="address" value="' . $address . '">';
echo '<input type=hidden name="page_to_redirect" value="' . $page_to_redirect . '">';
echo '</form>';
echo '</p>';




if($data!=="ERROR003"){
	echo '<p class="warning_p ">';
	//echo 'Warning!<br>After the change of the network name, <br>all linked peripherals must be reprogrammed!';
	echo $lang_net_reprogrammed;
	echo '</p>';
}


echo '<p>';
echo '<form name="home" action="./index.php" method=GET>';
echo '<input type=hidden name="counter" value="1">';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
echo '<input type=button onclick="document.save.submit();" value="'. $lang_btn_save . '" class="btn_pag">';
echo '</form>';
echo '</p>';



echo '</div>';
echo '</body></html>'; 
?>