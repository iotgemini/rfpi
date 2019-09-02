<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/09/2019

Description: it send the command to get the settings of the TIMER

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
//		Specific library for the Peri7
		include './lib/peri7_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$fw_version_peri = $_GET['fw_version_peri'];

$strCmd = "DATA RF ".$address_peri." 524275022E2E2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important

writeFIFO(FIFO_GUI_CMD, $strCmd);

header('Location: ./read_fifo_trigger_setting.php?address_peri='.$address_peri.'&position_id='.$position_id.'&counter='.$counter.'&cont_retry='.$cont_retry.'&fw_version_peri='.$fw_version_peri.'&redirect_page='.$redirect_page);
	
?>



