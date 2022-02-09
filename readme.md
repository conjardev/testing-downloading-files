# Test Repo
This is a linux-based DIY protection system.

# Installation
**This program is currently only available on Ubuntu, more distros are coming soon**<br>
No dependancies need to be installed for any distros, the provided `setup.sh` script is all that needs to be run.

***It is highly advised NOT to download and manually install, the `startup.sh` script should be used in almost all scenarios***

## Ubuntu

#### Install
```
sudo wget https://raw.githubusercontent.com/conjardev/testing-downloading-files/main/setup.sh && sudo chmod 774 setup.sh  && sudo ./setup.sh
```
> Download, prepare and execute `startup.sh`

⚠️: This will clear the `/var/www/html` folder of all it's contents.<br>
⚠️: This is designed to be used on a clean install, if you intend to use this with Apache already installed, ensure you are not in the `/var/www/html` directory when you run this command.
<br><br><br>
#### Update
```
cd /var/www/html/ && sudo git pull https://github.com/conjardev/testing-downloading-files.git main
```
> Downloads the latest version of this repository into the appropriate directory
