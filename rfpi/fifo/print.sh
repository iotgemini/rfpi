#!/bin/bash
echo "FIFO RFPISTATUS:"
while : ; do
	clear 
    cat /etc/rfpi/fifo/fiforfpistatus
	sleep 1
    #do other stuff
done
