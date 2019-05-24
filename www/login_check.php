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


//---------------------------------------------------------------------------------------//
//		library with all useful functions to use RFPI
		include './lib/rfberrypi.php';  
//---------------------------------------------------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_authenticating="Authenticating .....";
$lang_db_does_not_exist = "The database does not exist!";
if($_SESSION["language"]=="IT"){
	$lang_authenticating="Autenticazione .....";
	$lang_db_does_not_exist = "Il database &egrave; inesistente!";
}else if($_SESSION["language"]=="FR"){	
	$lang_authenticating="Authentification .....";
	$lang_db_does_not_exist = "La base de donn&eacute;es n&rsquo;existe pas!";
}else if($_SESSION["language"]=="SP"){	
	$lang_authenticating="Autenticaci&oacute;n .....";
	$lang_db_does_not_exist = "La base de datos no existe!";
}

//---------------------------------------------------------------------------------------//



$user=$_POST['user'];
$pwd=$_POST['pwd'];

$_SESSION["login_yes"]=0;

$admin_user = "";
$admin_pwd = "";
if(file_exists("./config/login.txt")){
	echo "<br>" . $lang_authenticating; 

	$myfile = fopen("./config/login.txt", 'r');
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
		$_SESSION["login_yes"]=1;
	}
	
	header('Location: index.php') ;
}else{
	echo "<br><h2>" . $lang_db_does_not_exist . "</h2>"; 
}

?>



