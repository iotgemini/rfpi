<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		07/04/2025

Description: this is a panel where to enable/disable the settings of the platform


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

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//		Specific library for the json file
		include './lib/json_lib.php';  
		
//		Specific library with formulas
		include './peri_15_lib.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_title_keep_off_led="Keep OFF status Led:";
$lang_title_ADC_compensation="ADC Compensation:";
if($_SESSION["language"]=="IT"){
	$lang_title_keep_off_led="Mantieni spento il Led di stato:";
	$lang_title_ADC_compensation="ADC Compensazione:";
}else if($_SESSION["language"]=="FR"){
	$lang_title_keep_off_led="Gardez le voyant dl&apos;&eacute;tat &eacute;teint:";
	$lang_title_ADC_compensation="ADC Compenstation:";
}else if($_SESSION["language"]=="SP"){
	$lang_title_keep_off_led="Mantenga el LED de estado apagado:";
	$lang_title_ADC_compensation="ADC Compensaci&oacute;n:";
}

//---------------------------------------------------------------------------------------//




$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];
$status_rfpi=$_GET['status_rfpi'];
$data_rfpi=$_GET['data_rfpi'];



$index = 8;
$byte0_settings = intval( $data_rfpi[0+$index] . $data_rfpi[1+$index] , 16); //byte 0

/*$ADC1_Average = intval( $data_rfpi[18+$index] . $data_rfpi[19+$index] , 16); //byte 9
$ADC1_Average = $ADC1_Average << 8;
$ADC1_Average += intval( $data_rfpi[20+$index] . $data_rfpi[21+$index] , 16); //byte 10 MSB
*/

$sem_Led_TX_keep_OFF = 0;
//$ADC_compensation = 0;

if( (intval($byte0_settings, 10) & 0x01)==0)	$sem_Led_TX_keep_OFF = 1;
//if( (intval($byte0_settings, 10) & 0x02)==0)	$ADC_compensation = 1;
	

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

echo '<form name="set_functions" action="./cmd_set_settings_settings.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_hex_special_function" value="'. $id_hex_special_function . '">';
echo '<input type=hidden name="redirect_page" value="'. $redirect_page . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';  
echo '<td class="td_peripheral">'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">'.$lang_title_keep_off_led.'</td>';  
echo '<td class="td_peripheral" align=center>';
if(intval($sem_Led_TX_keep_OFF, 10)==1){
	echo '<input type="checkbox" name="sem_Led_TX_keep_OFF" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}else{
	echo '<input type="checkbox" name="sem_Led_TX_keep_OFF" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}
echo '</td>';
echo '</tr>';
/*
echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">'.$lang_title_ADC_compensation.'</td>';  
echo '<td class="td_peripheral" align=center>';
if(intval($ADC_compensation, 10)==1){
	echo '<input type="checkbox" name="ADC_compensation" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}else{
	echo '<input type="checkbox" name="ADC_compensation" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}
echo '</td>';
echo '</tr>';
*/
/*echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">MCU Volt:</td>';  
echo '<td class="td_peripheral" align=center>';
echo str_mcu_volt_peri_15($ADC1_Average); 
echo 'V';
echo '</td>';
echo '</tr>';*/

echo '<tr class="table_title_field_line">';
echo '<td colspan=2 align=center>';
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
