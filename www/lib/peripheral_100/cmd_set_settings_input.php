<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/01/2020

Description: it send the command to set the settings of the Input Duty

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
		
//----------------------------------END INCLUDE--------------------------------------------//


//---------------------------------------------------------------------------------------//
//		Strings for languages

$lang_setting_sent="Setting sent!";
if($_SESSION["language"]=="IT"){
	$lang_setting_sent="Impostazioni inviate!";
}else if($_SESSION["language"]=="FR"){	
	$lang_setting_sent="Param&egrave;tres envoy&eacute;s!";
}else if($_SESSION["language"]=="SP"){	
	$lang_setting_sent="Configuraci&oacute;n enviados!";
}

//---------------------------------------------------------------------------------------//


$position_id=$_GET['position_id'];
$address_peri=$_GET['address_peri'];
$id_hex_special_function=$_GET['id_hex_special_function'];
$redirect_page = $_GET['redirect_page'];





//GETTING CUSTOM INFORMATIONS

$fun_input_ctrl_output[8];
$byte_fun_input_ctrl_output[16];
for($i=0;$i<8;$i++)
	$fun_input_ctrl_output[$i]=0;
for($i=0;$i<16;$i++)
	$byte_fun_input_ctrl_output[$i]=' ';
$fun8=0;
$fun9=0;
$byte_fun8=0;
$byte_fun9=0;
$l=0;
for($i=0;$i<8;$i++){
	$status_input_trigger[$i] = $_GET['status_input_trigger'.$i];
	$input_trigger[$i] = $_GET['input_trigger'.$i];
	$output_to_control[$i] = $_GET['output_to_control'.$i];
	$status_output_to_set[$i] = $_GET['status_output_to_set'.$i];
	
	
	$fun_input_ctrl_output[$i] = (intval($output_to_control[$i], 10)); //id output to control
	$fun_input_ctrl_output[$i] |= (intval($input_trigger[$i], 10)<<3); //id input to use for trigger
	$fun_input_ctrl_output[$i] |= (intval($status_output_to_set[$i], 10)<<6); //status to set on the selected output
	
	
	$byte_temp = dechex( $fun_input_ctrl_output[$i] );
	if(strlen($byte_temp)<2) $byte_temp = "0" . $byte_temp;
	
	$byte_fun_input_ctrl_output[$l] = $byte_temp[0];
	$byte_fun_input_ctrl_output[$l+1] = $byte_temp[1];
	$l+=2;

	//echo $fun_input_ctrl_output[$i] ;
	//echo '<br>';
	
	/*echo $status_input_trigger[$i];
	echo ' ';
	echo $input_trigger[$i];
	echo ' ';
	echo $output_to_control[$i];
	echo ' ';
	echo $status_output_to_set[$i];
	echo '<br>';
	*/
	
	
	
	if($i<4){
		$fun8 |= (intval($status_input_trigger[$i], 10)<<(2*$i));
	}else{
		$fun9 |= (intval($status_input_trigger[$i], 10)<<(2*($i-4)));
	}
	
	//echo '<br><br>';
	
}

$byte_fun8 = dechex( $fun8 );
if(strlen($byte_fun8)<2) $byte_fun8 = "0" . $byte_fun8;
//echo 'byte8='.$byte_fun8;
//echo '<br>';

$byte_fun9 = dechex( $fun9 );
if(strlen($byte_fun9)<2) $byte_fun9 = "0" . $byte_fun9;
//echo 'byte9='.$byte_fun9;

//echo '<br><br>';



//building the string command and writing into fifo command:
$TAG0="DATA";
$TAG1="RF";
$TAG2=$address_peri;
$TAG3="524266" . $id_hex_special_function; for($i=0;$i<16;$i++){$TAG3=$TAG3 . $byte_fun_input_ctrl_output[$i];} $TAG3=$TAG3 . $byte_fun8 . $byte_fun9 .  "2E2E";

$cmd_to_write_into_fifo = $TAG0." ".$TAG1." ".$TAG2." ".$TAG3." "; //the space at the end is important

$cmd_to_write_into_fifo = strtoupper ($cmd_to_write_into_fifo);//make the string upper case

writeFIFO(FIFO_GUI_CMD, $cmd_to_write_into_fifo);
	
//echo $cmd_to_write_into_fifo;
	

	
	
//redirecting to home
echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';
echo '<br>';

//echo 'Setting sent!';
echo $lang_setting_sent;

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="'.$lang_btn_home.'" class="btn_pag">';
echo '</form>';
echo '</p>';


//redirecting to next page
$next_page = "../.././index.php";
if($redirect_page!=""){
	$next_page = $redirect_page;
}

echo '<script type="text/javascript">';
echo 'setTimeout("'; 
	echo "location.href = '". $next_page . "';";
echo '", 1500);'; 
echo '</script>';

echo '</div>';
echo '</body></html>';

?>



