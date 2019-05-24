<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/12/2017

Description: it stop the getting data procedure

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

//		library with all useful functions to use RFPI
		include './../../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = 0; // $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$strCmd = "STOP CMD "; //the space at the end is important

writeFIFO(FIFO_GUI_CMD, $strCmd);
$data=readFIFO(FIFO_RFPI_STATUS);

header('Location: /index.php');
	/*
//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';*/
?>



