# IoT Beacon

IoT based project for Haaga-Helia University of Applied Sciences, ICT Infrastructure Project -course. End product will be able to scan data from Bluetooth beacons with Raspberry Pi -computers and forward it to a database i.e. a server. The data will then be used to build a HTML based application which either alerts when the beacons leave a designated area or alerts when they enter a forbidden area.

## Project team

- Niko Kulmanen - Project manager
- Rasmus Ekman - Project worker
- Pekka Hämäläinen - Project worker and secretary
- Joni Mattsson - Project worker

# Ubuntu server

(add text, specifications etc?)

## Ubuntu server initialization (work in progress, make a one big heading? add under headings for more steps?)

We made a bootable Linux USB stick with Kingston 8GB flash drive using Rufus 3.8 to create an ISO-image with Xubuntu 16.04.3

- Remove LAN-cable before installation
- Open legacy boot menu with F9
- Choose USB-stick
- "Cannot boot system due to start job running for hold error"
- Try restarting again until the system starts properly

Installation steps

- English
- Install Xubuntu/Erase disk and install Xubuntu
- Continue
- Continue
- Erase disk and install Xubuntu
- Continue
- Helsinki
- Kyeboard layout Finnish and Finnish
- Your name: iotbeacon
- Computer name: rauta
- Username: iotbeacon
- Require password to login
- Continue
- Restart

Open terminal

```
Ctrl + Alt + T
```

Change keyboard layout to Finnish keyboard

```
setxkbmap fi
```

Update and reboot the system

```
sudo apt-get update
sudo apt-get upgrade
ssudo reboot
```

Firewall configuration and Apache2 Web Server installation

```
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
sudo apt-get update
sudo apt-get install apache2
sudo reboot
```

Try localhost address on web browser, it works and opens the Apache2 default page

Find out the current IP address

```
hostname -I
```

Try 172.28.171.211 IP address on browser, this also works and opens the Apache2 default page

Enable userdir Apache module and restart the service

```
sudo a2enmod userdir
service apache2 restart
```

Go to the home directory and make the public_html folder, list the contents of the home directory to check that the public_html folder was succesfully created, and then create the index.html file inside the public_html folder

```
cd
whoami
ls
mkdir public_html
ls
cd public_html
nano index.html
```

I forgot to create the index.html file with sudo or "superuser do" permission so I delete the previous file recursively and create a new, secure infex.html file

```
rm -r index.html
sudo nano index.html
```

Copy basic HTML from https://www.w3schools.com/html/tryit.asp?filename=tryhtml_basic_document to the index.html file and add some headings

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

Go to addresses localhost/~iotbeacon and 172.28.171.211/~iotbeacon using web browser

Both addresses work in lab environment

Navigate to 000-default.conf virtual host file and configure temporary domain names using the specified parameters, they are only used for testing purposes

```
cd /etc/apache2/sites-available
ls
sudo nano 000-default.conf
```

```
ServerName www.iotbeacon.com
ServerAlias iotbeacon.com
```

Restart Apache service and edit hosts files inside etc folder to complete the temporary domain name configuration

```
service apache2 restart
cd /etc
sudoedit hosts
```

```
127.0.0.1 www.iotbeacon.com
127.0.0.1 iotbeacon.com
```

www.iotbeacon.com/~iotbeacon and iotbeacon.com/~iotbeacon are now working and showing the desired HTML text

## Configuring static IP addresses on server using CLI and GUI

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

Both terminal and graphical configuration had problems at first with the assigned lab environment DNS addresses since I couldn't get them working at all - only after I changed the DNS addresses to Google Public DNS addresses 8.8.8.8 and 8.8.4.4, I got the internet working, but now finally the lab environment DNS addresses are working apparently due to a fix from higher-ups so I configure them again on the server and keep Google Public DNS as a backup option

## Installing SSH on server

Install SSH (Secure Shell) client and server

```
sudo apt-get install -y openssh-server openssh-client
```

After installing SSH, I try to connect with another Linux computer from the lab enviroment to the server

```
ssh iotbeacon@x.x.x.x
```

Connection is succesful

Other project member tries to connect to the server from his house using Linux terminal and SSH - connection is not succesful because apparently you can't reach these static IP addresses outside of the lab environment

## Installing Firefox on server

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

## Updating server from version 16.04.3 to 16.04.6

Server operating system is updated to a newer version of 16.04 LTS (Long Term Support) via graphical user interface prompt

Shut down the server before leaving the server room

```
sudo poweroff
```

## Installing MariaDB database on server

Update package lists for upgrades and new packages from repositories

```
sudo apt-get update
```

Install MariaDB relational database

```
sudo apt-get install mariadb-server
```

## Installing PHP on server

Install PHP (Hypertext Preprocessor)

```
sudo apt-get install php libapache2-mod-php
```

Navigate to mods-available folder and list the contents

```
cd /etc/apache2/mods-available
ls
```

Edit php7.0.conf file, add hashtags to following lines, and save while exiting

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


# Bluetooth beacons

- 2 x Bluetooth low energy (BLE) BlueBeacon tags developed by BlueUp
- Beacons broadcast their identifier to nearby devices (in this project Raspberry Pi)
- Beacons are configured using BlueBeacon Manager App

## BlueBeacon Manager App

- Includes Device (beacon) informations, Global settings, Eddystone slots, iBeacon / Quuppa slots, Safety slot.
- We upgraded the firmware and changed passwords to the beacons

# Raspberry Pi 

- 3 x Raspberry Pi 3 model B

Specifications:

- 1 Gt RAM
- 1,2 GHz Broadcom BCM2837 64-bit ARMv8 Quad-core CPU
- BLE
- BCM43143 Wi-FI IEEE 802.11 b/g/n
- HDMI/RCA
- 3.5 mm 
- RJ45
- 4x USB
- MicroSD card reader

## Rasbperry Pi installation

- Heat sinks
- Case
- MicroSD card with Raspberry Pi NOOBS
- keyboard
- Mouse
- 5.1 V / 2.5 A USB power supply
- HDMI cable
- Display

## Operating system

- installed Raspbian using MicroSD card with pre-installed NOOBS (New Out Of Box Software)
- Raspbian version 10 (buster)

## Create a new sudo user

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

Remove user "pi"
Log in as xxxx &

```
sudo deluser -remove-home pi
sudo rm -vf /etc/sudoers.d/010_pi-nopasswd
```

Changed user password & enabled ssh using Raspberry Pi Software Configuration Tool

```
sudo raspi-config
```
