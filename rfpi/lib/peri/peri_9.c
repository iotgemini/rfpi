/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					25/06/2019


Description: library for the peripheral


******************************************************************************************/



//#include <stdio.h>
//#include <math.h>




void return_str_with_formula_name(char *str_to_return, unsigned char num_formula_to_show){

			if(num_formula_to_show==1){
				strcpy(str_to_return, HEADER_FORMULA_1);
			}else if(num_formula_to_show==2){
				strcpy(str_to_return, HEADER_FORMULA_2);
				
			}else if(num_formula_to_show==4){
				strcpy(str_to_return, HEADER_FORMULA_4);
			}else if(num_formula_to_show==5){
				strcpy(str_to_return, HEADER_FORMULA_5);
			}else if(num_formula_to_show==6){
				strcpy(str_to_return, HEADER_FORMULA_6);
			}else if(num_formula_to_show==7){
				strcpy(str_to_return, HEADER_FORMULA_7);
			}else if(num_formula_to_show==8){
				strcpy(str_to_return, HEADER_FORMULA_8);
			}else if(num_formula_to_show==9){
				strcpy(str_to_return, HEADER_FORMULA_9);
			}else if(num_formula_to_show==10){
				strcpy(str_to_return, HEADER_FORMULA_10);
			}else if(num_formula_to_show==11){
				strcpy(str_to_return, HEADER_FORMULA_11);
			}else if(num_formula_to_show==12){
				strcpy(str_to_return, HEADER_FORMULA_12);
				
			}else if(num_formula_to_show==13){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori
				//$str_to_return = $lang_temperatura_interna_peri9;
				strcpy(str_to_return, HEADER_FORMULA_13);
			}else if(num_formula_to_show==14){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori	
				//$str_to_return = $lang_temperatura_esterna_peri9;
				strcpy(str_to_return, HEADER_FORMULA_14);
			}else if(num_formula_to_show==15){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori	
				//$str_to_return = $lang_flow_meter_peri9;
				strcpy(str_to_return, HEADER_FORMULA_15);
			}else if(num_formula_to_show==16){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori	
				strcpy(str_to_return, HEADER_FORMULA_16);
				
			}else{
				strcpy(str_to_return, "INPUT");
			}

}

float return_number_with_applied_formula(int num_formula, unsigned char ADC_8bit_value){
	float varTempFloat=0;
	
	if(ADC_8bit_value==1) ADC_8bit_value=0;
		
	if(num_formula==1){
		varTempFloat = temperature_pyrometer_peri9(ADC_8bit_value);
	}else if(num_formula==2){
		varTempFloat = voltage_TVDC_peri9(ADC_8bit_value);
		
	}else if(num_formula==4 || num_formula==5 || num_formula==6 || num_formula==7){
		varTempFloat = voltage_0to10V_from_8bit_value_peri9(ADC_8bit_value);
		
	}else if(num_formula==9){
		varTempFloat = power_KW_peri9(ADC_8bit_value);
	
	}else if(num_formula==10){
		varTempFloat = production_mt_min_peri9(ADC_8bit_value);

	}else if(num_formula==11){
		varTempFloat = rif_power_percent_peri9(ADC_8bit_value);
	
	}else if(num_formula==12){
		varTempFloat = rif_production_percent_peri9(ADC_8bit_value);
		
		
	}else if(num_formula==13){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori
		varTempFloat = temperatura_int_ext_peri9(ADC_8bit_value);
	}else if(num_formula==14){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori
		varTempFloat = temperatura_int_ext_peri9(ADC_8bit_value);
	}else if(num_formula==15){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori
		varTempFloat = flow_meter_peri9(ADC_8bit_value);
		
		
	}else{
		varTempFloat = voltage_0to10V_from_8bit_value_peri9(ADC_8bit_value);
	}
	
	return varTempFloat;
	
}



int doesFileExist(const char *filename) {
    struct stat st;
    int result = stat(filename, &st);
    return result = 0;
}


//save the data sent by the Peri9 into a file
void save_data_peri9_into_a_file(unsigned char *dataRFPI, unsigned char *address){
	
	FILE *file_pointer; //generic pointer to file, used in multiple places
	FILE *file_pointer2; //generic pointer to file, used in multiple places
	char file_path[50], file_path2[50];
	char line_file[NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI9];
	//char *line_file;
	//char *array_data_file[varMaxNumData_PERI9];
	char **array_data_file;
	unsigned int cont_lines;
	unsigned int tempCharIdx = 0U;
	int tempChar;
	char *data_to_write_into_file;
	unsigned int i;
	 //char *dynamicLine = NULL;
	unsigned char buffer[10];
	int status, diff;
	unsigned char varSem1,varSemFileExist;
	varSemFileExist = 0;
	char ch;
	
	//printf("PIPPO!%s\n",address); fflush(stdout);
	
	//fprintf(file_pointer,"%s\n", strTemp2); //writing on the file the line of the inputs		
	//fprintf(file_pointer,"%d %d %s %s %s\n",			
	
	strcpy(file_path, PATH_FILE_DATA_PERI9); //copying the path where is the file
	strcat(file_path, address); 
	strcat(file_path, "_data.txt"); 

	//printf("Ciao0!!!!!\n");fflush(stdout);
	
	//I am going to count the number of lines does exist into the file
	cont_lines=0;
	file_pointer = fopen(file_path,"r"); //opening the file data
	if( file_pointer == NULL){ 
		//if the file does not exist then I create one new
			//fclose(file_pointer); 
			perror("Error: ");
			printf("Error while opening the file: %s\n", file_path);
			//exit(EXIT_FAILURE);
			file_pointer2 = fopen(file_path,"w+");
			if( file_pointer2 == NULL){
				perror("Error: ");
				printf("Error while creating the file: %s\n", file_path);
				//exit(EXIT_FAILURE);
			}else{
				//fprintf(file_pointer2,"%s", EOF);
				fclose(file_pointer2); 
			}
			
	}else{
		varSemFileExist = 0;
		while (!feof(file_pointer)) {   
			//fscanf(file_pointer, "%s", line_file);
			/*if(fscanf(file_pointer, "%s", line_file)){
				break; 
			}else{
				varSemFileExist=1;
			}*/
			
			//just counts the number of lines into the file
			while(!feof(file_pointer))
			{
			  ch = fgetc(file_pointer);
			  if(ch == '\n')
			  {
				cont_lines++;
			  }
			}

			//cont_lines++;
		}
		//if(varSemFileExist)
			
		fclose(file_pointer);
	}
	
	
	//*array_data_file = (char*)malloc(cont_lines*sizeof(char*));
	
	
	/*cont_lines=0;
	file_pointer = fopen(file_path,"r"); //opening the list of peripherals 
	if( file_pointer == NULL){
			perror("Error: ");
			printf("Error while opening the file: %s\n", file_path);
			exit(EXIT_FAILURE);
	}else{
		
		//while (!feof(file_pointer)) {  
		
			//fscanf(file_pointer, "%s", line_file); //printf("LINEFILE=%s\n",line_file);
			//array_data_file[cont_lines] = strdup(line_file);
			
			//array_data_file[cont_lines] = (char*)malloc((strlen(line_file)+1)*sizeof(char));
			//strcpy(array_data_file[cont_lines], line_file);
			
			//cont_lines++;
			
			
			//if (fscanf(file_pointer, "%d %d %s %s %s\n", &i, &IdPeripheral, NamePeripheral, NameFileDescriptor, peripheralAddress)) {
		//while (fgets(line_file, sizeof(line_file), file_pointer)) {	
		
		while(tempChar = fgetc(file_pointer)){
			
			//tempChar = fgetc(file_pointer);
			if (tempChar == EOF) {
				line_file[tempCharIdx] = '\0';
				//dynamicLine = strdup(line_file);
				array_data_file[cont_lines] = strdup(line_file);
				//fprintf(stdout, "%s\n", dynamicLine);
				//free(dynamicLine);
				//dynamicLine = NULL;
				break;
			}
			else if (tempChar == '\n') {
				line_file[tempCharIdx] = '\n';
				tempCharIdx = 0U;
				//dynamicLine = strdup(line_file);
				array_data_file[cont_lines] = strdup(line_file);
				cont_lines++;
				//fprintf(stdout, "%s\n", dynamicLine);
				//free(dynamicLine);
				//dynamicLine = NULL;
				continue;
			}
			else
				if(tempCharIdx<NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI9-1)
					line_file[tempCharIdx++] = (char)tempChar;
				else
					line_file[tempCharIdx] = '\0';
		
		
			//line_file = (char*)malloc((strlen(line_file)+1)*sizeof(char));
			
			
			
			//array_data_file[cont_lines] = (char*)malloc((strlen(line_file)+1)*sizeof(char));
			//strcpy(array_data_file[cont_lines], line_file);
			//$data_to_write_into_file .= $line_file;
			//cont_lines++;
		//}
		}
			
	}	
	fclose(file_pointer);
	*/
	
	/*if(cont_lines > varMaxNumData_PERI9){
		data_to_write_into_file = (char*)malloc((strlen(line_file)+1)*sizeof(char));
		data_to_write_into_file="";
		$i = $cont_lines - varMaxNumData_PERI9;
		$j=0;
		while($j<varMaxNumData_PERI9){
			$data_to_write_into_file .= $array_data_file[$i];
			$i++;
			$j++;
		}
	}*/


	//printf("Ciao1!!!!!");fflush(stdout);
	
	if(cont_lines>varMaxNumData_PERI9){
		
		diff = cont_lines - varMaxNumData_PERI9;// + LENGHT_ARRAY_DATA_FROM_PERI9;
		
		file_pointer = fopen(file_path,"r");
		strcpy(file_path2, file_path);
		strcat(file_path2, ".backup");
		file_pointer2 = fopen(file_path2,"w+");
		
		//printf("PIPPO0!!!!!\n");fflush(stdout);
		
		varSem1 = 1;
		cont_lines = 0;
		if( file_pointer == NULL || file_pointer2 == NULL){
			perror("Error: ");
			printf("Error while opening the file: %s\n OR the file: %s\n", file_path, file_path2);
			//exit(EXIT_FAILURE);
			varSem1 = 0; 
			if( file_pointer != NULL) fclose(file_pointer);
			if( file_pointer2 != NULL) fclose(file_pointer2);	
		}else{
			
			while (!feof(file_pointer)) { 

				if( fgets (line_file, NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI9, file_pointer)!=NULL ) {
					//fscanf(file_pointer, "%s", line_file);
				
					if(cont_lines>diff){
						fprintf(file_pointer2,"%s", line_file);
					}
				}
				cont_lines++;
			}
			fclose(file_pointer);
			fclose(file_pointer2);
		}
		//printf("PIPPO1!!!!!\n");fflush(stdout);
		
		
		if(varSem1){
			//delete the file
			status = remove(file_path);
			if( status == 0 ){
				//printf("%s file deleted successfully.\n",file_path);
			}else{
				perror("Error: ");
				printf("Unable to delete the file %s\n", file_path);
				varSem1=0;
			}
		}

		if(varSem1){
			status= rename(file_path2, file_path); //( oldname , newname );
			if ( status == 0 ){
				//printf( "File successfully renamed: %s\n", file_path );
			}else{
				perror("Error: ");
				printf( "Error renaming file: %s\n", file_path2 );
				varSem1=0;
			}
		}

	}

	//printf("Ciao2!!!!!");fflush(stdout);

	file_pointer = fopen(file_path,"a+");
	if( file_pointer == NULL){
		perror("Error: ");
		printf("Error while opening the file: %s\n", file_path);
		//exit(EXIT_FAILURE);
	}else{
		//rewriting the file up to varMaxNumData_PERI9
		i=0;
		/*if(cont_lines > varMaxNumData_PERI9){
			i = cont_lines - varMaxNumData_PERI9;
		}
		while(i<cont_lines){
			fputs(array_data_file[i], file_pointer);
			i++;
		}
		*/
		
		//writing then the new data
		i=0;
		//$data_to_write_into_file="";
		while(i<LENGHT_ARRAY_DATA_FROM_PERI9){
			if(dataRFPI[i+10]>0){ //the data start from the position 10
					//fwrite($myfile, "TEMPERATURE=" . $array_data[$i] . "|\n");
					//$myfile = file_put_contents($file_path, "TEMPERATURE=" . $array_data[$i] . "|\n".PHP_EOL , FILE_APPEND);
					//$data_to_write_into_file .= "TEMPERATURE=" . $array_data[$i] . "|\n";
					strcpy(line_file, "TEMPERATURE=");
					itoaRFPI(dataRFPI[i+10],buffer,10);
					strcat(line_file, buffer );
					strcat(line_file, "|\n");
					//fputs(line_file, file_pointer);
					fprintf(file_pointer,"%s", line_file);
			}
			i++;
		}
		//$myfile = file_put_contents($file_path, $data_to_write_into_file.PHP_EOL , FILE_APPEND);
			
		fclose(file_pointer);	
	}
	
	
	//free(dynamicLine);
	//dynamicLine = NULL;

}




//get the config to save the data
void get_conf_data_peri9(unsigned char *address){
	unsigned int int_Num_Data = 0;
	unsigned int sem_Data_After_Stop = 0;
	FILE *file_pointer; //generic pointer to file, used in multiple places
	char file_path[50];
	strcpy(file_path,PATH_FILE_DATA_PERI9);
	strcat(file_path,address);
	strcat(file_path,"_conf_data.txt");
	file_pointer = fopen(file_path,"r"); //opening the file data
	if( file_pointer != NULL){ 
		if(!feof(file_pointer)) {  
			if (fscanf(file_pointer, "%d %d\n", &sem_Data_After_Stop, &int_Num_Data));	
		}
		fclose(file_pointer);
	}
	//printf("PERI9 Conf Data: %d, %d\n",sem_Data_After_Stop,int_Num_Data);
	if(int_Num_Data>0)
		varMaxNumData_PERI9 = int_Num_Data*5;
	else
		varMaxNumData_PERI9 = MAX_NUM_OF_DATA_PERI9;
}




//save the data sent by the Peri9 into a temproary file and when the maximum is reached then send an email with the file
void save_data_peri9_into_a_file_and_send_email(unsigned char *dataRFPI, unsigned char *address, unsigned char *namePeri){
	
	unsigned char cmd[100];  // to hold the command.
	  
	FILE *file_pointer; //generic pointer to file, used in multiple places
	FILE *file_pointer2; //generic pointer to file, used in multiple places
	char file_path[50], file_path2[50];
	char line_file[NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI9];
	//char *line_file;
	//char *array_data_file[varMaxNumData_PERI9];
	char **array_data_file;
	unsigned int cont_lines;
	unsigned int tempCharIdx = 0U;
	int tempChar;
	char *data_to_write_into_file;
	unsigned int i;
	 //char *dynamicLine = NULL;
	char buffer[500],buffer2[100];
	int status, diff;
	unsigned char varSemFileExist, varSemCreateEmptyFile;
	varSemFileExist = 0;
	varSemCreateEmptyFile = 0;
	char ch;
	float varTempFloat;
	
	
	strcpy(file_path, "/tmp/"); //copying the path where is the file
	strcat(file_path, address); 
	strcat(file_path, "_data.txt"); 
	
	
	
	//I am going to count the number of lines does exist into the file
	cont_lines=0;
	file_pointer = fopen(file_path,"r"); //opening the file data
	if( file_pointer == NULL){ 
		//if the file does not exist then I create one new
			
			perror("Error: ");
			printf("Error while opening the file: %s\n", file_path);
			varSemCreateEmptyFile = 1;			
	}else{
		varSemFileExist = 0;
		while (!feof(file_pointer)) {   
			
			//just counts the number of lines into the file
			while(!feof(file_pointer))
			{
			  ch = fgetc(file_pointer);
			  if(ch == '\n')
			  {
				cont_lines++;
			  }
			}

		}
			
		fclose(file_pointer);
	}
	
	
	
	
	
	if(cont_lines>((unsigned int)(varMaxNumData_PERI9)/5)){
		//then I send the file and I create a new one
	
			file_pointer = fopen(file_path,"a+");
			strcpy(buffer,"</table>");
			strcat(buffer,"</body></html>");
			fprintf(file_pointer,"%s", buffer);
			fclose(file_pointer);
			
			
			//creating a file to leave the time to the fork to send data
			strcpy(file_path2, file_path);
			strcat(file_path2, ".email");
			strcpy(cmd, "cp ");
			strcat(cmd, file_path);
			strcat(cmd, " ");
			strcat(cmd, file_path2);
			system(cmd);     // execute it.
			
			//send the file by email
			send_email_with_data(file_path2, namePeri);
		
			//removing the file just sent
			status = remove(file_path);
			if( status == 0 ){
				//printf("%s file deleted successfully.\n",file_path);
			}else{
				perror("Error: ");
				printf("Unable to delete the file %s\n", file_path);
	
			}
			
			varSemCreateEmptyFile=1;
				
	}
	
	
	if(varSemCreateEmptyFile){
		//creating an empy file
			file_pointer2 = fopen(file_path,"w+");
			if( file_pointer2 == NULL){
				perror("Error: ");
				printf("Error while creating the file: %s\n", file_path);
				//exit(EXIT_FAILURE);
			}else{
				strcpy(buffer , "Subject: ");
				strcat(buffer,namePeri);
				strcat(buffer,"\n");
				strcat(buffer,"MIME-Version: 1.0\n");
				strcat(buffer,"Content-Type: text/html; charset-'us-ascii'\n");
				strcat(buffer,"Content-Disposition: inline\n");
				
				strcat(buffer , "<html><body><head>");
				strcat(buffer,"<meta content='text/html' http-equiv='Content-Type'>");
				strcat(buffer,"</head>");
				strcat(buffer,"<table border=1>");
				strcat(buffer,"<tr>");
				
				strcat(buffer,"<td>"); 
				return_str_with_formula_name(buffer2, NUM_FORMULA_IN0);
				strcat(buffer,buffer2);
				strcat(buffer," </td>");
				
				strcat(buffer,"<td>"); 
				return_str_with_formula_name(buffer2, NUM_FORMULA_IN1);
				strcat(buffer,buffer2);
				strcat(buffer," </td>");
				
				strcat(buffer,"<td>"); 
				return_str_with_formula_name(buffer2, NUM_FORMULA_IN2);
				strcat(buffer,buffer2);
				strcat(buffer," </td>");
				
				strcat(buffer,"<td>"); 
				return_str_with_formula_name(buffer2, NUM_FORMULA_IN3);
				strcat(buffer,buffer2);
				strcat(buffer," </td>");
				
				strcat(buffer,"<td>"); 
				return_str_with_formula_name(buffer2, NUM_FORMULA_IN4);
				strcat(buffer,buffer2);
				strcat(buffer," </td>");
				
				
				//column 6
				strcat(buffer,"<td>");
				strcpy(buffer2, HEADER_COLUM_9);
				strcat(buffer, buffer2 );
				strcat(buffer, " " );
				strcat(buffer,"</td>");
			
				//column 7
				strcat(buffer,"<td>");
				strcpy(buffer2, HEADER_COLUM_10);
				strcat(buffer, buffer2 );
				strcat(buffer, " " );
				strcat(buffer,"</td>");
	
				
				strcat(buffer,"</tr>\n");
				
				
				//fprintf(file_pointer2, buffer);
				fprintf(file_pointer2, "%s",buffer);//LINUX_MINT
				
				fclose(file_pointer2); 
			}
	}
	
	
	//saving data on the file
	file_pointer = fopen(file_path,"a+");
	if( file_pointer == NULL){
		perror("Error: ");
		printf("Error while opening the file: %s\n", file_path);
		//exit(EXIT_FAILURE);
	}else{
		
		//writing then the new data
		i=0;
		//$data_to_write_into_file="";
		while(i<LENGHT_ARRAY_DATA_FROM_PERI9){
			if(dataRFPI[i+10]>0){ //the data start from the position 10

					strcpy(line_file,"<tr>");
					
					//data 1
					strcat(line_file,"<td>");
					//itoaRFPI(dataRFPI[i+10],buffer,10);
					varTempFloat = return_number_with_applied_formula(NUM_FORMULA_IN0,dataRFPI[i+10]);
					sprintf_float_number(buffer,varTempFloat,2);
					strcat(line_file, buffer );
					strcat(line_file, " " );
					strcat(line_file,"</td>");
					i++;
					
					//data 2
					strcat(line_file,"<td>");
					//itoaRFPI(dataRFPI[i+10],buffer,10);
					varTempFloat = return_number_with_applied_formula(NUM_FORMULA_IN1,dataRFPI[i+10]);
					sprintf_float_number(buffer,varTempFloat,2);
					strcat(line_file, buffer );
					strcat(line_file, " " );
					strcat(line_file,"</td>");
					i++;
					
					//data 3
					strcat(line_file,"<td>");
					//itoaRFPI(dataRFPI[i+10],buffer,10);
					varTempFloat = return_number_with_applied_formula(NUM_FORMULA_IN2,dataRFPI[i+10]);
					sprintf_float_number(buffer,varTempFloat,2);
					strcat(line_file, buffer );
					strcat(line_file, " " );
					strcat(line_file,"</td>");
					i++;
					
					//data 4
					strcat(line_file,"<td>");
					//itoaRFPI(dataRFPI[i+10],buffer,10);
					varTempFloat = return_number_with_applied_formula(NUM_FORMULA_IN3,dataRFPI[i+10]);
					sprintf_float_number(buffer,varTempFloat,2);
					strcat(line_file, buffer );
					strcat(line_file, " " );
					strcat(line_file,"</td>");
					i++;
					
					//data 5
					strcat(line_file,"<td>");
					//itoaRFPI(dataRFPI[i+10],buffer,10);
					//varTempFloat = return_number_with_applied_formula(NUM_FORMULA_IN4,dataRFPI[i+10]);
					//sprintf_float_number(buffer,varTempFloat,2);
					if(dataRFPI[i+10]==1){
						strcpy(buffer,"1");
					}else{ //if(dataRFPI[i+10]==2){
						strcpy(buffer,"0");
					}//else{
					//	strcpy(buffer,"-1");
					//}
					strcat(line_file, buffer );
					strcat(line_file, " " );
					strcat(line_file,"</td>");
					//i++;
					
					
					//time 
					strcat(line_file,"<td>"); 
					//snprintf(buffer, sizeof(buffer), "%02x:%02x:%02x", RTC_hour_bcd, RTC_minute_bcd, RTC_second_bcd); //&frasl;
					snprintf(buffer, sizeof(buffer), "%02x:%02x", RTC_hour_bcd, RTC_minute_bcd); //&frasl;
					strcat(line_file,buffer);
					strcat(line_file," </td>");
					
					//date
					strcat(line_file,"<td>"); 
					snprintf(buffer, sizeof(buffer), "%02x/%02x/%02x",  RTC_date_bcd, RTC_month_bcd, RTC_year_bcd); //&frasl;
					strcat(line_file,buffer);
					strcat(line_file," </td>");
					
					
					
					strcat(line_file,"</tr>");
					
					strcat(line_file, "\n");
					//fputs(line_file, file_pointer);
					fprintf(file_pointer,"%s", line_file);
					
			}
			i++;
		}
		
			
		fclose(file_pointer);	
	}
	
}





void sprintf_float_number(char *str, float float_number, unsigned char num_of_decimal){
	
	//float float_number = 678.0123;
	unsigned int mult_for_decimal=1;
	while(num_of_decimal>0){
		mult_for_decimal = mult_for_decimal *10;
		num_of_decimal--;
	}

	if(mult_for_decimal>10000) mult_for_decimal = 10000;
	
	int d1 = float_number;            // Get the integer part (678).
	float f2 = float_number - d1;     // Get fractional part (678.0123 - 678 = 0.0123).
	//int d2 = trunc(f2 * mult_for_decimal);   // Turn into integer (123).
	int d2 = (int)(f2 * mult_for_decimal); // Or this one: Turn into integer.

	// Print as parts, note that you need 0-padding for fractional bit.
	// Since d1 is 678 and d2 is 123, you get "678.0123".
	if(mult_for_decimal==1)
		sprintf (str, "%d", d1);
	else if(mult_for_decimal==10)
		sprintf (str, "%d.%01d", d1, d2);
	else if(mult_for_decimal==100)
		sprintf (str, "%d.%02d", d1, d2);
	else if(mult_for_decimal==1000)
		sprintf (str, "%d.%03d", d1, d2);
	else if(mult_for_decimal==10000)
		sprintf (str, "%d.%04d", d1, d2);

//sprintf (str, "%d", d1);
}


float voltage_0to10V_from_8bit_value_peri9(unsigned char ADC_8bit_value){
	
	float voltage = (float)ADC_8bit_value; 
	voltage = (float)(ADC_8bit_value * 10) ; 
	voltage = voltage / 256;
	//$voltage = ceil($voltage);
	return voltage;
	
}


float temperature_pyrometer_peri9(unsigned char ADC_8bit_value){
	float temperature = (float)((ADC_8bit_value * (FORMULA_1_MAX_VALUE-FORMULA_1_MIN_VALUE) ) / 255)+FORMULA_1_MIN_VALUE;
		
	return temperature;
}


float voltage_TVDC_peri9(unsigned char ADC_8bit_value){
	float voltage = ADC_8bit_value; 
	voltage = (float)((ADC_8bit_value * 450 ) / 256); 

	return voltage;
}


float power_KW_peri9(unsigned char ADC_8bit_value){
	
	float power = (float)ADC_8bit_value; 
	power = (float)(ADC_8bit_value * (FORMULA_9_MAX_VALUE / 10)) ; 
	power = power / 256; //divide by 256 because is the maximum value on 8bit
	power = power + FORMULA_9_MIN_VALUE;
	//$power = ceil($power);
	return power;
	
}


float production_mt_min_peri9(unsigned char ADC_8bit_value){
	
	float production = (float)ADC_8bit_value; 
	production = (float)(ADC_8bit_value * (FORMULA_10_MAX_VALUE-FORMULA_10_MIN_VALUE)) ; 
	production = production / 256; //divide by 256 because is the maximum value on 8bit
	production = production + FORMULA_10_MIN_VALUE;
	//$production = ceil($production);
	return production;
	
}

float rif_power_percent_peri9(unsigned char ADC_8bit_value){
	
	float power = (float)ADC_8bit_value; 
	power = (float)(ADC_8bit_value * 10) ; 
	power = power / 256; //divide by 256 because is the maximum value on 8bit
	//$power = ceil($power);
	return power;
	
}


float rif_production_percent_peri9(unsigned char ADC_8bit_value){
	
	float production = (float)ADC_8bit_value; 
	production = (float)(ADC_8bit_value * 10) ; 
	production = production / 256; //divide by 256 because is the maximum value on 8bit
	//$production = ceil($production);
	return production;
	
}


float temperatura_int_ext_peri9(unsigned char ADC_8bit_value){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori
	
	float temperatura = (float)(ADC_8bit_value & 127); 
	if((ADC_8bit_value & 128) == 128){
		temperatura += 0.5;
	}

	return temperatura;
	
}


float flow_meter_peri9(unsigned char ADC_8bit_value){ //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori
	
	float flow_meter; 
	//flow_meter = (float)(ADC_8bit_value / 10) ; 
	//flow_meter = (float)(ADC_8bit_value/3) ;
	flow_meter = flow_meter / 10;
	//$flow_meter = ceil($flow_meter);
	return flow_meter;
	
}






//save the data sent by the Peri9 into a file, into a row there will be data0, data1, data2, data3, data4, time, date
void save_data_peri9_into_a_file_1row_7data(unsigned char *dataRFPI, unsigned char *address){
	
	FILE *file_pointer; //generic pointer to file, used in multiple places
	FILE *file_pointer2; //generic pointer to file, used in multiple places
	char file_path[50], file_path2[50];
	char line_file[NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI9];
	//char *line_file;
	//char *array_data_file[varMaxNumData_PERI9];
	char **array_data_file;
	unsigned int cont_lines;
	unsigned int tempCharIdx = 0U;
	int tempChar;
	char *data_to_write_into_file;
	unsigned int i;
	 //char *dynamicLine = NULL;
	unsigned char buffer[10];
	int status, diff;
	unsigned char varSem1,varSemFileExist;
	varSemFileExist = 0;
	char ch;
	
	//printf("PIPPO!%s\n",address); fflush(stdout);
	
	//fprintf(file_pointer,"%s\n", strTemp2); //writing on the file the line of the inputs		
	//fprintf(file_pointer,"%d %d %s %s %s\n",			
	
	strcpy(file_path, PATH_FILE_DATA_PERI9); //copying the path where is the file
	strcat(file_path, address); 
	strcat(file_path, "_data_peri9.txt"); 

	//printf("Ciao0!!!!!\n");fflush(stdout);
	
	//I am going to count the number of lines does exist into the file
	cont_lines=0;
	file_pointer = fopen(file_path,"r"); //opening the file data
	if( file_pointer == NULL){ 
		//if the file does not exist then I create one new
			//fclose(file_pointer); 
			perror("Error: ");
			printf("Error while opening the file: %s\n", file_path);
			//exit(EXIT_FAILURE);
			file_pointer2 = fopen(file_path,"w+");
			if( file_pointer2 == NULL){
				perror("Error: ");
				printf("Error while creating the file: %s\n", file_path);
				//exit(EXIT_FAILURE);
			}else{
				//fprintf(file_pointer2,"%s", EOF);
				fclose(file_pointer2); 
			}
			
	}else{
		varSemFileExist = 0;
		while (!feof(file_pointer)) {   
			//fscanf(file_pointer, "%s", line_file);
			/*if(fscanf(file_pointer, "%s", line_file)){
				break; 
			}else{
				varSemFileExist=1;
			}*/
			
			//just counts the number of lines into the file
			while(!feof(file_pointer))
			{
			  ch = fgetc(file_pointer);
			  if(ch == '\n')
			  {
				cont_lines++;
			  }
			}

			//cont_lines++;
		}
		//if(varSemFileExist)
			
		fclose(file_pointer);
	}
	
	
	
	if(cont_lines>varMaxNumData_PERI9){
		
		diff = cont_lines - varMaxNumData_PERI9;// + LENGHT_ARRAY_DATA_FROM_PERI9;
		
		file_pointer = fopen(file_path,"r");
		strcpy(file_path2, file_path);
		strcat(file_path2, ".backup");
		file_pointer2 = fopen(file_path2,"w+");
		
		//printf("PIPPO0!!!!!\n");fflush(stdout);
		
		varSem1 = 1;
		cont_lines = 0;
		if( file_pointer == NULL || file_pointer2 == NULL){
			perror("Error: ");
			printf("Error while opening the file: %s\n OR the file: %s\n", file_path, file_path2);
			//exit(EXIT_FAILURE);
			varSem1 = 0; 
			if( file_pointer != NULL) fclose(file_pointer);
			if( file_pointer2 != NULL) fclose(file_pointer2);	
		}else{
			
			while (!feof(file_pointer)) { 

				if( fgets (line_file, NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI9, file_pointer)!=NULL ) {
					//fscanf(file_pointer, "%s", line_file);
				
					if(cont_lines>diff){
						fprintf(file_pointer2,"%s", line_file);
					}
				}
				cont_lines++;
			}
			fclose(file_pointer);
			fclose(file_pointer2);
		}
		//printf("PIPPO1!!!!!\n");fflush(stdout);
		
		
		if(varSem1){
			//delete the file
			status = remove(file_path);
			if( status == 0 ){
				//printf("%s file deleted successfully.\n",file_path);
			}else{
				perror("Error: ");
				printf("Unable to delete the file %s\n", file_path);
				varSem1=0;
			}
		}

		if(varSem1){
			status= rename(file_path2, file_path); //( oldname , newname );
			if ( status == 0 ){
				//printf( "File successfully renamed: %s\n", file_path );
			}else{
				perror("Error: ");
				printf( "Error renaming file: %s\n", file_path2 );
				varSem1=0;
			}
		}

	}

	//printf("Ciao2!!!!!");fflush(stdout);

	file_pointer = fopen(file_path,"a+");
	if( file_pointer == NULL){
		perror("Error: ");
		printf("Error while opening the file: %s\n", file_path);
		//exit(EXIT_FAILURE);
	}else{
		//rewriting the file up to varMaxNumData_PERI9
		i=0;
		/*if(cont_lines > varMaxNumData_PERI9){
			i = cont_lines - varMaxNumData_PERI9;
		}
		while(i<cont_lines){
			fputs(array_data_file[i], file_pointer);
			i++;
		}
		*/
		
		//writing then the new data
		i=0;
		//$data_to_write_into_file="";
		line_file[0]='\0';
		while(i<5){ //LENGHT_ARRAY_DATA_FROM_PERI9
			//if(dataRFPI[i+10]>0){ //the data start from the position 10
					//fwrite($myfile, "TEMPERATURE=" . $array_data[$i] . "|\n");
					//$myfile = file_put_contents($file_path, "TEMPERATURE=" . $array_data[$i] . "|\n".PHP_EOL , FILE_APPEND);
					//$data_to_write_into_file .= "TEMPERATURE=" . $array_data[$i] . "|\n";
					
					//strcpy(line_file, "DATA"); 
					strcat(line_file, "DATA"); 
					itoaRFPI(i,buffer,10);
					strcat(line_file, buffer );
					strcat(line_file, "=");
					
					itoaRFPI(dataRFPI[i+10],buffer,10);
					strcat(line_file, buffer );
					
					strcat(line_file, " ");
					//fputs(line_file, file_pointer);
					//fprintf(file_pointer,"%s", line_file);
			//}
			i++;
		}
		
		//time 
		strcat(line_file,"TIME="); 
		snprintf(buffer, sizeof(buffer), "%02x:%02x:%02x", RTC_hour_bcd, RTC_minute_bcd, RTC_second_bcd); //&frasl;
		//snprintf(buffer, sizeof(buffer), "%02x:%02x", RTC_hour_bcd, RTC_minute_bcd); //&frasl;
		strcat(line_file,buffer);
		strcat(line_file," ");
				
		//date
		strcat(line_file,"DATE="); 
		snprintf(buffer, sizeof(buffer), "%02x-%02x-%02x",  RTC_date_bcd, RTC_month_bcd, RTC_year_bcd); //&frasl;
		strcat(line_file,buffer);
		strcat(line_file," ");
					
					
		strcat(line_file, "\n");
		//$myfile = file_put_contents($file_path, $data_to_write_into_file.PHP_EOL , FILE_APPEND);
		
		fprintf(file_pointer,"%s", line_file);
			
		fclose(file_pointer);	
	}
	


}

