<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		03/08/2017

Description: this is a panel where to setup the thermostat


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
//		Specific library for the peri10
		include './lib/peri10_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages


$lang_title_enabled="ENABLED";
$lang_combo_disabled = "Disabled";

$lang_title_monday = "Monday";
$lang_title_tuesday = "Tuesday";
$lang_title_wednesday = "Wednesday";
$lang_title_thursday = "Thursday";
$lang_title_friday = "Friday";
$lang_title_saturday = "Saturday";
$lang_title_sunday = "Sunday";

$lang_array_months = array("January","February","March","April","May","June","July","August","Semptember","October","November","December");

$lang_choose_month_to_enable = 'Choose for which months enable the thermostat function:';
$lang_enable_thermostat_on_relay = 'Enable Function Thermostat on the Relay';

if($_SESSION["language"]=="IT"){

	$lang_title_enabled="ABILITATA";
	$lang_combo_disabled = "Disabilita";
	
	$lang_title_monday = "Luned&Igrave;";
	$lang_title_tuesday = "Marted&Igrave;";
	$lang_title_wednesday = "Mercoled&Igrave;";
	$lang_title_thursday = "Gioved&Igrave;";
	$lang_title_friday = "Venerd&Igrave;";
	$lang_title_saturday = "Sabato";
	$lang_title_sunday = "Domenica";
	
	$lang_array_months = array("Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre");

	$lang_choose_month_to_enable = 'Scegli per quale mese abilitare la funzione termostato:';
	$lang_enable_thermostat_on_relay = 'Abilita la funzione termostato sul Rel&egrave;';
	
	
}else if($_SESSION["language"]=="FR"){	

	$lang_title_enabled="ACTIV&Eacute;E";
	$lang_combo_disabled = "D&eacute;sactiver";
	
	$lang_title_monday = "Lundi";
	$lang_title_tuesday = "Mardi";
	$lang_title_wednesday = "Mercredi";
	$lang_title_thursday = "Jeudi";
	$lang_title_friday = "Vendredi";
	$lang_title_saturday = "Samedi";
	$lang_title_sunday = "Dimanche";
	
	$lang_array_months = array("Janvier","F&eacute;vrier","Mars","Avril","Mai","Juin","Juillet","Ao&Ucirc;t","Septembre","Octobre","Novembre","D&eacute;cembre");

	$lang_choose_month_to_enable = 'Choisissez le mois pour activer la fonction du thermostat:';
	$lang_enable_thermostat_on_relay = 'Activer la fonction de thermostat sur le relais';
	
	
}else if($_SESSION["language"]=="SP"){	

	$lang_title_enabled="ACTIVO";
	$lang_combo_disabled = "Inhabilitar";
	
	$lang_title_monday = "Lunes";
	$lang_title_tuesday = "Martes";
	$lang_title_wednesday = "Mi&eacute;rcoles";
	$lang_title_thursday = "Jueves";
	$lang_title_friday = "Viernes";
	$lang_title_saturday = "S&aacute;bado";
	$lang_title_sunday = "Domingo";
	
	$lang_array_months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

	$lang_choose_month_to_enable = 'Elegir qu&eacute; mes para habilitar la funci&oacute;n de termostato:';
	$lang_enable_thermostat_on_relay = 'Activar la función de termostato en el rel&eacute;';
	
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];


$num_special_function = $_GET['num_special_function'];
$num_bytes_to_get = $_GET['num_bytes_to_get'];


$path_data_file = $_GET['path_data_file'];



$lenght_array_data_file = $num_bytes_to_get;
//il b7 MSB viene usato per indicare se il controllo di temperatura è abilitato per quella fascia oraria
//il b6 viene usato per indicare 0.5°C
$array_data_file = array(
	18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,
	18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,
	18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,
	18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,
	18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,
	18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,
	18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,
	0,0
);

//in the last row there are 2 bytes, this 2 bytes are used for enable disable the control for each month:
//	MSB_EN/DI_month		LSB_EN/DI_month

//for the byte LSB_EN/DI_month:
	//b0:	0=disabled 1=enabled for the month of January
	//b1:	0=disabled 1=enabled for the month of February
	//b2:	0=disabled 1=enabled for the month of March
	//b3:	0=disabled 1=enabled for the month of April
	//b4:	0=disabled 1=enabled for the month of May
	//b5:	0=disabled 1=enabled for the month of June
	//b6:	0=disabled 1=enabled for the month of July
	//b7:	0=disabled 1=enabled for the month of August

//for the byte MSB_EN/DI_month:
	//b0:	0=disabled 1=enabled for the month of September
	//b1:	0=disabled 1=enabled for the month of October
	//b2:	0=disabled 1=enabled for the month of November
	//b3:	0=disabled 1=enabled for the month of December
	//b4:	spare
	//b5:	spare
	//b6:	spare
	//b7:	spare
	

if(file_exists($path_data_file)!==TRUE){ 
	//the file does not exist!
	echo "There was a error! Impossible to find the file with all settings!";
}else{
	$handle_file = fopen($path_data_file, 'r');
	$cont_lines = 0;
	while(feof($handle_file)!==TRUE && $cont_lines<$lenght_array_data_file){ 

		$line_file=fgets($handle_file, 5);
		$array_data_file[$cont_lines] = (int) $line_file;
		//echo $cont_lines . "=";
		//echo $array_data_file[$cont_lines] . " ";
		//echo '<br>';
		
		//if($cont_lines===167)
		//	echo $array_data_file[$cont_lines]. " ";
		
		$cont_lines++;
	}
}
$byte168=$byte169=255;
if($cont_lines==$lenght_array_data_file){
	$byte168=$array_data_file[$lenght_array_data_file-2];
	$byte169=$array_data_file[$lenght_array_data_file-1];
}
$sem_thermostat = 1;
if(($byte169 & 0x80) == 0x00) $sem_thermostat = 0; 
$sem_month = array(1,1,1,1,1,1,1,1,1,1,1,1);
for($i=0,$weight=1;$i<8;$i++,$weight*=2){
	if(($byte168 & $weight) == 0x00) $sem_month[$i] = 0; 
}
for($i=0,$weight=1;$i<4;$i++,$weight*=2){
	if(($byte169 & $weight) == 0x00) $sem_month[$i+8] = 0; 
}




//echo $array_data_file[23+$id_day]. " ";

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

echo '<form name="peri10_btn_gpio_functions" action="./cmd_set_thermostat_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="num_special_function" value="'. $num_special_function . '">';
echo '<input type=hidden name="lenght_array_data_file" value="'. $lenght_array_data_file . '">';
echo '<input type=hidden name="byte168" value="'. $byte168 . '">';
echo '<input type=hidden name="byte169" value="'. $byte169 . '">';

echo '<table class="table_peripheral" border=1>';


echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral"></td>';  

for($cont=0;$cont<24;$cont++){
	echo '<td class="td_peripheral">'.$cont.'-'.($cont+1).'</td>';  
}


//echo '<td class="td_peripheral">'.$lang_title_function.'</td>';  
//echo '<td class="td_peripheral">GPIO0<br>'.$lang_title_enabled.'</td>';  
echo '</tr>';



//function generate_combo_temperature($id_day, $array_data_file){
function generate_combo_temperature($id_day){ 
	global $array_data_file; $pippo=0;
	//echo " DAY=" .$id_day. " ";
	for($cont_hour=0;$cont_hour<24;$cont_hour++){ 
		//echo $array_data_file[$cont_hour + (24*$id_day)]. " ";
		echo '<td class="td_peripheral">';
			echo '<select name="temperature_day'.$id_day.'_hour'.$cont_hour.'">';
				if(($array_data_file[$cont_hour + (24*$id_day)]&128)==128){
					echo '<option value="0">D</option>';
				}else{
					echo '<option value="0" selected>D</option>'; $pippo=1;
				}
				$cont_even=0;
				for($cont=1;$cont<63;$cont=$cont+0.5){
					$temp = ($array_data_file[$cont_hour + (24*$id_day)]&(255-128-64));
					if(($array_data_file[$cont_hour + (24*$id_day)]&64)==64){ 
						$temp = $temp + 0.5;
					}
					
					$value_option = $cont|128;
					if($cont_even==1){
						$value_option |= 64;
						$cont_even=0;
					}else{
						$cont_even++;
					}
					
					if($cont == $temp && ($array_data_file[$cont_hour + (24*$id_day)]&128)==128){
						echo '<option value="'.$value_option.'" selected>'.$cont.'</option>';
					}else{
						echo '<option value="'.$value_option.'">'.$cont.'</option>';
					}
				}
			echo '</select>'; //echo $array_data_file[$cont_hour + (24*$id_day)] ."=".$temp ."-" . $cont ." | ".$pippo;
		echo '</td>';  
	}
}



//Monday
echo '<tr class="table_line_even">';
$id_day = 0;
echo '<td class="td_peripheral">'.$lang_title_monday.'</td>';  
//generate_combo_temperature($id_day, $array_data_file);
generate_combo_temperature($id_day);
echo '</tr>';
//Tuesday
echo '<tr class="table_line_even">';
$id_day++;
echo '<td class="td_peripheral">'.$lang_title_tuesday.'</td>';  
generate_combo_temperature($id_day);
echo '</tr>';
//Wednesday
echo '<tr class="table_line_even">';
$id_day++;
echo '<td class="td_peripheral">'.$lang_title_wednesday.'</td>';  
generate_combo_temperature($id_day);
echo '</tr>';
//Thursday
echo '<tr class="table_line_even">';
$id_day++;
echo '<td class="td_peripheral">'.$lang_title_thursday.'</td>';  
generate_combo_temperature($id_day);
echo '</tr>';
//Friday
echo '<tr class="table_line_even">';
$id_day++;
echo '<td class="td_peripheral">'.$lang_title_friday.'</td>';  
generate_combo_temperature($id_day);
echo '</tr>';
//Saturday
echo '<tr class="table_line_even">';
$id_day++;
echo '<td class="td_peripheral">'.$lang_title_saturday.'</td>';  
generate_combo_temperature($id_day);
echo '</tr>';
//Sunday
echo '<tr class="table_line_even">';
$id_day++;
echo '<td class="td_peripheral">'.$lang_title_sunday.'</td>';  
generate_combo_temperature($id_day);

echo '</tr>';


echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" colspan=25>';  
echo 'D = '.$lang_combo_disabled;
echo '</td>';  
echo '</tr>';




//function to change the status of the months and the status of the global function thermostat
echo '<script type="text/JavaScript">';
echo 'function change_byte168(month,obj_chk_box){';
echo 	'var value_obj_chk_box = obj_chk_box.value;';
echo 	'if(value_obj_chk_box==0){ value_obj_chk_box=1; }else{ value_obj_chk_box=0; }';
echo 	'obj_chk_box.value = value_obj_chk_box;';
echo 	'var weight_month=1;';
echo 	'var i;';
echo 	'for(i=0;i<month;i++){';
echo 		'weight_month = weight_month * 2;';		
echo 	'}'; 
echo 	'var tick_obj_chk_box = obj_chk_box.checked ;';
echo 	'var var_byte168 = parseInt(document.peri10_btn_gpio_functions.byte168.value);'; 
echo 	'if(tick_obj_chk_box==false){'; 
echo 		'var_byte168 &= (255-weight_month);';
echo 	'}else{';
echo 		'var_byte168 |= weight_month;';
echo 	'}';
echo 	'document.peri10_btn_gpio_functions.byte168.value = var_byte168;';
echo '}';
echo '</script>';

echo '<script type="text/JavaScript">';
echo 'function change_byte169(month,obj_chk_box){';
echo 	'var value_obj_chk_box = obj_chk_box.value;';
echo 	'if(value_obj_chk_box==0){ value_obj_chk_box=1; }else{ value_obj_chk_box=0; }';
echo 	'obj_chk_box.value = value_obj_chk_box;';
echo 	'var weight_month=1;';
echo 	'var i;';
echo 	'for(i=0;i<month;i++){';
echo 		'weight_month = weight_month * 2;';		
echo 	'}'; 
echo 	'var tick_obj_chk_box = obj_chk_box.checked ;';
echo 	'var var_byte169 = parseInt(document.peri10_btn_gpio_functions.byte169.value);'; 
echo 	'if(tick_obj_chk_box==false){'; 
echo 		'var_byte169 &= (255-weight_month);';
echo 	'}else{';
echo 		'var_byte169 |= weight_month;';
echo 	'}';
echo 	'document.peri10_btn_gpio_functions.byte169.value = var_byte169;';
echo '}';
echo '</script>';
		
		
echo '<tr class="table_line_even">';
echo '<td class="td_peripheral" colspan=7>';
	if(intval($sem_thermostat, 10)==0){
		echo '<input type="checkbox" name="sem_thermostat" value="0" onchange="change_byte169(7,this)">';
	}else{
		echo '<input type="checkbox" name="sem_thermostat" value="1" onchange="change_byte169(7,this)" checked>';
	}
	echo $lang_enable_thermostat_on_relay; //'Enable Function Thermostat on the Relay:';
echo '</td>'; 
 
echo '<td class="td_peripheral" colspan=6>';
	echo $lang_choose_month_to_enable; //'Choose for which months enable the thermostat function:';
echo '</td>'; 

for($i=0;$i<8;$i++){ //going to display the fist 8 months
	echo '<td class="td_peripheral" colspan=1>';
		if($sem_month[$i]==0){ //january
			echo '<input type="checkbox" name="sem_month_'.$lang_array_months[$i].'" value="0" onchange="change_byte168('.$i.',this);">';
		}else{
			echo '<input type="checkbox" name="sem_month_'.$lang_array_months[$i].'" value="1" onchange="change_byte168('.$i.',this);" checked>';
		}
		echo ' '.$lang_array_months[$i]; //display the name of the month
	echo '</td>'; 
}
for($i=8;$i<12;$i++){ //going to display the last 4 months
	echo '<td class="td_peripheral" colspan=1>';
		if($sem_month[$i]==0){ //january
			echo '<input type="checkbox" name="sem_month_'.$lang_array_months[$i].'" value="0" onchange="change_byte169('.($i-8).',this);">';
		}else{
			echo '<input type="checkbox" name="sem_month_'.$lang_array_months[$i].'" value="1" onchange="change_byte169('.($i-8).',this);" checked>';
		}
		echo ' '.$lang_array_months[$i]; //display the name of the month
	echo '</td>'; 
}

echo '</tr>';




echo '<tr class="table_title_field_line">';
echo '<td colspan=25 align=center>';
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
