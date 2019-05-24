<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		24/04/2015

Description: it delete all signal database for the current peripheral

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

//---------------------------------BEGIN INCLUDE-------------------------------------------//
//		Specific library for the Peri3: Sensore-Attuatore
		include './lib/peri3_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$signal_name = $_GET['signal_name'];

$address_peri = $_GET['address_peri'];

$line_position = $_GET['line_position'];

if(file_exists("./db/".$address_peri."_db_infrared_signals.txt")){
	if(file_exists("./db/".$address_peri."_db_infrared_signals.backup")){ //delete the old backup
		unlink("./db/".$address_peri."_db_infrared_signals.backup");
	}
	rename("./db/".$address_peri."_db_infrared_signals.txt", "./db/".$address_peri."_db_infrared_signals.backup");
}

header('Location: ./infrared_functions_settings.php?address_peri='.$address_peri);

?>



