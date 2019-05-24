<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		16/05/2015

Description: tools to change parameters

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

$lang_time="Your Time";
if($_SESSION["language"]=="IT"){
	$lang_time="La tua ora";
}else if($_SESSION["language"]=="FR"){	
	$lang_time="Votre temps";
}else if($_SESSION["language"]=="SP"){	
	$lang_time="Su tiempo";
}

//---------------------------------------------------------------------------------------//


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<p>';
echo '<img src="' . DIRECTORY_IMG . 'logo.png"  class="img_logo" alt="RFPI">';
echo '</p>';

echo '<p>';


echo '<p align=left>';
echo '<form name="home" action="./index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<p align=left>';
echo '<table class="settings_table">';

/*echo '<tr>';
echo '<td>Powered by:</td><td>ATIONE, Esse Di Esse Elettronica</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Programmer:</td><td>Emanuele Aimone</td>';
echo '</tr>';
*/
echo '<tr>'; 
echo '<td colspan=2>';
echo print_release_version(); //function from ./lib/rfberrypi.php
echo '</td>';
echo '</tr>';

echo '</table>';
echo '</p>';
echo '<p align=left>';
echo '<form name="home" action="./index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</body>';
echo '</html>';
?>


