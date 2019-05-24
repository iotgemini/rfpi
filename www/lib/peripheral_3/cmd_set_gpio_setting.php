<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		07/05/2015

Description: it send the command to set the settings of the GPIO

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

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$gpio0_setting = $_GET['gpio0_setting'];
$gpio1_setting = $_GET['gpio1_setting'];

$strCmd = "DATA RF ".$address_peri." 52426609010".$gpio0_setting."0".$gpio1_setting."2E2E2E2E2E2E2E2E2E "; //the space at the end is important

writeFIFO(FIFO_GUI_CMD, $strCmd);

//header('Location: ./xxxxxxx.php?address_peri='.$address_peri.'&address_peri='.$address_peri.'&counter='.$counter.'&cont_retry='.$cont_retry.'&redirect_page='.$redirect_page);
//header('Location: ../.././index.php');
header('Location: ../.././delete_peripheral.php?position_id='.$position_id.'&redirect_page=./lib/peripheral_3/reinstall_after_gpio_setting');
	
?>



