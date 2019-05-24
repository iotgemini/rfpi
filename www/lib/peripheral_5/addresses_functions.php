<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		07/12/2015

Description: this is a panel where to setup the ADDRESSES


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
//		Specific library for the Peri5
		include './lib/peri5_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$id_btn=$_GET['id_btn'];

$network_address_peri_to_control=$_GET['network_address_peri_to_control'];
$device_address_peri_to_control=$_GET['device_address_peri_to_control'];
$master_address_peri_to_control=$_GET['master_address_peri_to_control'];
$id_output_to_control=$_GET['id_output_to_control'];
$int_id_output_to_control = hexdec ( $id_output_to_control );
//$int_id_output_to_control = intval($id_output_to_control, 10);

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<br>';

echo '<H2>Addresses for the button ';
echo $id_btn;
echo '</H2><br><br>';

echo '<form name="peri5_btn_addresses_functions" action="./cmd_set_addresses_setting.php" method=GET>';
echo '<input type=hidden name="id_btn" value="'. $id_btn . '">';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_output_to_control" value="'. $id_output_to_control . '">';

echo '<table class="table_peripheral">';

echo '<tr class="table_title_field_line">';
//echo '<td class="td_peripheral"></td>';  
echo '<td class="td_peripheral">Network Address</td>';   
echo '<td class="td_peripheral">Peripheral Address</td>';  
echo '<td class="td_peripheral">Master Address</td>'; 
echo '<td class="td_peripheral">ID Output</td>'; 
echo '</tr>';

echo '<tr class="table_line_even">';
//echo '<td class="td_peripheral">ADDRESSES</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="network_address_peri_to_control" value="'; echo $network_address_peri_to_control; echo '" size="4" maxlength="4">';
//echo '<br> MAX = ';
echo '</td>';   
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="device_address_peri_to_control" value="'; echo $device_address_peri_to_control; echo '" size="4" maxlength="4">';
//echo '<br> MAX = ';
echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="master_address_peri_to_control" value="'; echo $master_address_peri_to_control; echo '" size="4" maxlength="4">';
//echo '<br> MAX = ';
echo '</td>'; 

echo '</td>';  
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="id_output_to_control" value="'; echo $int_id_output_to_control; echo '" size="3" maxlength="3">';
//echo '<br> MAX = ';
echo '</td>';  

echo '</tr>';


echo '<tr class="table_line_odd">';

echo '<td class="td_peripheral" align=center>';
echo 'Your current network address is: ';
printNetworkAddress();
//echo '<br>(Address in common with Radio Control and Peripheral)';
echo '</td>';

echo '<td class="td_peripheral" align=center>';
echo 'When button is pressed it control the Peripheral with this address';
echo '</td>';

echo '<td class="td_peripheral" align=center>';
echo 'The Master Address normally is AAFF';
echo '</td>';

echo '<td class="td_peripheral" align=center>';
echo 'First output has ID=0, MAX ID=255';
echo '</td>';

echo '</tr>';


echo '<tr class="table_title_field_line">';
echo '<td colspan=4 align=center>';
echo '<input type=submit value="APPLY" class="btn_functions">';		
echo '</td>';
echo '</tr>';	


echo '</table>';

echo '</form>';

//END: READING THE FILE WHERE ALL SIGNALS ARE STORED AND BULDING THE TABLE


//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
