<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		25/05/2019

Description: it is the library to build the control panel for the 100th peripheral


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

//		function peripheral_100($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput);
												
												
//--------------------------------END FUNCTIONS DESCRIPTIONS-----------------------------------//


//---------------------------------BEGIN INCLUDE-------------------------------------------//
//		Specific library for the Peri_100
		include './lib/peripheral_100/peri_100_lib.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//





function peripheral_100($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){


	//---------------------------------------------------------------------------------------//
	//		Strings for languages

	$lang_btn_load_json = "Load Json";
	$lang_btn_build_json = "Build Json";
	$lang_btn_trigger = "Temperature";
	$lang_btn_input = "Input";
	$lang_temperature="Temperature: ";
	$lang_btn_thermostat="Thermostat";
	$lang_btn_rgb="Set RGB";
	$lang_msg_turn_on="Turn ON";
	$lang_msg_turn_off="Turn OFF";
	if($_SESSION["language"]=="IT"){
		$lang_btn_load_json = "Carica Json";
		$lang_btn_build_json = "Costruisci Json";
		$lang_btn_trigger = "Temperatura";
		$lang_btn_input = "Entrate";
		$lang_temperature="Temperatura: ";
		$lang_btn_thermostat="Termostato";
	}else if($_SESSION["language"]=="FR"){
		$lang_btn_load_json = "Charge Json";
		$lang_btn_build_json = "Costruire Json";
		$lang_btn_trigger = "Temp&eacute;rature";
		$lang_btn_input = "Entr&eacute;e";
		$lang_temperature="Temp&eacute;rature: ";
		$lang_btn_thermostat="Thermostat";
	}else if($_SESSION["language"]=="SP"){
		$lang_btn_load_json = "Carga Json";
		$lang_btn_build_json = "Costruir Json";
		$lang_btn_trigger = "Temperatura";
		$lang_btn_input = "Entrada";
		$lang_temperature="Temperature: ";
		$lang_btn_thermostat="Termostato";
	}

	//---------------------------------------------------------------------------------------//

	$array_input_to_show = array();
	$array_output_to_show = array();
	$array_function_to_show = array();
	$array_input_formula_to_show = array();
	create_array_from_config_file($address_peri, $idperipheral, 
									$array_input_to_show, 
									$array_output_to_show, 
									$array_function_to_show, 
									$array_input_formula_to_show
									);
									
	//echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_100 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td align=center>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'ant_grey.png" class="img_ant" alt="'.DEFINE_lang_msg_no_communication.'"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'ant_green.png" class="img_ant" alt="'.$lang_msg_connected.'"> ';
	}
	echo '<br>';
	echo '<br>';
	
	//echo '</td>';
	//echo '<td>&nbsp';
	echo $name;
	echo '</td>';
	
	echo '</tr></table>';
	echo '</td>';  
				
	//print the name of the input and the status
	echo '<td>';
	$l=0;
	while ($l<$numInput) { 

		//if(($l==0 || $l==1 || $l==2 || $l==3) && $array_input_to_show[$l]==1){
			echo 'IN' . $l;
			
			if($arrayStatusInput[$l]==-1){
				echo '= &#63;';
			}else{	
				echo '= ';
				echo $arrayStatusInput[$l];
			}
			/*	
			if($arrayStatusInput[$l]==1){
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
				echo '= 1';
			}else if($arrayStatusInput[$l]==0){
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
				echo '= 0';
			}else{
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
				echo '= &#63;';
			}*/
			echo '<br>';
		//}
		$l++;
		
	}
	
	echo '</td>';
	
				
	//print the name of the output and the status
	echo '<td>';
	

	
	/*
	echo '<script src="sliders.js"></script>';
	
	
	echo '<div id="demo" class="example">';
	
	
	echo '<div class="sliders yui3-skin-sam">';
	//echo '<div class="sliders">';
    echo '<dl>';
    echo '    <dt>R: <span id="r-val" class="val"></span></dt><dd id="r-slider"></dd>';
    echo '    <dt>G: <span id="g-val" class="val"></span></dt><dd id="g-slider"></dd>';
    echo '    <dt>B: <span id="b-val" class="val"></span></dt><dd id="b-slider"></dd>';
	echo '	</dl>';
	echo '</div>';
	echo '<div class="color"></div>';
	echo '<div class="output">';
	echo '	<dl>';
	echo '		<dt>Hex:</dt><dd id="hex"></dd>';
	echo '		<dt>RGB:</dt><dd id="rgb"></dd>';
	echo '		<dt>HSL:</dt><dd id="hsl"></dd>';
	echo '	</dl>';
	echo '</div>';
	

  echo '<script type="text/JavaScript">';
    echo '        YUI().use("slider", "color", function(Y){';
                // sliders
    echo 'var rSlider = new Y.Slider({ min: 0, max: 255, value: Math.round(Math.random()*255) }),';
     echo '   gSlider = new Y.Slider({ min: 0, max: 255, value: Math.round(Math.random()*255) }),';
     echo '   bSlider = new Y.Slider({ min: 0, max: 255, value: Math.round(Math.random()*255) }),';

        // slider values
     echo '   rVal = Y.one("#r-val"),';
    echo '    gVal = Y.one("#g-val"),';
    echo '    bVal = Y.one("#b-val"),';

        // color strings
    echo '    hex = Y.one("#hex"),';
     echo '   rgb = Y.one("#rgb"),';
     echo '   hsl = Y.one("#hsl"),';

        // color chip
    echo '    color = Y.one(".color");';

    // render sliders
    echo 'rSlider.render("#r-slider");';
    echo 'gSlider.render("#g-slider");';
    echo 'bSlider.render("#b-slider");';


                // register update events
    echo 'rSlider.after("thumbMove", function(e) {';
    echo '    rVal.set("text", rSlider.get("value"));';
    echo '    updateColors();';
    echo '});';
    echo 'gSlider.after("thumbMove", function(e) {';
    echo '    gVal.set("text", gSlider.get("value"));';
    echo '    updateColors();';
    echo '});';
    echo ' bSlider.after("thumbMove", function(e) {';
    echo '    bVal.set("text", bSlider.get("value"));';
    echo '    updateColors();';
    echo '});';

    // update the colors strings
    echo 'function updateColors() {';
    echo '    var r = rSlider.get("value"),';
    echo '        g = gSlider.get("value"),';
    echo '        b = bSlider.get("value"),';
    echo '        rgbStr = Y.Color.fromArray([r,g,b], Y.Color.TYPES.RGB);';

    echo '    color.setStyle("backgroundColor", rgbStr);';

    echo '    rgb.set("text", rgbStr);';

    echo '    hex.set("text", Y.Color.toHex(rgbStr));';
    echo '   hsl.set("text", Y.Color.toHSL(rgbStr));';
    echo '}';


            
    echo 'rVal.set("text", rSlider.get("value"));';
    echo 'gVal.set("text", gSlider.get("value"));';
    echo 'bVal.set("text", bSlider.get("value"));';
	echo ' updateColors();';

    echo '        });';
    echo '    </script>';
	
	
	echo '</div>';


*/


	$contAnalogueOutputs = 0;
	$firstOutputIsAnalogue = 0;
	while ($l<$numOutput) {
		if($arrayNameOutput[$l]=="ANALOGUE"){
			if($l==0){
				$firstOutputIsAnalogue = 1;
			}
			$contAnalogueOutputs++;
		}
		$l++;
	}
	$sem_RGB_Shield_connected = 0;
	if(/*$firstOutputIsAnalogue == 1 &&*/ $contAnalogueOutputs >= 3){	//if the first output is analogue and there are other 2 output analogue then is the RGB shield connected
		$sem_RGB_Shield_connected = 1;
	}
	
	$l=0;
	while ($l<$numOutput) {
		//echo $arrayNameOutput [$l];
		//echo ':';
	
		
		if($array_output_to_show[$l]==1){
			echo '<script type="text/JavaScript">';
			echo 'function change' . $id . '_' . $l . '(value){';
			echo 'document.set_' . $id . '_output_' . $l . '.output_value.value=value;';
			echo 'document.set_' . $id . '_output_' . $l . '.submit();';
			echo '}';
			echo '</script>';
		
			echo '<form name="set_' . $id . '_output_' . $l . '" action="set_output.php" method=GET>';
			//echo $arrayNameOutput[$l];
			//echo ' ';
			echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
			echo '<input type=hidden name="output_id" value="' . $l . '">';
			if($arrayNameOutput[$l]=="ANALOGUE"){
				if($sem_RGB_Shield_connected == 0){
					echo '<input type=text name="output_value" value="' . $arrayStatusOutput [$l] . '" class="text_value_io">';
					echo '<input type=submit value="Set Output">';
					//echo '<br>';
				}
			}else{
				echo '<input type=hidden name="output_value" value="' . $arrayStatusOutput[$l] . '">';
				if($arrayStatusOutput[$l]==0){
					//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'on.png" onclick="change' . $id . '_' . $l . '(1)"  class="img_button_square_enable" alt="'.$lang_msg_turn_on.'"> ';
					echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'switch_off.png" onclick="change' . $id . '_' . $l . '(1)"  class="img_switch_off" alt="'.$lang_msg_turn_on.'"> ';
					//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'off.png"  class="img_button_square_disable" alt="'.$lang_msg_turn_off.'"> ';
				}else{
					//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'on.png" class="img_button_square_disable" alt="'.$lang_msg_turn_on.'"> ';
					//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'off.png" onclick="change' . $id . '_' . $l . '(0)"  class="img_button_square_enable" alt="'.$lang_msg_turn_off.'"> ';
					echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'switch_on.png" onclick="change' . $id . '_' . $l . '(0)"  class="img_switch_on" alt="'.$lang_msg_turn_off.'"> ';
				}
				
				
				if($arrayStatusOutput[$l]==1){
					echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
				}else if($arrayStatusOutput[$l]==0){
					echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
				}else{
					echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
				}
				//echo '<br><br>';
			
			}
			echo '</form>';
		}

		$l++;
	}
	
	if($sem_RGB_Shield_connected == 1){	//if the first output is analogue and there are other 2 output analogue then is the RGB shield connected
		
		echo '<form name="peri_100_btn_rgb_functions_'.$id.'" action="./lib/peripheral_100/cmd_send_rgb_data.php" method=GET>';
		$value_hex_RED_LED =  convert_byte_to_2ChrHex($arrayStatusOutput[$numOutput-3]); //"00";
		$value_hex_GREEN_LED = convert_byte_to_2ChrHex($arrayStatusOutput[$numOutput-1]); //"00";
		$value_hex_BLUE_LED = convert_byte_to_2ChrHex($arrayStatusOutput[$numOutput-2]); //"00";
		
		echo '<h2>RGB=';
		echo '  <input type="color" name="favcolor" value="#' . $value_hex_RED_LED . $value_hex_GREEN_LED . $value_hex_BLUE_LED .'" onchange="change_RGB_'.$id.'(0)">';
		echo '</h2>';
		$byte_to_convert=$numOutput-3;
		$id_hex_special_function = convert_byte_to_2ChrHex($byte_to_convert); //"00"; //hexadecimal format. example 0x02 as to be written as "02"
		//echo $id_hex_special_function;
		echo '<input type=hidden name="position_id" value="'.$id.'">';
		echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
		echo '<input type=hidden name="id_hex_special_function" value="'.$id_hex_special_function.'">'; 
		echo '<input type=hidden name="TAG0" value="DATA">'; 				//Command
		echo '<input type=hidden name="TAG1" value="RF">'; 					//second parameter
		echo '<input type=hidden name="TAG2" value="'.$address_peri.'">';	//third parameter
		
		$str_TAG3 = "52426F" . $id_hex_special_function . "0018" . $value_hex_RED_LED . $value_hex_GREEN_LED . $value_hex_BLUE_LED . "2E2E2E2E2E2E2E"; 
		echo '<input type=hidden name="TAG3" value="'.$str_TAG3.'">';		//fourth parameter
		echo '<input type=hidden name="page_to_show_data" value="login.php">';
		//echo '<input type=text name="VALUECOLOR" value="'.$address_peri.'"><br>';	
		echo '<input type=hidden name="REDCOLOR" value="'.$value_hex_RED_LED.'">';	
		echo '<input type=hidden name="GREENCOLOR" value="'.$value_hex_GREEN_LED.'">';	
		echo '<input type=hidden name="BLUECOLOR" value="'.$value_hex_BLUE_LED.'">';
		
		//echo '<input type=button value="'.$lang_btn_rgb.'" onclick="change_RGB_'.$id.'(0)" class="btn_functions">';
		
		echo '<script type="text/JavaScript">';
		echo 'function change_RGB_'.$id.'(value){';
		echo 'var selected_color = document.peri_100_btn_rgb_functions_'.$id.'.favcolor.value;';
		echo 'selected_color = selected_color.toUpperCase();';
		echo 'var value_hex_RED_LED = selected_color[1]+selected_color[2];';
		echo 'var value_hex_GREEN_LED = selected_color[3]+selected_color[4];';
		echo 'var value_hex_BLUE_LED = selected_color[5]+selected_color[6];';
		//echo 'document.peri_100_btn_rgb_functions_'.$id.'.VALUECOLOR.value = selected_color;';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.REDCOLOR.value = value_hex_RED_LED;';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.GREENCOLOR.value = value_hex_GREEN_LED;';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.BLUECOLOR.value = value_hex_BLUE_LED;';
		echo 'var value_TAG3 = "52426F' . $id_hex_special_function . '0018"+value_hex_RED_LED+value_hex_GREEN_LED+value_hex_BLUE_LED+"2E2E2E2E2E2E2E";';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.TAG3.value = value_TAG3;';
		echo 'document.peri_100_btn_rgb_functions_'.$id.'.submit();';
		echo '}';
		echo '</script>';
		echo '</form>';
	}
	echo '</td>';
	
	//special functions
	echo '<td>';
	
	echo '<br>';
	
	//here the special functions
	
	
	if($numInput==0 && $numOutput==0 && $numInput==0 && $numInput==0) { 
	if($array_function_to_show[0]==1){
	
	//Button to load json configurations
	echo '<form name="peri_100_btn_load_json_'.$id.'" action="./lib/peripheral_100/select_file_to_upload.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	$id_hex_special_function = "00"; //hexadecimal format. example 0x02 as to be written as "02"
	echo '<input type=hidden name="id_hex_special_function" value="'.$id_hex_special_function.'">'; 
	echo '<input type=hidden name="TAG0" value="SENDJSONSETTINGS">'; 				//Command
	echo '<input type=hidden name="TAG1" value="'.$address_peri.'">'; 					//second parameter
	echo '<input type=hidden name="TAG2" value="'.FIFO_PATH.'">';	//third parameter
	$str_TAG3 = "NULL"; 
	echo '<input type=hidden name="TAG3" value="'.$str_TAG3.'">';		//fourth parameter
	echo '<input type=hidden name="page_to_show_data" value="show_settings_fifo_timer.php">';
	echo '<input type=submit value="'.$lang_btn_load_json.'" class="btn_functions">';
	echo '</form>';
	
	//Button to build json configurations
	echo '<a href="http://www.iotgemini.com/conf" target="_blank" class="btn_functions">'.$lang_btn_build_json.'</a>';
	echo '<br><br>';
	
	
	}
	}
	
	
	
	/*if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
	
	
  echo '<form name="upload_json" method="POST" action="./lib/peripheral_100/upload.php" enctype="multipart/form-data">';
  echo '  <div>';
  echo '    <span>Upload a File:</span>';
  echo '    <input type="file" name="uploadedFile" />';
  echo '  </div>';
  echo '  <input type="submit" name="uploadBtn" value="Upload" />';
  echo '</form>';
	*/
	
	echo '</td>';
}






function peripheral2_100($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){


	//---------------------------------------------------------------------------------------//
	//		Strings for languages

	$lang_btn_load_json = "Load Json2";
	$lang_btn_trigger = "Temperature";
	$lang_btn_input = "Input";
	$lang_temperature="Temperature: ";
	$lang_btn_thermostat="Thermostat";
	if($_SESSION["language"]=="IT"){
		$lang_btn_load_json = "Carica Json";
		$lang_btn_trigger = "Temperatura";
		$lang_btn_input = "Entrate";
		$lang_temperature="Temperatura: ";
		$lang_btn_thermostat="Termostato";
	}else if($_SESSION["language"]=="FR"){
		$lang_btn_load_json = "Charge Json";
		$lang_btn_trigger = "Temp&eacute;rature";
		$lang_btn_input = "Entr&eacute;e";
		$lang_temperature="Temp&eacute;rature: ";
		$lang_btn_thermostat="Thermostat";
	}else if($_SESSION["language"]=="SP"){
		$lang_btn_load_json = "Carga Json";
		$lang_btn_trigger = "Temperatura";
		$lang_btn_input = "Entrada";
		$lang_temperature="Temperature: ";
		$lang_btn_thermostat="Termostato";
	}

	//---------------------------------------------------------------------------------------//

	$array_input_to_show = array();
	$array_output_to_show = array();
	$array_function_to_show = array();
	$array_input_formula_to_show = array();
	create_array_from_config_file($address_peri, $idperipheral, 
									$array_input_to_show, 
									$array_output_to_show, 
									$array_function_to_show, 
									$array_input_formula_to_show
									);
									
	echo '<link rel="stylesheet" href="' . DIRECTORY_CSS_PERI_100 . 'peripheral.css" type="text/css" >';
	
	//echo '<td>' . $id . '</td>';  
	//echo '<td>' . $idperipheral . '</td>';  
	//echo '<td>' . $address_peri . '</td>'; 	
	echo '<td>';
	
	echo '<table><tr>';
	echo '<td align=center>';
	
	if($arrayStatusInput[0]==-1){
		echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'ant_grey.png" class="img_ant" alt="'.DEFINE_lang_msg_no_communication.'"> ';
	}else{
		echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'ant_green.png" class="img_ant" alt="'.$lang_msg_connected.'"> ';
	}
	echo '<br>';
	echo '<br>';
	
	//echo '</td>';
	//echo '<td>&nbsp';
	echo $name;
	echo '</td>';
	
	echo '</tr></table>';
	echo '</td>';  
				
	//print the name of the input and the status
	echo '<td>';
	$l=0;
	while ($l<$numInput) { 

		//if(($l==0 || $l==1 || $l==2 || $l==3) && $array_input_to_show[$l]==1){
			echo 'IN' . $l;
			
			if($arrayStatusInput[$l]==-1){
				echo '= &#63;';
			}else{	
				echo '= ';
				echo $arrayStatusInput[$l];
			}
			/*	
			if($arrayStatusInput[$l]==1){
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
				echo '= 1';
			}else if($arrayStatusInput[$l]==0){
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
				echo '= 0';
			}else{
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
				echo '= &#63;';
			}*/
			echo '<br>';
		//}
		$l++;
		
	}
	
	echo '</td>';
	
				
	//print the name of the output and the status
	echo '<td>';
	$l=0;
	while ($l<$numOutput) {
		//echo $arrayNameOutput [$l];
		//echo ':';
		echo '<script type="text/JavaScript">';
		echo 'function change' . $id . '_' . $l . '(value){';
		echo 'document.set_' . $id . '_output_' . $l . '.output_value.value=value;';
		echo 'document.set_' . $id . '_output_' . $l . '.submit();';
		echo '}';
		echo '</script>';
		
		echo '<form name="set_' . $id . '_output_' . $l . '" action="set_output.php" method=GET>';
		echo '<input type=hidden name="peripheral_id" value="' . $id . '">';
		echo '<input type=hidden name="output_id" value="' . $l . '">';
		echo '<input type=hidden name="output_value" value="' . $arrayStatusOutput[$l] . '">';
		
		
		/*
		if($array_output_to_show[$l]==1){
			if($arrayStatusOutput[$l]==0){
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'on.png" onclick="change' . $id . '_' . $l . '(1)"  class="img_button_square_enable" alt="'.$lang_msg_turn_on.'"> ';
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'switch_off.png" onclick="change' . $id . '_' . $l . '(1)"  class="img_switch_off" alt="'.$lang_msg_turn_on.'"> ';
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'off.png"  class="img_button_square_disable" alt="'.$lang_msg_turn_off.'"> ';
			}else{
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'on.png" class="img_button_square_disable" alt="'.$lang_msg_turn_on.'"> ';
				//echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'off.png" onclick="change' . $id . '_' . $l . '(0)"  class="img_button_square_enable" alt="'.$lang_msg_turn_off.'"> ';
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'switch_on.png" onclick="change' . $id . '_' . $l . '(0)"  class="img_switch_on" alt="'.$lang_msg_turn_off.'"> ';
			}
			
			
			if($arrayStatusOutput[$l]==1){
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_green.png" class="img_led" alt="'.$lang_msg_on.'"> ';
			}else if($arrayStatusOutput[$l]==0){
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_red.png" class="img_led" alt="'.$lang_msg_off.'"> ';
			}else{
				echo '<img src="' . DIRECTORY_IMG_PERI_100 . 'led_grey.png" class="img_led" alt="'.DEFINE_lang_msg_no_communication.'"> ';
			}
		}*/
		
		echo '<input type=text name="output_value" value="' . $arrayStatusOutput [$l] . '" class="text_value_io">';
		echo '<input type=submit value="Set Output">';
					
					
		echo '</form>';
		
		$l++;
	}
	echo '</td>';
	
	//special functions
	echo '<td>';
	
	echo '<br>';
	
	//here the special functions
	
	if($numInput==0 && $numOutput==0 && $numInput==0 && $numInput==0) { 
	
	//Button to load json configurations
	if($array_function_to_show[0]==1){
	echo '<form name="peri_100_btn_load_json_'.$id.'" action="./lib/peripheral_100/select_file_to_upload.php" method=GET>';
	echo '<input type=hidden name="position_id" value="'.$id.'">';
	echo '<input type=hidden name="address_peri" value="'.$address_peri.'">';
	$id_hex_special_function = "00"; //hexadecimal format. example 0x02 as to be written as "02"
	echo '<input type=hidden name="id_hex_special_function" value="'.$id_hex_special_function.'">'; 
	echo '<input type=hidden name="TAG0" value="SENDJSONSETTINGS">'; 				//Command
	echo '<input type=hidden name="TAG1" value="'.$address_peri.'">'; 					//second parameter
	echo '<input type=hidden name="TAG2" value="'.FIFO_PATH.'">';	//third parameter
	$str_TAG3 = "NULL"; 
	echo '<input type=hidden name="TAG3" value="'.$str_TAG3.'">';		//fourth parameter
	echo '<input type=hidden name="page_to_show_data" value="show_settings_fifo_timer.php">';
	echo '<input type=submit value="'.$lang_btn_load_json.'" class="btn_functions">';
	echo '</form>';
	
	
	//Button to build json configurations
	echo '<a href="http://www.iotgemini.com/conf" target="_blank" class="btn_functions">'.$lang_btn_build_json.'</a>';
	
	
	}
	
	}
	
	
	
	/*if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
	
	
  echo '<form name="upload_json" method="POST" action="./lib/peripheral_100/upload.php" enctype="multipart/form-data">';
  echo '  <div>';
  echo '    <span>Upload a File:</span>';
  echo '    <input type="file" name="uploadedFile" />';
  echo '  </div>';
  echo '  <input type="submit" name="uploadBtn" value="Upload" />';
  echo '</form>';
	*/
	
	echo '</td>';
}


?>