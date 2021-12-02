<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		28/11/2021

Description: this is a panel where to setup the THERMOSTAT


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
//		Specific library for the Peri
		include './lib/peri_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$position_id=$_GET['position_id'];

$address_peri=$_GET['address_peri'];

$fw_version_peri = $_GET['fw_version_peri'];

$thermostat_enabled=$_GET['thermostat_enabled'];

$id_packet=$_GET['id_packet'];

$j=0;
while ($j<10) {
	$temperatures[$j]=$_GET['temperatures_'.$j];
	//echo $temperatures[$j]; echo '<br>';
	$j++;
}


echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<head>';
echo '<script type="text/JavaScript">';

echo 'var timeRTC ="';
echo str_rtc_time();
echo '";';

	
echo '//var timeRTC ="11:dd:ff";';
echo 'var lastRtcTime;';
echo '	var secondRTC;';
echo '	var minuteRTC;';
echo '	var hourRTC;';
	
echo '	function startYourTime() {';
echo '		var today=new Date();';
echo '		var h=today.getHours();';
echo '		var m=today.getMinutes();';
echo '		var s=today.getSeconds();';
echo '		m = checkTime(m);';
echo '		s = checkTime(s);';
echo '		document.getElementById("ytime").innerHTML = "Your time = "+h+":"+m+":"+s;';
echo '		var t = setTimeout(function(){startYourTime()},500);';
echo '	}';
	
	
echo '	function RtcTime() {';
echo '		var today=new Date();';
echo '		var h=today.getHours();';
echo '		var m=today.getMinutes();';
echo '		s=today.getSeconds();';
		
echo '		if(s != lastRtcTime){';
echo '			lastRtcTime = s;';
			
echo '			secondRTC++;';
echo '			if(secondRTC>59){';
echo '				secondRTC = 0;';
echo '				minuteRTC++;';
echo '				if(minuteRTC>59){';
echo '					minuteRTC=0;';
echo '					hourRTC++;';
echo '					if(hourRTC>23){';
echo '						hourRTC=0;';
echo '					}';
echo '				}';
echo '			}';
			
echo '			var mm = checkTime(minuteRTC);';
echo '			var ss = checkTime(secondRTC);';
			
echo '			document.getElementById("rtc").innerHTML = "RTC time = "+hourRTC+":"+mm+":"+ss;';
echo '		}';
echo '		var t = setTimeout(function(){RtcTime()},500);';
echo '	}';
	
echo '	function startRtcTime() {';
echo '		startYourTime();';
		
echo '		if(timeRTC.substring(0, 2) != "NO"){ //if there is a RTC';
echo '			hourRTC=parseInt(timeRTC.substring(0, 2));';
echo '			minuteRTC=parseInt(timeRTC.substring(3, 5));';
echo '			secondRTC=parseInt(timeRTC.substring(6, 7));';
			
echo '			document.getElementById("rtc").innerHTML = "RTC time = "+hourRTC+":"+minuteRTC+":"+secondRTC;';
echo '			var t = setTimeout(function(){RtcTime()},500);';
echo '		}';
echo '	}';

echo '	function checkTime(i) {';
echo '		if (i<10) {i = "0" + i};  // add zero in front of numbers < 10';
echo '		return i;';
echo '	}';
	
echo '	function setYourTime(){';
echo '		var setYourTime_today=new Date();';
echo '		var setYourTime_h=setYourTime_today.getHours();';
echo '		var setYourTime_m=setYourTime_today.getMinutes();';
echo '		var setYourTime_s=setYourTime_today.getSeconds();';
		
echo '		peri6_btn_set_temperature09.temperatures_5.value="" + setYourTime_h.toString();';
echo '		peri6_btn_set_temperature09.temperatures_6.value="" + setYourTime_m.toString();';
echo '		peri6_btn_set_temperature09.temperatures_7.value="" + setYourTime_s.toString();';
echo '	}';
	

echo '</script>';
echo '</head>';
echo '<body  onload="startRtcTime()">';
echo '<div class="div_home">';

echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<form name="peri6_btn_set_temperature09" action="./cmd_set_thermostat09_setting.php" method=GET>';
echo '<input type=hidden name="position_id" value="'. $position_id . '">';
echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
echo '<input type=hidden name="id_packet" value="'. $id_packet . '">';

echo '<table class="table_peripheral">';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral">HOURS</td>';  
echo '<td class="td_peripheral">SetPoints</td>';  
echo '</tr>';

$i=20; //show hours from 20 to 23
$max_to_show=4; //show 4 lines of temperature
if($id_packet==0) $i=0;  //show hours from 0 to 9
if($id_packet==1) $i=10; //show hours from 10 to 19
if($id_packet<2) $max_to_show=10; //show 10 lines

$j=0;
while ($j<$max_to_show) { 
	if($j%2==0){
		echo '<tr class="table_line_even">';
	}else{
		echo '<tr class="table_line_odd">';
	}
	echo '<td class="td_peripheral">Hour '.$i.'</td>'; 
	echo '<td class="td_peripheral" align=center>';
	echo '<input type="text" name="temperatures_'.$j.'" value="'; echo $temperatures[$j]; echo '" size="1" maxlength="3">';
	echo '&#176C';
	echo '</td>';
	echo '</tr>';
	$j++;
	$i++;
}
if($id_packet>1){
	echo '<tr class="table_line_even">';
	echo '<td class="td_peripheral">Trigger Offset</td>'; 
	echo '<td class="td_peripheral" align=center>';
	echo 'SetPoint - <input type="text" name="temperatures_4" value="'; echo $temperatures[4]; echo '" size="1" maxlength="3">';
	echo '&#176C';
	echo '<br>If temperature is lower than this value then<br>the relay is turned ON until reach the SetPoint.';
	echo '</td>';
	echo '</tr>';
	
	echo '<tr class="table_line_odd">';
	echo '<td class="td_peripheral">Time Now HH:MM:SS</td>'; 
	echo '<td class="td_peripheral" align=center>';
	echo '<input type="text" name="temperatures_5" value="'; echo $temperatures[5]; echo '" size="1" maxlength="2">';
	echo ':<input type="text" name="temperatures_6" value="'; echo $temperatures[6]; echo '" size="1" maxlength="2">';
	echo ':<input type="text" name="temperatures_7" value="'; echo $temperatures[7]; echo '" size="1" maxlength="2">';
	echo ' <input type=button value="Set your time" class="btn_pag" onclick="setYourTime();">';
	echo '</td>';
	echo '</tr>';
		
	//juist filling the data for the next page
	$j=8;
	while ($j<10) { 
		echo '<input type=hidden name="temperatures_'.$j.'" value="46">';
		$j++;
	}
		
	echo '<tr class="table_line_even">';

	echo '<td class="td_peripheral">Thermostat Enabled</td>'; 
	echo '<td class="td_peripheral" align=center>';
	if(intval($thermostat_enabled, 10)==0){
		echo '<input type="checkbox" name="thermostat_enabled" value="0" onchange="if(this.value==0) this.value=1; else this.value=0;">';
	}else{
		echo '<input type="checkbox" name="thermostat_enabled" value="1" onchange="if(this.value==0) this.value=1; else this.value=0;" checked>';
	}
	echo '</td>';
	echo '</tr>';
}

echo '<tr class="table_title_field_line">';
echo '<td colspan=6 align=center>';
echo '<input type=submit value="APPLY" class="btn_functions">';		
echo '</td>';
echo '</tr>';	

echo '</table>';

echo '</form>';

//END: READING THE FILE WHERE ALL SIGNALS ARE STORED AND BULDING THE TABLE


//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';


echo '<div id="rtc"></div>';
echo '<div id="ytime"></div>';

echo '</div>';

echo '</body></html>';
?>
