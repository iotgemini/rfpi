<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/05/2016

Description: it check user and password

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

//-------------------------------BEGIN DEFINE----------------------------------//

define("DEFAULT_PATH", "NO"); 			//this force the library rfberrypi.php to take the following paths
define("DIRECTORY_IMG", "/img/"); 		//redefined here for the library rfberrypi.php
define("DIRECTORY_CSS", "/css/"); 		//redefined here for the library rfberrypi.php

//-------------------------------END DEFINE----------------------------------//


//---------------------------------------------------------------------------------------//
//		library with all useful functions to use RFPI
		include './../lib/rfberrypi.php';  
		
//---------------------------------------------------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_error_space_typed_1 = "DO NOT TYPE SPACES INTO THE USER AND PASSWORD!";
$lang_error_space_typed_2 = "Relogin with the old User and Password and type new data.";
$lang_data_updated = "Data updated!";
$lang_db_not_exist = "Does not exist the database!";
if($_SESSION["language"]=="IT"){
	$lang_error_space_typed_1 = "NON DIGITARE SPAZI DENTRO USER E PASSWORD!";
	$lang_error_space_typed_2 = "Fai un nuovo accesso con User e Password vecchi e digitare nuovamente i dati.";
	$lang_data_updated = "Dati aggiornati!";
	$lang_db_not_exist = "Il database non esiste!";
}else if($_SESSION["language"]=="FR"){	
	$lang_error_space_typed_1 = "NE PAS TAPER DANS L&rsquo;ESPACE UTILISATEUR ET MOT DE PASSE!";
	$lang_error_space_typed_2 = "Connectez-vous &agrave; nouveau avec l&rsquo;ancien utilisateur et mot de passe et tapez les nouvelles donn&eacute;es.";
	$lang_data_updated = "Les donn&eacute;es mises &agrave; jour!";
	$lang_db_not_exist = "La base de donn&eacute;es n&rsquo;existe pas!";
}else if($_SESSION["language"]=="SP"){	
	$lang_error_space_typed_1 = "No escriba espacio de usuario y contrase&ntilde;a!";
	$lang_error_space_typed_2 = "Conectarse de nuevo con el viejo de usuario y contrase&ntilde;a y escribe nuevos datos.";
	$lang_data_updated = "Datos actualizados!";
	$lang_db_not_exist = "La base de datos no existe!";
}

//---------------------------------------------------------------------------------------//


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<head>';
echo '</head>';
echo '<body>';
echo '<div class="div_home">';

echo '<p>';
echo '<img src="' . DIRECTORY_IMG . 'logo.png"  class="img_logo" alt="RFPI">';
echo '</p>';

$user=$_POST['user'];
$pwd=$_POST['pwd'];

if(preg_match('/\s/',$user) || preg_match('/\s/',$pwd)){
	echo "<br><h2>";
	echo $lang_error_space_typed_1; //DO NOT TYPE SPACES INTO THE USER AND PASSWORD!
	echo "</h2><br>";
	echo $lang_error_space_typed_2;//Relogin with the old User and Password and type new data.
	echo "<br>";
	
	//button HOME
	echo '<p align=left>';
	echo '<form name="back" action="./login_to_security_settings.php" method=GET>';
	echo '<input type=submit value="'.$lang_btn_back.'" class="btn_pag">';
	echo '</form>';
	echo '</p>';
	echo '<br>';
}else

if(file_exists("./login.txt")){
	$myfile = fopen("./login.txt", "w"); 
	$txt = "1\r\n";
	fwrite($myfile, $txt);
	$txt = $user . "\r\n";
	fwrite($myfile, $txt);
	$txt = $pwd . "\r\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	//file_put_contents("./login.txt", $txt);
	echo "<br><h2>";
	echo $lang_data_updated ;
	echo "</h2>";
	echo '<script type="text/JavaScript">';
	echo 'setTimeout("'; echo "location.href = './../index.php';"; echo '", 3000); ';
	echo '</script>';
}else{
	echo "<br><h2>";
	echo $lang_db_not_exist; //Does not exist the database!
	echo "</h2>";
	echo '<script type="text/JavaScript">';
	echo 'setTimeout("'; echo "location.href = './../index.php';"; echo '", 3000); ';
	echo '</script>';
}



//button HOME
echo '<p align=left>';
echo '<form name="home" action="./../index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';





echo '</div>';

echo '</body></html>';


?>



