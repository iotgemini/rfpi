<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		11/07/2016

Description: this show on chart the data
			 


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
		include './lib/peri9_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_description_low_trigger="If the temperature is below the LOW Trigger the relay is ON.";
$lang_description_high_trigger="If the temperature is above the HIGH Trigger the relay is OFF.";
$lang_title_time_between_samples="Time between samples.<br>This value then is multiplayed by 100mS.<br>If set at 0 then is clocked on IN1.";
$lang_title_enable_send_data="Enable to receive the data when ready.";
$lang_after_this_time_the_output_change="After the time specified here the relay will be turned OFF";
$lang_title_function="With the signal input GPIO0<br> changes output:";
$lang_title_enabled="ENABLED";
$lang_description_function="Change Relay status from the input.<br>(each impulse on the input will change the Relay status)";
$lang_relay="Relay";
if($_SESSION["language"]=="IT"){
	$lang_description_low_trigger="Se la temperatura &egrave; inferiore alla Soglia Inferiore allora il rel&egrave; &egrave; acceso.";
	$lang_description_high_trigger="Se la temperatura &egrave; superiore alla Soglia Superiore allora il rel&egrave; &egrave; spento.";
	$lang_title_time_between_samples="Tempo tra un campionamento e il sucessivo.<br>Questo valore viene moltiplicato per 100mS.<br>Se impostato a 0 il clock viene preso dall&rsquo;input IN1.";
	$lang_title_enable_send_data="Abilita per ricevere i dati quando sono pronti.";
	$lang_after_this_time_the_output_change="Dopo il tempo, che viene specificato qui, il rel&egrave; verr&agrave; spento";
	$lang_title_function="Con il segnale GPIO0,<br> cambia lo stato dell&rsquo;uscita:";
	$lang_title_enabled="ABILITATA";
	$lang_description_function="Cambia lo stato del rel&egrave; quando cambia l&rsquo;input.<br>(ogni impulso sull&rsquo;ingresso cambia lo stato del rel&egrave;)";
	$lang_relay="Rel&egrave;";
}else if($_SESSION["language"]=="FR"){	
	$lang_description_low_trigger="Si la temp&eacute;rature est inf&eacute;rieure au seuil inf&eacute;rieur, alors le relais est activ&eacute;.";
	$lang_description_high_trigger="Si la temp&eacute;rature est sup&eacute;rieure au seuil sup&eacute;rieur, alors le relais est d&eacute;sactiv&eacute;.";
	$lang_title_time_between_samples="Temps entre le pr&eacute;l&egrave;vement et la prochaine.<br>Connexion Cette valeur est multipli&eacute;e par 100mS.<br>Si la valeur 0, l&rsquo;horloge est tir&eacute;e de la IN1 d&rsquo;entr&eacute;e.";
	$lang_title_enable_send_data="Vous avez besoin de recevoir des donn&eacute;es quand ils sont pr&ecirc;ts.";
	$lang_after_this_time_the_output_change="Apr&egrave;s le temps qui est sp&eacute;cifi&eacute; ici, le relais sera d&eacute;sactiv&eacute;";
	$lang_title_function="Avec un signal d&rsquo;GPIO0<br> modifie le statut de sortie:";
	$lang_title_enabled="ACTIV&Eacute;E";
	$lang_relay="Rel&egrave;";
	$lang_description_function="Changer l&rsquo;&eacute;tat du relais lorsque l&rsquo;entr&eacute;e change.<br>(chaque impulsion sur l&rsquo;&eacute;tat changeant du relais)";
}else if($_SESSION["language"]=="SP"){	
	$lang_description_low_trigger="Si la temperatura es menor que el umbral inferior, el rel&eacute; est&aacute; activado.";
	$lang_description_high_trigger="Si la temperatura es mayor que el umbral superior, el rel&eacute; est&aacute; desactivado.";
	$lang_title_time_between_samples="El tiempo entre el muestreo y el siguiente.<br>Usuario Este valor se multiplica por 100 ms.<br>Si se establece en 0, el reloj se toma de la entrada IN1.";
	$lang_title_enable_send_data="Es necesario para recibir datos cuando est&eacute;n listos.";
	$lang_after_this_time_the_output_change="Despu&eacute;s del tiempo especificado aqu&Iacute;, el rel&eacute; se desconecta";
	$lang_title_function="Con la se&ntilde;al de GPIO0<br> cambia el estado de la salida:";
	$lang_title_enabled="ACTIVO";
	$lang_description_function="Cambiar el estado del rel&eacute; cuando se cambia de entrada.<br>(cada pulso en el estado cambiante del rel&eacute;)";
	$lang_relay="Rel&eacute;";
}

//---------------------------------------------------------------------------------------//



$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];




	$file_path = "./config/". $address_peri ."_what_to_show.txt";
	if(file_exists($file_path)!==TRUE){ //echo "ciao";
		$array_input_to_show = array(1,1,1,1,1);
		$array_output_to_show = array(1,1,1,1,1);
		$array_function_to_show = array(1,1,1,1,1);
		$array_input_formula_to_show = array(0,0,0,0,0);
	}else{ 
		$array_input_to_show = array(0,0,0,0,0);
		$array_output_to_show = array(0,0,0,0,0);
		$array_function_to_show = array(0,0,0,0,0);
		$array_input_formula_to_show = array(0,0,0,0,0);
		$cont_input_to_show = 0;
		$cont_output_to_show = 0;
		$cont_function_to_show = 0;
		$cont_function_formula_to_show = 0;
		$cont_lines = 0;
		//$handle_file = fopen("./lib/peripheral_3/config/". $address_peri ."_what_to_show.txt", 'r');
		//$handle_file = fopen("./lib/peripheral_3/config/what_to_show.txt", 'r');
		$handle_file = fopen($file_path, 'r');
		while(feof($handle_file)!==TRUE && $cont_lines<30){ 
			$line_file=fgets($handle_file, 20);
			//echo $cont_lines . " ";
			$cont_lines++;
			
			//getting input to show
			if (strpos($line_file,'INPUT_' . (string)($cont_input_to_show)) !== false) {
				if (strpos($line_file,"YES") !== false) {
					$array_input_to_show[$cont_input_to_show] = 1;
				}
				$cont_input_to_show++;
			}else
			
			//getting output to show
			if (strpos($line_file,'OUTPUT_' . (string)$cont_output_to_show ) !== false) {
				if (strpos($line_file,"YES") !== false) {
					$array_output_to_show[$cont_output_to_show] = 1;
				}
				$cont_output_to_show++;
			}else
			
			//getting function to show
			if (strpos($line_file,'FUNCTION_' . (string)$cont_function_to_show ) !== false) {
				if (strpos($line_file,"YES") !== false) {	
					$array_function_to_show[$cont_function_to_show] = 1;
				}
				$cont_function_to_show++;
			}else
			
			//getting function formula to show
			if (strpos($line_file,'INPUT_FORMULA_' . (string)$cont_function_formula_to_show ) !== false) {
				/*if (strpos($line_file,"YES") !== false) {	
					$array_input_formula_to_show[$cont_function_formula_to_show] = 1;
				}*/
				$position_equal = strpos($line_file,'=') + 1;
				
				$str_sub_temp = substr($line_file, $position_equal, strlen($line_file));
				
				$array_input_formula_to_show[$cont_function_formula_to_show] = (int)str_replace(' ', '', $str_sub_temp);
				
				$cont_function_formula_to_show++;
			}

		}
		
	}
	fclose($handle_file);
	
	
	
	
	
	$file_data_path2 = "./data/" . $address_peri . "_data.txt"; 
	
	


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

//print_the_file_data($address_peri,$array_input_formula_to_show[0], $file_data_path2);
//print_the_file_data($address_peri, 1,$file_data_path2);
//print_only_the_last_4data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path2);
print_5columns_of_data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path2);


/*
echo '<form name="peri9_btn_parameters_functions" action="./cmd_set_data.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';

echo '<table class="table_peripheral" border=1>';


// Time between samples
echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral" colspan=1>'.$lang_title_time_between_samples.'</td>';    
echo '</td>'; 
echo '</tr>';

echo '<tr class="table_line_even" >';
echo '<td class="td_peripheral" align=center >';
echo '<input type="text" name="time_between_samples" value="'; 
	//echo number_format((float)strval(voltage_0to10V_from_8bit_value_peri9($time_between_samples)), 0, '.', ''); 
	//echo str_voltage_0to10V_from_8bit_value_peri9($time_between_samples);
	echo (string)$time_between_samples;
echo '" size="3" maxlength="3">';
echo '<br>(0 - 255)';
echo '</td>'; 
echo '</tr>';



//Send data when ready
echo '<tr class="table_title_field_line">'; 
echo '<td class="td_peripheral">'.$lang_title_enable_send_data.'</td>';  
echo '</td>'; 
echo '</tr>';

echo '<tr class="table_line_even" >';
echo '<td class="td_peripheral" align=center >';
if(intval($sem_status_send_array_temperature_pyrometer, 10)==0){
	echo '<input type="checkbox" name="sem_status_send_array_temperature_pyrometer" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
}else{
	echo '<input type="checkbox" name="sem_status_send_array_temperature_pyrometer" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
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

echo '<br>';*/

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
