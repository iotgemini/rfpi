<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/12/2017

Description: this is a panel where to setup the INPUT


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
//		Specific library for the peri_11
		include './lib/peri_11_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_description_low_trigger="If the temperature is below the LOW Trigger the relay is ON.";
$lang_description_high_trigger="If the temperature is above the HIGH Trigger the relay is OFF.";
$lang_title_low_trigger="LOW Trigger";
$lang_title_high_trigger="HIGH Trigger";
$lang_after_this_time_the_output_change="After the time specified here the relay will be turned OFF";
$lang_title_function="With this input<br> changes output:";
$lang_title_enabled="ENABLED";
$lang_description_function="Change Relay status from the input.<br>(each impulse on the input will change the Relay status)";
$lang_relay="Relay";
if($_SESSION["language"]=="IT"){
	$lang_description_low_trigger="Se la temperatura &egrave; inferiore alla Soglia Inferiore allora il rel&egrave; &egrave; acceso.";
	$lang_description_high_trigger="Se la temperatura &egrave; superiore alla Soglia Superiore allora il rel&egrave; &egrave; spento.";
	$lang_title_low_trigger="Soglia Inferiore";
	$lang_title_high_trigger="Soglia Superiore";
	$lang_after_this_time_the_output_change="Dopo il tempo, che viene specificato qui, il rel&egrave; verr&agrave; spento";
	$lang_title_function="Con questo input<br> cambia lo stato dell&rsquo;uscita:";
	$lang_title_enabled="ABILITATA";
	$lang_description_function="Cambia lo stato del rel&egrave; quando cambia l&rsquo;input.<br>(ogni impulso sull&rsquo;ingresso cambia lo stato del rel&egrave;)";
	$lang_relay="Rel&egrave;";
}else if($_SESSION["language"]=="FR"){	
	$lang_description_low_trigger="Si la temp&eacute;rature est inf&eacute;rieure au seuil inf&eacute;rieur, alors le relais est activ&eacute;.";
	$lang_description_high_trigger="Si la temp&eacute;rature est sup&eacute;rieure au seuil sup&eacute;rieur, alors le relais est d&eacute;sactiv&eacute;.";
	$lang_title_low_trigger="Seuil Inf&eacute;rieur";
	$lang_title_high_trigger="Seuil Sup&eacute;rieur";
	$lang_after_this_time_the_output_change="Apr&egrave;s le temps qui est sp&eacute;cifi&eacute; ici, le relais sera d&eacute;sactiv&eacute;";
	$lang_title_function="Avec un input<br> modifie le statut de sortie:";
	$lang_title_enabled="ACTIV&Eacute;E";
	$lang_relay="Rel&egrave;";
	$lang_description_function="Changer l&rsquo;&eacute;tat du relais lorsque l&rsquo;entr&eacute;e change.<br>(chaque impulsion sur l&rsquo;&eacute;tat changeant du relais)";
}else if($_SESSION["language"]=="SP"){	
	$lang_description_low_trigger="Si la temperatura es menor que el umbral inferior, el rel&eacute; est&aacute; activado.";
	$lang_description_high_trigger="Si la temperatura es mayor que el umbral superior, el rel&eacute; est&aacute; desactivado.";
	$lang_title_low_trigger="Umbral m&aacute;s bajo";
	$lang_title_high_trigger="Umbral superior";
	$lang_after_this_time_the_output_change="Despu&eacute;s del tiempo especificado aqu&Iacute;, el rel&eacute; se desconecta";
	$lang_title_function="Con la input<br> cambia el estado de la salida:";
	$lang_title_enabled="ACTIVO";
	$lang_description_function="Cambiar el estado del rel&eacute; cuando se cambia de entrada.<br>(cada pulso en el estado cambiante del rel&eacute;)";
	$lang_relay="Rel&eacute;";
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];
$status_rfpi=$_GET['status_rfpi'];
$data_rfpi=$_GET['data_rfpi'];



$index = 8;
$byte_input_enabled = intval( $data_rfpi[0+$index] . $data_rfpi[1+$index] , 16); //byte 0
$input_enabled1 = intval( $data_rfpi[2+$index] . $data_rfpi[3+$index] , 16); //byte 1
$input_enabled2 = intval( $data_rfpi[4+$index] . $data_rfpi[5+$index] , 16); //byte 2
$input_enabled3 = intval( $data_rfpi[6+$index] . $data_rfpi[7+$index] , 16); //byte 3

$ADC0_enabled = intval( $data_rfpi[8+$index] . $data_rfpi[9+$index] , 16); //byte 4
$ADC0_trigger_low = intval( $data_rfpi[10+$index] . $data_rfpi[11+$index] , 16); //byte 5
$ADC0_trigger_high = intval( $data_rfpi[12+$index] . $data_rfpi[13+$index] , 16); //byte 6

$ADC1_enabled = intval( $data_rfpi[14+$index] . $data_rfpi[15+$index] , 16); //byte 7
$ADC1_trigger_low = intval( $data_rfpi[16+$index] . $data_rfpi[17+$index] , 16); //byte 8
$ADC1_trigger_high = intval( $data_rfpi[18+$index] . $data_rfpi[19+$index] , 16); //byte 9


 

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

//button Settings
/*echo '<p align=center>';
echo '<a href="./infrared_functions_settings.php?address_peri='.$address_peri.'" class="btn_cmd">Settings</a>';
echo '</p>';
*/

echo '<form name="peri_11_btn_gpio_functions" action="./cmd_set_setting_input.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_hex_special_function" value="'. $id_hex_special_function . '">';
echo '<input type=hidden name="redirect_page" value="'. $redirect_page . '">';

echo '<table class="table_peripheral" border=1>';




//INPUT
echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral" colspan=8>INPUT '.$lang_title_enabled.'</td>';  //With this input<br> changes output:
echo '</tr>';
//line1:
echo '<tr class="table_line_even">';
for($y=1,$c=0;$c<8;$y=$y*2,$c++){
	echo '<td class="td_peripheral">';

	if(($byte_input_enabled & $y)==0){
		echo '<input type="checkbox" name="input_enabled'.$c.'" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
	}else{
		echo '<input type="checkbox" name="input_enabled'.$c.'" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
	}
	echo "<br>";
	echo "I".$c;
	echo "<br>";
	echo "&darr;";
	echo "<br>";
	echo "Q".$c;;

	echo '</td>';
}
echo '</tr>';



echo '<tr class="table_title_field_line">';
echo '<td colspan=8 align=center>';
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';

//END: READING THE FILE WHERE ALL SIGNALS ARE STORED AND BULDING THE TABLE

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
