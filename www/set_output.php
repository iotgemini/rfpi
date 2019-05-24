<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/05/2016

Description: it will create the FIFO with the output to set
example FIFO: peri=0 out=0 1

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


$peripheral_id=$_GET['peripheral_id'];
$output_id=$_GET['output_id'];
$output_value=$_GET['output_value'];

writeFIFO(FIFO_GUI_CMD, "PERIOUT " . $peripheral_id . " ".$output_id." ".$output_value." ");
	
header( 'Location: get_status.php?action=PERI_OUT' ) ;

?>