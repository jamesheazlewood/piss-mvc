# piss-mvc

PHP Idiot-proof Simple Stripped MVC Framework. Or simply: PHP it simple, stupid.
Easy as piss MVC for small projects.

This framework is for people that need a very simple starting point for a web app.

It contains a class-based MVC structure but doesn't contain anything else. That's up to you to do.

## Prereqs

1. wamp or vagrant server install etc. Includes apache, php, and mysql.
1. git

## Setup

1. Clone this repo into a directory your web server can access eg.
   `wamp/www/yoursite/piss` or `/var/www/yoursite/piss/`. So you should have for example 'var/www/yoursite/piss/init.php'
1. Use the `_base` structure for your folder structure. Your app folder is where all your stuff will be.
1. Set up your database. Get your password and username and edit the db-config.php file in your app/config/ folder.
1. Point your web server to yoursite/piss/app/webroot/ and point the logs output to yoursite/logs/. Everything in webroot is accessible by the public.

## Version control

You may use your own version control in your app folder. example `yoursite/app/.git/` and then keep
PISS under its own version control if you would like updates - keeping in mind any updates could
your app. I will do my best to keep backwards-compatibility.

## Vendors

All PHP vendor scripts and libs should be placed in the `/yoursite/vendors/` folder

## Usage

Usage coming soon.

Basic usage is MVC style. You create model files like app/model/user.php and controllers like app/controller/userscontroller.php and views like app/views/users/something.php

Refer to the code to see how it works.

Routes work basically like `www.yoursite.com/users/something/param1/param2/param3`.

## Directory structure

Folder strucutre must be the following:

- `piss/` - PISS MVC framework (This framework, may have own git control)
  - `app.php`
  - `config.php`
  - ...the rest of the framework files belonging to `piss-mvc`
- `app/` - Your app's home and your git root
  - `conf/` - app and database configs (if any)
  - `controller/` - controllers (the C in MVC)
  - `db/` - database exports (if any)
  - `res/` - flat data like json (if any)
  - `model/` - data models (the M in MVC)
  - `view/` - views and templates (the V in MVC)
  - `webroot/` - the root of the domain namem anything in here is accessible via the web
- `logs/` - empty folder until logs start appearing
- `vendor/` - empty folder until you add any other repos from other frameworks
  (think of this as a manually-managed `node_modules`).
