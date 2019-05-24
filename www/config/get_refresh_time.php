<?php

/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/05/2016

Description: tools to change the refresh time

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

//-------------------------------BEGIN DEFINE----------------------------------//

define("DEFAULT_PATH", "NO"); 			//this force the library rfberrypi.php to take the following paths
define("DIRECTORY_IMG", "/img/"); 		//redefined here for the library rfberrypi.php
define("DIRECTORY_CSS", "/css/"); 		//redefined here for the library rfberrypi.php

//-------------------------------END DEFINE----------------------------------//


//---------------------------------------------------------------------------------------//
//		library with all useful functions to use RFPI
		include './../lib/rfberrypi.php';  
		
//---------------------------------------------------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_type_refresh_time="Type the refresh time in seconds: ";
if($_SESSION["language"]=="IT"){
	$lang_type_refresh_time="Digita il tempo di aggiornamento in secondi: ";
}else if($_SESSION["language"]=="FR"){	
	$lang_type_refresh_time="Type the refresh time in seconds: ";
}else if($_SESSION["language"]=="SP"){	
	$lang_type_refresh_time="Type the refresh time in seconds: ";
}

//---------------------------------------------------------------------------------------//


$current_refresh_time = '';

if(file_exists("./refresh_time.txt")){
	$myfile = fopen("./refresh_time.txt", 'r');
	$current_refresh_time = fgets($myfile);
	fclose($myfile);
}

if($current_refresh_time === '')
	$current_refresh_time = 60;

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

echo '<p>';
echo '<form name="save" action="./save_refresh_time.php" method=GET>';
echo $lang_type_refresh_time.'<input type=text name="refresh_time" value="' . $current_refresh_time . '">';
echo '</form>';
echo '</p>';

echo '<p>';
echo '<form name="home" action="../index.php" method=GET>';
echo '<input type=hidden name="counter" value="1">';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
echo '<input type=button onclick="document.save.submit();" value="'.$lang_btn_save.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';
echo '</body></html>';
?>