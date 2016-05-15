<?php
	// Report all PHP errors
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	Config::write('Debug', true);
	date_default_timezone_set('Australia/Sydney'); // timezone that date() uses to save to the database
	
	// core config goes here
	// Read these like Config::read('Website.description') for example
	Config::write('Website.name', 'todo');
	Config::write('Website.slogan', 'todo');
	Config::write('Website.description', 'todo.');

	// Other misc configs
	Config::write('Nice.date', 'j M Y');
	Config::write('Nice.datetime', 'j M Y, g:ia');
	Config::write('Nice.time', 'g:ia');