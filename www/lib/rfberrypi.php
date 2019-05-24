<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/05/2019

Description: it is the library with all useful function to use RFPI

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


//-------------------------------BEGIN FUNCTIONS DESCRIPTIONS----------------------------------//

//		function release_version();				//it return the release version written into /etc/rfpi/release.txt

//		function print_release_version();		//it print the whole history release written into /etc/rfpi/release.txt

//		function writeFIFO($nameFIFO, $data); 	//it write the $data into the FIFO with name $nameFIFO

//		function readFIFO($nameFIFO); 			//it read and return the data readed into the FIFO with name $nameFIFO

//		function existsFIFO($nameFIFO);			//if the FIFO exist means the rfpi.c has not read it

//		function htmlMsgWaitanswerFromRFPI(); 	//it just shows a message: Waiting answer from RFPI......

//		function printRawTablePeripheral();		//it print the table with the tools

//		function printToolsBar(); 				//it print the table with the tools

//		function checkExistPeripheralFunction($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput); 
												//it will call the function with the tools to use the peripheral identified by $idperipheral
			
//		function printTablePeripheral(); 		//it print with echo a table with all data of the linked peripheral

//		function printNetworkName();			//reads from the FIFO the network name and prints it

//		function printNetworkAddress();			//reads from the FIFO the network name and calculate the address to then print it

//		function addressFromName(&$name);		//it calculate and return the address for the network. Example name="SDS" return="00EA"

//		function convert_2ChrHex_to_byte(&$twoChrHex); //get an array of two char that represent a hexadecimal value and convert it in a valid number between 0 and 255

//		function convert_byte_to_2ChrHex(&$byte); //get a number between 0 and 255 and convert it into an array of two char that represent a hexadecimal
						
//		function str_rtc_time();				//print the time from RTC

//		function str_rtc_date();				//print the time from RTC

//		function validate_network_name(&$network_name);	//it substitute the spaces with underscore and check if the legth is lower of the maximun.
												//The maximum is 128 characters (see the constant MAX_LEN_NET_NAME into /etc/rfpi/lib/rfberrypi.h)

//		function validate_name(&$name);			//it substitute the spaces with underscore and check if the legth is lower of the maximun.
												//The maximum is 50 characters (see the constant MAX_LEN_NAME into /etc/rfpi/lib/rfberrypi.h)

//		function printFlagsBar();				//it print the table with the flags to change language

//		function create_array_from_config_file(&$address_peri,&$idperipheral, 		//read the config file for a peri and then push into the arry the value
//												&$array_input_to_show, 
//												&$array_output_to_show, 
//												&$array_function_to_show, 
//												&$array_input_formula_to_show
//												);
	
												
//-------------------------------END FUNCTIONS DESCRIPTIONS----------------------------------//

//-------------------------------BEGIN DEFINE----------------------------------//

//DEFINES of FIFO
define("FIFO_PATH", "/etc/rfpi/fifo/"); 							//whwere all FIFO files are written
define("FIFO_RFPI_RUN", "/etc/rfpi/fifo/fiforfpirun"); 				//used to check if the rfpi is operating
define("FIFO_GUI_CMD", "/etc/rfpi/fifo/fifoguicmd"); 				//used to send command and notifications to the RFPI 
define("FIFO_RFPI_STATUS", "/etc/rfpi/fifo/fiforfpistatus"); 		//used to send command and notifications to the RFPI 
define("FIFO_RFPI_PERIPHERAL", "/etc/rfpi/fifo/fifoperipheral"); 	//used to get the status of the peripherals (it can goes from 0 to 254)
define("FIFO_RFPI_NET_NAME", "/etc/rfpi/fifo/fifonetname"); 		//used to get the network name set
define("FIFO_RTC", "/etc/rfpi/fifo/fifortc"); 						//used to get the time from the RTC
define("FIFO_GET_BYTES_U", "/etc/rfpi/fifo/fifogetbytesu"); 		//used to get GET_BYTES_U
define("FIFO_SEND_BYTES_U", "/etc/rfpi/fifo/fifosendbytesf"); 		//used to get SEND_BYTES_F


//DEFINES of message to write into the FIFO RFPI DATA
define("STATUS_ERROR_GOT",  "STATUS ERROR GOT NULL "); 	//used to get the status of the peripherals (it can goes from 0 to 254)
define("FIND_NEW_PERIPHERAL",  "FIND NEW PERI NULL "); 	//used to find a new peripheral


//absolute path where is the website
define("PATH_WWW", "/var/www"); 	//used to set the language to show

//path language
define("PATH_FILE_LANGUAGE_TO_SET", PATH_WWW . "/config/lang.txt"); 	//used to set the language to show


define("DIRECTORY_CONFIG_ALL_SETTINGS", "/etc/rfpi/config/"); 			//where all aettings are kept
//path where are kept the configuration file for all peripherals
define("DIRECTORY_CONFIG_PERI", DIRECTORY_CONFIG_ALL_SETTINGS ."peri/");	//where the configuration files for this peripheral are kept
//address_peri . FILE_NAME_CONFIG_PERI . id . FILE_EXTENSION_CONFIG_PERI
define("FILE_NAME_CONFIG_PERI", "_what_to_show_peri_"); 						
define("FILE_EXTENSION_CONFIG_PERI", ".txt"); 								

//-------------------------------END DEFINE----------------------------------//

//-------------------------------BEGIN INCLUDE CSS----------------------------------//

//DEFINES of DIRECTORY
if(DEFAULT_PATH != "NO"){ //in some file this path are redefined
	define("DIRECTORY_IMG", "/img/"); 		//where all pictures are kept
	define("DIRECTORY_IMG_FLAGS", "/img/flags"); 		//where all flags are kept
	define("DIRECTORY_CSS", "/css/"); 		//where all CSS are kept
}




//include the CSS
//echo '<head>';
echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'peripheral_table.css" type="text/css" >';
echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'body.css" type="text/css" >';
echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'tools_bar.css" type="text/css" >';
echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'settings.css" type="text/css" >';
//echo '</head>';

//-------------------------------END INCLUDE CSS----------------------------------//



//-------------------------------BEGIN FUNCTION ALWAYS EXECUTED----------------------------------//

session_start(); 	//used for the login

if($_SESSION["login_yes"]=="" && $_SERVER['PHP_SELF']!='/login.php' && $_SERVER['PHP_SELF']!='/login_check.php'){
	header('Location: login.php') ;
}

//http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) { //1800 = 30min
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) { //1800 = 30min
    // session started more than 30 minutes ago
    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

//if($_SESSION["language"]===""){
	if(file_exists(PATH_FILE_LANGUAGE_TO_SET)){
		$myfile = fopen(PATH_FILE_LANGUAGE_TO_SET, 'r');
		$language_to_set="";
		if(feof($handle)!==TRUE) $language_to_set = fgets($myfile);
		//echo $language_to_set."<br>";
		//$language_to_set = substr ( $language_to_set , 0, $language_to_set.lenght -2 );
		$language_to_set =  str_replace("\n", '', $language_to_set);
		$language_to_set =  str_replace("\r", '', $language_to_set);
		$language_to_set =  str_replace("/\s/", '', $language_to_set);
		if($language_to_set!="")
			$_SESSION["language"]=$language_to_set;
		else
			$_SESSION["language"]="EN";
		fclose($myfile);
	
	}else{
		$_SESSION["language"]="EN";
	}
//}
//echo $_SESSION["language"]."<br>";

//http://php.net/manual/en/reserved.variables.session.php
//if (isset($_SESSION['form_convert'])){
//    $form_convert = $_SESSION['form_convert'];
//}else{
//    $form_convert = array();
//    $_SESSION['form_convert']=$form_convert;
//}


//-------------------------------END FUNCTION ALWAYS EXECUTED----------------------------------//



//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_btn_home="HOME";
$lang_btn_save="Save";
$lang_btn_retry="Try Again";
$lang_btn_login="Login";
$lang_btn_back="<<BACK";
$lang_btn_apply="APPLY";

$lang_msg_on="ON";
$lang_msg_off="OFF";
$lang_msg_turn_on="Turn ON";
$lang_msg_turn_off="Turn OFF";
$lang_msg_no_communication="No communication!";
$lang_msg_connected="Connected!";

$lang_release_version = "Current Release ";
$lang_last_update = "Last Update ";
$lang_history_release = "History: ";

$lang_title_id = "ID";
$lang_title_type = "Type";
$lang_title_address = "Address";
$lang_title_name = "Name";
$lang_title_input = "Inputs";
$lang_title_output = "Outputs";
$lang_title_functions = "Functions";
		
if($_SESSION["language"]=="IT"){
	$lang_btn_home="HOME";
	$lang_btn_save="Salva";
	$lang_btn_retry="Riprova";
	$lang_btn_login="Accesso";
	$lang_btn_back="<<INDIETRO";
	$lang_btn_apply="APPLICA";
	
	$lang_msg_on="ACCESO";
	$lang_msg_off="SPENTO";
	$lang_msg_turn_on="ACCENDI";
	$lang_msg_turn_off="SPEGNI";
	$lang_msg_no_communication="No comunicazione!";
	$lang_msg_connected="Connesso!";
	
	$lang_release_version = "Versione ";
	$lang_last_update = "Ultimo Aggiornamento ";
	$lang_history_release = "Storia: ";
	
	$lang_title_id = "ID";
	$lang_title_type = "Tipo";
	$lang_title_address = "Indirizzo";
	$lang_title_name = "Nome";
	$lang_title_input = "Entrate";
	$lang_title_output = "Uscite";
	$lang_title_functions = "Funzioni";

}else if($_SESSION["language"]=="FR"){	
	$lang_btn_home="HOME";
	$lang_btn_save="Enregistrer";
	$lang_btn_retry="R&eacute;essayer";
	$lang_btn_login="Identifiant";
	$lang_btn_back="<<RETOUR";
	$lang_btn_apply="APPLIQUER";
	
	$lang_msg_on="ON";
	$lang_msg_off="OFF";
	$lang_msg_turn_on="Turn ON";
	$lang_msg_turn_off="Turn OFF";
	$lang_msg_no_communication="Pas de communication!";
	$lang_msg_connected="Connect&eacute;!";
	
	$lang_release_version = "Version ";
	$lang_last_update = "Derni&egrave;re mise &agrave; jour ";
	$lang_history_release = "Histoire: ";
	
	$lang_title_id = "ID";
	$lang_title_type = "Type";
	$lang_title_address = "Adresse";
	$lang_title_name = "Nom";
	$lang_title_input = "Entr&eacute;es";
	$lang_title_output = "Sorties";
	$lang_title_functions = "Fonctions";
	
}else if($_SESSION["language"]=="SP"){	
	$lang_btn_home="HOME";
	$lang_btn_save="Salvar";
	$lang_btn_retry="Int&eacute;ntalo de nuevo";
	$lang_btn_login="Iniciar sesi&oacute;n";
	$lang_btn_back="<<VOLVER";
	$lang_btn_apply="APLICAR";
	
	$lang_msg_on="ON";
	$lang_msg_off="OFF";
	$lang_msg_turn_on="Turn ON";
	$lang_msg_turn_off="Turn OFF";
	$lang_msg_no_communication="No hay comunicaci&oacute;n!";
	$lang_msg_connected="Conectado!";
	
	$lang_release_version = "Versi&oacute;n ";
	$lang_last_update = "&Uacute;ltima actualizaci&oacute;n ";
	$lang_history_release = "Historia: ";
	
	$lang_title_id = "ID";
	$lang_title_type = "Especie";
	$lang_title_address = "Direcci&oacute;n";
	$lang_title_name = "Nombre";
	$lang_title_input = "Entradas";
	$lang_title_output = "Salidas";
	$lang_title_functions = "Funciones";
	
}

define("DEFINE_lang_btn_home", $lang_btn_home);
define("DEFINE_lang_btn_save", $lang_btn_save);
define("DEFINE_lang_btn_retry", $lang_btn_retry);
define("DEFINE_lang_btn_login", $lang_btn_login);
define("DEFINE_lang_btn_back", $lang_btn_back);
define("DEFINE_lang_btn_apply", $lang_btn_apply);

define("DEFINE_lang_msg_on", $lang_msg_on);
define("DEFINE_lang_msg_off", $lang_msg_off);
define("DEFINE_lang_msg_turn_on", $lang_msg_turn_on);
define("DEFINE_lang_msg_turn_off", $lang_msg_turn_off);
define("DEFINE_lang_msg_no_communication", $lang_msg_no_communication);
define("DEFINE_lang_msg_connected", $lang_msg_connected);

define("DEFINE_lang_release_version", $lang_release_version);
define("DEFINE_lang_last_update", $lang_last_update);
define("DEFINE_lang_history_release", $lang_history_release);

define("DEFINE_lang_title_id", $lang_title_id);
define("DEFINE_lang_title_type", $lang_title_type);
define("DEFINE_lang_title_address", $lang_title_address);
define("DEFINE_lang_title_name", $lang_title_name);
define("DEFINE_lang_title_input", $lang_title_input);
define("DEFINE_lang_title_output", $lang_title_output);
define("DEFINE_lang_title_functions", $lang_title_functions);


//---------------------------------------------------------------------------------------//


//-------------------------------BEGIN INCLUDE----------------------------------//

	include './lib/list_cpanel_peripherals.php';  	//library with the list of all avialable control panel for peripheral with assigned an official ID number

//-------------------------------END INCLUDE----------------------------------//


//it return the release version written into /etc/rfpi/release.txt
function release_version(){
	$path_file_release="/etc/rfpi/release.txt";
	$handle_release = fopen($path_file_release, 'r');
	
	$str_release_version = $lang_release_version;
	if(feof($handle_release)!==TRUE){ 
		$str_release_version .= fgets($handle_release, 40);
	}
	fclose($handle_release);
	return $str_release_version;
}

//it print the whole history release written into /etc/rfpi/release.txt
function print_release_version(){
	$handle_release = fopen("/etc/rfpi/release.txt", 'r');
	$i=0;
	while(feof($handle_release)!==TRUE){ 
		if($i==0){
			echo $lang_release_version;
		}else if($i==1){
			echo $lang_last_update;
		}else if($i==3){
			echo $lang_history_release;
		}
		
		$release_history=fgets($handle_release, 100);
		echo $release_history;
		echo '<br>';
		$i++;
	}
	fclose($handle_release);
}
	

//it write the $data into the FIFO with name $nameFIFO
function writeFIFO($nameFIFO, $data){
	posix_mkfifo($nameFIFO, 0666); 
	$fifo_handle = fopen($nameFIFO, 'w'); 
	fwrite($fifo_handle, $data); 
	fclose($fifo_handle);
}

//it read and return the data readed into the FIFO with name $nameFIFO
function readFIFO($nameFIFO){
	$handle = fopen($nameFIFO, 'r');
	if(feof($handle)!==TRUE){ 
		$data=fgets($handle, 200);
		fclose($handle);
	}
	//@unlink($nameFIFO); 
	return $data;
}

//if the FIFO exist means the rfpi.c has not read it
function existsFIFO($nameFIFO){
	return file_exists($nameFIFO);
}

//it just shows a message: Waiting answer from RFPI......
function htmlMsgWaitanswerFromRFPI(){

	echo '<p style="color:blue"></p>';
	
		if($_SESSION["language"]=="IT")	
			echo "In attesa di una risposta dal RFPI......"; 
		else if($_SESSION["language"]=="FR")	
			echo "En attente de r&eacute;ponse de RFPI ......";
		else if($_SESSION["language"]=="SP")	
			echo "Esperando respuesta de RFPI ......";
		else 
			echo "Waiting answer from RFPI......";
	
	echo '</p>';	
	ob_flush(); //it will send to the client what has been executed and then proceed with next instruction
	flush();
	
}


//it print with echo a raw table with all data of the linked peripheral
function printRawTablePeripheral(){
	
echo '<table border=1>';



		
echo '<tr style="color:blue">';
echo '<td>ID</td>';  
echo '<td>Peripheral_Type</td>'; 
echo '<td>Peripheral_Name</td>';  
echo '<td>Input</td>'; 
echo '<td>Output</td>'; 
echo '<td>';
echo '</td>';
echo '</tr>';

if(file_exists(FIFO_RFPI_PERIPHERAL)){
	$handle = fopen(FIFO_RFPI_PERIPHERAL, 'r');
	$i=0; $t=0; $maxcountperipheral=254;
	
	while($i<$maxcountperipheral && feof($handle)!==TRUE){ 
		if($t==0){
			$t++;
			if($data = fscanf($handle, "%s %s %s %s\n")){
				list ($id,$idperipheral,$name, $address_peri) = $data;
				echo '<tr>';
				echo '<td>' . $id . '</td>';  
				echo '<td>' . $idperipheral . '</td>';  
				echo '<td>' . $name . '</td>';  
			}
			
		} 
		if($t==1){
			$t++;
			
			if($data=fgets($handle)){
				echo '<td>';
				
				//get number of input
				$maxLenLine=strlen($data);
				$j=0;
				$strNum="";
				$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$strNum=$strNum . $data[$j];
						$j++;
					}
				}
				$numInput = (int)$strNum; //number of input
				
				//get all name of the input
				$j=$count+1;
				$l=0; 
				$arrayNameInput[$l] ="";
				while ($l<$numInput && $j<$maxLenLine) {
					if($data[$j]===" "){ 
						$l++;
						$arrayNameInput[$l] ="";
					}else{
						$arrayNameInput[$l]=$arrayNameInput[$l] . $data[$j];
					}
					$j++;
				}

				//get all status of the input
				$l=0;
				$arrayStatusInput[$l] ="";
				while ($l<$numInput && $j<$maxLenLine) {
					if($data[$j]===" "){
						$l++;
						$arrayStatusInput[$l] ="";
					}else{
						$arrayStatusInput[$l]=$arrayStatusInput[$l] . $data[$j];
					}
					$j++;
				}
				
				//print the name of the input and the status
				$l=0;
				while ($l<$numInput) {
					echo $arrayNameInput [$l];
					echo ' = ';
					echo $arrayStatusInput [$l];
					echo '<br>';
					$l++;
				}
				echo '</td>';
			}
			
		}
		if($t==2){
			$t++;
			
			if($data=fgets($handle)){
				echo '<td>';
			
				//get number of output
				$maxLenLine=strlen($data);
				$j=0;
				$strNum="";
				$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$strNum=$strNum . $data[$j];
						$j++;
					}
				}
				$numOutput = (int)$strNum; //number of output
				
				//get all name of the output
				$j=$count+1;
				$l=0; 
				$arrayNameOutput[$l] ="";
				while ($l<$numOutput && $j<$maxLenLine) {
					if($data[$j]===" "){ 
						$l++;
						$arrayNameOutput[$l] ="";
						//echo ' ';
					}else{
						$arrayNameOutput[$l]=$arrayNameOutput[$l] . $data[$j];
					}
					$j++;
				}
				
				//get all status of the output
				$l=0;
				$arrayStatusOutput[$l] ="";
				while ($l<$numOutput && $j<$maxLenLine) {
					if($data[$j]===" "){
						$l++;
						$arrayStatusOutput[$l] ="";
					}else{
						$arrayStatusOutput[$l]=$arrayStatusOutput[$l] . $data[$j];
					}
					$j++;
				}
				
				//print the name of the output and the status
				$l=0;
				while ($l<$numOutput) {
					echo $arrayNameOutput [$l];
					echo ':';
					echo '<form name="set_' . $id . '_output" action="set_output.php" method=GET>';
					echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
					echo '<input type=hidden name="output_id" value="' . $l . '">';
					echo '<input type=text name="output_value" value="' . $arrayStatusOutput [$l] . '" class="text_value_io">';
					echo '<input type=submit value="Set Output">';
					echo '</form>';
	
					$l++;
				}
				echo '</td>';
			}
			
		}
		if($t>=3 && feof($handle)!==TRUE){
			$t=0;
		
			echo '<td>';
			/*echo '<form name="delete_' . $id . '" action="delete_peripheral.php" method=GET>';
			echo '<input type=hidden name="position_id" value="' . $id . '">';
			echo '<input type=submit value="delete">';
			echo '</form>';
			*/
			echo '</td>';
			echo '</tr>';
		}
		
		
		$i++;
	}
	fclose($handle);
	//@unlink(FIFO_RFPI_PERIPHERAL); 
}

echo '</table>';
}

//it print the table with the tools
function printToolsBar(){

	echo '<table class="table_tools">';

		echo '<tr class="table_title_field_line">';

			$str_refresh="Refresh";
			$str_findnew="Find New";
			$str_settings="Settings";
			if($_SESSION["language"]=="IT"){
					$str_refresh="Aggiorna";
					$str_findnew="Trova";
					$str_settings="Parametri";
			}else if($_SESSION["language"]=="FR"){	
					$str_refresh="Rafra&Icirc;chir";
					$str_findnew="Trouver";//Nouveau
					$str_settings="Param&egrave;tres";
			}else if($_SESSION["language"]=="SP"){	
					$str_refresh="Refrescar";
					$str_findnew="Buscar";  //Nueva
					$str_settings= "Par&aacute;metros";//"Configuraci&oacute;n";
			}
			
			
			//Refresh
			echo '<form name="refresh" action="./refresh.php" method=GET>';
			echo '<input type=hidden name="refresh" value="main">';
			echo '<input type=submit value="' . $str_refresh . '" class="btn_bar">';
			echo '</form>';
				
			//find new
			echo '<form name="find" action="./find_new.php" method=GET>';
			echo '<input type=hidden name="find" value="main">';
			echo '<input type=submit value="' . $str_findnew . '" class="btn_bar">';
			echo '</form>';
			
			//Setting
			echo '<form name="settings" action="./settings.php" method=GET>';
			echo '<input type=hidden name="settings" value="main">';
			echo '<input type=submit value="' . $str_settings . '" class="btn_bar">';
			echo '</form>';
		
		echo '</tr>';
	echo '</table>';
}


//it print with echo a table with all data of the linked peripheral
function printTablePeripheral(){
	
	global $lang_title_id, $lang_title_type, $lang_title_address, $lang_title_name, $lang_title_input, $lang_title_output, $lang_title_functions;

echo '<table class="table_peripheral">';
//echo '<table class="table_peripheral" border=1>';
//echo '<table class="datagrid">';
/*
echo '<thead>';
	echo '<tr>';
		//echo '<th>'.$lang_title_id.'</th>';
		//echo '<th>'.$lang_title_type.'</th>';
		echo '<th>'.$lang_title_address.'</th>';
		echo '<th>'.$lang_title_name.'</th>';
		echo '<th>'.$lang_title_input.'</th>';
		echo '<th>'.$lang_title_output.'</th>';
		echo '<th>'.$lang_title_functions.'</th>';
	echo '</tr>';
echo '</thead>';
**/

echo '<tr class="table_title_field_line">';
	
	//echo '<td class="td_peripheral">'.$lang_title_id.'</td>';  
	//echo '<td class="td_peripheral">'.$lang_title_type.'</td>'; 
	echo '<td class="td_peripheral">'.$lang_title_address.'</td>';  
	echo '<td class="td_peripheral">'.$lang_title_name.'</td>';  
	echo '<td class="td_peripheral">'.$lang_title_input.'</td>'; 
	echo '<td class="td_peripheral">'.$lang_title_output.'</td>'; 
	echo '<td class="td_peripheral">'.$lang_title_functions.'</td>';

echo '</tr>';


if(file_exists(FIFO_RFPI_PERIPHERAL)){
	$last_address="";
	$address_peri="";
	$last_data="";
	$data="";
	$num_special_functions_peri="";
	$fw_version_peri="";
	$handle = fopen(FIFO_RFPI_PERIPHERAL, 'r');
	$i=0; $t=0; $maxcountperipheral=254;
	
	while($i<$maxcountperipheral && feof($handle)!==TRUE){ 

		if($data = fscanf($handle, "%s %s %s %s %s %s\n")){ 
				list($id,$idperipheral,$name, $address_peri, $num_special_functions_peri,  $fw_version_peri) = $data;
				$t++;
		}
		
		if($num_special_functions_peri==""){
			$num_special_functions_peri = "0";
		}
		
		if($fw_version_peri==""){
			$fw_version_peri = "0";
		}
		
		if($last_address !== $address_peri){
			$last_address = $address_peri;
			
		if($data=fgets($handle)){
		//if($last_data !== $data){
		
				$last_data = $data;
				//get number of input
				$maxLenLine=strlen($data);
				$j=0;
				$strNum="";
				$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$strNum=$strNum . $data[$j];
						$j++;
					}
				}
				$numInput = (int)$strNum; //number of input
				
				//get all name of the input
				$j=$count+1;
				$l=0; 
				$arrayNameInput[$l] ="";
				while ($l<$numInput && $j<$maxLenLine) {
					if($data[$j]===" "){ 
						$l++;
						$arrayNameInput[$l] ="";
					}else{
						$arrayNameInput[$l]=$arrayNameInput[$l] . $data[$j];
					}
					$j++;
				}

				//get all status of the input
				$l=0;
				$arrayStatusInput[$l] ="";
				while ($l<$numInput && $j<$maxLenLine) {
					if($data[$j]===" "){
						$l++;
						$arrayStatusInput[$l] ="";
					}else{
						$arrayStatusInput[$l]=$arrayStatusInput[$l] . $data[$j];
					}
					$j++;
				}
				$t++;
		}
			
		if($data=fgets($handle)){
		//if($last_data !== $data){	
				//get number of output
				$maxLenLine=strlen($data);
				$j=0;
				$strNum="";
				$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$strNum=$strNum . $data[$j];
						$j++;
					}
				}
				$numOutput = (int)$strNum; //number of output
				
				//get all name of the output
				$j=$count+1;
				$l=0; 
				$arrayNameOutput[$l] ="";
				while ($l<$numOutput && $j<$maxLenLine) {
					if($data[$j]===" "){ 
						$l++;
						$arrayNameOutput[$l] ="";
					}else{
						$arrayNameOutput[$l]=$arrayNameOutput[$l] . $data[$j];
					}
					$j++;
				}
				
				//get all status of the output
				$l=0;
				$arrayStatusOutput[$l] ="";
				while ($l<$numOutput && $j<$maxLenLine) {
					if($data[$j]===" "){
						$l++;
						$arrayStatusOutput[$l] ="";
					}else{
						$arrayStatusOutput[$l]=$arrayStatusOutput[$l] . $data[$j];
					}
					$j++;
				}
				$t++;
		}
			
		if($t===3){ //if $t==3 then all data have been got
		
			//alternating the line colour
			if($i%2===0)
					echo '<tr class="table_line_even">';
				else
					echo '<tr class="table_line_odd">';
					
			echo '<td>' . $address_peri . '</td>'; 	

			if(!checkExistPeripheralFunction($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri)){
			
				//printing all data if there is no an apposite function for this peripheral
			
				//echo '<td>' . $id . '</td>';  
				//echo '<td>' . $idperipheral . '</td>'; 
				//echo '<td>' . $address_peri . '</td>'; 	
				echo '<td  align=center>' . $name . '</td>';  
				
				//print the name of the input and the status
				echo '<td>';
				$l=0;
				//var_dump($arrayNameInput);
				while ($l<$numInput) {
					echo $arrayNameInput [$l];
					echo ' = ';
					echo $arrayStatusInput [$l];
					echo '<br>';
					$l++;
				}
				echo '</td>';
				
				//print the name of the output and the status
				echo '<td>';
				$l=0;
				while ($l<$numOutput) {
					echo $arrayNameOutput [$l];
					echo ':';
					echo '<form name="set_' . $id . '_output" action="set_output.php" method=GET>';
					echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
					echo '<input type=hidden name="output_id" value="' . $l . '">';
					echo '<input type=text name="output_value" value="' . $arrayStatusOutput [$l] . '" class="text_value_io">';
					echo '<input type=submit value="Set Output">';
					echo '</form>';
	
					$l++;
				}
				echo '</td>';
				
			}

			//printing the functions of the peripheral
			//echo '<td>';
			//echo '</td>';
			echo '</tr>';
			
			//printing the button delete
			/*echo '<td>';
			echo '<form name="delete_' . $id . '" action="delete_peripheral.php" method=GET>';
			echo '<input type=hidden name="position_id" value="' . $id . '">';
			echo '<img src="' . DIRECTORY_IMG . 'delete.png" onclick="document.delete_' . $id . '.submit();"  class="img_delete" alt="Delete"> ';
			echo '</form>';
			echo '</td>';
			
			echo '</tr>';*/
			
		}
		}else{
			if($data=fgets($handle)); //just to go to next line
			if($data=fgets($handle)); //just to go to next line
		}
		
		$t=0;
		$i++;
	}
	//@unlink(FIFO_RFPI_PERIPHERAL); 
}

echo '</table>';
}

//ask to the rfpi.c the network name set and prints
function printNetworkName(){

	//open the fifo to check the name into
	$networkName=readFIFO(FIFO_RFPI_NET_NAME);
	echo $networkName;

}

//reads from the FIFO the network name and calculate the address to then print it
function printNetworkAddress(){
	//$networkNameFIFO=readFIFO(FIFO_RFPI_NET_NAME);
	$addressNetwork = "0000";
	$addressNetworkInt=0;
	
	/*$addressNetwork  = dechex ( $addressNetworkInt );
	if(strlen($addressNetwork)==1){
		$addressNetwork = "000" . $addressNetwork;
	}else if(strlen($addressNetwork)==2){
		$addressNetwork = "00" . $addressNetwork;
	}else if(strlen($addressNetwork)==3){
		$addressNetwork = "0" . $addressNetwork;
	}*/
	
	//$addressNetwork = addressFromName($networkNameFIFO);
	$handle = fopen(FIFO_RFPI_NET_NAME, 'r');
	if(feof($handle)!==TRUE){ 
		$data=fgets($handle, 200);
	}
	if(feof($handle)!==TRUE){ 
		$addressNetwork=fgets($handle, 20);
	}
	fclose($handle);
	
	
	$addressNetwork = strtoupper ($addressNetwork); //make the string upper case
	
	echo $addressNetwork;
}

//it calculate and return the address for the network. Example name="SDS" return="00EA"
//The maximum lent of the name is 128 characters (see the constant MAX_LEN_NET_NAME into /etc/rfpi/lib/rfberrypi.h)
function addressFromName(&$name){
	$address_peri = "0000";
	$i=0;
	$intAddress=0;
	$strHex="000";
	$byteH=0;
	$byteL=0;
	
	$lenName=strlen($name); //get the length of the name
	
	if($lenName>128)	//maximum length allowed is 128 characters
		$lenName=128;
		
	$intAddress=0;
	for($i=0;$i<$lenName;$i++){
		//$intAddress=$intAddress+$name[$i];
		$intAddress=$intAddress + ord ( $name[$i] );
	}
	
	//dividing the integer address contained into i in two bytes High and Low and copying into the strCmd
	$byteH=0;
	$byteH=$byteH | ($intAddress>>8);

	$byteL=0;
	$byteL=byteL | $intAddress;

	$strHex = convert_byte_to_2ChrHex($byteH);
	$address_peri[0]=$strHex[0];
	$address_peri[1]=$strHex[1];
	
	$strHex = convert_byte_to_2ChrHex($byteL);
	$address_peri[2]=$strHex[0];
	$address_peri[3]=$strHex[1];
	
	//$address_peri[4]='\0';
	
	return $address_peri;
}


//get an array of two char that represent a hexadecimal value and conver it into a valid number between 0 and 255
function convert_2ChrHex_to_byte(&$twoChrHex){
	$byteSignal = 0;
	$pos_last_chr = 2;

	for($i=0; $i < $pos_last_chr; $i++){ 
		if(ord($twoChrHex[$i]) >= 48 && ord($twoChrHex[$i]) <= 57){ //check if it is between '0' and '9'
			$byteSignal = $byteSignal | (ord($twoChrHex[$i]) - 48);
		}else if(ord($twoChrHex[$i]) >= 65 && ord($twoChrHex[$i]) <= 70){ //check if it is between 'A' and 'F'
			$byteSignal = $byteSignal | ((ord($twoChrHex[$i]) - 65) + 10);
		}

		if($i < ($pos_last_chr - 1)){
			$byteSignal = $byteSignal << 4; 
		}
	}
	
	
	return $byteSignal;
}

//get a number between 0 and 255 and convert it into an array of two char that represent a hexadecimal
function convert_byte_to_2ChrHex(&$byte/*, &$twoChrHex*/){
				
	$twoChrHex = "00";
						
	//converting in ASCII hexadecimal
	$byteWork = $byte & 0x0F; 
	if($byteWork>9){
		$twoChrHex[1] = chr ( ($byteWork+65-10));
	}else{
		$twoChrHex[1] = chr ( ($byteWork+48));
	}
	$byteWork = $byte >> 4;
						
	if($byteWork>9)
		$twoChrHex[0] = chr ( ($byteWork+65-10));
	else
		$twoChrHex[0] = chr ( ($byteWork+48));
					
	return $twoChrHex;
}


//print the time from RTC
function str_rtc_time(){

	$RTC_time_and_date=readFIFO(FIFO_RTC);
	if(strlen($RTC_time_and_date)>0){
		$RTC_time = substr ( $RTC_time_and_date , 0 , strpos($RTC_time_and_date," ") ); 
	}else{
		$RTC_time="00/00/00";
	}
	return $RTC_time;
	
}

//print the date from RTC
function str_rtc_date(){

	$RTC_time_and_date=readFIFO(FIFO_RTC);
	if(strlen($RTC_time_and_date)>0){
		$RTC_date = substr ( $RTC_time_and_date , strpos($RTC_time_and_date," ")+1 , strlen($RTC_time_and_date) ); 
	}else{
		$RTC_date="00/00/00";
	}
	return $RTC_date;
	
}

//it substitute the spaces with underscore and check if the legth is lower of the maximun.
//The maximum is 128 characters (see the constant MAX_LEN_NET_NAME into /etc/rfpi/lib/rfberrypi.h)
function validate_network_name(&$network_name){
	
	//manipulating the string:
	$network_name = str_replace(' ', '_', $network_name); //substituting the spaces with underscore
	if(strlen($network_name)>127){ //checking the length is lower than the maximum of 128 characters
		$network_name = substr($network_name, 0, 127);
	}

	return $network_name;
}


//it substitute the spaces with underscore and check if the legth is lower of the maximun.
//The maximum is 50 characters (see the constant MAX_LEN_NAME into /etc/rfpi/lib/rfberrypi.h)
function validate_name(&$name){
	
	//manipulating the string:
	$name = str_replace(' ', '_', $name); //substituting the spaces with underscore
	if(strlen($name)>49){ //checking the length is lower than the maximum of 128 characters
		$name = substr($name, 0, 49);
	}

	return $name;
}


//it print the table with the flags to changa language
function printFlagsBar(){
	echo '<table class="table_flags">';

		echo '<tr class="table_title_field_line_flags">';
			
			//EN
			echo '<form name="EN_form" action="./config/save_language.php" method=GET>';
			echo '<input type=hidden name="language" value="EN">';
			echo '<input type="image" name="submit" src="'.DIRECTORY_IMG_FLAGS.'/EN.png" border="0" alt="Submit" />';
			echo '</form>';
			
			//IT
			echo '<form name="IT_form" action="./config/save_language.php" method=GET>';
			echo '<input type=hidden name="language" value="IT">';
			echo '<input type="image" name="submit" src="'.DIRECTORY_IMG_FLAGS.'/IT.png" border="0" alt="Submit" />';
			echo '</form>';
			
			//SP
			echo '<form name="SP_form" action="./config/save_language.php" method=GET>';
			echo '<input type=hidden name="language" value="SP">';
			echo '<input type="image" name="submit" src="'.DIRECTORY_IMG_FLAGS.'/SP.png" border="0" alt="Submit" />';
			echo '</form>';
				
			//FR
			echo '<form name="FR_form" action="./config/save_language.php" method=GET>';
			echo '<input type=hidden name="language" value="FR">';
			echo '<input type="image" name="submit" src="'.DIRECTORY_IMG_FLAGS.'/FR.png" border="0" alt="Submit" />';
			echo '</form>';
		
		echo '</tr>';
	echo '</table>';
}


//read the config file for a peri and then push into the arry the value
//this is the way to call thi function:
//	$array_input_to_show = array();
//	$array_output_to_show = array();
//	$array_function_to_show = array();
//	$array_input_formula_to_show = array();

//	create_array_from_config_file($address_peri, $idperipheral, 
//									$array_input_to_show, 
//									$array_output_to_show, 
//									$array_function_to_show, 
//									$array_input_formula_to_show
//									);
function create_array_from_config_file(&$address_peri,&$idperipheral, 
											&$array_input_to_show, 
											&$array_output_to_show, 
											&$array_function_to_show, 
											&$array_input_formula_to_show
											){
	
	$file_path = DIRECTORY_CONFIG_PERI . $address_peri . FILE_NAME_CONFIG_PERI . $idperipheral . FILE_EXTENSION_CONFIG_PERI;
	//echo $file_path;
	if(file_exists($file_path)!==TRUE){ 
		//the file does not exist, thus it create array with all value=1
		$cont=0;
		while($cont<20){
			array_push($array_input_to_show, 1);
			array_push($array_output_to_show, 1);
			array_push($array_function_to_show, 1);
			array_push($array_input_formula_to_show, 0);
			
			$cont++;
		}
	}else{ 
		$cont_input_to_show = 0;
		$cont_output_to_show = 0;
		$cont_function_to_show = 0;
		$cont_function_formula_to_show = 0;
		$cont_lines = 0;

		$handle_file = fopen($file_path, 'r');
		while(feof($handle_file)!==TRUE && $cont_lines<100){ 
			$line_file=fgets($handle_file, 40);
			//echo $cont_lines . " ";
			$cont_lines++;
			
			//getting input to show
			if (strpos($line_file,'INPUT_' . (string)($cont_input_to_show)) !== false) {
				if (strpos($line_file,"YES") !== false) {
					//$array_input_to_show[$cont_input_to_show] = 1;
					array_push($array_input_to_show, 1);
				}
				$cont_input_to_show++;
			}else
			
			//getting output to show
			if (strpos($line_file,'OUTPUT_' . (string)$cont_output_to_show ) !== false) {
				if (strpos($line_file,"YES") !== false) {
					//$array_output_to_show[$cont_output_to_show] = 1;
					array_push($array_output_to_show, 1);
				}
				$cont_output_to_show++;
			}else
			
			//getting function to show
			if (strpos($line_file,'FUNCTION_' . (string)$cont_function_to_show ) !== false) {
				if (strpos($line_file,"YES") !== false) {	
					//$array_function_to_show[$cont_function_to_show] = 1;
					array_push($array_function_to_show, 1);
				}
				$cont_function_to_show++;
			}else
			
			//getting function formula to show
			if (strpos($line_file,'INPUT_FORMULA_' . (string)$cont_function_formula_to_show ) !== false) {
				//if (strpos($line_file,"YES") !== false) 
				//	$array_input_formula_to_show[$cont_function_formula_to_show] = 1;
				
				$position_equal = strpos($line_file,'=') + 1;

				$position_end = strlen($line_file);
				
				$str_sub_temp = substr($line_file, $position_equal, $position_end );
				
				//$array_input_formula_to_show[$cont_function_formula_to_show] = (int)str_replace(' ', '', $str_sub_temp);
	
				$int_function_to_show = (int)(str_replace(' ', '', $str_sub_temp));
				
				array_push($array_input_formula_to_show, $int_function_to_show);
				
				$cont_function_formula_to_show++;
			}

		}
		fclose($handle_file);
		
	}
	
	
}



?>