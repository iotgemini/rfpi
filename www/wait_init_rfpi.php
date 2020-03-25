<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/03/2020

Description:	it return the writing to the client and then it go back to the
				index.php and check if the rfpi routine has been initialised

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

$lang_wait_rfpi="Waiting for the RFPI service routine...";
if($_SESSION["language"]=="IT"){
	$lang_wait_rfpi="In attesa del programma di servizio RFPI...";
}else if($_SESSION["language"]=="FR"){	
	$lang_wait_rfpi="En attente de la routine de service RFPI...";
}else if($_SESSION["language"]=="SP"){	
	$lang_wait_rfpi="A la espera de la rutina de servicio RFPI...";
}

//---------------------------------------------------------------------------------------//


$counter=$_GET['counter'];

if($counter==='')
	$counter=0;
	
$counter++;

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<p>';
echo '<img src="' . DIRECTORY_IMG . 'logo.png"  class="img_logo" alt="RFPI">';
echo '</p>';

echo '<p>';
//Waiting for the RFPI service routine...
echo $lang_wait_rfpi;
echo '</p>';

/*$dir    = '/etc/rfpi/fifo/';
$files1 = scandir($dir);
$files2 = scandir($dir, 1);

print_r($files1);
print_r($files2);
*/

echo '<script type="text/javascript">';
echo ' setTimeout("';
echo "location.href = 'index.php?counter=" . $counter . "';";
echo '", 500); ';
echo '</script>';

echo '</div>';
echo '</body></html>';

?>