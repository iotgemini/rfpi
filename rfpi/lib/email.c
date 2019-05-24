/******************************************************************************************

Programmer: 			Emanuele Aimone
Last Update: 			01/11/2017


Description: send an email

 
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

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
//int sem_pid_child = 0;
//pid_t child_pid;

//delay milliseconds
void send_email_with_data(unsigned char *pathFileData, unsigned char *nameData){
	
		unsigned char var_return=0;
        unsigned char cmd[100];  // to hold the command.
        unsigned char to[] = "cefiremote@gmail.com"; // email id of the recepient.
		/*unsigned char subject[100] = "Subject: "; 
        unsigned char body[] = " ";    // email body.
        unsigned char tempFile[100];     // name of tempfile.

        strcpy(tempFile,tempnam("/tmp","sendmail")); // generate temp file name.

        FILE *fp = fopen(tempFile,"w"); // open it for writing.
		strcat(subject, nameData);
		fprintf(fp,"%s\n",subject);        // write body to it.
		
		//writing inside the data
        fprintf(fp,"%s\n",body);        // write body to it.

        fclose(fp);             // close it.
*/
		
	/*	sprintf(cmd,"ssmtp %s < %s",to,pathFileData); // prepare command.
		printf("\nEMAIL: \n%s\n", cmd); fflush(stdout); 
        var_return = system(cmd);     // execute it.
*/

		
		//http://stackoverflow.com/questions/5609074/running-a-shell-command-in-a-c-program
		// 
		/*if(sem_pid_child==1){
			if(child_pid!=0) printf(" PID CHILD: %d\n", child_pid); fflush(stdout); 
		}else{*/
		
	
			
			pid_t child_pid = fork();
			if (child_pid == 0)
			{   
				//sem_pid_child=1;
				//printf("\n\n SEM PID CHILD: %d\n", sem_pid_child); fflush(stdout); 
				
				// in child
				// set up arguments 
				//sprintf(cmd,"/usr/sbin/ssmtp %s < %s",to,pathFileData); // prepare command.
			//	sprintf(cmd,"ssmtp %s < %s",to,pathFileData); // prepare command.
			//	printf("\nEMAIL: \n%s\n\n", cmd); fflush(stdout); 
			
				// launch here
				//execv("/usr/sbin/ssmtp", 0);
				//execv(cmd, 0);
				// if you ever get here, there's been an error - handle it
				 
				
				sprintf(cmd,"ssmtp %s < %s",to,pathFileData); // prepare command.
				printf("\nEMAIL: \n%s\n\n", cmd); fflush(stdout); 
				var_return = system(cmd);     // execute it.
		
				exit(0);
			}
			else if (child_pid < 0)
			{   // handle error
				printf("ERROR: Impossible to fork the process to send email!\n"); fflush(stdout); 
			}
		//}
		
		
		//printf("\n var_return = %d\n" , var_return);
       // return 0;
			
}

/*
void send_status_to_support(unsigned char *pathFileData, unsigned char *nameData){
	
		unsigned char var_return=0;
        unsigned char cmd[200];  // to hold the command.
        unsigned char to[] = "RFPI@gmail.com"; // email id of the recepient.
		
		unsigned char subject[100] = "Subject: "; 
        unsigned char body[] = " ";    // email body.
        unsigned char tempFile[100];     // name of tempfile.
		unsigned char tempFileData[100];     // name of tempfile.

        strcpy(tempFile,tempnam("/tmp","sendto")); // generate temp file name.
		strcpy(tempFileData,tempnam("/tmp","sendtodata")); // generate temp file name.
		//strcpy(tempFile,mkstemp("/tmp","sendto")); // generate temp file name.
		//strcpy(tempFileData,mkstemp("/tmp","sendtodata")); // generate temp file name.

        FILE *fp = fopen(tempFile,"w"); // open it for writing.
		strcat(subject, nameData);
		fprintf(fp,"%s\n",subject);        // write body to it.
		
	
		
		//writing inside the data
        fprintf(fp,"%s\n",body);        // write body to it.
		fclose(fp);             // close it.
		
		sprintf(cmd,"cat %s ",tempFile); // prepare command.
		var_return = system(cmd);     // execute it.
		printf("\nFILE:\n%s\n\n", tempFile); fflush(stdout); 
		
		sprintf(cmd,"paste -d '\n' %s %s > %s ",tempFile,pathFileData,tempFileData); // prepare command.
		var_return = system(cmd);     // execute it.

        

		
	//	sprintf(cmd,"ssmtp %s < %s",to,pathFileData); // prepare command.
	//	printf("\nEMAIL: \n%s\n", cmd); fflush(stdout); 
    //    var_return = system(cmd);     // execute it.


		
		//http://stackoverflow.com/questions/5609074/running-a-shell-command-in-a-c-program
		// 
		//if(sem_pid_child==1){
		//	if(child_pid!=0) printf(" PID CHILD: %d\n", child_pid); fflush(stdout); 
		//}else{
		
	
			
			pid_t child_pid = fork();
			if (child_pid == 0)
			{   
				//sem_pid_child=1;
				//printf("\n\n SEM PID CHILD: %d\n", sem_pid_child); fflush(stdout); 
				
				// in child
				// set up arguments 
				//sprintf(cmd,"/usr/sbin/ssmtp %s < %s",to,pathFileData); // prepare command.
			//	sprintf(cmd,"ssmtp %s < %s",to,pathFileData); // prepare command.
			//	printf("\nEMAIL: \n%s\n\n", cmd); fflush(stdout); 
			
				// launch here
				//execv("/usr/sbin/ssmtp", 0);
				//execv(cmd, 0);
				// if you ever get here, there's been an error - handle it
				
				
				
				sprintf(cmd,"ssmtp %s < %s ",to,tempFileData); // prepare command.
				//printf("\nEMAIL: \n%s\n\n", cmd); fflush(stdout); 
				var_return = system(cmd);     // execute it.
		
				exit(0);
			}
			else if (child_pid < 0)
			{   // handle error
				//printf("ERROR: Impossible to fork the process to send email!\n"); fflush(stdout); 
			}
		//}
		
		
		//printf("\n var_return = %d\n" , var_return);
       // return 0;
			
}
*/
