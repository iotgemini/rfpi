<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/08/2016

Description: this is the page where modify the settings
			 


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
		include './lib/peri8_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_title_enable_send_data="Auto refresh position:";
$lang_title_enabled="ENABLED";
if($_SESSION["language"]=="IT"){
	$lang_title_enable_send_data="Aggiorna posizione in automatico:";
	$lang_title_enabled="ABILITATA";
}else if($_SESSION["language"]=="FR"){	
	$lang_title_enable_send_data="Actualiser emplacement automatiquement:";
	$lang_title_enabled="ACTIV&Eacute;E";
}else if($_SESSION["language"]=="SP"){	
	$lang_title_enable_send_data="Refrescar ubicaci&oacute;n de forma autom&aacute;tica:";
	$lang_title_enabled="ACTIVO";
}

//---------------------------------------------------------------------------------------//



$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];


$invia_stato_dopo_cmd_domotica=$_GET['invia_stato_dopo_cmd_domotica'];
$data2=$_GET['data2'];


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<br>';

echo '<form name="peri8_btn_parameters_functions" action="./cmd_set_settings.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral" border=1>';



//Send back the position
echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral">'.$lang_title_enable_send_data.'</td>';  
echo '</td>'; 
echo '</tr>';

echo '<tr class="table_line_even" >';
echo '<td class="td_peripheral" align=center >';
if(intval($invia_stato_dopo_cmd_domotica, 10)==0){
	echo '<input type="checkbox" name="invia_stato_dopo_cmd_domotica" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="invia_stato_dopo_cmd_domotica" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>'; 
echo '</tr>';


echo '<tr class="table_title_field_line">';
echo '<td colspan=6 align=center>';
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';

echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
