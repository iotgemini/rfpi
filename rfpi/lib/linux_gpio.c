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


#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>
#include <unistd.h>
#include <fcntl.h>
#include <poll.h>
#include "linux_gpio.h"


#include <sys/stat.h>
#include <sys/types.h>


//it load a new gpioX under /sys/class/gpio/
int linux_gpio_export(unsigned int num_gpio)
{
	int fd, len;
	char buf[MAX_NUM_CHAR];

	fd = open(PATH_GPIO "/export", O_WRONLY);
	if (fd < 0) {
		perror("gpio/export");
		return fd;
	}

	len = snprintf(buf, sizeof(buf), "%d", num_gpio);
	write(fd, buf, len);
	close(fd);

	return 0;
}

// unload the number of the GPIO
int linux_gpio_unexport(unsigned int num_gpio)
{
	int fd, len;
	char buf[MAX_NUM_CHAR];

	fd = open(PATH_GPIO "/unexport", O_WRONLY);
	if (fd < 0) {
		perror("gpio/export");
		return fd;
	}

	len = snprintf(buf, sizeof(buf), "%d", num_gpio);
	write(fd, buf, len);
	close(fd);
	return 0;
}

// set the direction of the GPIO if input or output
int linux_gpio_set_dir(unsigned int num_gpio, PIN_DIRECTION out_flag)
{
	int fd;
	char buf[MAX_NUM_CHAR];

	snprintf(buf, sizeof(buf), PATH_GPIO  "/gpio%d/direction", num_gpio);

	fd = open(buf, O_WRONLY);
	if (fd < 0) {
		perror("gpio/direction");
		return fd;
	}

	if (out_flag == OUTPUT_PIN)
		write(fd, "out", 4);
	else
		write(fd, "in", 3);

	close(fd);
	return 0;
}


// assign a value to the GPIO
int linux_gpio_set_value(unsigned int num_gpio, PIN_VALUE value)
{
	int fd;
	char buf[MAX_NUM_CHAR];

	snprintf(buf, sizeof(buf), PATH_GPIO "/gpio%d/value", num_gpio);

	fd = open(buf, O_WRONLY);
	if (fd < 0) {
		perror("gpio/set-value");
		return fd;
	}

	if (value==LOW_GPIO)
		write(fd, "0", 2);
	else
		write(fd, "1", 2);

	close(fd);
	return 0;
}

//return the value of the gpio
int linux_gpio_get_value(unsigned int num_gpio, unsigned int *value)
{
	int fd;
	char buf[MAX_NUM_CHAR];
	char ch;

	snprintf(buf, sizeof(buf), PATH_GPIO "/gpio%d/value", num_gpio);

	fd = open(buf, O_RDONLY);
	if (fd < 0) {
		perror("gpio/get-value");
		return fd;
	}

	read(fd, &ch, 1);

	if (ch != '0') {
		*value = 1;
	} else {
		*value = 0;
	}

	close(fd);
	return 0;
}



int linux_gpio_set_edge(unsigned int num_gpio, char *edge)
{
	int fd;
	char buf[MAX_NUM_CHAR];

	snprintf(buf, sizeof(buf), PATH_GPIO "/gpio%d/edge", num_gpio);

	fd = open(buf, O_WRONLY);
	if (fd < 0) {
		perror("gpio/set-edge");
		return fd;
	}

	write(fd, edge, strlen(edge) + 1);
	close(fd);
	return 0;
}



int linux_gpio_fd_open(unsigned int num_gpio)
{
	int fd;
	char buf[MAX_NUM_CHAR];

	snprintf(buf, sizeof(buf), PATH_GPIO "/gpio%d/value", num_gpio);

	fd = open(buf, O_RDONLY | O_NONBLOCK );
	if (fd < 0) {
		perror("gpio/fd_open");
	}
	return fd;
}


int linux_gpio_fd_close(int fd)
{
	return close(fd);
}
