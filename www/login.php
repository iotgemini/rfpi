<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		16/05/2016

Description: ask for user and password

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

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<head>';
echo '</head>';
echo '<body>';
echo '<div class="div_home">';

echo '<p>';
echo '<img src="' . DIRECTORY_IMG . 'logo.png"  class="img_logo" alt="RFPI">';
echo '</p>';

echo '<form name="login" action="./login_check.php" method=POST>';
echo '	<br><input type="text" name="user" placeholder="Email ID" required autofocus />';
echo '	<br><br><input type="password" name="pwd" placeholder="Password" required /> ';
echo '	<br><br><input type="submit" value="'.$lang_btn_login.'" class="btn_cmd"/>';
echo '</form>';
//echo $_SERVER['PHP_SELF'];
//echo '	<br>';
//echo $_SESSION["login_yes"];
echo '</div>';

echo '</body></html>';

?>



