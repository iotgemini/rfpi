<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		28/11/2015

Description: it send the command to set the settings of the THERMOSTAT

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
//		Specific library for the Peri
		include './lib/peri_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$redirect_page = $_GET['redirect_page'];

$counter = $_GET['counter'];

$cont_retry = $_GET['cont_retry'];

$thermostat_enabled=$_GET['thermostat_enabled'];

if($thermostat_enabled==="1"){
	$thermostat_enabled = "01";
}else{
	$thermostat_enabled = "00";
}

$id_packet=$_GET['id_packet'];
if($id_packet==="0") $id_packet = "00";
else if($id_packet==="1") $id_packet = "01";
else $id_packet = "02";

$j=0;
$hex_data_to_send="";
while ($j<10) {
	$temperatures[$j]=$_GET['temperatures_'.$j];
	if(intval($temperatures[$j],10)>90 || intval($temperatures[$j],10)<-25){
		$temperatures[$j] = "16";
	}
	$byte_temperatures[$j] = dechex( intval($temperatures[$j],10) );
	if(strlen($byte_temperatures[$j])<2) $byte_temperatures[$j] = "0" . $byte_temperatures[$j];
	//echo $byte_temperatures[$j] ; echo " ";
	$hex_data_to_send = $hex_data_to_send . $byte_temperatures[$j];
	$j++;
}

//dechex ( int $number )


$strCmd = "DATA RF ".$address_peri." 52426604". $thermostat_enabled . $id_packet . $hex_data_to_send . " "; //the space at the end is important

$strCmd = strtoupper ($strCmd);//make the string upper case
//echo $strCmd ;

writeFIFO(FIFO_GUI_CMD, $strCmd);
	
echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';
echo '<br>';

echo 'Setting sent!';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';



//redirect to page 
echo '<script type="text/javascript">';
echo 'setTimeout("'; 
	echo "location.href = '../.././index.php';";
echo '", 1500);'; 
echo '</script>';

echo '</div>';
echo '</body></html>';


?>



