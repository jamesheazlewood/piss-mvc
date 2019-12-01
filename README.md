# piss-mvc

PHP Idiot-proof Simple Stripped MVC Framework. Or simply: PHP it simple, stupid. Easy as piss MVC for small projects. 

This framework is for people that need a very simple starting point for a web app.

It contains a class-based MVC structure but doesn't contain anything else. That's up to you to do. 

## Prereqs

1. wamp or vagrant server install etc. Includes apache, php, and mysql.
2. git

## Setup

1. Clone this repo into a directory your web server can access eg 'wamp/www/yoursite/piss' or '/var/www/yoursite/piss/'. So you should have for example 'var/www/yoursite/piss/init.php'
2. Use the '\_base' structure for your folder structure. Your app folder is where all your stuff will be.
3. Set up your database. Get your password and username and edit the db-config.php file in your app/config/ folder.
4. Point your web server to yoursite/piss/app/webroot/ and point the logs output to yoursite/logs/. Everything in webroot is accessible by the public.

## Version control

You may use your own version control in your app folder. example yoursite/app/.git/

## Vendors

All PHP vendor scripts and libs should be placed in the /yoursite/vendors/ folder

## Usage

Usage coming soon.

Basic usage is MVC style. You create model files like app/model/user.php and controllers like app/controller/userscontroller.php and views like app/views/users/something.php

Refer to the code to see how it works.

Routes work basically like www.yoursite.com/users/something/param1/param2/param3