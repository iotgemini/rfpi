<?php

/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		19/05/2016

Description: form where to type the name of the peripheral name to set

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

$lang_error_space="You typed a space! Please do not time spaces!";
$lang_type_name="Type the peripheral name: ";
if($_SESSION["language"]=="IT"){
	$lang_error_space="You typed a space! Please do not time spaces!";
	$lang_type_name="Digita in nome del periferico: ";
}else if($_SESSION["language"]=="FR"){	
	$lang_error_space="You typed a space! Please do not time spaces!";
	$lang_type_name="Tapez le nom p&eacute;riph&eacute;rique: ";
}else if($_SESSION["language"]=="SP"){	
	$lang_error_space="You typed a space! Please do not time spaces!";
	$lang_type_name="Escriba el nombre perif&eacute;rica: ";
}

//---------------------------------------------------------------------------------------//


$address=$_GET["address"]; //get the address from the url

$error=$_GET["error"];

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

if($error==="space"){
	echo '<p style="color:darkred">';
	//echo 'You typed a space! Please do not time spaces!';
	echo $lang_error_space;
	echo '</p>';
}

echo '<br>';

echo '<p>';
echo '<form name="save" action="./save_peri_name.php" method=GET>';
echo $lang_type_name . '<input type=text name="name" value="">';//(DO NOT TYPE SPACES!)';
echo '<input type=hidden name="address" value="' . $address . '">';
echo '<input type=hidden name="page_to_redirect" value="' . $page_to_redirect . '">';
echo '</form>';
echo '</p>';

echo '<p>';
echo '<form name="home" action="./index.php" method=GET>';
echo '<input type=hidden name="counter" value="1">';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
echo '<input type=button onclick="document.save.submit();" value="'.$lang_btn_save.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';
echo '</body></html>';
?>