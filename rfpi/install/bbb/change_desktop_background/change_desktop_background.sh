#!/bin/bash
clear
echo "Substituting picture of the desktop background....."
cp /opt/desktop-background.jpg /etc/rfpi/install/bbb/change_desktop_background/desktop-background.original.jpg
cp /etc/rfpi/install/bbb/change_desktop_background/desktop-background.jpg /opt/desktop-background.jpg
echo "Done!"
