<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		16/05/2016

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

$counter=$_GET['counter'];

if($counter==='')
	$counter=0;

if(!file_exists(FIFO_RFPI_RUN)){ 
		//if the RFPI routine has not started yet it goes to wait for a while and then get back to the index.php to retry
		header( 'Location: wait_init_rfpi.php?counter=' . $counter ) ;
}else{
	
	//open the fifo to check the message into
	$data=readFIFO(FIFO_RFPI_RUN);
	
	//if the message into the fifo is 'TRUE' it redirect to the home.php to show the  list of peripheral else it redirect to display the error message
	if($data==="TRUE"){
		header('Location: home.php') ;
	}else{
		//it notify to the running application rfpi.c the error has been got
		writeFIFO(FIFO_GUI_CMD, STATUS_ERROR_GOT);
		header('Location: error_msg_rfpi.php?data='.$data.'&counter=' . $counter );
	}
	
}

?>



