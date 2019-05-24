<?php
/******************************************************************************************

Programmer: 		Emanuele Aimone
Last Update: 		02/04/2019

Description: here is defined where are the controls panels for the peripheral with an official ID number

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


//----------------------------------BEGIN LIST PATH CPANEL-------------------------------------//

include './lib/peripheral_1/cpanel_1.php';  	//library with the control panel for the first peripheral
include './lib/peripheral_2/cpanel_2.php';  	//library with the control panel for the second peripheral
include './lib/peripheral_3/cpanel_3.php';  	//library with the control panel for the third peripheral (Sensore-Attuatore)
include './lib/peripheral_4/cpanel_4.php';  	//library with the control panel for the 4th peripheral (MP1-TIMER)
include './lib/peripheral_5/cpanel_5.php';  	//library with the control panel for the 5th peripheral	(Radio Control)
include './lib/peripheral_6/cpanel_6.php';  	//library with the control panel for the 6th peripheral	(Thermostat 24hours)
include './lib/peripheral_7/cpanel_7.php';  	//library with the control panel for the 7th peripheral (0-10V input)
include './lib/peripheral_8/cpanel_8.php';  	//library with the control panel for the 8th peripheral (blinds)
include './lib/peripheral_9/cpanel_9.php';  	//library with the control panel for the 9th peripheral. Array 0-10V input
include './lib/peripheral_10/cpanel_10.php';  	//library with the control panel for the 10th peripheral. Thermostat with RTC
include './lib/peripheral_11/cpanel_11.php';  	//library with the control panel for the 11th peripheral. 8 Relay and 8 opto isolated inputs
include './lib/peripheral_12/cpanel_12.php';  	//library with the control panel for the 12th peripheral. 2 Relay and 4 opto isolated inputs

include './lib/peripheral_99/cpanel_99.php';  	//library with the control panel that say is not original
include './lib/peripheral_100/cpanel_100.php';  //library with the control panel with the button to load the configurations from json file

//-----------------------------------END LIST PATH CPANEL--------------------------------------//



//-------------------------------BEGIN FUNCTIONS DESCRIPTIONS----------------------------------//

//		function checkExistPeripheralFunction($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri);
												//it has the list of the available functions where there is the control panel for each peripheral
												
//--------------------------------END FUNCTIONS DESCRIPTIONS-----------------------------------//



//it will call the function with the tools to use the peripheral identified by $idperipheral
function checkExistPeripheralFunction($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri){

	$varReturn = 1;
	
	if($idperipheral==1){
		//peripheral with 1 digital input and 1 relay

		peripheral_1($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 
	
		/* here type your "else if" like the example below:
		
			}else if($idperipheral==5469){ //here the ID number of your peripheral, in this example the ID has value 5469
				
				include './lib/peripheral_5469/cpanel_5469.php';  	//this is the file where is kept the code of your control panel
				//now you have to call the function kept into cpanel_5469.php to make the webserver to build your control panel
				peripheral_5469($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri);
				
			return 1; //remember to return 1 always, otherwise will be built another generic control panel
			
		*/
		
		
	}else if($idperipheral==2){
		//peripheral with 1 analogue input, 1 relay, 1 analogue output
		
		peripheral_2($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 

	}else if($idperipheral==3){
		//peripheral with 2 sensors, 2 relay, 1 digital input, 2 GPIO, Infrared RX and TX
		
		peripheral_3($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 

	}else if($idperipheral==4){
		//peripheral with 1 digital input, 1 relay and NTC. Function to timer the relay and turn on and off the relay setting the temperature trigger
		
		peripheral_4($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 
	
	}else if($idperipheral==5){
		//Radio control
		
		peripheral_5($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 
	
	}else if($idperipheral==6){
		//peripheral with 1 digital input and 1 relay + a function to thermostat over 24hours
		
		peripheral_6($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 

	}else if($idperipheral==7){
		//peripheral with 1 digital input, 1 digital output, 1 relay, a temperature sensor, two 0-10V input
		
		peripheral_7($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 

	}else if($idperipheral==8){
		//peripheral for blinds
		
		peripheral_8($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 

	}else if($idperipheral==9){
		//peripheral with 1 digital input, 1 digital output, 1 relay, a temperature sensor, two 0-10V input
		
		peripheral_9($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 

	}else if($idperipheral==10){
		//peripheral with RTC and a weekly control for thermostat
		
		peripheral_10($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 
	
	}else if($idperipheral==11){
		//peripheral with 8 Relay and 8 opto isolated inputs
		
		peripheral_11($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 
	
	}else if($idperipheral==12){
		//peripheral with 2 Relay and 4 opto isolated inputs
		
		peripheral_12($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 
	
	}else if($idperipheral==99){
		//says is not original
		
		peripheral_99($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 
	
	}else if($idperipheral==100){
		//says is not original
		
		peripheral_100($id, $idperipheral, $name, $address_peri, $numInput, $numOutput, $arrayNameInput, $arrayStatusInput, $arrayNameOutput, $arrayStatusOutput, $num_special_functions_peri, $fw_version_peri); 
	
		
		
	}else{
		$varReturn = 0;
	}
	
	return $varReturn;
}


?>