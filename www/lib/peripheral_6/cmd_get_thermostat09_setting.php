<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		27/11/2015

Description: it send the command to get the settings of the THERMOSTAT

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
//		Specific library for the Peri
		include './lib/peri_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$id_packet=$_GET['id_packet'];

$str_id_packet = "02";
if($id_packet==0) $str_id_packet = "00";
if($id_packet==1) $str_id_packet = "01";

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$strCmd = "DATA RF ".$address_peri." 5242750400".$str_id_packet."2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important

writeFIFO(FIFO_GUI_CMD, $strCmd);

header('Location: ./read_fifo_thermostat09_setting.php?address_peri='.$address_peri.'&position_id='.$position_id.'&id_packet='.$id_packet.'&counter='.$counter.'&cont_retry='.$cont_retry.'&redirect_page='.$redirect_page);
	
?>



