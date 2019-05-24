#!/bin/bash
#clear
sudo systemctl stop serial-getty@ttyS0.service
sudo chmod 777 /dev/ttyS0