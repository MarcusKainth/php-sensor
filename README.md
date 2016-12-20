# PHP Sensor
A project for 5CS012 (Collaborative Development) at the University of Wolverhampton.

## Introduction
A temperature sensor display in PHP for the [Arduino Sensor](https://github.com/marcuskainth/arduino-sensor) project. Please bare in mind that the project is unfinished but the main components work correctly.

## Installation
The project requires MySQL. To set up for your own server find the config.php file located in the includes folder and enter your credentials
```php
$server = "example.com";
$user = "username";
$password = "password";
$db = "database";
```

You need to import the databases. You can find the SQL script in the root of the repository called database.sql

## Usage
For use with the [Arduino Sensor](https://github.com/marcuskainth/arduino-sensor) project.

To store data into the database from an external source such as the Arduino Sensor, use the get.php file. Store data requires a HTTP GET request and can store currently a maximum of 2 temperature from 2 seperate sensors. You can modify this if you like. Simply:
```
https://example.com/get.php?temp1=value&temp2=value
```
Where value is a floating point number.

## Requirements
- Web Server (nginx used)
- PHP5 (Tested on 5.5)
- MySQL (5.4 used)

## Author
Created for [University of Wolverhampton](https://www.wlv.ac.uk), [Computer Science](https://www.wlv.ac.uk/about-us/our-schools-and-institutes/faculty-of-science-and-engineering/school-of-mathematics-and-computer-science/) module 5CS012 (Collaborative Development) by:

[Marcus Kainth](https://www.marcuskainth.co.uk), [@marcuskainth](https://github.com/marcuskainth)