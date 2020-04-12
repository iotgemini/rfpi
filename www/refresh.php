<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		12/04/2020

Description: it unlink the FIFO and redirect on index.php

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


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_refresh="In updating....";
if($_SESSION["language"]=="IT"){
	$lang_refresh="In aggiornamento....";
}else if($_SESSION["language"]=="FR"){	
	$lang_refresh="Dans la mise &agrave; jour....";
}else if($_SESSION["language"]=="SP"){	
	$lang_refresh="En la actualizaci&oacute;n....";
}

//---------------------------------------------------------------------------------------//



//ask to the RFPI.C to update all inputs and outputs status
writeFIFO(FIFO_GUI_CMD, "REFRESH PERI STATUS ALL ");

//@unlink(FIFO_RFPI_RUN); 

echo '<br>' . $lang_refresh; 
ob_flush(); //it will send to the client the html before to goes forward with the next instruction
flush();
	
echo '<script type="text/JavaScript">';
//wait a second thus if the rfpi.c is running will recreate the fifo
?> setTimeout("location.href = 'index.php';", 300); <?php
echo '</script>';

?>