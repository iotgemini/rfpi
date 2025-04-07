<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		06/04/2025

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
//		Specific library for the Peri
		include './lib/peri_lib_15.php';  

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
$lang_title_function="With the signal input<br> changes output:";
$lang_title_enabled="ENABLED";
$lang_description_function="Change Relay status from the input.<br>(each impulse on the input will change the Relay status)";
$lang_relay="Relay";
if($_SESSION["language"]=="IT"){
	$lang_description_low_trigger="Se la temperatura &egrave; inferiore alla Soglia Inferiore allora il rel&egrave; &egrave; acceso.";
	$lang_description_high_trigger="Se la temperatura &egrave; superiore alla Soglia Superiore allora il rel&egrave; &egrave; spento.";
	$lang_title_low_trigger="Soglia Inferiore";
	$lang_title_high_trigger="Soglia Superiore";
	$lang_after_this_time_the_output_change="Dopo il tempo, che viene specificato qui, il rel&egrave; verr&agrave; spento";
	$lang_title_function="Con il segnale<br> cambia lo stato dell&rsquo;uscita:";
	$lang_title_enabled="ABILITATA";
	$lang_description_function="Cambia lo stato del rel&egrave; quando cambia l&rsquo;input.<br>(ogni impulso sull&rsquo;ingresso cambia lo stato del rel&egrave;)";
	$lang_relay="Rel&egrave;";
}else if($_SESSION["language"]=="FR"){	
	$lang_description_low_trigger="Si la temp&eacute;rature est inf&eacute;rieure au seuil inf&eacute;rieur, alors le relais est activ&eacute;.";
	$lang_description_high_trigger="Si la temp&eacute;rature est sup&eacute;rieure au seuil sup&eacute;rieur, alors le relais est d&eacute;sactiv&eacute;.";
	$lang_title_low_trigger="Seuil Inf&eacute;rieur";
	$lang_title_high_trigger="Seuil Sup&eacute;rieur";
	$lang_after_this_time_the_output_change="Apr&egrave;s le temps qui est sp&eacute;cifi&eacute; ici, le relais sera d&eacute;sactiv&eacute;";
	$lang_title_function="Avec un signal d&rsquo;<br> modifie le statut de sortie:";
	$lang_title_enabled="ACTIV&Eacute;E";
	$lang_relay="Rel&egrave;";
	$lang_description_function="Changer l&rsquo;&eacute;tat du relais lorsque l&rsquo;entr&eacute;e change.<br>(chaque impulsion sur l&rsquo;&eacute;tat changeant du relais)";
}else if($_SESSION["language"]=="SP"){	
	$lang_description_low_trigger="Si la temperatura es menor que el umbral inferior, el rel&eacute; est&aacute; activado.";
	$lang_description_high_trigger="Si la temperatura es mayor que el umbral superior, el rel&eacute; est&aacute; desactivado.";
	$lang_title_low_trigger="Umbral m&aacute;s bajo";
	$lang_title_high_trigger="Umbral superior";
	$lang_after_this_time_the_output_change="Despu&eacute;s del tiempo especificado aqu&Iacute;, el rel&eacute; se desconecta";
	$lang_title_function="Con la se&ntilde;al de<br> cambia el estado de la salida:";
	$lang_title_enabled="ACTIVO";
	$lang_description_function="Cambiar el estado del rel&eacute; cuando se cambia de entrada.<br>(cada pulso en el estado cambiante del rel&eacute;)";
	$lang_relay="Rel&eacute;";
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$input_enabled=$_GET['input_enabled'];
$input0_enabled = ($input_enabled & 0x0F);
$input1_enabled = ($input_enabled & 0xF0)>>4;

$GPIO0_enabled_nega_logica=$_GET['GPIO0_enabled'];
$GPIO0_enabled = ($GPIO0_enabled_nega_logica & 0x0F);
$GPIO0_nega_logica = ($GPIO0_enabled_nega_logica>>4) & 0x0F;


$GPIO0_trigger_low=$_GET['GPIO0_trigger_low'];
$GPIO0_trigger_high=$_GET['GPIO0_trigger_high'];
/*$IN1_enabled=$_GET['IN1_enabled'];
$IN1_trigger_low=$_GET['IN1_trigger_low'];
$IN1_trigger_high=$_GET['IN1_trigger_high'];
*/

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

echo '<form name="peri_15_btn_gpio_functions" action="./cmd_set_input_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral" border=1>';




//Digital  INPUT0
echo '<tr class="table_title_field_line">';
//echo '<td class="td_peripheral">With signal input changes output:</td>';  
echo '<td class="td_peripheral" colspan=2>'.$lang_title_function.'</td>';  
echo '<td class="td_peripheral">IN0<br>'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" colspan=2>';
if(intval($input0_enabled, 10)==2){
	echo '<input type="radio" name="in0_output_to_control" value="1" >&nbsp'.$lang_relay.'1';
	echo '&nbsp;&nbsp;<input type="radio" name="in0_output_to_control" value="2" checked="checked">&nbsp'.$lang_relay.'2';
	echo '<input type="radio" name="in0_output_to_control" value="3" >&nbspSV2';
}else if(intval($input0_enabled, 10)==3){
	echo '<input type="radio" name="in0_output_to_control" value="1" >&nbsp'.$lang_relay.'1';
	echo '&nbsp;&nbsp;<input type="radio" name="in0_output_to_control" value="2">&nbsp'.$lang_relay.'2';
	echo '&nbsp;&nbsp;<input type="radio" name="in0_output_to_control" value="3" checked="checked">&nbspSV2';
}else{
	echo '<input type="radio" name="in0_output_to_control" value="1" checked="checked">&nbsp'.$lang_relay.'1';
	echo '&nbsp;&nbsp;<input type="radio" name="in0_output_to_control" value="2">&nbsp'.$lang_relay.'2';
	echo '<input type="radio" name="in0_output_to_control" value="3" >&nbspSV2';
}

echo '</td>';  

echo '<td class="td_peripheral" align=center>';
if(intval($input0_enabled, 10)==0){
	echo '<input type="checkbox" name="input0_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="input0_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>';
echo '</tr>';


//Digital  INPUT1
echo '<tr class="table_title_field_line">';
//echo '<td class="td_peripheral">With signal input changes output:</td>';  
echo '<td class="td_peripheral" colspan=2>'.$lang_title_function.'</td>';  
echo '<td class="td_peripheral">IN1<br>'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" colspan=2>';
if(intval($input1_enabled, 10)==2){
	echo '<input type="radio" name="in1_output_to_control" value="1" >&nbsp'.$lang_relay.'1';
	echo '&nbsp;&nbsp;<input type="radio" name="in1_output_to_control" value="2" checked="checked">&nbsp'.$lang_relay.'2';
	echo '<input type="radio" name="in1_output_to_control" value="3" >&nbspSV2';
}else if(intval($input1_enabled, 10)==3){
	echo '<input type="radio" name="in1_output_to_control" value="1" >&nbsp'.$lang_relay.'1';
	echo '&nbsp;&nbsp;<input type="radio" name="in1_output_to_control" value="2">&nbsp'.$lang_relay.'2';
	echo '&nbsp;&nbsp;<input type="radio" name="in1_output_to_control" value="3" checked="checked">&nbspSV2';
}else{
	echo '<input type="radio" name="in1_output_to_control" value="1" checked="checked">&nbsp'.$lang_relay.'1';
	echo '&nbsp;&nbsp;<input type="radio" name="in1_output_to_control" value="2">&nbsp'.$lang_relay.'2';
	echo '<input type="radio" name="in1_output_to_control" value="3" >&nbspSV2';
}

echo '</td>';  

echo '<td class="td_peripheral" align=center>';
if(intval($input1_enabled, 10)==0){
	echo '<input type="checkbox" name="input1_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="input1_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
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


// GPIO0 (strip SV1)
echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral" colspan=1>SV1<br>'.$lang_title_low_trigger.'</td>';    
echo '<td class="td_peripheral" colspan=1>SV1<br>'.$lang_title_high_trigger.'</td>';
echo '<td class="td_peripheral">SV1<br>'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even" >';
echo '<td class="td_peripheral" align=center >';
echo '<input type="text" name="GPIO0_trigger_low" value="'; 
	//echo number_format((float)strval(voltage_0to10V_from_8bit_value_peri_15($GPIO0_trigger_low)), 0, '.', ''); 
	echo str_voltage_0to10V_from_8bit_value_peri_15($GPIO0_trigger_low);
echo '" size="4" maxlength="4">';
echo '<br>(0 - 5V)';
echo '</td>';    
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="GPIO0_trigger_high" value="'; 
	//echo number_format((float)strval(voltage_0to10V_from_8bit_value_peri_15($GPIO0_trigger_high)), 0, '.', ''); 
	echo str_voltage_0to10V_from_8bit_value_peri_15($GPIO0_trigger_high);
echo '" size="4" maxlength="4">';
echo '<br>(0 - 5V)';
echo '</td>';  

echo '<td class="td_peripheral" align=center rowspan=1>';
if(intval($GPIO0_enabled, 10)==0){
	echo '<input type="checkbox" name="GPIO0_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="GPIO0_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>';
echo '</tr>';


//********* NEGA LOGICA SOGLIE ADC ********* 
echo '<tr class="table_line_even" >';
echo '<td class="td_peripheral" colspan=1 align=center >';
if(intval($GPIO0_nega_logica, 10)==0){
	echo '<input type="checkbox" name="GPIO0_nega_logica" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="GPIO0_nega_logica" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo 'NOT';
echo '</td>';
//echo '</tr>';


//********* OUTPUT TO CONTROL CON SOGLIE ADC ********* 
//echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" colspan=2 align=center >';
	if(intval($GPIO0_enabled, 10)==2){
		echo '<input type="radio" name="GPIO0_output_to_control" value="1" >&nbsp'.$lang_relay.'1';
		echo '<input type="radio" name="GPIO0_output_to_control" value="2" checked="checked">&nbsp'.$lang_relay.'2';
		echo '&nbsp;&nbsp;<input type="radio" name="GPIO0_output_to_control" value="3">&nbspSV2';
	}else if(intval($GPIO0_enabled, 10)==3){
		echo '<input type="radio" name="GPIO0_output_to_control" value="1" >&nbsp'.$lang_relay.'1';
		echo '<input type="radio" name="GPIO0_output_to_control" value="2" >&nbsp'.$lang_relay.'2';
		echo '&nbsp;&nbsp;<input type="radio" name="GPIO0_output_to_control" value="3" checked="checked">&nbspSV2';
	}else{
		echo '<input type="radio" name="GPIO0_output_to_control" value="1" checked="checked">&nbsp'.$lang_relay.'1';
		echo '<input type="radio" name="GPIO0_output_to_control" value="2" >&nbsp'.$lang_relay.'2';
		echo '&nbsp;&nbsp;<input type="radio" name="GPIO0_output_to_control" value="3">&nbspSV2';
	}
echo '</td>';
echo '</tr>';



// IN1
/*
echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral" colspan=1>IN1<br>'.$lang_title_low_trigger.'</td>';    
echo '<td class="td_peripheral" colspan=1>IN1<br>'.$lang_title_high_trigger.'</td>';
echo '<td class="td_peripheral">IN1<br>'.$lang_title_enabled.'</td>';  
echo '</tr>';

echo '<tr class="table_line_even" >';
echo '<td class="td_peripheral" align=center >';
echo '<input type="text" name="IN1_trigger_low" value="'; 
	//echo number_format((float)strval(voltage_0to10V_from_8bit_value_peri_15($IN1_trigger_low)), 0, '.', ''); 
	echo str_voltage_0to10V_from_8bit_value_peri_15($IN1_trigger_low);
echo '" size="4" maxlength="4">';
echo '<br>(0 - 5V)';
echo '</td>';    
echo '<td class="td_peripheral" align=center>';
echo '<input type="text" name="IN1_trigger_high" value="'; 
	//echo number_format((float)strval(voltage_0to10V_from_8bit_value_peri_15($IN1_trigger_high)), 0, '.', ''); 
	echo str_voltage_0to10V_from_8bit_value_peri_15($IN1_trigger_high);
echo '" size="4" maxlength="4">';
echo '<br>(0 - 5V)';
echo '</td>';  

echo '<td class="td_peripheral" align=center rowspan=2>';
if(intval($IN1_enabled, 10)==0){
	echo '<input type="checkbox" name="IN1_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="IN1_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
}
echo '</td>';
echo '</tr>';

echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" colspan=2 align=center >';
	if(intval($IN1_enabled, 10)==2){
		echo '<input type="radio" name="IN1_output_to_control" value="1" >&nbsp'.$lang_relay;
		echo '&nbsp;&nbsp;<input type="radio" name="IN1_output_to_control" value="2" checked="checked">&nbspGPIO1';
	}else{
		echo '<input type="radio" name="IN1_output_to_control" value="1" checked="checked">&nbsp'.$lang_relay;
		echo '&nbsp;&nbsp;<input type="radio" name="IN1_output_to_control" value="2">&nbspGPIO1';
	}
echo '</td>';
echo '</tr>';*/





echo '<tr class="table_title_field_line">';
echo '<td colspan=6 align=center>';
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
