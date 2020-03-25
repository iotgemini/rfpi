<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/03/2020

Description: it write the FIFO and redirect on read_fifo_data.php


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
		include './../../lib/rfberrypi.php';  
//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];


$redirect_page = $_GET['redirect_page'];


$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$TAG0=$_GET['TAG0'];
$TAG1=$_GET['TAG1'];
$TAG2=$_GET['TAG2'];
$TAG3=$_GET['TAG3'];

$page_to_show_data=$_GET['page_to_show_data'];

$cmd_to_write_into_fifo = $TAG0." ".$TAG1." ".$TAG2." ".$TAG3." "; //the space at the end is important

//echo $cmd_to_write_into_fifo . " <br>";
writeFIFO(FIFO_GUI_CMD, $cmd_to_write_into_fifo);


// sleep for 1 second
//sleep(1);
usleep( 100 * 1000 );//delay to leave the time for the execution of the command



//COMMAND TO REFRESH THE IO
$TAG0="REFRESH";
$TAG1="PERI";
$TAG2="STATUS";
$TAG3=$address_peri;

$cmd_to_write_into_fifo = $TAG0." ".$TAG1." ".$TAG2." ".$TAG3." "; //the space at the end is important

//echo $cmd_to_write_into_fifo . " <br>";
writeFIFO(FIFO_GUI_CMD, $cmd_to_write_into_fifo);

usleep( 50 * 1000 );//delay to leave the time for the execution of the command

//@unlink(FIFO_RFPI_RUN); 

//0x52 = R
//0x42 = B 
//0x75 = u  
//$strCmd = "DATA RF ".$address_peri." 524275" . $id_hex_special_function . "2E2E2E2E2E2E2E2E2E2E2E2E "; //the space at the end is important
//writeFIFO(FIFO_GUI_CMD, $strCmd);

header('Location: ./login.php');
//header( 'Location: ./../../home.php') ;

/*

header('Location: ./read_fifo_data.php?page_to_show_data='. $page_to_show_data 
						. '&TAG0=' . $TAG0 
						. '&TAG1=' . $TAG1 
						. '&TAG2=' . $TAG2 
						. '&TAG3=' . $TAG3 
						. '&address_peri=' . $address_peri
						. '&position_id=' . $position_id
						. '&id_hex_special_function=' . $id_hex_special_function
						. '&counter=' . $counter
						. '&cont_retry=' . $cont_retry
						. '&redirect_page=' . $redirect_page

	);*/

?>