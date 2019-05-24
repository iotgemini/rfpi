/******************************************************************************************

	Last Update: 			11 / 04 / 2019
	
	Description:			library
							
	
	License:				This software is provided in a way
							free and without any warranty
							The software can be modified and
							unlimited copied.
							The author does not assume any
							liability for damage brought or
							caused by this software.
							
	Licenza:				Questo software viene fornito in modo 
							gratuito e senza alcuna garanzia
							Il software può essere modificato e 
							copiato senza limiti.
							L'autore non si assume nessuna 
							responsabilità per danni portati o
							causati da questo software.

******************************************************************************************/


#ifndef BBB_GPIO_H_
#define BBB_GPIO_H_

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
	LOW_BBB = 0,
	HIGH_BBB = 1
} PIN_VALUE;


int bbb_gpio_export(unsigned int num_gpio);
int bbb_gpio_unexport(unsigned int num_gpio);
int bbb_gpio_set_dir(unsigned int num_gpio, PIN_DIRECTION out_flag);
int bbb_gpio_set_value(unsigned int num_gpio, PIN_VALUE value);
int bbb_gpio_get_value(unsigned int num_gpio, unsigned int *value);
int bbb_gpio_set_edge(unsigned int num_gpio, char *edge);
int bbb_gpio_fd_open(unsigned int num_gpio);
int bbb_gpio_fd_close(int fd);

#endif /* BBB_GPIO_H_ */
