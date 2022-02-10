# - This is currently unfinished -
This is a linux-based DIY protection system.

# Installation
**This program is currently only available on Ubuntu, more distros are coming soon**<br>
No dependancies need to be installed for any distros, the provided `setup.sh` script is all that needs to be run.

***It is highly advised NOT to download and manually install, the `setup.sh` script should be used in almost all scenarios***

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

## Manual
❗**This is officially unsupported, this may not work with the full featureset.**<br>
❗**It is only advised to use this if your distro is not supported!**

#### Install
##### Download dependancies
- Update your package manager
- Install the following dependancies
  - Git
  - Apache 2
  - Mysql-Server
  - PHP
  - PHP-mysqlnd
- Restart apache.
##### Setup MySQL
- Enter `mysql` with the `sudo mysql -u root` command
- Perform the following commands:
  ```    
  CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
  GRANT ALL PRIVILEGES on *.* TO 'username'@'localhost';
  ```
##### Clone the repository
- Clone the repository into `/var/www/html`
  - Option A: Manual
    - Find and select the "Code" tab on the repository, download and unzip it in the target file
   - Option B: Using git
     - `sudo git clone https://github.com/conjardev/testing-downloading-files.git`
##### Tidy up
- Remove `setup.sh` from your clone (`sudo rm /var/www/html/setup.sh`)
- Remove `readme.md` from your clone (`sudo rm /var/www/html/readme.md`)
##### Complete setup
The ***only*** way (that I am aware of) to complete the setup is through the web-based install wizard.
Depending on your distribution, find the IP adress of your device, and enter it into a browser, if all goes well, you will see a page instructing you on how to finish setting up the controller.

#### Update
To update a manual installation, you need to pull down the latest version from GitHub.
No manual changes are required **at this time**
