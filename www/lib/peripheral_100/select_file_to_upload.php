<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		11/04/2019

Description: form where to select the file to upload

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
		include './../../lib/rfberrypi.php';  
//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$redirect_page = $_GET['redirect_page'];

$TAG0=$_GET['TAG0'];
$TAG1=$_GET['TAG1'];
$TAG2=$_GET['TAG2'];
$TAG3=$_GET['TAG3'];




echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';
echo '<br>';
echo '<br>';



	if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
	
	$link_page = 'upload.php?position_id='.$position_id.'&address_peri='.$address_peri.'&redirect_page='.$redirect_page.'&TAG0='.$TAG0.'&TAG1='.$TAG1.'&TAG2='.$TAG2.'&TAG3='.$TAG2;
	echo '<form name="upload_file" method="POST" action="'.$link_page.'" enctype="multipart/form-data">';
	echo '<div align=center>';
	echo '<span>Upload the Json: </span>';
	echo '<input type="file" name="uploadedFile" />';
	echo '<br><br><input type="submit" name="uploadBtn" value="Upload" class="btn_functions" />';
	echo '</div>';
	echo '</form>';
  	
	
echo '<br>';
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



