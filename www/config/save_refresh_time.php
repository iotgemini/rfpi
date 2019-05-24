<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		16/05/2016

Description: it save the peripheral name given by the page get_peri_name.php

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
		include './../lib/rfberrypi.php';  
		
//---------------------------------------------------------------------------------------//


$refresh_time=$_GET["refresh_time"]; //get from the url

//it will write into the config.txt
$myfile = fopen("./refresh_time.txt", "w"); 
$txt = $refresh_time;
fwrite($myfile, $txt);
fclose($myfile);
//echo 'done!';
header( 'Location: ./../index.php' ) ;
?>