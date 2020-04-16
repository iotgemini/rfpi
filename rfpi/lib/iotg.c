/******************************************************************************************

Programmer: 					Emanuele Aimone
Start date:						02/04/2020
Last Update: 					17/04/2020


Description: library for the RFPI

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
							
	
	ATTENTION:				This software is provided in a way
							free and without any warranty
							The author does not assume any
							liability for damage brought or
							caused by this software.
							
	ATTENZIONE:				Questo software viene fornito in modo 
							gratuito e senza alcuna garanzia
							L'autore non si assume nessuna 
							responsabilità per danni portati o
							causati da questo software.


******************************************************************************************/


//this function return the MPN from the id, this is used for peripheral 100
char* return_mpn(char *mpn, int *id_shield){
	int id;
	//if(*id_shield>7|| *id_shield<0){
	//	*id_shield = 0;
	//}
	id = *id_shield;
	if(id==1){
		strcpy(mpn,"LED");
	}else if(id==2){
		strcpy(mpn,"SWITCH");
	}else if(id==3){
		strcpy(mpn,"MCP9701A");
	}else if(id==4){
		strcpy(mpn,"00172");
	}else if(id==5){
		strcpy(mpn,"DHT11");
	}else if(id==6){
		strcpy(mpn,"ADC0V5V");
	}else if(id==7){
		strcpy(mpn,"HC-SR505");
	}else if(id==8){
		strcpy(mpn,"DHT22");
	}else{
		// Implementation of itoa(). Convert a number into a string
		mpn=itoaRFPI(id, mpn, 10);
		//strcpy(mpn,"NULL");
	}
	
	return mpn;
}