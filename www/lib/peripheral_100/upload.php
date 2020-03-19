<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		19/03/2020

Description: upload a file

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




//https://code.tutsplus.com/tutorials/how-to-upload-a-file-in-php-with-example--cms-31763

// get details of the uploaded file
$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
$fileName = $_FILES['uploadedFile']['name'];
$fileSize = $_FILES['uploadedFile']['size'];
$fileType = $_FILES['uploadedFile']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

/*
$allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');
if (in_array($fileExtension, $allowedfileExtensions)) {
...
}*/

// directory in which the uploaded file will be moved
//$uploadFileDir = '/var/www/lib/peripheral_100/';
$uploadFileDir = $TAG2;
$dest_path = $uploadFileDir . $newFileName;
 
if(move_uploaded_file($fileTmpPath, $dest_path))
{
	//echo "path=".$uploadFileDir . "<br>";
	$message ='File is successfully uploaded!';
	//rename($dest_path, $uploadFileDir . "newconfig.json");
	rename($dest_path, $uploadFileDir);
	$statusUpload = "OK";
  
	$page_to_show_data=$_GET['page_to_show_data'];

	$cmd_to_write_into_fifo = $TAG0." ".$TAG1." ".$TAG2." ".$TAG3." "; //the space at the end is important

	//echo $cmd_to_write_into_fifo . " <br>";
	writeFIFO(FIFO_GUI_CMD, $cmd_to_write_into_fifo);
	
	echo $message;
}
else
{
	$statusUpload = "KO";
	$message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
	echo '<br><p style="color:darkred">'.$message.'</p>';
}

//echo $message;


  	
	echo '<script type="text/javascript">';
	echo "setTimeout('";
	echo 'location.href = "/index.php"';
	echo "', 3000);";
	echo '</script>';


/*header('Location: ./status_upload.php?page_to_show_data='. $page_to_show_data 
						. '&status_upload='. $statusUpload 
						. '&address_peri=' . $address_peri
						. '&position_id=' . $position_id
						. '&redirect_page=' . $redirect_page
						. '&TAG0=' . $TAG0 
						. '&TAG1=' . $TAG1 
						. '&TAG2=' . $TAG2 
						. '&TAG3=' . $TAG3 

	);*/

	
echo '<br>';
echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';


/*
$json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

//var_dump(json_decode($json));
//var_dump(json_decode($json, true));

var_dump(json_decode($uploadFileDir . "config.json"));
*/


echo '</div>';

echo '</body></html>';

?>



