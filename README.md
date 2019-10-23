# IoT Beacon

IoT based project for Haaga-Helia University of Applied Sciences, ICT Infrastructure Project -course. End product will be able to scan data from Bluetooth beacons with Raspberry Pi -computers and forward it to a database i.e. a server. The data will then be used to build a HTML based application which either alerts when the beacons leave a designated area or alerts when they enter a forbidden area.


## Project team

- Rasmus Ekman - Project manager
- Pekka Hämäläinen - Project worker and secretary
- Niko Kulmanen - Project worker
- Joni Mattsson - Project worker


# Xubuntu server

After initialization, show a cleaned up list of shortened specifications and hardware information of the server using Linux terminal

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

Here are the specifications of the server computer we are using

- Model - HP Compaq 8200 Elite CMT (Convertible Minitower) PC
- Processor - Intel Core i5-2400 3.10GHz
- Standard memory - 4GiB DIMM DDR3 Synchronous 1333 MHz
- Graphics card - Intel Corporation 2nd Generation Core Processor Family Integrated Graphics Controller
- Operating system - Xubuntu 16.04.6 LTS


## Creating a Linux USB stick

We made a bootable Linux USB stick from Kingston DataTraveler 100 8GB USB 2.0 Flash Drive by downloading Rufus 3.8 application from https://rufus.ie/ and using it on a Windows computer to create an ISO (International Standards Organization) image with Xubuntu 16.04.3 that we downloaded from http://cdimage.ubuntu.com/xubuntu/releases/16.04.3/release/ - Xubuntu is a lighter derivative of the Ubuntu operating system

We initially tried using an UNetboot based Linux USB stick but it was not recognized by the server computer we chose to use on this project


## Initializing the server (Work in progress)

We had trouble getting Xubuntu to boot from the previously created Linux USB stick, but with these steps we got it working eventually

- Remove LAN (local area network) cable before turning on the server computer
- Open Legacy Boot Menu with F9 key or other key that works with a particular version
- Choose Linux USB stick in Legacy Boot Menu

The terminal gave a prompt "A start job is running for Hold until boot process finishes up" and booting didn't seem to advance

We restarted the server computer multiple times until the system finally booted properly, and executed the following steps during installation

- English
- Install Xubuntu/Erase disk and install Xubuntu
- Continue
- Continue
- Erase disk and install Xubuntu
- Continue
- Helsinki
- Keyboard layout Finnish and Finnish
- Your name: iotbeacon
- Computer name: rauta
- Username: iotbeacon
- Require password to login
- Continue
- Restart

When Xubuntu operating system restarts and opens the desktop screen, open the Linux terminal - we will be mainly using terminal to configure the server

```
Ctrl + Alt + T
```

Change keyboard layout to Finnish keyboard, so that the keys respond correctly when typing

```
setxkbmap fi
```

Check the name of the current user, in this case the user is iotbeacon because that is the only user on this server

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


## Configuring firewall on the server

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


## Installing Apache2 Web Server on the server

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

Try the dynamic IP address x.x.x.x on web browser address bar - this also works and opens the Apache2 default page

Enable userdir Apache2 module and restart the Apache2 service

```
sudo a2enmod userdir
service apache2 restart
```

Go to the home directory and make the public_html folder, list the contents of the home directory to check that the public_html folder was succesfully created, after which navigate inside the public_html folder and create the index.html file

```
cd
mkdir public_html
ls
cd public_html
nano index.html
```

I forgot to create the index.html file with sudo or "superuser do" permission so I delete the previous file recursively and create a new, secure index.html file

```
rm -r index.html
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
Ctrl X
Yes
Enter
```

Check the name of the current Xubuntu user - it is needed to open the the index page that we previously made by creating and editing the index.html file

```
whoami
```

Open addresses localhost/~iotbeacon and x.x.x.x/~iotbeacon using web browser

Both addresses work successfully inside the lab environment

Navigate to sites-available directory and open the 000-default.conf file

```
cd /etc/apache2/sites-available
ls
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


## Configuring static IP addresses on the server using CLI and GUI

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

Edit interfaces file and add the required parameters for the static IP address - don't remove any old text from inside the file

```
sudo nano /etc/network/interfaces
```

```
auto eno1
iface eno1 inet static
address x.x.x.x
netmask x.x.x.x
gateway x.x.x.x
dns-nameservers x.x.x.x x.x.x.x
```

Flush IP, restart networking service, and reboot the server

```
sudo ip addr flush eno1
systemctl restart networking.service
sudo reboot
```

Configuration does seem to work using command line but I still remove any changes I did in terminal just to be safe - I read many tutorials stating that it is highly recommended to configure IP addresses with GUI (graphical user interface) using Network Connections application in current versions of Ubuntu because the application might override any command line changes nevertheless

- Navigate to desktop and open Edit Connections tab from upper right corner under a symbol depicting two arrows
- Choose Ethernet and Wired connection 1 and press Edit
- Go to IPv4 Settings
- Change Method to Manual
- Add a new address and enter the parameters
- Add DNS servers
- Save
- Open Connection Information from upper right corner under the same symbol depicting two arrows
- Check that the IPv4 settings are correct

Reboot the server to apply changes

```
sudo reboot
```

Internet is now working, and pinging from other lab environment computers is successful

Both terminal and graphical configuration had problems at first with the assigned lab environment DNS addresses since I couldn't get them working at all - only after I changed the DNS addresses to Google Public DNS addresses 8.8.8.8 and 8.8.4.4, did I get the internet working, but now finally the lab environment DNS addresses are working, apparently due to a fix from higher-ups, so I configure them again on the server and keep Google Public DNS as a backup option


## Installing SSH on the server

Install SSH (Secure Shell) client and server

```
sudo apt-get install -y openssh-server openssh-client
```

After installing SSH, I try to connect with another Linux computer from the lab enviroment to the server

```
ssh iotbeacon@x.x.x.x
```

Connection is successful

Other project member tries to connect to the server from his house using Linux terminal and SSH - connection is not succesful because apparently you can't reach these static IP addresses outside of the lab environment


## Installing Firefox on the server

Establish an SSH connection in terminal using another Linux computer withing lab environment

```
ssh iotbeacon@x.x.x.x
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


## Updating the server from version 16.04.3 to 16.04.6

Server operating system is updated to a newer version of 16.04 LTS (Long Term Support) via graphical user interface prompt

Check if the operating system version changed

```
cat /etc/os-release
```

OS version is now 16.04.6, shut down the server before leaving the server room

```
sudo poweroff
```


## Installing MariaDB database on the server (Work in progress)

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Install MariaDB relational database

```
sudo apt-get install mariadb-server
```


## Installing PHP on the server (Work in progress)

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

Delete the index.html file and check that it is removed

```
sudo rm -r index.html
ls
```

Create a new file called index.php, paste the previously copied HTML inside it, and add a simple calculation of 1+3 to test the function of PHP

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

Exit and save the file, and open localhost/~iotbeacon to test if PHP is working correctly

The web page now shows the previously written headings and number 4, indicating that the PHP calculation was successful


## Installing Salt on the server

Install Salt (SaltStack)

```
sudo apt-get -y install salt-master
```

Salt Master communicates with the minions over TCP ports 4505 and 4506 so we need to allow those for firewall

```
sudo ufw allow 4505/tcp
sudo ufw allow 4506/tcp
```

Accept Minion Key on Master (after installing and configuring the Salt-Minions)

```
sudo salt-key -A
Unaccepted Keys:
xxx
Proceed? [n/Y]
Key for minion xxx accepted.
```

Testing

``` 
sudo salt 'xxx' cmd.run 'hostname -I' 
xxx: 172.28.xxx.xx
``` 


## Establishing SSH connection with PuTTY (Work in progress)


## Establishing remote desktop connection (Work in progress)


# Bluetooth beacon

- 2 x Bluetooth low energy (BLE) BlueBeacon tags developed by BlueUp
- Beacons broadcast their identifier to nearby devices - in this project to Raspberry Pis
- Beacons are configured using BlueBeacon Manager App


## BlueBeacon Manager App

- Includes Device (beacon) information, Global settings, Eddystone slots, iBeacon / Quuppa slots, Safety slot
- We upgraded the firmware and changed the passwords for the beacons


# Raspberry Pi

We are using three to five Raspberry Pi 3 model B computers on this project

Here are the specifications of the Raspberry Pi models we are using

- 1 Gt RAM
- 1,2 GHz Broadcom BCM2837 64-bit ARMv8 Quad-core CPU
- BLE
- BCM43143 Wi-FI IEEE 802.11 b/g/n
- HDMI/RCA
- 3.5 mm 
- RJ45
- 4x USB
- MicroSD card reader


## Installing Raspberry Pis

- Heat sinks
- Case
- MicroSD card with Raspberry Pi NOOBS
- keyboard
- Mouse
- 5.1 V / 2.5 A USB power supply
- HDMI cable
- Display


## Operating system on Raspberry Pis

- installed Raspbian using MicroSD card with pre-installed NOOBS (New Out Of Box Software)
- Raspbian version 10 (buster)


## Creating a new sudo user on Raspberry Pis

Rasbian has a default user "pi". For safety reasons we replaced pi with a new user:

```
sudo adduser xxxx
sudo adduser xxxx sudo
```

Add new user to same groups as "pi"

```
for GROUP in $(groups pi | sed -e 's/^pi //'); do
sudo adduser xxxx $GROUP; done
```

Add nopasswd rule for new user and change "pi" to "xxxx"

```
sudo cp /etc/sudoers.d/010_pi-nopasswd /etc/sudoers.d/010_xxx-nopasswd
sudo chmod u+w /etc/sudoers.d/010_xxx-nopasswd
sudo sed -i 's/pi/xxx/g' /etc/sudoers.d/010_xxx-nopasswd
sudo chmod u-w /etc/sudoers.d/010_xxx-nopasswd
sudo reboot
```

Remove user "pi" &
Log in as xxxx 

```
sudo deluser -remove-home pi
sudo rm -vf /etc/sudoers.d/010_pi-nopasswd
```

Changed user password & enabled ssh using Raspberry Pi Software Configuration Tool

```
sudo raspi-config
```


## Configuring static IP addresses on Raspberry Pis

```
sudo nano /etc/dhcpcd.conf 
  -> uncomment and edit under Example static IP configuration section
  
sudo reboot  
```

Tested:
```
Ping raspberrypi.local
```
Reached the new static ip address with a ping

The ssh connection was also successfully established


## Programs installed on Raspberry Pis

- BlueZ 

  Bluetooth stack for Linux kernel-based family of operating systems

```
sudo apt-get -y install bluez bluez-hcidump
```

- MQTT
  
  Network protocol that transports messages between devices
  
```
sudo pip install paho-mqtt
```

- SALT

  Configuration management and orchestration tool
  
```
sudo apt-get -y install salt-minion
sudoedit /etc/salt/minion → master: (master ip address) & id: (the name on the minion)
sudo systemctl restart salt-minion.sercive
```

# Bluetooth scanner script (Work in progress)

Copy scanner Shell script from https://stackoverflow.com/questions/27401918/detecting-presence-of-particular-bluetooth-device-with-mac-address

Make a bash folder for testing purposes in one of the Rasperry Pis

```
sudo mkdir test
```

Navigate inside it and create the Shell file

```
cd test
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
