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

$lang_type_new_data="Type the new data:";
$lang_db_not_exist = "The database does not exist!";
if($_SESSION["language"]=="IT"){
	$lang_type_new_data="Digita i nuovi dati di login:";
	$lang_db_not_exist = "Il database è inesistente!";
}else if($_SESSION["language"]=="FR"){	
	$lang_type_new_data="Tapez les nouvelles donn&eacute;es de connexion:";
	$lang_db_not_exist = "La base de donn&eacute;es n&rsquo;existe pas!";
}else if($_SESSION["language"]=="SP"){	
	$lang_type_new_data="Escriba los nuevos datos de inicio de sesi&oacute;n:";
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


$admin_user = "";
$admin_pwd = "";
if(file_exists("./login.txt")){
	$myfile = fopen("./login.txt", 'r');
	if(feof($handle)!==TRUE) $security_level = fgets($myfile);
	if(feof($handle)!==TRUE) $admin_user = fgets($myfile);
	if(feof($handle)!==TRUE) $admin_pwd = fgets($myfile);
	fclose($myfile);

	//$admin_user = substr ( $admin_user , 0, $admin_user.lenght - 2 ); //cut the last character
	//$admin_pwd = substr ( $admin_pwd , 0, $admin_pwd.lenght - 2 ); //cut the last character
	
	$admin_user =  str_replace("\n", '', $admin_user);
	$admin_user =  str_replace("\r", '', $admin_user);
	$admin_user =  str_replace("/\s/", '', $admin_user);
	
	$admin_pwd =  str_replace("\n", '', $admin_pwd);
	$admin_pwd =  str_replace("\r", '', $admin_pwd);
	$admin_pwd =  str_replace("/\s/", '', $admin_pwd);

	if($admin_user==$user && $admin_pwd==$pwd && $admin_user!=="" && $admin_pwd!=="" && $user!="" && $pwd!=""){
		
		echo "<br><h2>";
		echo $lang_type_new_data;
		echo "</h2>";
		
		echo '<form name="login" action="./login_set_data.php" method=POST>';
		echo '	<br><input type="text" name="user" placeholder="Email ID" required autofocus />';
		echo '	<br><br><input type="password" name="pwd" placeholder="Password" required /> ';
		echo '	<br><br><input type="submit" value="'.$lang_btn_save.'" class="btn_cmd"/>';
		echo '</form>';
	}else{
		$_SESSION["login_yes"]=0;
		echo "<br><h2>Wrong ID or PWD!</h2>";
		echo '<script type="text/JavaScript">';
		echo 'setTimeout("'; echo "location.href = './../index.php';"; echo '", 3000); ';
		echo '</script>';
	}
}else{
		echo "<br><h2>";
		echo $lang_db_not_exist;
		echo "</h2>";
	
}

echo "<br>";

//button HOME
echo '<p align=left>';
echo '<form name="home" action="./../index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';

//header('Location: index.php') ;

echo '</div>';

echo '</body></html>';


?>



