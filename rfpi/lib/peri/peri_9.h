/******************************************************************************************

Programmer: 					Emanuele Aimone
Last Update: 					16/06/2017


Description: library for the peripheral


******************************************************************************************/


#define MAX_NUM_OF_DATA_PERI9							20		//+10 samples
#define NUM_CHARACTERS_IN_A_LINE_OF_THE_FILE_PERI9		100
#define	PATH_FILE_DATA_PERI9							"/var/www/lib/peripheral_9/data/"
#define	LENGHT_ARRAY_DATA_FROM_PERI9					10


#define	NUM_FORMULA_IN0									15
#define	NUM_FORMULA_IN1									5
#define	NUM_FORMULA_IN2									13
#define	NUM_FORMULA_IN3									14
#define	NUM_FORMULA_IN4									16


#define	HEADER_FORMULA_1								"Pyrometer (°C)"
#define	FORMULA_1_MAX_VALUE								1300 //°C
#define	FORMULA_1_MIN_VALUE								300	 //°C

#define	HEADER_FORMULA_2								"TVDC (V)"

#define	HEADER_FORMULA_4								"IN0 (V)"
#define	HEADER_FORMULA_5								"IN1 (V)"
#define	HEADER_FORMULA_6								"IN2 (V)"
#define	HEADER_FORMULA_7								"IN3 (V)"
#define	HEADER_FORMULA_8								"Heater Running (1=YES 0=NO)"

#define	HEADER_FORMULA_9								"Power (KW)"
#define	FORMULA_9_MAX_VALUE								400 //KW
#define	FORMULA_9_MIN_VALUE								0 //KW

#define	HEADER_FORMULA_10								"Production (mt/min)"
#define	FORMULA_10_MAX_VALUE							5 //mt/min
#define	FORMULA_10_MIN_VALUE							0 //mt/min

#define	HEADER_FORMULA_11								"Power Ref. (%)"
#define	HEADER_FORMULA_12								"Production Ref. (%)"

#define	HEADER_FORMULA_13								"IN2 Temp."
#define	HEADER_FORMULA_14								"IN3 Temp."
#define	HEADER_FORMULA_15								"Flow Meter"
#define	HEADER_FORMULA_16								"Recorder"


#define	HEADER_COLUM_9									"TIME"
#define	HEADER_COLUM_10									"DATE"


unsigned int varMaxNumData_PERI9 = 20;

//save the data sent by the Peri9 into a file
void save_data_peri9_into_a_file(unsigned char *dataRFPI, unsigned char *address);

//get the config to save the data
void get_conf_data_peri9(unsigned char *address);

//save the data sent by the Peri9 into a temproary file and when the maximum is reached then send an email with the file
void save_data_peri9_into_a_file_and_send_email(unsigned char *dataRFPI, unsigned char *address, unsigned char *namePeri);

void return_str_with_formula_name(char *str_to_return, unsigned char num_formula_to_show);
float return_number_with_applied_formula(int num_formula, unsigned char ADC_8bit_value);

void sprintf_float_number(char *str, float float_number, unsigned char num_of_decimal);
float voltage_0to10V_from_8bit_value_peri9(unsigned char ADC_8bit_value);
float temperature_pyrometer_peri9(unsigned char ADC_8bit_value);
float voltage_TVDC_peri9(unsigned char ADC_8bit_value);
float power_KW_peri9(unsigned char ADC_8bit_value);
float production_mt_min_peri9(unsigned char ADC_8bit_value);
float rif_power_percent_peri9(unsigned char ADC_8bit_value);
float rif_production_percent_peri9(unsigned char ADC_8bit_value);
float temperatura_int_ext_peri9(unsigned char ADC_8bit_value); //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori
float flow_meter_peri9(unsigned char ADC_8bit_value); //fatto per lo scatolotto che registra il Delta Temperature su raffreddamento tristori

void save_data_peri9_into_a_file_1row_7data(unsigned char *dataRFPI, unsigned char *address); //save the data sent by the Peri9 into a file, into a row there will be data0, data1, data2, data3, data4, time, date
