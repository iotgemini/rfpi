/******************************************************************************************

Last Update: 					23/10/2017


Description: library for the GPIO under Linux Embedded OS

 *    This is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with this software.  If not, see <http://www.gnu.org/licenses/>.

							
	
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


#ifndef LINUX_GPIO_H_
#define LINUX_GPIO_H_

 /****************************************************************
 * Constants
 ****************************************************************/

#define MAX_NUM_CHAR 64

#define PATH_GPIO "/sys/class/gpio"

typedef enum
{
	INPUT_PIN = 0,
	OUTPUT_PIN = 1
} PIN_DIRECTION;

typedef enum
{
	LOW_GPIO = 0,
	HIGH_GPIO = 1
} PIN_VALUE;


int linux_gpio_export(unsigned int num_gpio);
int linux_gpio_unexport(unsigned int num_gpio);
int linux_gpio_set_dir(unsigned int num_gpio, PIN_DIRECTION out_flag);
int linux_gpio_set_value(unsigned int num_gpio, PIN_VALUE value);
int linux_gpio_get_value(unsigned int num_gpio, unsigned int *value);
int linux_gpio_set_edge(unsigned int num_gpio, char *edge);
int linux_gpio_fd_open(unsigned int num_gpio);
int linux_gpio_fd_close(int fd);

#endif /* LINUX_GPIO_H_ */
