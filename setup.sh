#!/bin/bash

# Update
echo "Updating apt"
sudo apt -qq update && sudo apt -qq -y upgrade

# Install dependancies
echo "Installing dependancy: git"
sudo apt -qq install -y git
echo "Installing dependancy: apache2"
sudo apt -qq install -y apache2
echo "Installing dependancy: mysql-server"
sudo apt -qq install -y mysql-server
echo "Installing dependancy: php"
sudo apt -qq install -y php

# Empty /var/www/html
sudo rm -rf /var/www/html

# Clone into /var/www/html
sudo git clone https://github.com/conjardev/testing-downloading-files.git /var/www/html

# Done
printf "\n\n"
echo "The install is done, complete the ssetup on any LAN device at:"
hostname -I
