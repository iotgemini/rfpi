<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		13/01/2017

Description: it send the command to get the settings of the thermostat

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
//		Specific library for the peri10
		include './lib/peri10_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$num_special_function = 4;
$num_bytes_to_get = 170;

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = 0; // $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$strCmd = "GET_BYTES_U ".$position_id." ".$num_special_function." ".$num_bytes_to_get." "; //the space at the end is important

writeFIFO(FIFO_GUI_CMD, $strCmd);
$data=readFIFO(FIFO_RFPI_STATUS);

header('Location: ./read_fifo_thermostat_setting.php?address_peri='.$address_peri.'&position_id='.$position_id.'&counter='.$counter.'&cont_retry='.$cont_retry.'&redirect_page='.$redirect_page.'&num_bytes_to_get='.$num_bytes_to_get.'&num_special_function='.$num_special_function);
	/*
//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';*/
?>



