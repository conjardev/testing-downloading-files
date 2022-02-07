Install:
```
sudo wget https://raw.githubusercontent.com/conjardev/testing-downloading-files/main/setup.sh && sudo chmod 774 setup.sh  && sudo ./setup.sh
```
Caution : This will clear the `/var/www/html` folder of all it's contents. This is designed to be used on a clean install,
if you intend to use this with Apache already installed, ensure you are not in the `/var/www/html` directory when you run this command.

This command will Download, Prepare and Run the setup command


Update
```
cd /var/www/html/ && sudo git pull https://github.com/conjardev/testing-downloading-files.git main
```
