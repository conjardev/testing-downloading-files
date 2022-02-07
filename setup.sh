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

# Set up tables
sudo mysql -u root -proot -e "CREATE TABLE `Devices` (`UUID` int NOT NULL AUTO_INCREMENT,`ip` text NOT NULL,`Name` text NOT NULL,`Deployment` text NOT NULL,`Recording` text NOT NULL,`Type` text NOT NULL,PRIMARY KEY (`UUID`)) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci"

# Done
printf "\n\n"
echo "The install is done, complete the setup on any LAN device at:"
hostname -I

# Tidy up
# Remove cloned "setup.sh"
sudo rm /var/www/html/setup.sh

# Remove cloned "readme.md"
sudo rm /var/www/html/readme.md

# Delete this script
sudo rm setup.sh
