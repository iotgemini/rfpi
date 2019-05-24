<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		17/05/2016

Description: it is the library with all useful function to use RFberry Pi


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


//-------------------------------BEGIN INCLUDE----------------------------------//

	

//-------------------------------END INCLUDE----------------------------------//


//-------------------------------BEGIN FUNCTIONS DESCRIPTIONS----------------------------------//

//		function convertDataSignalAndDraw($numLine, $coefficientMoltiplication, &$arrayPacketDataSignal, $max_xpx_canvas);
					//it parse and convert the data given by the Sensore-Attuatore and contained into  $arrayPacketDataSignal and then draw the right signal
					//it echo a canvas with the signal
		
//		function drawSignal($numLine, $coefficientMoltiplication, &$arrayPacketDataSignal);
					//it just draw the signal from the data given by $arrayPacketDataSignal
					//it echo a canvas with the signal


												
//-------------------------------END FUNCTIONS DESCRIPTIONS----------------------------------//

//-------------------------------BEGIN DEFINE----------------------------------//

//define("DEFAULT_PATH", "NO"); 			//this force the library rfberrypi.php to take the following paths
//define("DIRECTORY_IMG", "/img/"); 		//redefined here for the library rfberrypi.php
//define("DIRECTORY_CSS", "/css/"); 		//redefined here for the library rfberrypi.php

define("DIRECTORY_IMG_PERI_4", "/img/peripheral/"); 		//where all default pictures for any peripheral are kept
define("DIRECTORY_CSS_PERI_4", "/css/"); 					//where all default style for any peripheral are kept

//-------------------------------END DEFINE----------------------------------//

//-------------------------------BEGIN INCLUDE CSS----------------------------------//

//include the CSS
//echo '<link rel="stylesheet" href="./css/peripheral.css" type="text/css" >';
//echo '<link rel="stylesheet" href="' . DIRECTORY_CSS . 'settings.css" type="text/css" >';

//-------------------------------END INCLUDE CSS----------------------------------//






//it parse and convert the data given by the Sensore-Attuatore and contained into  $arrayPacketDataSignal and then draw the right signal
//it echo a canvas with the signal
function convertDataSignalAndDraw($numLine, $coefficientMoltiplication, &$arrayPacketDataSignal, $max_xpx_canvas){
	
	$arrayPacketDataSignalConverted[sizeof($arrayPacketDataSignal)*strlen($arrayPacketDataSignal[0])];
	$usSignalData[sizeof($arrayPacketDataSignal)*strlen($arrayPacketDataSignal[0])];
	
	$totalNumDataSignal=0;
	for( $l = 0; $l < sizeof($arrayPacketDataSignal); $l++){
		for( $i = 0; $i < strlen($arrayPacketDataSignal[$l]); $i+=2){
			$strTemp = $arrayPacketDataSignal[$l][$i] . $arrayPacketDataSignal[$l][$i+1];
			$byteSignal = convert_2ChrHex_to_byte($strTemp) ;
			$usSignalData[$totalNumDataSignal] = $byteSignal;
			$totalNumDataSignal++;
			//echo $usSignalData[$l+$i]." ";
			//echo $arrayPacketDataSignal[$l][$i] . $arrayPacketDataSignal[$l][$i+1]." ";
		}
	}
	
	echo "<br><br>";
	
	//creating the array with the data converted
	for($l=0,$i=0;$i<$totalNumDataSignal;$i++){
				
		if($usSignalData[$i]==0){
			$i++;
			if($i<$totalNumDataSignal){
				if($l>0){
					$arrayPacketDataSignalConverted[$l-1] += $usSignalData[$i];
				}else{
					$arrayPacketDataSignalConverted[$l] += $usSignalData[$i];
					$l++;
				}
			}
		}else{
			$arrayPacketDataSignalConverted[$l] = $usSignalData[$i];
			$l++;
		}
		
	}
			
	$contNumSignals = $l;
	
	//for($i=0;$i<$totalNumDataSignal;$i++) echo $arrayPacketDataSignalConverted[$i]." ";
	
	
	$x_divider = 4; //used to resize the draw
	
	$max_x_px_canvas = $max_xpx_canvas;
	$max_y_px_canvas = 118;
	
	//Starting position
	$y_px_signal=60;
	$x_px_signal=20;
	
	$y_px_offset = 28;
	
	
	$x_px_text_offset = 1;
	$y_px_text_offset = 12;
	$y_px_text_offset2 = 12;
	
	echo '<canvas  id="canvas_signal_';
	echo $numLine;
	
	echo '" width="'.$max_x_px_canvas.'" height="'.$max_y_px_canvas.'" style="border:1px solid #d3d3d3;">';
	
	echo '</canvas><br>';
				
	$tempStrIdCanvas = "canvas_signal_";
	$tempStrIdCanvas = $tempStrIdCanvas . strval($numLine);
	
	echo '<script type="text/javascript">';
	echo 'var c=document.getElementById("';
	echo $tempStrIdCanvas;
	echo '");';
	echo 'var ctx=c.getContext("2d");';
	echo 'ctx.beginPath();';
	
	$Clock = 1;
	
	echo 'ctx.moveTo('.$x_px_signal.','.(($Clock*$y_px_signal)+$y_px_offset).');';
	$x_px_signal += 3;
	echo 'ctx.lineTo('.$x_px_signal.','.(($Clock*$y_px_signal)+$y_px_offset).');';
	
	$lasUpDownText=1;
	for( $l = 0; $l < $contNumSignals; $l++){

		$byteSignal = $arrayPacketDataSignalConverted[$l];
				
		$Clock = !$Clock;

		if($l%2==0)
			$lasUpDownText = !$lasUpDownText;

		echo 'ctx.strokeText("'.strval($byteSignal*$coefficientMoltiplication ).'uS",'.($x_px_signal+$x_px_text_offset).', '.((($y_px_signal+$y_px_offset)*$Clock)+$y_px_text_offset+($y_px_text_offset2*$lasUpDownText)).');';
		
		
		echo 'ctx.lineTo('.$x_px_signal.','.(($Clock*$y_px_signal)+$y_px_offset).');';
		
		$x_px_signal += ($byteSignal/$x_divider);
		echo 'ctx.lineTo('.$x_px_signal.','.(($Clock*$y_px_signal)+$y_px_offset).');';
					
	}
	
	echo 'ctx.stroke();';
	echo '</script>';
}




//it just draw the signal from the data given by $arrayPacketDataSignal
//it echo a canvas with the signal
function drawSignal($numLine, $coefficientMoltiplication, &$arrayPacketDataSignal){
	
	$x_divider = 4; //used to resize the draw
	
	$max_x_px_canvas = 800;
	
	//Starting position
	$y_px_signal=100;
	$x_px_signal=20;
	
	$y_px_offset = 20;
	
	$x_px_text_offset = 1;
	$y_px_text_offset = 15;
	
	echo '<canvas  id="canvas_signal_';
	echo $numLine;
	
	echo '" width="'.$max_x_px_canvas.'" height="150" style="border:1px solid #d3d3d3;">';
	
	echo '</canvas><br>';
				
	$tempStrIdCanvas = "canvas_signal_";
	$tempStrIdCanvas = $tempStrIdCanvas . strval($numLine);
	
	echo '<script type="text/javascript">';
	echo 'var c=document.getElementById("';
	echo $tempStrIdCanvas;
	echo '");';
	echo 'var ctx=c.getContext("2d");';
	echo 'ctx.beginPath();';
	
	$Clock = 1;
	
	echo 'ctx.moveTo('.$x_px_signal.','.(($Clock*$y_px_signal)+$y_px_offset).');';
	$x_px_signal += 3;
	echo 'ctx.lineTo('.$x_px_signal.','.(($Clock*$y_px_signal)+$y_px_offset).');';
	
	for( $l = 0; $l < sizeof($arrayPacketDataSignal); $l++){
		for( $i = 0; $i < strlen($arrayPacketDataSignal[$l]); $i++){
			$strTemp = $arrayPacketDataSignal[$l][$i] . $arrayPacketDataSignal[$l][$i+1];
			$byteSignal = convert_2ChrHex_to_byte($strTemp) ;
			//echo '-byte='; echo $byteSignal;
			//echo ' '.$strTemp;
			
			//if($byteSignal>0){
				
				$Clock = !$Clock;
				//echo 'ctx.strokeText("'.strval($byteSignal*$coefficientMoltiplication).' uS",'.($x_px_signal+$x_px_text_offset).', '.((($y_px_signal+$y_px_offset)/2)+($y_px_text_offset*$Clock)).');';
				echo 'ctx.strokeText("'.strval($byteSignal*$coefficientMoltiplication ).'uS",'.($x_px_signal+$x_px_text_offset).', '.((($y_px_signal+$y_px_offset)*$Clock)+$y_px_text_offset).');';
				
				echo 'ctx.lineTo('.$x_px_signal.','.(($Clock*$y_px_signal)+$y_px_offset).');';
				//$x_px_signal += ($byteSignal*$coefficientMoltiplication);
			//}			
			$x_px_signal += ($byteSignal/$x_divider);
			echo 'ctx.lineTo('.$x_px_signal.','.(($Clock*$y_px_signal)+$y_px_offset).');';
					
		}
	}
	
	echo 'ctx.stroke();';
	echo '</script>';
}




?>
