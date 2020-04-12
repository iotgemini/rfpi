<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		12/04/2016

Description: it save the network name given by the page get_network_name.php

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

$network_name=$_GET["name"]; //get the network name from the url

//manipulating the string:
//substituting the spaces with underscore
//checking the length is lower than the maximum of 128 characters
$network_name = validate_network_name($network_name);


if(preg_match('/\s/',$network_name)){
	header( 'Location: get_network_name.php?error=space&page_to_redirect='.$page_to_redirect ) ;
}else{

	//it will write into the fiforfpidata the tag=name, type=net, and value=name
	//example FIFO: name net mynetworkname
	writeFIFO(FIFO_GUI_CMD, "NAME NET " . $network_name . " ");
	
	sleep(1);
	
	if($page_to_redirect === '')
		header( 'Location: index.php' ) ;
	else	
		header( 'Location: '.$page_to_redirect.'.php' ) ;
}
?>