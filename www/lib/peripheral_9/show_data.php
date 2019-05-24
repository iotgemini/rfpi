<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		19/06/2017

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

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php'; 
		
//		Specific library for the Peri
		include './lib/peri9_lib.php';  
		
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
$idperipheral=$_GET['idperipheral'];



$file_data_path2 = "./data/" . $address_peri . "_data.txt"; 
	
$file_data_path3 = "./data/" . $address_peri . "_data_peri9.txt"; 
	

$array_input_to_show = array();
$array_output_to_show = array();
$array_function_to_show = array();
$array_input_formula_to_show = array();
create_array_from_config_file($address_peri, $idperipheral, 
								$array_input_to_show, 
								$array_output_to_show, 
								$array_function_to_show, 
								$array_input_formula_to_show
								);


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

echo '    <br>';

$display_data_X_YES = 1;
$display_data_X_NO = 0;
$size_px_canvas = 600;

echo '<p>';
echo '<table>';

echo '<tr>';
echo '<td>';
			
$id_name_canvas0 = "canvas0";
draw_chart_of_data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path3, 
			$id_name_canvas0,			//has to be different if there are more chart
			$size_px_canvas,	//size of the chart
			$display_data_X_YES, //data 0
			$display_data_X_NO, //data 1
			$display_data_X_NO, //data 2
			$display_data_X_NO, //data 3
			$display_data_X_NO, //data 4
			$display_data_X_YES, //time
			$display_data_X_NO	 //date
			);

echo '</td>';
echo '<td>';


$id_name_canvas0 = "canvas1";
draw_chart_of_data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path3, 
			$id_name_canvas0,			//has to be different if there are more chart
			$size_px_canvas,	//size of the chart
			$display_data_X_NO, //data 0
			$display_data_X_YES, //data 1
			$display_data_X_NO, //data 2
			$display_data_X_NO, //data 3
			$display_data_X_NO, //data 4
			$display_data_X_YES, //time
			$display_data_X_NO	 //date
			);



echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<td>';

$id_name_canvas0 = "canvas2";
draw_chart_of_data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path3, 
			$id_name_canvas0,			//has to be different if there are more chart
			$size_px_canvas,	//size of the chart
			$display_data_X_NO, //data 0
			$display_data_X_NO, //data 1
			$display_data_X_YES, //data 2
			$display_data_X_NO, //data 3
			$display_data_X_NO, //data 4
			$display_data_X_YES, //time
			$display_data_X_NO	 //date
			);

echo '</td>';
echo '<td>';

$id_name_canvas0 = "canvas3";
draw_chart_of_data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path3, 
			$id_name_canvas0,			//has to be different if there are more chart
			$size_px_canvas,	//size of the chart
			$display_data_X_NO, //data 0
			$display_data_X_NO, //data 1
			$display_data_X_NO, //data 2
			$display_data_X_YES, //data 3
			$display_data_X_NO, //data 4
			$display_data_X_YES, //time
			$display_data_X_NO	 //date
			);



echo '</td>';
echo '</tr>';

echo '</table>';
echo '</p>';


echo '<br>';

//print_the_file_data($address_peri,$array_input_formula_to_show[0], $file_data_path2);
//print_the_file_data($address_peri, 1,$file_data_path2);
//print_only_the_last_4data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path2);

//print_5columns_of_data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path2);


print_7columns_of_data_from_the_file($address_peri, $array_input_formula_to_show, $file_data_path3);




//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
