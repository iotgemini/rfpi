<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		07/05/2015

Description: wait a second and will start the procedure to find a new peri

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

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';
echo '<br>';

echo 'Waiting a while before reinstall the peripheral.....';

//redirect to page of find_new.php
echo '<script type="text/javascript">';
echo 'setTimeout("'; 
	echo "location.href = '../.././find_new.php';";
echo '", 1500);'; 
echo '</script>';

echo '</div>';
echo '</body></html>';

//header('Location: ../.././find_new.php);
	
?>



