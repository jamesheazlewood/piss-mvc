<?php
  // Read these like Config::read('Website.description') for example
  Config::write('Website.home', 'http://todo.localhost');

  // emails
  Config::write('Support.name', 'todo');
  Config::write('Support.email', 'todo@todo.com.au');
  Config::write('Admin.name', 'todo');
  Config::write('Admin.email', 'todo@todo.com.au');
  Config::write('Website.from.name', 'todo');
  Config::write('Website.from.email', 'no-reply@todo.com.au');
  Config::write('Website.reply.name', 'No reply');
  Config::write('Website.reply.email', 'no-reply@todo.com.au');

  // adds to passwords to make them secure
  // IMPORTANT: if this is changed, all the passwords in the database will be WRONG!!
  Config::write('Security.salt', '4534535435====change-this-shiz====345345345345');