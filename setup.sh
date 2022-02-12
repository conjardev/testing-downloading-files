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
echo "Installing dependancy: php-mysqlnd"
sudo apt -qq install -y php-mysqlnd

# Restart for luck
echo "Restarting Apache"
sudo service apache2 restart

# Set up default MYSQL user
# The default username and password are "username" and "password"
# However these values are changed on setup.
echo "Creating user"
mysql -u root <<EOF
CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES on *.* TO 'username'@'localhost';
EOF

# Empty /var/www/html
sudo rm -rf /var/www/html

# Clone into /var/www/html
sudo git clone https://github.com/conjardev/testing-downloading-files.git /var/www/html

# Allow write permissions for files that need to be written to
sudo chmod 706 /var/www/html/configuration/passwords.json # Allow write to passwords file
sudo chmod 706 /var/www/html/configuration/controllerInfo.json # Allow write to controller info file

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
