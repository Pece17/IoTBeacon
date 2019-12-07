# IoT Beacon "Lighthouse"

IoT based project for Haaga-Helia University of Applied Sciences, ICT Infrastructure Project -course. End product "Lighthouse" will be able to scan data from Bluetooth beacons with Raspberry Pi -computers and forward it to a database i.e. a server. The data will then be used to build a HTML based application which either alerts when the beacons leave a designated area or alerts when they enter a forbidden area.


## Project team

- Rasmus Ekman - Project manager
- Pekka Hämäläinen - Project worker and secretary
- Niko Kulmanen - Project worker
- Joni Mattsson - Project worker


## Index

- [1. Xubuntu server](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#1-xubuntu-server)
- [2. Bluetooth beacons](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#2-bluetooth-beacons)
- [3. Raspberry Pis](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#3-raspberry-pis)
- [4. Scripts and files](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#4-scripts-and-files)
- [5. Running and stopping scripts](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#5-running-and-stopping-scripts)
- [6. Database](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#6-database)
- [7. Testing](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#7-testing)
- [8. In conclusion](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#8-in-conclusion)
- [9. Further development](https://github.com/Rasmusekmanhh/IoTBeacon/blob/master/README.md#9-further-development)


# 1. Xubuntu server

After initialization, show a cleaned up list of shortened specifications and hardware information of the server

```
sudo lshw -short -sanitize
```

To find out more specific information about the graphics card of the server, list all PCI (Peripheral Component Interconnect) devices - simply put, they are devices connected to the motherboard via PCI interface or bus, as it is also called

```
sudo lspci
```

Only show PCI information about the graphics card

```
lspci | grep -i --color 'vga\|3d\|2d'
```

Find out the operating system version

```
cat /etc/os-release
```

Here are the specifications of the server we are using

- Model - ```HP Compaq 8200 Elite CMT (Convertible Minitower) PC```
- Processor - ```Intel Core i5-2400 3.10GHz```
- Standard memory - ```4GiB DIMM DDR3 Synchronous 1333 MHz```
- Graphics card - ```Intel Corporation 2nd Generation Core Processor Family Integrated Graphics Controller```
- Operating system - ```Xubuntu 16.04.6 LTS```


## 1.1. Creating a Linux USB stick

We made a bootable Linux USB stick from Kingston DataTraveler 100 8GB USB 2.0 Flash Drive by downloading Rufus 3.8 application from https://rufus.ie/ and using it on a Windows computer to create an ISO (International Standards Organization) image with Xubuntu 16.04.3 that we downloaded from http://cdimage.ubuntu.com/xubuntu/releases/16.04.3/release/ - Xubuntu is a lighter derivative of the Ubuntu operating system

We initially tried using an UNetboot based Linux USB stick but it was not recognized by the server computer we chose to use on this project


## 1.2. Initializing the server

We had trouble getting Xubuntu to boot from the previously created Linux USB stick, but with these steps we got it working eventually

- Remove LAN (local area network) cable before turning on the server computer
- Open Legacy Boot Menu with ```F9``` key or other key that works with a particular version
- Choose Linux USB stick in Legacy Boot Menu

The terminal gave a prompt "A start job is running for Hold until boot process finishes up" and booting didn't seem to advance

We restarted the server computer multiple times until the system finally booted properly, and executed the following steps during installation

1. ```English``` and ```Install Xubuntu```
2. ```Continue```
3. ```Erase disk and install Xubuntu``` and ```Install Now```
4. ```Continue```
5. ```Helsinki``` and ```Continue```
6. ```Finnish```, ```Finnish```, and ```Continue```
7. ```Your name: iotbeacon```, ```Your computer's name: rauta```, ```Pick a username: iotbeacon```, ```Choose a password: MonialaProjekti```, ```Confirm your password: MonialaProjekti```, ```Require my password to log in```, and ```Continue```
8. ```Restart Now```

When Xubuntu operating system restarts and opens the desktop screen, open the Linux terminal - we will be mainly using terminal to configure the server

```
Ctrl + Alt + T
```

Change keyboard layout to Finnish keyboard, so that the keys respond correctly when typing

```
setxkbmap fi
```

Check the name of the current user, in this case the user is ```iotbeacon``` because that is the only user on this server

```
whoami
```

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Upgrade to newer versions of the packages the server has installed

```
sudo apt-get upgrade
```

Reboot the system

```
sudo reboot
```


## 1.3. Configuring firewall on the server

Start configuring firewall by allowing port 22, a crucial step especially if you are configuring a virtual server because this port is responsible for allowing SSH - if you don't allow this port on a virtual server before enabling firewall, you are essentially blocking your access to it 

```
sudo ufw allow 22/tcp
```

Allow port 80 that is responsible for HTTP (Hypertext Transfer Protocol)

```
sudo ufw allow 80/tcp
```

Finally, allow port 443 that is responsible for HTTP over TLS/SSL (Transport Layer Security/Secure Sockets Layer)

```
sudo ufw allow 443/tcp
```

Enable firewall after allowing the desired ports

```
sudo ufw enable
```

Even though firewall is now enabled, it is possible to allow other ports later on if needed


## 1.4. Installing Apache2 Web Server on the server

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Install Apache2 Web Server

```
sudo apt-get install apache2
```

Reboot the system

```
sudo reboot
```

Try typing localhost on web browser address bar - it works and opens the Apache2 default page

Find out the current dynamic IP address of the server

```
hostname -I
```

Try the dynamic IP address ```x.x.x.x``` on web browser address bar - this also works and opens the Apache2 default page

Enable ```userdir``` Apache2 module 

```
sudo a2enmod userdir
```

Restart the Apache2 service

```
service apache2 restart
```

Go to the home directory and make the public_html folder, list the contents of the home directory to check that the public_html folder was succesfully created, after which navigate inside the public_html folder and create the index.html file

```
cd
```

```
mkdir public_html
```

```
ls
```

```
cd public_html
```

```
nano index.html
```

I forgot to create the index.html file with sudo (superuser do) permission so I delete the previous file recursively and create a new, secure index.html file

```
rm -r index.html
```

```
sudo nano index.html
```

Copy basic HTML (Hypertext Markup Language) template from https://www.w3schools.com/html/tryit.asp?filename=tryhtml_basic_document to the index.html file and add some text to headings

```
<!DOCTYPE html>
<html>
<body>

<h1>IoT Beacon</h1>

<p>Monialaprojekti</p>

<p>Web application will be added here<p>

</body>
</html>
```

Save the file

```
Ctrl + X + Y + Enter
```

Check the name of the current Xubuntu user - it is needed to open the the index page that we previously made by creating and editing the index.html file

```
whoami
```

Open addresses http://localhost/~iotbeacon and http://x.x.x.x/~iotbeacon using web browser

Both addresses work successfully inside the lab environment

Navigate to sites-available directory and open the 000-default.conf file

```
cd /etc/apache2/sites-available
```

```
ls
```

```
sudo nano 000-default.conf
```

Edit Apache2 Virtual Hosts by removing hashtags before ServerName and ServerAlias lines and by adding temporary domain names www.iotbeacon.com and iotbeacon.com respectively in front of them - they are used for testing purposes, and will only work when using web browser on the server

```
<VirtualHost *:80>
        # The ServerName directive sets the request scheme, hostname and port that
        # the server uses to identify itself. This is used when creating
        # redirection URLs. In the context of virtual hosts, the ServerName
        # specifies what hostname must appear in the request's Host: header to
        # match this virtual host. For the default virtual host (this file) this
        # value is not decisive as it is used as a last resort host regardless.
        # However, you must set it for any further virtual host explicitly.
        ServerName www.iotbeacon.com
        ServerAlias iotbeacon.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html

        # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
        # error, crit, alert, emerg.
        # It is also possible to configure the loglevel for particular
        # modules, e.g.
        #LogLevel info ssl:warn

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        # For most configuration files from conf-available/, which are
        # enabled or disabled at a global level, it is possible to
        # include a line for only one particular virtual host. For example the
        # following line enables the CGI configuration for this host only
        # after it has been globally disabled with "a2disconf".
        #Include conf-available/serve-cgi-bin.conf
</VirtualHost>

# vim: syntax=apache ts=4 sw=4 sts=4 sr noet
```

Restart Apache2

```
service apache2 restart
```

Navigate to etc directory and open the hosts file

```
cd /etc/
```

```
sudo nano hosts
```

Edit the hosts file by creating two lines starting with 127.0.0.1 loopback addresses and adding www.iotbeacon.com and iotbeacon.com respectively in front of them - temporary domain name configuration is now complete and should be working when using web browser on the server

```
127.0.0.1       localhost
127.0.1.1       rauta
127.0.0.1       www.iotbeacon.com
127.0.0.1       iotbeacon.com

# The following lines are desirable for IPv6 capable hosts
::1     ip6-localhost ip6-loopback
fe00::0 ip6-localnet
ff00::0 ip6-mcastprefix
ff02::1 ip6-allnodes
ff02::2 ip6-allrouters
```

www.iotbeacon.com/~iotbeacon and iotbeacon.com/~iotbeacon are now working and showing the contents of the previously created index.html file when using web browser on the server - addresses www.iotbeacon.com and iotbeacon.com open the Apache2 default page as supposed


## 1.5. Configuring static IP addresses on the server using CLI and GUI

I initially tried to configure the assigned static IP address using only CLI (command line interface)

Find out the operating system version

```
cat /etc/os-release
```

Find out the name of the used LAN interface

```
clear && echo $(ip -o -4 route get 8.8.8.8 | sed -nr 's/.*dev ([^\ ]+).*/\1/p')
ifconfig
```

Edit ```/etc/network/interfaces``` file

```
sudo nano /etc/network/interfaces
```

Add the required parameters for the static IP address - don't remove any old text from inside the file

```
# interfaces(5) file used by ifup(8) and ifdown(8)
auto lo
iface lo inet loopback

auto eno1
iface eno1 inet static
address 172.28.175.41
netmask 255.255.0.0
gateway 172.28.1.254
dns-nameservers 172.28.170.201 172.28.170.202
```

Flush IP

```
sudo ip addr flush eno1
```

Restart networking service

```
systemctl restart networking.service
```

Reboot the system

```
sudo reboot
```

Configuration does seem to work using command line, but I still remove any changes I did in terminal just to be safe - I read many tutorials stating that it is highly recommended to configure IP addresses with GUI (graphical user interface) using ```Network Connections``` application in current versions of Ubuntu, because the application might override any command line changes nevertheless

1. Go to desktop and open ```Edit Connections...``` from upper right corner under a symbol depicting two arrows
2. Choose ```Ethernet```, ```Wired connection 1```, and ```Edit```
3. Go to ```IPv4 Settings```
4. Change ```Method:``` to ```Manual```
5. Under ```Addresses```, add ```172.28.175.41``` to ```Address```, ```16``` to ```Netmask```, and ```172.28.1.254``` to ```Gateway```
6. Add ```172.28.170.201``` and ```172.28.170.202``` to ```DNS servers:```, and ```Save```
7. Open ```Connection Information``` from upper right corner under the same symbol depicting two arrows
8. Check that the IPv4 settings are correct

Reboot the system

```
sudo reboot
```

Internet is now working, and pinging from other lab environment computers is successful

Both terminal and graphical configuration had problems at first with the assigned lab environment DNS addresses since I couldn't get them working at all - only after I changed the DNS addresses to Google Public DNS addresses ```8.8.8.8``` and ```8.8.4.4```, did I get the internet working, but now finally the lab environment DNS addresses are working, apparently due to a fix from higher-ups, so I configure them again on the server and keep Google Public DNS as a backup option


## 1.6. Establishing SSH connection with terminal to the server

Install SSH (Secure Shell) client and server to the server

```
sudo apt-get install -y openssh-server openssh-client
```

After installing SSH, I connect to the server with another Linux terminal within the lab environment

```
ssh iotbeacon@172.28.175.41
```

Other project worker tries to connect to the server from his house using Linux terminal and SSH - connection is not successful because you can't reach these static IP addresses outside of the lab environment


## 1.7. Installing Firefox on the server

Establish an SSH connection in terminal using another Linux computer withing lab environment

```
ssh iotbeacon@172.28.175.41
```

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Update Firefox browser because default browser on the server does not support all sites, specifically GitHub

```
sudo apt-get install firefox
```

GitHub is now supported by Firefox and writing GitHub README.md report can be done simultaneously with the server while configuring it


## 1.8. Updating the server from version 16.04.3 to 16.04.6

Server operating system is updated to a newer version of 16.04 LTS (Long Term Support) via graphical user interface prompt

Check if the operating system version changed

```
cat /etc/os-release
```

OS version is now 16.04.6, shut down the server before leaving the server room

```
sudo poweroff
```


## 1.9. Installing MariaDB database on the server

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Install MariaDB relational database

```
sudo apt-get install mariadb-server mariadb-client
```

MariaDB database is now installed


## 1.10. Installing PHP and PHP modules on the server

Check command history to see what commands other project workers have given to the server - PHP does not seem to be installed yet based on the command history

```
history
```

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Install PHP (Hypertext Preprocessor) and PHP module for Apache2 web server, one of many modules available for PHP

```
sudo apt-get install php libapache2-mod-php
```

Navigate to mods-available directory and list the contents

```
cd /etc/apache2/mods-available
ls
```

Edit php7.0.conf file, add hashtags to following lines, and save

```
sudoedit php7.0.conf
```

```
#<IfModule mod_userdir.c>
#    <Directory /home/*/public_html>
#        php_admin_flag engine Off
#    </Directory>
#</IfModule>
```

```
Ctrl + X
Y
Enter
```

Restart Apache2

```
sudo service apache2 restart
```

Navigate to public_html folder and list the contents

```
cd
cd public_html
ls
```

Open the index.html file, copy the contents from inside, and exit

```
sudo nano index.html
```

```
<!DOCTYPE html>
<html>
<body>

<h1>IoT Beacon</h1>

<p>Monialaprojekti</p>

<p>Web application will be added here<p>

</body>
</html>
```

Delete the ```index.html``` file and check that it is removed

```
sudo rm -r index.html
ls
```

Create a new file called ```index.php```, paste the previously copied HTML inside it, and add a simple calculation of 1+3 to test the function of PHP

```
sudo nano index.php
```

```
<!DOCTYPE html>
<html>
<body>

<h1>IoT Beacon</h1>

<p>Monialaprojekti</p>

<p>Web application will be added here<p>

<?php

print(1+3);

?>

</body>
</html>
```

Exit and save the file, and open address http://localhost/~iotbeacon to test if PHP is working correctly

The web page now shows the previously written headings and number 4, indicating that the PHP calculation was successful


## 1.11. Installing Salt Master on the server

Install Salt Master

```
sudo apt-get -y install salt-master
```

Allow port 4505 that is responsible for Salt Master

```
sudo ufw allow 4505/tcp
```

Allow port 4506 that is also responsible for Salt Master

```
sudo ufw allow 4506/tcp
```

List all public keys

```
sudo salt-key -L
```

Accept all public keys for Salt Minions ```raspberrypi1```, ```raspberrypi2```, and ```raspberrypi3``` - do this only after installing and configuring Salt Minion to all three Raspberry Pis

```
sudo salt-key -A
```

The following output appears for which press ```Y``` to proceed

```
The following keys are going to be accepted:
Unaccepted Keys:
raspberrypi1
raspberrypi2
raspberrypi3
Proceed? [n/Y]
```

The following output appears which indicates that keys for Salt Minions ```raspberrypi1```, ```raspberrypi2```, and ```raspberrypi3``` were accepted

```
Key for minion raspberrypi1 accepted.
Key for minion raspberrypi2 accepted.
Key for minion raspberrypi3 accepted.
```

Using Salt command, check the static IP addresses of all three Salt Minions

``` 
sudo salt '*' cmd.run 'hostname -I'
``` 

Send a message to all Salt Minions and tell them to return ```True``` to check which Salt Minions are alive

```
sudo salt '*' test.ping
```


## 1.12. Establishing SSH connection with PuTTY to the server

To use PuTTY application to connect to the server, we first need to open the VDI (virtual desktop infrastructure) of Haaga-Helia from address https://vdi.haaga-helia.fi/vpn/index.html so that we can be in that same lab environment remotely

Once in VDI desktop, we need to open the PuTTY application in Windows and enter the IP address ```172.28.175.41``` of the server and use the port ```22``` for SSH connection - the terminal prompts a login screen after which the server terminal unlocks


# 2. Bluetooth beacons

Go to address https://www.aliexpress.com/item/32776774253.html to view the page of the online store where the Bluetooth beacons were bought

- Three Bluetooth low energy (BLE) beacons
- Model - ```Wellcore Bluetooth Ibeacon NRF51822 beacon Wristband Ibeacons Module with APP```
- Beacons broadcast their identifier to nearby devices that are Raspberry Pis in this project


# 3. Raspberry Pis

After initialization, update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Install lshw (Hardware Lister)

```
sudo apt-get install lshw
```

Show a cleaned up list of shortened specifications and hardware information of Raspberry Pis

```
sudo lshw -short -sanitize
```

Find out the operating system version

```
cat /etc/os-release
```

Here are the specifications of Raspberry Pis we are using

- Model - ```Raspberry Pi 3 Model B Rev 1.2```
- Processor - ```Quad Core 1.2GHz Broadcom BCM2837 64bit CPU```
- Standard memory - ```926MiB System memory```
- Graphics card - ```Integrated```
- Operating system - ```Raspbian GNU/Linux 10 (buster)```


## 3.1. Initializing Raspberry Pis

- Heat sinks
- Case
- MicroSD card with Raspberry Pi NOOBS
- keyboard
- Mouse
- 5.1 V / 2.5 A USB power supply
- HDMI cable
- Display


## 3.2. Creating a new sudo user on Raspberry Pis

Create a new user ```projektimies``` on all three Raspberry Pis, since we don't want to use the default root user ```pi```

```
sudo adduser projektimies
```

The following output appears and we need to assign a new password and repeat it - we press ```Enter``` for empty for all other values, and finally press ```Y``` to confirm the information

```
Adding user `projektimies' ...
Adding new group `projektimies' (1001) ...
Adding new user `projektimies' (1001) with group `projektimies' ...
Creating home directory `/home/projektimies' ...
Copying files from `/etc/skel' ...
New password:
Retype new password:
passwd: password updated successfully
Changing the user information for projektimies
Enter the new value, or press ENTER for the default
        Full Name []:
        Room Number []:
        Work Phone []:
        Home Phone []:
        Other []:
Is the information correct? [Y/n]
```

Give user ```projektimies``` sudo admin privileges

```
sudo adduser projektimies sudo
```

Add new user ```projektimies``` to same groups as root user ```pi```

```
for GROUP in $(groups pi | sed -e 's/^pi //'); do
sudo adduser projektimies $GROUP; done
```

Copy ```nopasswd``` rules from root user ```pi``` to user ```projektimies```

```
sudo cp /etc/sudoers.d/010_pi-nopasswd /etc/sudoers.d/010_projektimies-nopasswd
```

Give the following command related to ```nopasswd``` rules

```
sudo chmod u+w /etc/sudoers.d/010_projektimies-nopasswd
```

Give the following command related to ```nopasswd``` rules

```
sudo sed -i 's/pi/projektimies/g' /etc/sudoers.d/010_projektimies-nopasswd
```

Give the following command related to ```nopasswd``` rules

```
sudo chmod u-w /etc/sudoers.d/010_projektimies-nopasswd
```

Reboot the system

```
sudo reboot
```

If the log in screen opens after rebooting, log in as user ```projektimies```

If the log in screen does not open automatically, and opens the root user ```pi``` desktop instead, open the terminal and log in manually as user ```projektimies```

```
su - projektimies
```

Delete root user ```pi```

```
sudo deluser pi
```

Delete root user ```pi``` home directory

```
sudo deluser --remove-home pi
```

Delete ```nopasswd``` rules from root user ```pi```

```
sudo rm -vf /etc/sudoers.d/010_pi-nopasswd
```


## 3.3. Configuring static IP addresses on Raspberry Pis

Edit ```/etc/dhcpcd.conf```

```
sudo nano /etc/dhcpcd.conf 
```

Remove the the hashtags from the following lines and add your IP address, default gateway, and DNS addresses - ```interface eth0``` is LAN interface and ```interface wlan0``` is WLAN interface, we added our IP addresses to both just in case

```
# Example static IP configuration:
interface eth0
static ip_address=172.28.175.42
#static ip6_address=fd51:42f8:caae:d92e::ff/64
static routers=172.28.1.254
static domain_name_servers=172.28.170.201 172.28.170.202              

interface wlan0
static ip_address=172.28.175.42
static routers=172.28.1.254
static domain_name_servers=172.28.170.201 172.28.170.202
```

Reboot the system

```
sudo reboot  
```

Ping the local Raspberry Pi

```
Ping raspberrypi.local
```

Reached the new static IP address with a local ping

Repeat for Raspberry Pi 2 and Raspberry Pi 3, but change the static IP address to ```172.28.175.44``` and ```172.28.175.45``` respectively


## 3.4. Installing Salt Minion on Raspberry Pis

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Install Salt Minion

```
sudo apt-get -y install salt-minion
```

Edit ```/etc/salt/minion``` file

```
sudo nano /etc/salt/minion
```

Change ```# master: salt``` to ```master: 172.28.175.41``` that is the IP address of the server, and remove the hashtag

```
##### Primary configuration settings #####
##########################################
# This configuration file is used to manage the behavior of the Salt Minion.
# With the exception of the location of the Salt Master Server, values that are
# commented out but have an empty line after the comment are defaults that need
# not be set in the config. If there is no blank line after the comment, the
# value is presented as an example and is not the default.

# Per default the minion will automatically include all config files
# from minion.d/*.conf (minion.d is a directory in the same directory
# as the main minion config file).
#default_include: minion.d/*.conf

# Set the location of the salt master server. If the master server cannot be
# resolved, then the minion will fail to start.
master: 172.28.175.41
```

Also, change ```# id:``` to ```id: raspberrypi1``` and remove the hashtag

```
# Explicitly declare the id for this minion to use, if left commented the id
# will be the hostname as returned by the python call: socket.getfqdn()
# Since salt uses detached ids it is possible to run multiple minions on the
# same machine but with different ids, this can be useful for salt compute
# clusters.
id: raspberrypi1
```

Restart Salt Minion

```
sudo service salt-minion restart
```

Repeat for Raspberry Pi 2 and Raspberry Pi 3, but change the ```id:``` to ```raspberrypi2``` and ```raspberrypi3``` respectively


## 3.5. Establishing SSH connection with terminal to Raspberry Pis

Install SSH (Secure Shell) client and server to Raspberry Pis

```
sudo apt-get install -y openssh-server openssh-client
```

After installing SSH, I connect to the Raspberry Pi 1 with another Linux terminal within the lab environment

```
ssh projektimies@172.28.175.42
```

I connect to the Raspberry Pi 2 with another Linux terminal within the lab environment

```
ssh projektimies@172.28.175.44
```

I connect to the Raspberry Pi 3 with another Linux terminal within the lab environment

```
ssh projektimies@172.28.175.45
```


## 3.6. Establishing SSH connection with PuTTY to Raspberry Pis

To use PuTTY application to connect to Raspberry Pis, we first need to open the VDI (virtual desktop infrastructure) of Haaga-Helia from address https://vdi.haaga-helia.fi/vpn/index.html so that we can be in that same lab environment remotely

Once in VDI desktop, we need to open the PuTTY application in Windows and enter the IP address ```172.28.175.42``` of the Raspberry Pi 1, ```172.28.175.44``` of the Raspberry Pi 2, or ```172.28.175.45``` of the Raspberry Pi 3, and use the port ```22``` for SSH connection - the terminal prompts a login screen after which the server terminal unlocks
 
 
## 3.7. Establishing Remote Desktop Connection to Raspberry Pis

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Install Xrdp software to the Xrdp before we can connect remotely to it

```
sudo apt-get install xrdp
```

To use Remote Desktop Connection application to connect to Raspberry Pis, we first need to open the VDI (virtual desktop infrastructure) of Haaga-Helia from address https://vdi.haaga-helia.fi/vpn/index.html so that we can be in that same lab environment remotely

Once in VDI desktop, we need to open the Remote Desktop Connection application in Windows, enter the IP address ```172.28.175.42``` of the Raspberry Pi 1, ```172.28.175.44``` of the Raspberry Pi 2, or ```172.28.175.45``` of the Raspberry Pi 3, and connect - the application prompts a warning prompt where you need to press yes, after which the Raspberry Pi desktop opens a login screen and unlocks the desktop after entering the correct credentials


## 3.8. Changing the hostname on Raspberry Pis

Replace the old hostname ```raspberrypi``` with ```raspberrypi1```

```
sudo hostnamectl set-hostname raspberrypi1
```

Check that the new hostname was changed

```
hostname
```

Edit ```/etc/hosts``` file

```
sudo nano /etc/hosts
```

Add new hostname ```raspberrypi1``` after ```127.0.1.1```

```
127.0.0.1       localhost
::1             localhost ip6-localhost ip6-loopback
ff02::1         ip6-allnodes
ff02::2         ip6-allrouters

127.0.1.1       raspberrypi1
```

Reboot the system

```
sudo reboot
```

Repeat for Raspberry Pi 2 and Raspberry Pi 3, but change the hostnames to ```raspberrypi2``` and ```raspberrypi3``` respectively


## 3.9. Installing BlueZ on Raspberry Pis

Navigate to address https://github.com/singaCapital/BLE-Beacon-Scanner/blob/master/README.md to view the instructions for installing BlueZ Bluetooth stack for Linux kernel-based family of operating systems

```
sudo apt-get update
```

```
sudo apt-get install python-pip python-dev ipython
```

```
sudo apt-get install bluetooth libbluetooth-dev
```

```
sudo pip install pybluez
```

```
cd /lib/systemd/system
```

```
sudo nano bluetooth.service
```

```
[Unit]
Description=Bluetooth service
Documentation=man:bluetoothd(8)
ConditionPathIsDirectory=/sys/class/bluetooth

[Service]
Type=dbus
BusName=org.bluez
ExecStart=/usr/lib/bluetooth/bluetoothd --experimental
NotifyAccess=main
#WatchdogSec=10
#Restart=on-failure
CapabilityBoundingSet=CAP_NET_ADMIN CAP_NET_BIND_SERVICE
LimitNPROC=1

[Install]
WantedBy=bluetooth.target
Alias=dbus-org.bluez.service
```

```
sudo systemctl daemon-reload
```

```
sudo systemctl restart bluetooth
```

BlueZ is now installed


## 3.10. Installing PHP and PHP modules on Raspberry Pis

```
sudo apt-get install php php-mysql
```


# 4. Scripts and files

The Bluetooth scanner script needs to be able to locate Bluetooth beacons based on MAC addresses, and print an assigned ID like ```Beacon1```, the original MAC address, and the RSSI (Received Signal Strength Indicator) value of the desired beacon


## 4.1. Shell scripts

Copy scanner Shell script from https://stackoverflow.com/questions/27401918/detecting-presence-of-particular-bluetooth-device-with-mac-address

Create ```test``` directory for testing purposes in Rasperry Pis

```
sudo mkdir test
```

Navigate inside ```test``` directory

```
cd test
```

Create ```test.sh``` Shell file

```
sudo nano test.sh
```

Replace the placeholder MAC address and paste the desired MAC address in the Shell script

```
#!/bin/bash

sudo hcitool cc D0:2B:20:CE:3F:D4 2> /dev/null

while true
do
    bt=$(hcitool rssi D0:2B:20:CE:3F:D4 2> /dev/null)
    if [ "$bt" == "" ]; then
        sudo hcitool cc D0:2B:20:CE:3F:D4  2> /dev/null
        bt=$(hcitool rssi D0:2B:20:CE:3F:D4 2> /dev/null)
    fi

    echo "$bt"
done
```

```
#!/bin/bash

sudo hcitool cc AA:BB:CC:DD:EE:FF 2> /dev/null

while true
do
    bt=$(hcitool rssi AA:BB:CC:DD:EE:FF 2> /dev/null)
    if [ "$bt" == "F9:CB:56:29:BE:F7" ]; then
        sudo hcitool cc AA:BB:CC:DD:EE:FF  2> /dev/null
        bt=$(hcitool rssi AA:BB:CC:DD:EE:FF 2> /dev/null)
    fi

    echo "$bt"
done
```

```
#!/bin/bash
while [ 1 ]
do

# Set Parameters
Name='Bluetooth beacon'
bluetoothmac='F9:CB:56:29:BE:F7'


if [ "$?" = 0 ] ; then
bt1=$(l2ping -c1 -s32 -t1 "$bluetoothmac" > /dev/null && echo "On" || echo "Off")
if [[ $bt1 == 'On' ]]; then
device=$(echo "On")
technology="Bluetooth 1st attempt"
success="yes"
fi
fi


# If the device is still offline, declare it for processing
if [[ $success != 'yes' ]]; then
device=$(echo "Off")
fi


done
```


## 4.2. Python script ```BeaconScanner.py```

Go to address https://github.com/singaCapital/BLE-Beacon-Scanner to view the source of the ```BeaconScanner.py``` file

Run ```BeaconScanner.py``` python script with this command

```
sudo python BeaconScanner.py
```

Original ```BeaconScanner.py``` file

```  
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
	sock = bluez.hci_open_dev(dev_id)
	print ("\n *** Looking for BLE Beacons ***\n")
	print ("\n *** CTRL-C to Cancel ***\n")
except:
	print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
	while True:
		returnedList = ScanUtility.parse_events(sock, 10)
		for item in returnedList:
			print(item)
			print("")
except KeyboardInterrupt:
    pass
```

First modification to ```BeaconScanner.py``` file - this does not work correctly, and only prints ```Nope``` even though the beacon with the desired MAC address ```e2:e3:23:d1:b0:54``` is on

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                returnedList = ScanUtility.parse_events(sock, 10)
                for macAddress in returnedList:
                    if macAddress == "e2:e3:23:d1:b0:54":
                        print("Works")
                    else:
                        print("Nope")
except KeyboardInterrupt:
    pass
```

Second modification to ```BeaconScanner.py``` file where ```returnedList``` was changed to ```resultsArray``` and ```macAddress``` was changed to ```packet``` - ```except KeyError:``` was also added because KeyError was stopping the script, and we need to be able to bypass it to run the script in a continous loop, but this particular modification is not currently working and is giving ```Traceback (most recent call last):File "BeaconScanner.py", line 21, in <module> if packet ["macAddress"] == "e2:e3:23:d1:b0:54":KeyError: error 'macAddress'```

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                resultsArray = ScanUtility.parse_events(sock, 10)
                for packet in resultsArray:
                        if packet ["macAddress"] == "e2:e3:23:d1:b0:54":
                                print("BEACON FOUND")
                        else:
                                print("Not found")
                        except KeyError:
                                print("Not found")
except KeyboardInterrupt:
    pass
```

Third modification to ```BeaconScanner.py``` file where ```KeyError:``` problem seemes to have been fixed, or at least it does not print it anymore

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                resultsArray = ScanUtility.parse_events(sock, 10)
                for packet in resultsArray:
                        if packet ["macAddress"] == "e2:e3:23:d1:b0:54":
                                print("BEACON FOUND")
                        else:
                                print("Not found")
except KeyboardInterrupt:
    pass
except KeyError:
        pass
```

Fourth modification to ```BeaconScanner.py``` file where we added ```elif``` statemets for three other beacons and named the prints of the four beacons to ```BEACON 1```, ```BEACON 2```, ```BEACON 3```, and ```BEACON 4``` respectively when they are found by the scanner, though ```BEACON 3``` is not physically working currently - next step is to get the script to run continuously in an infinite loop and start looking ways to recoqnize the beacons based on RSSI, or basically distance or signal strength

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                resultsArray = ScanUtility.parse_events(sock, 10)
                for packet in resultsArray:
                        if packet ["macAddress"] == "e2:e3:23:d1:b0:54":
                                print("BEACON 1")
                        elif  packet ["macAddress"] == "d6:2c:ca:c0:d4:9c":
                                print("BEACON 2")
                        elif packet ["macAddress"] == "f2:36:00:21:c0:50":
                                print("BEACON 3")
                        elif packet ["macAddress"] == "f9:cb:56:29:be:f7":
                                print("BEACON 4")
                        else:
                                print("Not found")
except KeyboardInterrupt:
    pass
except KeyError:
        pass
```

Fifth modification to ```BeaconScanner.py``` file where we replaced ```elif``` with three ```if``` statements and added countering ```else```

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                resultsArray = ScanUtility.parse_events(sock, 10)
                for packet in resultsArray:
                        if packet ["macAddress"] == "e2:e3:23:d1:b0:54":
                                print("BEACON 1")
			else:
                                print("Not found 1")
                        if  packet ["macAddress"] == "d6:2c:ca:c0:d4:9c":
                                print("BEACON 2")
			else:
                                print("Not found 2")
                        if packet ["macAddress"] == "f2:36:00:21:c0:50":
                                print("BEACON 3")
                        else:
                                print("Not found 3")
except KeyboardInterrupt:
    pass
except KeyError:
        pass
```

Sixth modification to ```BeaconScanner.py``` file where we added prints for variables ```macAddress``` and ```rssi```, in other words the script now prints the original message it receives from one beacon

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                resultsArray = ScanUtility.parse_events(sock, 10)
                for packet in resultsArray:
                        if packet ["macAddress"] == "e2:e3:23:d1:b0:54":
                                print("BEACON FOUND:")
                                print("macAddress:",packet ["macAddress"])
                                print("rssi:",packet ["rssi"])
                        else:
                                print("Not found")
except KeyboardInterrupt:
    pass
except KeyError:
        pass
```

Seventh modification to ```BeaconScanner.py``` file where we added the MAC addresses of two other beacons with ```or``` statements - this way multiple ```ìf``` or ```elif``` statements are not necessary

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                resultsArray = ScanUtility.parse_events(sock, 10)
                for packet in resultsArray:
                         if packet ["macAddress"] == "e2:e3:23:d1:b0:54" or packet ["macAddress"] == "d6:2c:ca:c0:d4:9c" or packet ["macAddress"] == "f2:36:00:21:c0:50":
                                print("BEACON FOUND:")
                                print("macAddress:",packet ["macAddress"])
                                print("rssi:",packet ["rssi"])
                else:
                                print("Not found")
except KeyboardInterrupt:
        pass
except KeyError:
        pass
```

Eight modification to ```BeaconScanner.py``` file where we removed ```else``` statement and moved ```print("Not found")``` one level higher, so that it is not dependent on beacons being in vicinity - now the script will print ```Not found``` when ```while True:``` is working

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                resultsArray = ScanUtility.parse_events(sock, 10)
                for packet in resultsArray:
                         if packet ["macAddress"] == "e2:e3:23:d1:b0:54" or packet ["macAddress"] == "d6:2c:ca:c0:d4:9c" or packet ["macAddress"] == "f2:36:00:21:c0:50":
                                print("BEACON FOUND:")
                                print("macAddress:",packet ["macAddress"])
                                print("rssi:",packet ["rssi"])
                print("Not found")
except KeyboardInterrupt:
        pass
except KeyError:
        pass
```

Ninth modification to ```BeaconScanner.py``` file where went back to the original ```BeaconScanner.py``` file

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                returnedList = ScanUtility.parse_events(sock, 10)
                for item in returnedList:
                        if item ["macAddress"] == "e2:e3:23:d1:b0:54":
                                print("macAddress:",item ["macAddress"])
                                print("BEACON FOUND")
                                print("rssi",item ["rssi"])
                print("Not found")
except KeyboardInterrupt:
    pass
```

Tenth modification to ```BeaconScanner.py``` file where we replaced ```if item ["macAddress"] == "e2:e3:23:d1:b0:54":``` with ```if item.get("macAddress") == "e2:e3:23:d1:b0:54":``` syntax, which seems to have solved the ```Traceback (most recent call last):
  File "BeaconScanner.py", line 21, in <module>
    if item ["macAddress"] == "e2:e3:23:d1:b0:54":
KeyError: 'macAddress'``` error we've had ever since modifying the original ```BeaconScanner.py``` file - we also added ```if``` statements for all three beacons, prints for ```macAddress``` and ```rssi``` based on beacon, and 

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                returnedList = ScanUtility.parse_events(sock, 10)
                for item in returnedList:
                        if item.get("macAddress") == "e2:e3:23:d1:b0:54":
                                print("")
                                print("BEACON 1")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                        if item.get("macAddress") == "d6:2c:ca:c0:d4:9c":
                                print("")
                                print("BEACON 2")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                        if item.get("macAddress") == "f2:36:00:21:c0:50":
                                print("")
                                print("BEACON 3")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                print("not found")
except KeyboardInterrupt:
    pass
```

Eleventh modification to ```BeaconScanner.py``` where we added ```import os``` and ```os.system("php /home/projektimies/torstai48/test.php")```

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez
import os

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                returnedList = ScanUtility.parse_events(sock, 10)
                for item in returnedList:
                        if item.get("macAddress") == "e2:e3:23:d1:b0:54":
                                print("")
                                os.system("php /home/projektimies/torstai48/test.php")
				print("BEACON1")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                        if item.get("macAddress") == "d6:2c:ca:c0:d4:9c":
                                print("")
                                print("BEACON2")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                        if item.get("macAddress") == "f2:36:00:21:c0:50":
                                print("")
                                print("BEACON3")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                print("not found")
except KeyboardInterrupt:
    pass
```

Twelfth and final modification to ```BeaconScanner.py``` file where we added ```os.system("php /home/projektimies/Lighthouse/DatabaseInsert2.php")``` and ```os.system("php /home/projektimies/Lighthouse/DatabaseInsert3.php")``` lines respectively for ```BEACON2``` and ```BEACON3```, since ```BEACON1``` already had the same line inside its ```if``` statement - we also changed the path for all the scripts in all Raspberry Pis to ```/home/projektimies/Lighthouse/```

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS

import ScanUtility
import bluetooth._bluetooth as bluez
import os

#Set bluetooth device. Default 0.
dev_id = 0
try:
        sock = bluez.hci_open_dev(dev_id)
        print ("\n *** Looking for BLE Beacons ***\n")
        print ("\n *** CTRL-C to Cancel ***\n")
except:
        print ("Error accessing bluetooth")

ScanUtility.hci_enable_le_scan(sock)
#Scans for iBeacons
try:
        while True:
                returnedList = ScanUtility.parse_events(sock, 10)
                for item in returnedList:
                        if item.get("macAddress") == "e2:e3:23:d1:b0:54":
                                print("")
                                os.system("php /home/projektimies/Lighthouse/DatabaseInsert1.php")
                                print("")
				print("BEACON 1")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                        if item.get("macAddress") == "d6:2c:ca:c0:d4:9c":
                                print("")
                                os.system("php /home/projektimies/Lighthouse/DatabaseInsert2.php")
                                print("")
				print("BEACON 2")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                        if item.get("macAddress") == "f2:36:00:21:c0:50":
                                print("")
                                os.system("php /home/projektimies/Lighthouse/DatabaseInsert3.php")
                                print("")
				print("BEACON 3")
                                print(item.get("macAddress"))
                                print(item.get("rssi"))
                                print("")
                print("not found")
except KeyboardInterrupt:
        pass
```


## 4.3. Python script ```ScanUtility.py```

Go to address https://github.com/singaCapital/BLE-Beacon-Scanner to view the source of the ```ScanUtility.py``` file

Original ```ScanUtility.py``` file

```
#This is a working prototype. DO NOT USE IT IN LIVE PROJECTS


import sys
import struct
import bluetooth._bluetooth as bluez

OGF_LE_CTL=0x08
OCF_LE_SET_SCAN_ENABLE=0x000C

def hci_enable_le_scan(sock):
    hci_toggle_le_scan(sock, 0x01)

def hci_disable_le_scan(sock):
    hci_toggle_le_scan(sock, 0x00)

def hci_toggle_le_scan(sock, enable):
    cmd_pkt = struct.pack("<BB", enable, 0x00)
    bluez.hci_send_cmd(sock, OGF_LE_CTL, OCF_LE_SET_SCAN_ENABLE, cmd_pkt)

def packetToString(packet):
    """
    Returns the string representation of a raw HCI packet.
    """
    if sys.version_info > (3, 0):
        return ''.join('%02x' % struct.unpack("B", bytes([x]))[0] for x in packet)
    else:
        return ''.join('%02x' % struct.unpack("B", x)[0] for x in packet)

def parse_events(sock, loop_count=100):
    old_filter = sock.getsockopt( bluez.SOL_HCI, bluez.HCI_FILTER, 14)
    flt = bluez.hci_filter_new()
    bluez.hci_filter_all_events(flt)
    bluez.hci_filter_set_ptype(flt, bluez.HCI_EVENT_PKT)
    sock.setsockopt( bluez.SOL_HCI, bluez.HCI_FILTER, flt )
    results = []
    for i in range(0, loop_count):
        packet = sock.recv(255)
        ptype, event, plen = struct.unpack("BBB", packet[:3])
        packetOffset = 0
        dataString = packetToString(packet)
        """
        If the bluetooth device is an beacon then show the beacon.
        """
        #print (dataString)
        if dataString[34:50] == '0303aafe1516aafe' or '0303AAFE1116AAFE':
            """
            Selects parts of the bluetooth packets.
            """
            broadcastType = dataString[50:52]
            if broadcastType == '00' :
                type = "Eddystone UID"
                namespace = dataString[54:74].upper()
                instance = dataString[74:86].upper()
                resultsArray = [
                {"type": type, "namespace": namespace, "instance": instance}]
                return resultsArray

            elif broadcastType == '10':
                type = "Eddystone URL"
                urlprefix = dataString[54:56]
                if urlprefix == '00':
                    prefix = 'http://www.'
                elif urlprefix == '01':
                    prefix = 'https://www.'
                elif urlprefix == '02':
                    prefix = 'http://'
                elif urlprefix == '03':
                    prefix = 'https://'
                hexUrl = dataString[56:][:-2]
                url = prefix + hexUrl.decode("hex")
                rssi, = struct.unpack("b", packet[packetOffset -1])
                resultsArray = [{"type": type, "url": url}]
                return resultsArray

            elif broadcastType == '20':
                type = "Eddystone TLM"
                resultsArray = [{"type": type}]
                return resultsArray

            elif broadcastType == '30':
                type = "Eddystone EID"
                resultsArray = [{"type": type}]
                return resultsArray

            elif broadcastType == '40':
                type = "Eddystone RESERVED"
                resultsArray = [{"type": type}]
                return resultsArray

        if dataString[38:46] == '4c000215':
            """
            Selects parts of the bluetooth packets.
            """
            type = "iBeacon"
            uuid = dataString[46:54] + "-" + dataString[54:58] + "-" + dataString[58:62] + "-" + dataString[62:66] + "-" + dataString[66:78]
            major = dataString[78:82]
            minor = dataString[82:86]
            majorVal = int("".join(major.split()[::-1]), 16)
            minorVal = int("".join(minor.split()[::-1]), 16)
            """
            Organises Mac Address to display properly
            """
            scrambledAddress = dataString[14:26]
            fixStructure = iter("".join(reversed([scrambledAddress[i:i+2] for i in range(0, len(scrambledAddress), 2)])))
            macAddress = ':'.join(a+b for a,b in zip(fixStructure, fixStructure))
            rssi, = struct.unpack("b", packet[packetOffset -1])

            resultsArray = [{"type": type, "uuid": uuid, "major": majorVal, "minor": minorVal, "rssi": rssi, "macAddress": macAddress}]

            return resultsArray

    return results
```


## 4.4. PHP scripts ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php```

Go to address https://www.w3schools.com/php/php_mysql_insert.asp to view the source of the ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php``` files

All three Raspberry Pis will have PHP scripts ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php```, but they are slightly different in all three Raspberry Pis, so there has to be a total of nine different PHP database insertion scripts


### 4.4.1. PHP scripts ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php``` on Raspberry Pi 1

This is the ```DatabaseInsert1.php``` file for Beacon 1 in Raspberry Pi 1 that inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_1";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon1', 'e2:e3:23:d1:b0:54', 'Joni', 'Mattsson', 'Servula');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
```

This is the ```DatabaseInsert2.php``` file for Beacon 2 in Raspberry Pi 1 that inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_1";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon2', 'd6:2c:ca:c0:d4:9c', 'Rasmus', 'Ekman', 'Servula');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
```

This is the ```DatabaseInsert3.php``` file for Beacon 3 that in Raspberry Pi 1 inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_1";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon3', 'f2:36:00:21:c0:50', 'Niko', 'Kulmanen', 'Servula');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
```

### 4.4.2. PHP scripts ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php``` on Raspberry Pi 2

This is the ```DatabaseInsert1.php``` file for Beacon 1 that in Raspberry Pi 2 inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_2";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon1', 'e2:e3:23:d1:b0:54', 'Joni', 'Mattsson', '5005');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
```

This is the ```DatabaseInsert2.php``` file for Beacon 2 that in Raspberry Pi 2 inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_2";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon2', 'd6:2c:ca:c0:d4:9c', 'Rasmus', 'Ekman', '5005');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

```

This is the ```DatabaseInsert3.php``` file for Beacon 3 that in Raspberry Pi 2 inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_2";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon3', 'f2:36:00:21:c0:50', 'Niko', 'Kulmanen', '5005');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
```


### 4.4.3. PHP scripts ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php``` on Raspberry Pi 3

This is the ```DatabaseInsert1.php``` file for Beacon 1 that in Raspberry Pi 3 inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_3";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon1', 'e2:e3:23:d1:b0:54', 'Joni', 'Mattsson', '5004');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
```

This is the ```DatabaseInsert2.php``` file for Beacon 2 that in Raspberry Pi 3 inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_3";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon2', 'd6:2c:ca:c0:d4:9c', 'Rasmus', 'Ekman', '5004');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
```

This is the ```DatabaseInsert3.php``` file for Beacon 3 that in Raspberry Pi 3 inserts data into the database

```
<?php
$servername = "172.28.175.41";
$username = "raspbian_3";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon3', 'f2:36:00:21:c0:50', 'Niko', 'Kulmanen', '5004');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
```


## 4.5. PHP website ```index.php```

Go to address https://www.w3schools.com/php/php_mysql_select.asp to view the source of the ```index.php``` file

This is the first version of ```index.php``` file which is the PHP script enclosed with HTML elements - this will be the actual website that the server shows in the lab environment

```
<!DOCTYPE html>
<html>
<body>

<h1>IoT Beacon</h1>

<p>Monialaprojekti</p>

<p>Web application will be added here<p>

<meta http-equiv="refresh" content="5" >

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user_first_name, user_last_name, beacon_name FROM room_1_output";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "   " . $row["user_first_name"]. " - Name: " . $row["user_last_name"]. " " . $row["beacon_name"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>
```

This is the second version of ```index.php``` where we edited HTML elements

```
<!DOCTYPE html>
<html>
<body>

<meta http-equiv="refresh" content="1" >

<title>Lighthouse</title>

<h1>Joni</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, updated FROM room_1_output ORDER BY updated DESC LIMIT 3";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "   " . $row["beacon_name"]. " -  Nähty viimeksi: " . $row["user_last_name"]. " " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>
```

This is the third version of ```index.php```

```
<!DOCTYPE html>
<html>
<body>

<meta http-equiv="refresh" content="1" >

<title>Lighthouse</title>

<h1>Joni</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, updated, user_first_name, user_last_name FROM room_1_output ORDER BY updated DESC LIMIT 3";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "   " . $row["beacon_name"]. " - " . $row["user_first_name"]. " " . $row["user_last_name"]. " - Last seen in ROOMPLACEHOLDER - " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<h1>Rasmus</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, updated, user_first_name, user_last_name FROM room_1_output ORDER BY updated DESC LIMIT 3";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "   " . $row["beacon_name"]. " - " . $row["user_first_name"]. " " . $row["user_last_name"]. " - Last seen in ROOMPLACEHOLDER - " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<h1>Niko</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, updated, user_first_name, user_last_name FROM room_1_output ORDER BY updated DESC LIMIT 3";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "   " . $row["beacon_name"]. " - " . $row["user_first_name"]. " " . $row["user_last_name"]. " - Last seen in ROOMPLACEHOLDER - " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>
```

This is the fourth and final version of ```index.php``` file

```
<!DOCTYPE html>
<html>
<body>

<meta http-equiv="refresh" content="1" >

<title>Lighthouse</title>

<h1>Joni (Beacon1)</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, user_first_name, user_last_name, room_name, updated FROM beaconusers WHERE beacon_name = 'Beacon1' ORDER BY updated DESC LIMIT 5;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "  "  . $row["beacon_name"].  " |  "  . $row["user_first_name"].  "   " . $row["user_last_name"].   " |  Last seen in " . $row["room_name"]. " |  " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<h1>Rasmus (Beacon2)</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, user_first_name, user_last_name, room_name, updated FROM beaconusers WHERE beacon_name = 'Beacon2' ORDER BY updated DESC LIMIT 5;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "  "  . $row["beacon_name"].  " |  "  . $row["user_first_name"].  "   " . $row["user_last_name"].   " |  Last seen in " . $row["room_name"]. " |  " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>


<h1>Niko (Beacon3)</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, user_first_name, user_last_name, room_name, updated FROM beaconusers WHERE beacon_name = 'Beacon3' ORDER BY updated DESC LIMIT 5;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "  "  . $row["beacon_name"].  " |  "  . $row["user_first_name"].  "   " . $row["user_last_name"].   " |  Last seen in " . $row["room_name"]. " |  " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>
```


# 5. Running and stopping scripts

We need to be able to run and stop all the scripts in all three Raspberry Pis efficiently and reliably, preferably using one command in Xubuntu server to start all the scripts, and another command to stop all the scripts in all three Raspberry Pis


## 5.1. Restarting Python script ```BeaconScanner.py``` automatically after exception and running it infinitely with script ```forever```

Go to address https://www.alexkras.com/how-to-restart-python-script-after-exception-and-run-it-forever/ to view the source of the ```forever``` file

Create the ```forever``` file

```
sudo nano forever
```

Paste the following text inside the ```forever``` file

```
#!/usr/bin/python
from subprocess import Popen
import sys

filename = sys.argv[1]
while True:
    print("\nStarting " + filename)
    p = Popen("python " + filename, shell=True)
    p.wait()
```

Make the ```forever``` file executable

```
sudo chmod +x forever
```

Now we can run the previously created ```BeaconScanner.py``` infinitely using ```forever``` file as a failsafe 

```
sudo ./forever BeaconScanner.py
```


## 5.2. Running and stopping Python script ```BeaconScanner.py``` with Salt

In Salt Master, that is the Xubuntu server, give the following command to run ```BeaconScanner.py``` remotely in ```raspberrypi1``` Salt Minion that is the Raspberry Pi 1

```
sudo salt 'raspberrypi1' cmd.run 'python /home/projektimies/Lighthouse/BeaconScanner.py'
```

Give the following command to run ```BeaconScanner.py``` remotely in all three Salt Minions that are the three Raspberry Pis

```
sudo salt '*' cmd.run 'python /home/projektimies/Lighthouse/BeaconScanner.py'
```

Give the following command to stop ```BeaconScanner.py``` remotely in ```raspberrypi1``` Salt Minion that is the Raspberry Pi 1

```
sudo salt 'raspberrypi1' cmd.run 'killall python'
```

Give the following command to stop ```BeaconScanner.py``` remotely in all three Salt Minions that are the three Raspberry Pis

```
sudo salt '*' cmd.run 'killall python'
```


# 6. Database

We are using previously installed MariaDB relational database to create our database on the Xubuntu server


## 6.1. Allowing remote access to the database

Go to address https://www.configserverfirewall.com/ubuntu-linux/enable-mysql-remote-access-ubuntu/ to view the instructions for allowing remote access to MariaDB on the server from all three Raspberry Pis

Edit ```/etc/mysql/mariadb.conf.d/50-server.cnf``` file

```
sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
```

Change ```bind-address            = 127.0.0.1``` to ```bind-address            = 0.0.0.0```

```
#
# These groups are read by MariaDB server.
# Use it for options that only the server (but not clients) should see
#
# See the examples of server my.cnf files in /usr/share/mysql/
#

# this is read by the standalone daemon and embedded servers
[server]

# this is only for the mysqld standalone daemon
[mysqld]

#
# * Basic Settings
#
user            = mysql
pid-file        = /var/run/mysqld/mysqld.pid
socket          = /var/run/mysqld/mysqld.sock
port            = 3306
basedir         = /usr
datadir         = /var/lib/mysql
tmpdir          = /tmp
lc-messages-dir = /usr/share/mysql
skip-external-locking

# Instead of skip-networking the default is now to listen only on
# localhost which is more compatible and is not less secure.
bind-address            = 0.0.0.0
```

Allow port 3306 that is responsible for MySQL database system for Raspberry Pi 1

```
sudo ufw allow from 172.28.175.42 to any port 3306
```

Allow port 3306 that is responsible for MySQL database system for Raspberry Pi 2

```
sudo ufw allow from 172.28.175.44 to any port 3306
```

Allow port 3306 that is responsible for MySQL database system for Raspberry Pi 3

```
sudo ufw allow from 172.28.175.45 to any port 3306
```


## 6.2. Creating database ```iotbeacon``` in the database

Open MariaDB

```
sudo mysql
```

Create database ```iotbeacon```

```
CREATE DATABASE iotbeacon;
```

Grant all privileges on database ```iotbeacon``` to user ```niko``` - the user ```niko``` is created automatically by granting these privileges to it

```
GRANT ALL PRIVILEGES ON iotbeacon.* TO 'niko'@'172.28.175.41' IDENTIFIED BY 'MonialaProjekti';
```


## 6.3. Creating the tables in the database ```iotbeacon```

Using database ```iotbeacon```, create table ```beaconusers``` and add the following information inside the table

```
CREATE TABLE beaconusers (

beacon_name VARCHAR(30),
beacon_mac_address VARCHAR(40),
user_first_name VARCHAR(30),
user_last_name VARCHAR(30),
room_name VARCHAR(20),
updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
```


## 6.4. Creating remote users in the database for three Raspberry Pis

Create user ```raspbian_1``` for Raspberry Pi 1

```
CREATE USER 'raspbian_1'@'172.28.175.42' IDENTIFIED BY 'MonialaProjekti';
```

Create user ```raspbian_2``` for Raspberry Pi 2

```
CREATE USER 'raspbian_2'@'172.28.175.44' IDENTIFIED BY 'MonialaProjekti';
```

Create user ```raspbian_3``` for Raspberry Pi 3

```
CREATE USER 'raspbian_3'@'172.28.175.45' IDENTIFIED BY 'MonialaProjekti';
```


## 6.5. Giving permissions to remote users in the database

Grant all privileges on database ```iotbeacon``` to user ```raspbian_1```

```
GRANT ALL PRIVILEGES ON iotbeacon.* TO 'raspbian_1'@'172.28.175.42';
```

Grant all privileges on database ```iotbeacon``` to user ```raspbian_2```

```
GRANT ALL PRIVILEGES ON iotbeacon.* TO 'raspbian_2'@'172.28.175.44';
```

Grant all privileges on database ```iotbeacon``` to user ```raspbian_3```

```
GRANT ALL PRIVILEGES ON iotbeacon.* TO 'raspbian_3'@'172.28.175.45';
```


# 7. Testing

Description here


# 8. In conclusion

To recap, after following the instructions, you should have the following items

- One Xubuntu web server
- Three Raspberry Pis
- Three Blutooth beacons
- ```BeaconScanner.py```, ```ScanUtility.py```, ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php``` in folder ```Lighthouse``` in the same path on all three Raspberry Pis - notice that ```DatabaseInsert.php``` files actually have nine slightly different versions because the script changes based on which of the three Raspberry Pis it is located
- Database on the Xubuntu server
- ```index.php``` website on the Xubuntu server

The following mindmap should clarify the dependencies of the files and devices of the whole system

<img src=http://myy.haaga-helia.fi/~a1602651/kuvat/Lighthouse.png>


# 9. Further development

List of all the ideas for further development of the project

1. Android or iOS app, or improved web application instead of our PHP website ```index.php```

2. Python files for database insertion instead of PHP files - could the Python database insertion be inside ```BeaconScanner.py``` or would it need additional scripts?

3. Accurate room deduction based on RSSI signal strength, i.e. the lowest RSSI value is the nearest room - RSSI values of Bluetooth beacons need to be insertable to the database

4. Automation of Raspberry Pi configuration for additional Bluetooth scanners, i.e. Raspberry Pi 4, Raspberry Pi 5, and so on


# Issues and tasks

Here is a list of current issues and tasks to be solved

~~1. The website must show three people, ```Joni Mattsson```, ```Rasmus Ekman```, and ```Niko Kulmanen```, one for each Beacon, as permanent HTML elements and inform below each person ```Beacon number - Firstname Lastname - Last seen in Roomplaceholder - Timestamp``` or for example ```Beacon1 - Joni Mattsson - Last seen in 5004 - 2019-11-29 20:14:32``` - for instance, the website informs that person ```Joni Mattsson``` is found in the vicinity of room ```5004``` at the time of ```2019-11-29 20:14:32```, but is not found in the vicinity of 5005 or Servula currently - five newest rows of information about each person should update based on timestamp every 1-10 seconds~~

~~2. The website must work simultaneously with all three Beacons, ```Beacon1```, ```Beacon2```, and ```Beacon3```, meaning that ```Beacon2``` and ```Beacon3``` must be added to the database as well as all three Raspberry Pis which serve as rooms, ```5004```, ```5005```, and ```Servula``` - all three Beacons must have one PHP script each, ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php```, meaning total of three PHP scripts for ALL THREE Beacons on ALL THREE Raspberry Pis, so a total of nine different PHP scripts - also, remember to add line ```import os``` and three ```os.system("php /home/projektimies/Lighthouse/DatabaseInsert1-3.php")``` lines inside all three ```if``` statements in ```BeaconScanner.py``` files on all three Raspberry Pis~~

~~3. Run ```BeaconScanner.py``` script simultaneously in all Salt Mininions, ```raspberrypi1```, ```raspberrypi2```, and ```raspberrypi3```, from the Salt Master ```Xubuntu server``` using Salt command or Salt states~~

4. Automatic timeout or restart for ```BeaconScanner.py``` script, because running it infinitely is not ideal - will the timeout be for ```while loop``` or something else, and should we use ```forever``` file as a failsafe if the script stops?

~~5. Step-by-step instructions for creating the database and accessing database remotely from Raspberry Pis, especially the latter since we had problems getting it to work - start writing database instructions to GitHub from paragraph ```6.1.``` and divide to appropriate topics, like for example, ```6.1. Creating tables in the database```, ```6.2. Creating triggers in the database```, and so on - every single command from opening MariaDB database to creating tables should be listed chronologically inside ```code elements``` so that in theory, a random person could create a working database without prior knowledge using the instructions~~

6. Clean up GitHub report so that it is updated to the latest information, logical, chronological, neat, and follows the established standardization in formatting, for example paragraphs ```2.``` and ```3.``` need some polishing along many other paragraphs - Pekka's responsibility

7. Prepare the template for the Microsoft Word technical report that will be written in Finnish, and copy it to OneDrive so everyone can update it easily - at least technical topics like Xubuntu server, Bluetooth beacons, Raspberry Pis, Scripts, Website, and Database could be divided to paragraphs in advance

8. Remote Desktop Connection to Xubuntu server using the instructions from address http://c-nergy.be/blog/?p=9962 or other website - alternatively, using PuTTY seems to be working great via VDI, so accessing the server and editing the database and scripts should work fine remotely from home

~~9. Make a mindmap of script and device dependencies in https://bubbl.us/ or other website~~

~~10. Establish the final path ```/home/projektimies/Lighthouse/``` to ALL SCRIPTS in ALL THREE Raspberry Pis, so that it is the same path in ALL THREE Raspberry Pis, for example, ```BeaconScanner.py``` script is located in ```/home/projektimies/Lighthouse/BeaconScanner.py``` in ALL THREE Raspberry Pis - also, establish naming conventions to all relevant scripts and folders, ```Lighthouse``` is the folder where all the scripts like ```BeaconScanner.py```, ```ScanUtility.py```, ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, and ```DatabaseInsert3.php``` are located in Raspberry Pis - in the server, ```ìndex.php``` website file is located inside ```public_html``` folder in path ```/home/iotbeacon/public_html/index.php```~~

~~11. Create or edit the database based on new instructions - the database must focus around people instead of rooms, and it must print ```Beacon name - Firstname Lastname - Last seen in Roomplaceholder - Timestamp``` information, like for example, ```Beacon1 - Joni Mattsson - Last seen in Servula - 2019-11-29 20:14:32``` - how can we deduce in which room a Bluetooth beacon is if two Raspberry Pis detect it simultaneously, or can we just print two rooms like ```Last seen in 5004 5005``` if two Raspberry Pis detect it at the same time, is there also any way to at least print RSSI value to the website, since in best case scenario the database would deduce the room based on the lowest RSSI value?~~

12. Add the final versions of ```BeaconScanner.py```, ```ScanUtility.py```, ```DatabaseInsert1.php```, ```DatabaseInsert2.php```, ```DatabaseInsert3.php``` (total of nine files because of three Raspberry Pis), and ```index.php``` files to separate GitHub files

13. Write Peer review, everyone in the project group writes their own!

14. Write Powerpoint presentation and live demo!

15. Write Final report!

16. Write Technical report!

17. Add weekly working hours to Moodle!

18. Raspberry Pi 3 to a further classroom?

~~19. Own chapter for ```Installing Salt Minion on Raspberry Pis```~~

20. Remove passwords from GitHub

21. Make a text file with all the devices, usernames, and passwords used in the system
