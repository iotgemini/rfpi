<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/05/2016

Description: it save the peripheral name given by the page get_peri_name.php

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

$page_to_redirect=$_GET["page_to_redirect"];

$peri_name=$_GET["name"]; //get the peripheral name from the url
//manipulating the string:
//substituting the spaces with underscore
//checking the length is lower than the maximum of 128 characters
$peri_name = validate_network_name($peri_name);

$peri_address=$_GET["address"]; //get the address from the url

if(preg_match('/\s/',$peri_name)){
	header( 'Location: get_peri_name.php?error=space&page_to_redirect='.$page_to_redirect ) ;
}else{
	//it will write into the fifoguicmd the tag=name, type=peri, value1=name and value2=address
	writeFIFO(FIFO_GUI_CMD, "NAME PERI " . $peri_name . " " . $peri_address . " ");

	if($page_to_redirect === '')
		header( 'Location: index.php' ) ;
	else	
		header( 'Location: '.$page_to_redirect.'.php' ) ;
}

?>