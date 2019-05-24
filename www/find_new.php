<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/05/2016

Description: it will send to rfpi.c the tag to start to search a new module, then it will 
wait until the status will be back to OK

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


$action="FIND_NEW";
 
writeFIFO(FIFO_GUI_CMD, FIND_NEW_PERIPHERAL);

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

htmlMsgWaitanswerFromRFPI(); //it just shows a message: Waiting answer from RFPI......

//redirect to page of status
echo '<script type="text/javascript">';
echo 'setTimeout("'; 
	echo "location.href = './get_status.php?action=" . $action . "';";
echo '", 500);'; 
echo '</script>';

echo '</div>';
echo '</body></html>';

?>