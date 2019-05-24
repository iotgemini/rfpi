<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		08/09/2016

Description: it set the time on the RTC usign the command RTC SET hh:mm:ss

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

$page_to_redirect=$_GET["page_to_redirect"]; //get the link from the url

$str_rtc_time=$_GET["str_rtc_time"]; //get the time to set
$str_rtc_date=$_GET["str_rtc_date"]; //get the date to set

$str_rtc_date = str_replace('/', ':', $str_rtc_date); //substituting the / with :

//echo "RTC SET " . $str_rtc_time . " " . $str_rtc_date . " ";


if(preg_match('/\s/',$str_rtc_time)){
	header( 'Location: get_rtc_time.php?error=space&page_to_redirect='.$page_to_redirect ) ;
}else{
	if(preg_match('/\s/',$str_rtc_date)){
		writeFIFO(FIFO_GUI_CMD, "RTC SET " . $str_rtc_time . " 0 ");
	}else{
		//it will write into the fifoguicmd the tag=RTC, type=SET, and value=hh:mm:ss
		//example FIFO: RTC SET 12:59:00
		writeFIFO(FIFO_GUI_CMD, "RTC SET " . $str_rtc_time . " " . $str_rtc_date . " ");
	}
	
	if($page_to_redirect === '')
		header( 'Location: index.php' ) ;
	else	
		header( 'Location: '.$page_to_redirect.'.php' ) ;
}
?>