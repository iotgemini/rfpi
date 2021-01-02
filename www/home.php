<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/01/2021

Description: it shows the list of the linked peripherals.

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

$lang_time="Your Time";
if($_SESSION["language"]=="IT"){
	$lang_time="La tua ora";
}else if($_SESSION["language"]=="FR"){	
	$lang_time="Votre temps";
}else if($_SESSION["language"]=="SP"){	
	$lang_time="Su tiempo";
}

//---------------------------------------------------------------------------------------//


$refresh_time='';
if(file_exists("./config/refresh_time.txt")){
	$myfile = fopen("./config/refresh_time.txt", 'r');
	$refresh_time = fgets($myfile);
	fclose($myfile);
}
if($refresh_time === '')
	$refresh_time = 60;

$refresh_time = $refresh_time * 1000; //because the function setTimeout accept a value in milli seconds
 

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<head>';
echo '<script type="text/JavaScript">';

echo 'var timeRTC ="';
echo str_rtc_time();
echo '";';

	?> 	
	//var timeRTC ="11:dd:ff";
	var lastRtcTime;
	var secondRTC;
	var minuteRTC;
	var hourRTC;
	
	function startYourTime() {
		var today=new Date();
		var h=today.getHours();
		var m=today.getMinutes();
		var s=today.getSeconds();
		m = checkTime(m);
		s = checkTime(s);
		document.getElementById('ytime').innerHTML = ""+h+":"+m+":"+s;
		var t = setTimeout(function(){startYourTime()},500);
	}
	
	
	function RtcTime() {
		var today=new Date();
		var h=today.getHours();
		var m=today.getMinutes();
		s=today.getSeconds();
		
		if(s != lastRtcTime){
			lastRtcTime = s;
			
			secondRTC++;
			if(secondRTC>59){
				secondRTC = 0;
				minuteRTC++;
				if(minuteRTC>59){
					minuteRTC=0;
					hourRTC++;
					if(hourRTC>23){
						hourRTC=0;
					}
				}
			}
			
			var mm = checkTime(minuteRTC);
			var ss = checkTime(secondRTC);
			
			document.getElementById('rtc').innerHTML = "RTC time = "+hourRTC+":"+mm+":"+ss;
		}
		var t = setTimeout(function(){RtcTime()},500);
	}
	
	function startRtcTime() {
		startYourTime();
		
		if(timeRTC.substring(0, 2) != "NO"){ //if there is a RTC
			hourRTC=parseInt(timeRTC.substring(0, 2));
			minuteRTC=parseInt(timeRTC.substring(3, 5));
			secondRTC=parseInt(timeRTC.substring(6, 7));
			
			document.getElementById('rtc').innerHTML = "RTC time = "+hourRTC+":"+minuteRTC+":"+secondRTC;
			var t = setTimeout(function(){RtcTime()},500);
		}
	}

	function checkTime(i) {
		if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
		return i;
	}
	<?php
echo '</script>';
echo '</head>';
//echo '<body  onload="startRtcTime()">';
echo '<body>';
echo '<div class="div_home">';

//echo '<p>';
//echo '<img src="' . DIRECTORY_IMG . 'logo.png"  class="img_logo" alt="RFPI">';
//echo '</p>';

//it sends to the client what has been executed and then proceed with next instruction
ob_flush(); 
flush();

echo '<br>';
printToolsBar(); //it print the table with the tools. from ./lib/rfberrypi.php

/*echo '<table align="center">';
echo '<tr>';
echo '<td>';
	echo '<img src="' . DIRECTORY_IMG . 'logo.png"  width="45" height="45" alt="RFPI">';
echo '</td>';
echo '<td>';
	printToolsBar(); //it print the table with the tools. from ./lib/rfberrypi.php
echo '</td>';
echo '<td>';
	echo '<img src="' . DIRECTORY_IMG . 'logo.png"  width="45" height="45" alt="RFPI">';
echo '</td>';
echo '</tr>';
echo '</table>';
*/
	
/*
echo '<script>';
echo 'var currentLocation = window.location.href;';
echo 'var strURL = currentLocation; ';
echo 'strURL = strURL.slice(0, -9);  '; //the -9 take off /home.php
echo 'function redirectUI() {';
echo '	strURL = strURL + ":1880/ui";';
//echo '  document.getElementById("text1").innerHTML = strURL;';
echo 'window.location.href = strURL;';
echo '}';
echo 'function redirect81() {';
echo '	strURL = strURL + ":81";';
//echo '  document.getElementById("text1").innerHTML = strURL;';
echo 'window.location.href = strURL;';
echo '}';
echo '</script>';
echo '<table class="table_tools">';
		echo '<tr class="table_title_field_line">';
			echo '<input type="button" onclick="redirectUI()" value="UI" class="btn_bar">';
		echo '</tr>';
		echo '<tr class="table_title_field_line">';
			echo '<input type="button" onclick="redirect81()" value=":81" class="btn_bar">';
		echo '</tr>';
echo '</table>';
*/

echo '<br>';
/*echo '<table class="table_utility">';

		echo '<tr class="table_title_field_line">';
		
			//Music
			echo '<form name="music" action="./player/index.php" method=GET>';
			echo '<input type=hidden name="music" value="main">';
			echo '<input type=submit value="Music" class="btn_bar">';
			echo '</form>';
				
			//Cam
			echo '<form name="Cam" action="./cam/index.php" method=GET>';
			echo '<input type=hidden name="cam" value="main">';
			echo '<input type=submit value="Cam" class="btn_bar">';
			echo '</form>';
			
			//Pi Cam
			echo '<form name="Cam" action="./picam/index.php" method=GET>';
			echo '<input type=hidden name="cam" value="main">';
			echo '<input type=submit value="Pi Cam" class="btn_bar">';
			echo '</form>';
		
		echo '</tr>';
	echo '</table>';
	
echo '<br>';*/


printTablePeripheral(); //it prints a table with all data of all linked peripheral. from ./lib/rfberrypi.php


echo '<br>';

//print the time from RTC
//echo "RTC Time = ";
//echo str_rtc_time(); //function from ./lib/rfberrypi.php
echo '<div id="rtc"></div>';


//echo $lang_time;	
//echo '<div id="ytime"></div>';

echo '<br>';

//it print the table with the flags to changa language
printFlagsBar();
	
echo '<br><br>';

//echo 'Release ';
echo release_version(); //function from ./lib/rfberrypi.php


echo '<script type="text/JavaScript">';
	echo 'setTimeout("location.href = '; echo "'index.php'"; echo ';", '; echo $refresh_time; echo ' ); ';
echo '</script>';

echo '</div>';

echo '</body></html>';
?>


    





