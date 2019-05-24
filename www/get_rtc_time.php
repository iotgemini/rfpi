<?php

/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		03/08/2017

Description: form where to type the time to set on the RTC

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

$lang_error_space_use_format="You typed a space! Please use the following format: hh:mm:ss";
$lang_type_time="Type the time: ";
$lang_type_date="Type the date: ";
if($_SESSION["language"]=="IT"){
	$lang_error_space_use_format="È stato digitato uno spazio! Si prega di utilizzare il seguente formato: hh:mm:ss";
	$lang_type_time="Digita l&rsquo;ora: ";
	$lang_type_date="Digita la data: ";
}else if($_SESSION["language"]=="FR"){	
	$lang_error_space_use_format="Vous avez tap&eacute; un espace! S&rsquo;il vous plaît utilisez le format suivant: hh:mm:ss";
	$lang_type_time="Tapez le temps: ";
	$lang_type_date="Tapez la date: ";
}else if($_SESSION["language"]=="SP"){	
	$lang_error_space_use_format="Que ha escrito un espacio! Utilice el siguiente formato: hh:mm:ss";
	$lang_type_time="Escriba la hora: ";
	$lang_type_date="Escriba la fecha: ";
}

//---------------------------------------------------------------------------------------//


$page_to_redirect=$_GET["page_to_redirect"]; //get the address from the url

$error=$_GET["error"];

$str_rtc_time = str_rtc_time();
$str_rtc_date = str_rtc_date();
//echo "|".$str_rtc_time ."|". $str_rtc_date."|";


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

if($error==="space"){
	echo '<p style="color:darkred">';
	//echo 'You typed a space! Please use the following format: hh:mm:ss';
	echo $lang_error_space_use_format;
	echo '</p>';
}

echo '<br>';

echo '<p>';
echo '<form name="save" action="./set_rtc_time.php" method=GET>';
echo $lang_type_time.'<input type=text name="str_rtc_time" value="'; echo $str_rtc_time; echo '" maxlength="8">(DO NOT TYPE SPACES! Format: hh:mm:ss)<br>';
echo $lang_type_date.'<input type=text name="str_rtc_date" value="'; echo $str_rtc_date; echo '" maxlength="8">(DO NOT TYPE SPACES! Format: dd&frasl;mm&frasl;yy)'; //&frasl; for /
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