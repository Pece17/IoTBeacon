# IoT Beacon
IoT based project for Haaga-Helia University of Applied Sciences, ICT Infrastructure Project -course. End product will be able to scan data from Bluetooth beacons with Raspberry Pi -computers and forward it to a database. The data will then be used to build a HTML based application which either alerts when the beacons leave a designated area or alerts when they enter a forbidden area.

# Project team

Niko Kulmanen - Project manager

Rasmus Ekman - Project worker

Pekka Hämäläinen - Project worker

Joni Mattsson - Project worker

# Installing Ubuntu server
We made a bootable Linux USB stick with Kingston 8GB flash drive using Rufus 3.8 to create an ISO-image with Xubuntu 16.04.3.

- Remove LAN-cable before installation
- Open legacy boot menu with F9
- Choose USB-stick
- "Cannot boot system due to start job running for hold error"
- 

Installation:
- Install Xubuntu English language
- Continue
- Continue
- Erase disk and install Xubuntu
- Helsinki
- Kyeboard layout Finnish and Finnish
- Your name: Iot Beacon
- computer name: iotbeacon-HP
- username: iotbeacon
- Require password to login

Open terminal

- setxkbmap fi
- sudo apt-get update
- sudo apt-get upgrade
- sudoedit /etc/hostname

edit hostname iotbeacon-HP

- sudo reboot

Apache installation

- sudo ufw allow 22/tcp
- sudo ufw allow 80/tcp
- sudo ufw enable
- 
