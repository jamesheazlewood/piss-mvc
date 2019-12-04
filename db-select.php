<?php
// find differing database details
if(Config::read('DB.use')) {
  foreach($websites as $db) {
    if(in_array( $_SERVER['HTTP_HOST'], $db['domain_names'] )) {
      Config::write('DB.username', $db['username']);
      Config::write('DB.password', $db['password']);
      Config::write('DB.database', $db['database']);
      Config::write('DB.host', $db['host']);
      Config::write('DB.type', $db['type']);
    }
  }
}
