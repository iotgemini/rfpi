<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		20/11/2015

Description: it is the library with all useful function to use RFPI


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


//-------------------------------BEGIN INCLUDE----------------------------------//

	

//-------------------------------END INCLUDE----------------------------------//


//-------------------------------BEGIN FUNCTIONS DESCRIPTIONS----------------------------------//



												
//-------------------------------END FUNCTIONS DESCRIPTIONS----------------------------------//

//-------------------------------BEGIN DEFINE----------------------------------//

define("DEFAULT_PATH", "NO"); 			//this force the library rfberrypi.php to take the following paths
define("DIRECTORY_IMG", "/img/"); 		//redefined here for the library rfberrypi.php
define("DIRECTORY_CSS", "/css/"); 		//redefined here for the library rfberrypi.php

//-------------------------------END DEFINE----------------------------------//

//-------------------------------BEGIN INCLUDE CSS----------------------------------//

//include the CSS
echo '<link rel="stylesheet" href="./css/peripheral.css" type="text/css" >';
//echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'settings.css" type="text/css" >';

//-------------------------------END INCLUDE CSS----------------------------------//




?>
