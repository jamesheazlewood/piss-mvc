<?php
	// create a global array to store the database config
	Config::write('DB.host', 'localhost');
	Config::write('DB.username', 'root');
	Config::write('DB.password', '');
	Config::write('DB.database', 'mydumbsite');

	$websites = array();

	// live dev site
	$websites[] = array(
	  'domain_names' => array(
		  'mydumbsite.com',
		  'www.mydumbsite.com',
	  ),
	  'username' => 'mydumbsite',
	  'password' => 'mycrappypassword',
	  'database' => 'mydumbsite',
	  'host'     => 'localhost',
	);