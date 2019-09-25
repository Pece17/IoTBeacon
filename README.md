# IoT Beacon
IoT based project for Haaga-Helia University of Applied Sciences, ICT Infrastructure Project -course. End product will be able to scan data from Bluetooth beacons with Raspberry Pi -computers and forward it to a database. The data will then be used to build a HTML based application which either alerts when the beacons leave a designated area or alerts when they enter a forbidden area.

# Project team
- Niko Kulmanen - Project manager
- Rasmus Ekman - Project worker
- Pekka Hämäläinen - Project worker
- Joni Mattsson - Project worker

# Installing Ubuntu server
We made a bootable Linux USB stick with Kingston 8GB flash drive using Rufus 3.8 to create an ISO-image with Xubuntu 16.04.3.

- Remove LAN-cable before installation
- Open legacy boot menu with F9
- Choose USB-stick
- "Cannot boot system due to start job running for hold error"
- Try opening again

Installation:
- English
- Install Xubuntu/Erase disk and install Xubuntu
- Continue
- Continue
- Erase disk and install Xubuntu
- Continue
- Helsinki
- Kyeboard layout Finnish and Finnish
- Your name: iotbeacon
- computer name: rauta
- username: iotbeacon
- Require password to login
- Continue
- Restart

Open terminal

- setxkbmap fi
- sudo apt-get update
- sudo apt-get upgrade
(- sudoedit /etc/hostname)

(edit hostname iotbeacon-HP)

- sudo reboot

Apache installation

- sudo ufw allow 22/tcp
- sudo ufw allow 80/tcp
- sudo ufw enable
- sudo apt-get update
- sudo apt-get install apache2

localhost selaimeen

- hostname -I

172.28.171.211 selaimeen

- sudo a2enmod userdir
- service apache2 restart

Authenticate

- cd
- ls
- mkdir public_html
- ls
- whoami
- cd public_html
- nano index.html

kopioi html https://www.w3schools.com/html/tryit.asp?filename=tryhtml_basic_document

- ctrl x
- yes
- enter

Siirry localhost/~iotbeacon tai 172.28.171.211/~iotbeacon

Toimii labraverkossa

- cd /etc/apache2/sites-available
- ls
- sudo nano 000-default.conf

ServerName www.iotbeacon.com
ServerAlias iotbeacon.com

- service apache2 restart
- cd /etc
- sudoedit hosts

127.0.0.1 www.iotbeacon.com

127.0.0.1 iotbeacon.com

# Installing static IP adress on server

- cat /etc/os-release

Graafisesti ei onnistu, miten terminaalista?

https://www.howtoforge.com/tutorial/howto-set-a-static-ip-on-ubuntu/

https://www.snel.com/support/static-ip-configuration-ubuntu-16-04/

- clear && echo $(ip -o -4 route get 8.8.8.8 | sed -nr 's/.*dev ([^\ ]+).*/\1/p')
- sudo nano /etc/network/interfaces

[auto eno1
iface eno1 inet static
 address x.x.x.x
 netmask x.x.x.x
 gateway x.x.x.x
 dns-nameservers x.x.x.x x.x.x.x]

- sudo ip addr flush eno1

https://michael.mckinnon.id.au/2016/05/05/configuring-ubuntu-16-04-static-ip-address/

https://askubuntu.com/questions/766131/how-do-i-set-a-static-ip-in-ubuntu

https://support.us.ovhcloud.com/hc/en-us/articles/360000092264-How-to-Configure-Networking-for-a-VM-Running-Ubuntu-16-04

