<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		10/04/2019

Description: it check if the rfpi is running

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

$redirect_page = $_GET['redirect_page'];

//$position_id=$_GET['position_id'];
//writeFIFO(FIFO_GUI_CMD, "DELETE PERI " . $position_id . " NULL ");

$address=$_GET['address'];
writeFIFO(FIFO_GUI_CMD, "DELETE ADDRESS " . $address . " NULL ");
	
if($redirect_page)
	header( 'Location: '.$redirect_page.'.php' ) ;
else
	header( 'Location: index.php' ) ;

?>