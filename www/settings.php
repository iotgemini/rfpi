<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/01/2021

Description: tools to change parameters

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


//---------------------------------------------------------------------------------------//
//		library with all useful functions to use RFPI
		include './lib/rfberrypi.php';  
		
//---------------------------------------------------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_btn_security="Security";
$lang_btn_release_info="Release Info";
$lang_btn_change_refresh_time="Change Refresh Time";
$lang_btn_set_rtc="Set RTC";
$lang_net_name="Network Name:";
$lang_net_address="Network Address:";
$lang_btn_change_net_name="Change Network Name";
$lang_btn_change_peri_name="Change Peripheral Name";
if($_SESSION["language"]=="IT"){
	$lang_btn_security="Sicurezza";
	$lang_btn_release_info="Info Versione";
	$lang_btn_change_refresh_time="Cambia tempo aggiornamento";
	$lang_btn_set_rtc="Imposta RTC";
	$lang_net_name="Nome Rete:";
	$lang_net_address="Indirizzo Rete:";
	$lang_btn_change_net_name="Cambia il Nome della Rete";
	$lang_btn_change_peri_name="Cambia il Nome del Periferico";
}else if($_SESSION["language"]=="FR"){	
	$lang_btn_security="S&eacute;curit&eacute;";
	$lang_btn_release_info="Infos Versione";
	$lang_btn_change_refresh_time="Modification du temps de mise &agrave; jour";
	$lang_btn_set_rtc="R&eacute;glez RTC";
	$lang_net_name="Nom de r&eacute;seau:";
	$lang_net_address="Adresse de r&eacute;seau:";
	$lang_btn_change_net_name="Changement de nom de r&eacute;seau";
	$lang_btn_change_peri_name="Changer le nom du P&eacute;riph&eacute;rique";
}else if($_SESSION["language"]=="SP"){	
	$lang_btn_security="Seguridad";
	$lang_btn_release_info="Informaci&oacute;n Versione";
	$lang_btn_change_refresh_time="Cambio de tiempo de actualizaci&oacute;n";
	$lang_btn_set_rtc="Ajuste RTC";
	$lang_net_name="Nombre de red:";
	$lang_net_address="Direcci&oacute;n de red:";
	$lang_btn_change_net_name="Cambio de nombre de red";
	$lang_btn_change_peri_name="Cambiar el nombre del Perif&eacute;rico";
	
}

//---------------------------------------------------------------------------------------//


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

/*echo '<p>';
echo '<img src="' . DIRECTORY_IMG . 'logo.png"  class="img_logo" alt="RFPI">';
echo '</p>';
*/
echo '<br>';

//button settings

//button security
/*echo '<p align=center>';
echo '<a href="./config/login_to_security_settings.php" class="btn_release">'.$lang_btn_security.'</a>';
*/
//button release
//echo '<p align=center>';
//echo '<a href="./release.php" class="btn_release">'.$lang_btn_release_info.'</a>';


//button Change Refresh Time
echo '<a href="./config/get_refresh_time.php" class="btn_release">'.$lang_btn_change_refresh_time.'</a>';

//button Set RTC
/*$str_rtc_time = str_rtc_time();
if($str_rtc_time[0] != 'N' && $str_rtc_time[0] != 'O'){
	echo '<a href="./get_rtc_time.php?page_to_redirect=home" class="btn_release">'.$lang_btn_set_rtc.'</a>';
}
echo '</p>';
*/

//button HOME
echo '<p align=left>';
echo '<form name="home" action="./index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';



	
echo '<table class="settings_table">';
//echo '<table>';
	
	echo '<tr>';

			echo '<td class="net_name_table_td">';
			echo $lang_net_address.'<br>';
			printNetworkAddress();
			echo '</td>';
			
			echo '<td class="net_name_table_td">';
			echo $lang_net_name.'<br>';
			//ask to the rfpi.c the network name set and prints
			printNetworkName();
			echo '</td>';
			
			echo '<td>';
			//Change Network Name
			echo '<form name="name_network" action="./get_network_name.php" method=GET>';
			echo '<input type=hidden name="name_network" value="main">';
			echo '&nbsp';
			echo '<input type=submit value="'.$lang_btn_change_net_name.'" class="btnChangeSetting">';
			echo '</form>';
			echo '</td>';

	echo '</tr>';
		
		
	if(file_exists(FIFO_RFPI_PERIPHERAL)){
		$handle = fopen(FIFO_RFPI_PERIPHERAL, 'r');
		$i=0; $t=0; $maxcountperipheral=254;
		
		while($i<$maxcountperipheral && feof($handle)!==TRUE){ 
				
			if($data = fscanf($handle, "%s %s %s %s\n")){
					list ($id,$idperipheral,$name,$address) = $data;
					$t++;
			}
				
			if($data=fgets($handle)){
				//inpus
				$t++;
			}
				
			if($data=fgets($handle)){
				//outputs
				$t++;
			}
				
			if($t===3){ //if $t==3 then all data have been got
			
				echo '<tr>';
				
				//printing the address
				if($i%2===0)
						echo '<td class="peri_name_even_td">';
					else
						echo '<td class="peri_name_odd_td">';
				echo $address;
				echo '</td>';
				
				//printing the name
				//alternating the line colour
				if($i%2===0)
						echo '<td class="peri_name_even_td">';
					else
						echo '<td class="peri_name_odd_td">';
						
			
				echo $name;
				echo '</td>';  

				//Change Peripheral Name
				echo '<td>';
				echo '<form name="peripheral_name' . $address . '" action="./get_peri_name.php" method=GET>';
				echo '<input type=hidden name="address" value="' . $address . '">';
				echo '&nbsp';
				echo '<input type=submit value="'.$lang_btn_change_peri_name.'" class="btnChangeSetting">';
				echo '</form>';
				echo '</td>';
				
				//echo '</tr>';
				
				
				
				//printing the button delete
				echo '<td>';
				echo '<form name="delete_' . $id . '" action="delete_peripheral.php" method=GET>';
				echo '<input type=hidden name="position_id" value="' . $id . '">';
				echo '<input type=hidden name="address" value="' . $address . '">';
				echo '&nbsp';
				echo '<img src="' . DIRECTORY_IMG . 'delete.png" onclick="document.delete_' . $id . '.submit();"  class="img_delete" alt="Delete"> ';
				echo '</form>';
				echo '</td>';
				
				echo '</tr>';
			}
			
			$t=0;
			$i++;
		}
		@unlink(FIFO_RFPI_PERIPHERAL); 
	}
	
	
	echo '</table>';

echo '</p>';
echo '</div>';


echo '<p align=left>';
echo '<form name="home" action="./index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</body></html>';

?>




