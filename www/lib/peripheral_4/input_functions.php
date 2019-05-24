<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		19/05/2016

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
//		Specific library for the Peri4
		include './lib/peri4_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_after_this_time_the_output_change="After the time specified here the relay will be turned OFF";
$lang_title_function="FUNCTION";
$lang_title_enabled="ENABLED";
$lang_description_function="Change Relay status from the input.<br>(each impulse on the input will change the Relay status)";
if($_SESSION["language"]=="IT"){
	$lang_after_this_time_the_output_change="Dopo il tempo, che viene specificato qui, il rel&egrave; verr&agrave; spento";
	$lang_title_function="FUNZIONE";
	$lang_title_enabled="ABILITATA";
	$lang_description_function="Cambia lo stato del rel&egrave; quando cambia l&rsquo;input.<br>(ogni impulso sull&rsquo;ingresso cambia lo stato del rel&egrave;)";
}else if($_SESSION["language"]=="FR"){	
	$lang_after_this_time_the_output_change="Apr&egrave;s le temps qui est sp&eacute;cifi&eacute; ici, le relais sera d&eacute;sactiv&eacute;";
	$lang_title_function="FONCTION";
	$lang_title_enabled="ACTIV&Eacute;E";
	$lang_description_function="Changer l&rsquo;&eacute;tat du relais lorsque l&rsquo;entr&eacute;e change.<br>(chaque impulsion sur l&rsquo;&eacute;tat changeant du relais)";
}else if($_SESSION["language"]=="SP"){	
	$lang_after_this_time_the_output_change="Despu&eacute;s del tiempo especificado aqu&Iacute;, el rel&eacute; se desconecta";
	$lang_title_function="FUNCI&Oacute;N";
	$lang_title_enabled="ACTIVO";
	$lang_description_function="Cambiar el estado del rel&eacute; cuando se cambia de entrada.<br>(cada pulso en el estado cambiante del rel&eacute;)";
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$input_enabled=$_GET['input_enabled'];


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


//echo 'After the time specified here the relay will be turned OFF';
echo $lang_after_this_time_the_output_change;
echo '<br><br>';

echo '<form name="peri3_btn_gpio_functions" action="./cmd_set_input_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral" border=1>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral">'.$lang_title_function.'</td>';  
echo '<td class="td_peripheral">'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral">'.$lang_description_function.'</td>'; //Change Relay status from the input.<br>(each impulse on the input will change the Relay status) 

echo '<td class="td_peripheral" align=center>';
if(intval($input_enabled, 10)==0){
	echo '<input type="checkbox" name="input_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="input_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>';
echo '</tr>';

/*echo '<tr class="table_line_odd">';
echo '<td class="td_peripheral">GPIO1</td>';  
echo '<td class="td_peripheral" align=left>';
echo '<input type="radio" name="gpio1_setting" value="0"/ '; if($gpio1_setting==0) echo 'checked'; echo '>Digital Output<br>';
echo '<input type="radio" name="gpio1_setting" value="1"/ '; if($gpio1_setting==1) echo 'checked'; echo '>Digital Input<br>';
echo '<input type="radio" name="gpio1_setting" value="2"/ '; if($gpio1_setting==2) echo 'checked'; echo '>Analogue Input';
echo '</td>';   
echo '</tr>';
*/

echo '<tr class="table_title_field_line">';
echo '<td colspan=6 align=center>';
echo '<input type=submit value="'.$lang_btn_apply.'" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';

//END: READING THE FILE WHERE ALL SIGNALS ARE STORED AND BULDING THE TABLE


//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
