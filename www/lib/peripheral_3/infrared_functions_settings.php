<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		23/04/2015

Description: this is a pannel to manage the infrared signal acquired and others settings


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
//		Specific library for the Peri3: Sensore-Attuatore
		include './lib/peri3_lib.php';  

//		library with all useful functions to use RFPI
		include './../../lib/rfberrypi.php';  
		
//----------------------------------END INCLUDE--------------------------------------------//

$address_peri=$_GET['address_peri'];

$show_signal=$_GET['show_signal'];

$max_x_px_canvas=$_GET['max_x_px_canvas'];

if($max_x_px_canvas==="" || $max_x_px_canvas==0)
	$max_x_px_canvas=800;

echo '<html>';
echo ' <meta content="width=device-width, initial-scale=1" name="viewport"/>';
echo '<body>';
echo '<div class="div_home">';

echo '<br>';

//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '<br>';


//button << BACK
echo '<p align=center>';
echo '<a href="./infrared_functions.php?address_peri='.$address_peri.'" class="btn_cmd">&lt;&lt; BACK</a>';
echo '</p>';

/*
//button Save MEM
echo '<p align=center>';
//button Save RAM in MEM0
echo '<a href="./cmd_save_signal_in_mem0.php?address_peri='.$address_peri.'" class="btn_cmd">Save RAM in MEM0</a>';
//button Save RAM in MEM1
echo ' <a href="./cmd_save_signal_in_mem1.php?address_peri='.$address_peri.'" class="btn_cmd">Save RAM in MEM1</a>';
echo '</p>';
*/


//BEGIN: READING THE FILE WHERE ALL SIGNALS ARE STORED AND BULDING THE TABLE

echo '<table class="table_peripheral">';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral" colspan="3">';  


echo '<p align=left>';

//button SHOW SIGNALS
echo '<form name="home" action="./infrared_functions_settings.php" method=GET>';
echo 'Size Canvas: <input type=text name="max_x_px_canvas" value="'.strval($max_x_px_canvas).'" size="1">px ';
echo '<input type=hidden name="address_peri" value="' . $address_peri . '">';
echo '<input type=hidden name="show_signal" value="1">';
//echo '<br>';
echo '<input type=submit value="SHOW SIGNALS" class="btn_pag">';
echo '</form>';
 
//button HIDE SIGNALS
echo '<form name="home" action="./infrared_functions_settings.php" method=GET>';
echo '<input type=hidden name="max_x_px_canvas" value="'.strval($max_x_px_canvas).'" size="1">';
echo '<input type=hidden name="address_peri" value="' . $address_peri . '">';
echo '<input type=hidden name="show_signal" value="0">';
echo '<input type=submit value="HIDE SIGNALS" class="btn_pag">';
echo '</form>';

echo '</p>';

echo '</td>';  
echo '</tr>';

echo '<tr class="table_title_field_line">';
echo '<td class="td_peripheral">Name</td>';  
echo '<td class="td_peripheral">Signal</td>';  
echo '<td class="td_peripheral">CMD</td>';  
echo '</tr>';

if(file_exists("./db/".$address_peri."_db_infrared_signals.txt")){
	$handle = fopen("./db/".$address_peri."_db_infrared_signals.txt", 'r');
	$i=0; $t=0;

	while(feof($handle)!==TRUE){ 
	
		if($data=fgets($handle)){
				//echo '<tr>'; 
				
				//alternating the line colour
				if($i%2===0)
					echo '<tr class="table_line_even">';
				else
					echo '<tr class="table_line_odd">';
							
				echo '<td>';
				
				//get the name of the signal
				$maxLenLine=strlen($data);
				$j=0;
				$strName="";
				$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$strName=$strName . $data[$j];
						$j++;
					}
				}
				
				echo $strName;
				
				echo '</td>';
				echo '<td>';
				
				//get number of packets
				$j=$count+1;
				$strNum="";
				//$count=0;
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$strNum=$strNum . $data[$j];
						$j++;
					}
				}
				$numPackets = (int)$strNum; //number of packets
				//echo $numPackets;
				
				
				//get the coefficient of moltiplication
				$j=$count+1;
				$l=0; 
				$coefficientMoltiplication = 1;
				$strNum="";
				while ($j<$maxLenLine) {
					if($data[$j]===" "){
						$count=$j;
						$j=$maxLenLine;
					}else{
						$strNum=$strNum . $data[$j];
						$j++;
					}
				}
				$coefficientMoltiplication = (int)$strNum; //number of packets
				//echo $coefficientMoltiplication;
				
				
				//get all data/packets of the signal
				$j=$count+1;
				$l=0; 
				$arrayPacketDataSignal[$l] ="";
				$tempStrIdCanvas = "";
				while ($l<$numPackets && $j<$maxLenLine) {
					if($data[$j]===" "){ 
						$l++;
						$arrayPacketDataSignal[$l] ="";
					}else{
						$arrayPacketDataSignal[$l]=$arrayPacketDataSignal[$l] . $data[$j];
					}
					$j++;
				}
								
				//print the data of the infrared signal
				
				
				//draw the signal into the div
				if($show_signal==1){
					convertDataSignalAndDraw($i, $coefficientMoltiplication, $arrayPacketDataSignal, $max_x_px_canvas);
						
					$l=0;
					while ($l<$numPackets) {
						if($l!=0) echo ' + ';
						
						if($show_signal==1)
							echo $arrayPacketDataSignal[$l];
						
						$l++;
					}
				}else{ //show only how many packet as
					echo "Num Packets:<br>". $numPackets ;
				}
				echo '</td>';
				
				
				echo '<td>';
				
				//Button reproduce signal
				echo '<form name="peri3_btn_DELETE" action="./delete_signal_from_db.php" method=GET>';
				echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
				echo '<input type=hidden name="line_position" value="'. $i . '">';
							
				//converting in ASCII hexadecimal
				$twoChrHex = "00";
				$byteWork = $numPackets & 0x0F; 
				if($byteWork>9){
					$twoChrHex[1] = chr ( ($byteWork+65-10));
				}else{
					$twoChrHex[1] = chr ( ($byteWork+48));
				}
				$byteWork = $numPackets >> 4;
				if($byteWork>9)
					$twoChrHex[0] = chr ( ($byteWork+65-10));
				else
					$twoChrHex[0] = chr ( ($byteWork+48));
					
				$num_packets_str = $twoChrHex;
					
				echo '<input type=hidden name="num_packets" value="'. $num_packets_str . '">';
				echo '<input type=hidden name="data_signal" value="'; 
				//concatening the packets and assingin to the hidden field "DataSignal"
				$l=0;
				while ($l<$numPackets) {
					echo $arrayPacketDataSignal[$l];
					$l++;
				}				
				echo '">';
	
				echo '<input type=submit value="DELETE" class="btn_functions">';
				echo '</form>';
				
				//button Save RAM in MEM0
				echo '<form name="peri3_btn_MEM0" action="./start_send_packet_signal.php" method=GET>';
				echo '<input type=hidden name="redirect_page" value="cmd_save_signal_in_mem0">';
				echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
				echo '<input type=hidden name="line_position" value="'. $i . '">';
				echo '<input type=hidden name="num_packets" value="'. $num_packets_str . '">';
				echo '<input type=hidden name="data_signal" value="'; 
				//concatening the packets and assingin to the hidden field "DataSignal"
				$l=0;
				while ($l<$numPackets) {
					echo $arrayPacketDataSignal[$l];
					$l++;
				}				
				echo '">';
				echo '<input type=submit value="Save in MEM0" class="btn_functions">';
				echo '</form>';
				
				//button Save RAM in MEM1
				echo '<form name="peri3_btn_MEM1" action="./start_send_packet_signal.php" method=GET>';
				echo '<input type=hidden name="redirect_page" value="cmd_save_signal_in_mem1">';
				echo '<input type=hidden name="address_peri" value="'. $address_peri . '">';
				echo '<input type=hidden name="line_position" value="'. $i . '">';
				echo '<input type=hidden name="num_packets" value="'. $num_packets_str . '">';
				echo '<input type=hidden name="data_signal" value="'; 
				//concatening the packets and assingin to the hidden field "DataSignal"
				$l=0;
				while ($l<$numPackets) {
					echo $arrayPacketDataSignal[$l];
					$l++;
				}				
				echo '">';
				echo '<input type=submit value="Save in MEM1" class="btn_functions">';
				echo '</form>';
				
				echo '</td>'; 
		
		}
	
		$i++;
	}
}

echo '</table>';

//END: READING THE FILE WHERE ALL SIGNALS ARE STORED AND BULDING THE TABLE


//button HOME
echo '<p align=left>';
echo '<form name="home" action="/index.php" method=GET>';
echo '<input type=submit value="Home" class="btn_pag">';
echo '</form>';
echo '</p>';

echo '</div>';

echo '</body></html>';
?>
