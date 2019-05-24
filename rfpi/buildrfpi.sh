#!/bin/bash
clear
echo Compiling the rfpi.c ......
gcc -o rfpi rfpi.c
mv rfpi bin
chmod 777 -R /etc/rfpi/bin/
