<?php
	// Report all PHP errors
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	Config::write('Debug', true);
	date_default_timezone_set('Australia/Sydney'); // timezone that date() uses to save to the database
	
	// core config goes here
	Config::write('Website.name', 'todo');
	Config::write('Website.slogan', 'todo');
	//Config::write('Website.description', 'In2science is an innovative and proven multi-university schools partnership program that places university students as peer mentors in Victorian low socio-economic schools.');
	Config::write('Website.description', 'todo.');
	//Config::write('Website.home', 'http://in2science.app.live.futuresquared.com.au'); // without trailing slash!
	Config::write('Website.home', 'http://todo.localhost');

	// Read these like Config::read('Website.description') for example

	// emails
	Config::write('Support.email', 'todo@todo.com.au');
	Config::write('Support.name', 'todo');
	Config::write('Admin.name', 'todo');
	Config::write('Admin.email', 'todo@todo.com.au');
	Config::write('Website.from.name', 'todo');
	Config::write('Website.from.email', 'no-reply@todo.com.au');
	Config::write('Website.reply.name', 'No reply');
	Config::write('Website.reply.email', 'no-reply@todo.com.au');

	// adds to passwords to make them secure
	// IMPORTANT: if this is changed, all the passwords in the database will be WRONG!!
	Config::write('Security.salt', '4534535435====change-this-shiz====345345345345');

	// Other misc configs
	Config::write('Nice.date', 'j M Y');
	Config::write('Nice.datetime', 'j M Y, g:ia');
	Config::write('Nice.time', 'g:ia');