<?php
// this file holds global functions called "helpers"

// session class
class Session {
  //
  public static function start() {
    // start session
    session_cache_limiter();
    session_start();
  }  
  
  // adds value to the session
  public static function write($key, $value) {
    $_SESSION[$key] = $value;
  }
  
  // add value to an array of data
  public static function insert($key, $value) {
    $_SESSION[$key][] = $value;
  }
  
  // add value to an array of data
  public static function delete($key) {
    unset($_SESSION[$key]);
  }
  
  // read data out of session by key
  // if exists, return it, otherwise return false.
  public static function read($key) {
    return (isset($_SESSION[$key]) ? $_SESSION[$key] : false);
  }
  
  // returns the entire variable
  public static function readAll() {
    return $_SESSION;
  }    
  
  // close wrtie of session
  public static function close() {
    // stop session
    session_write_close();
  }  
}  

// this basically spits out an array in "preformatted" tags.
// eg. : <pre>Array() { blah } </pre>
function pr($data, $return = false) {
  $result = sprintf('<pre>%s</pre>', print_r($data, true));
  if($return) {
    return $result;
  } else {
    echo $result;
    return false;
  }
}

// DL array - outputs or returns an array as HTML DL DD
function dla($data, $return = false) {
  $result = '<dl>';
  foreach($data as $k => $v) {
    $result .= '<dt>' . $k . '</dt><dd>' . $v . '</dd>';
  }
  $result .= '</dl>';
  if($return) {
    return $result;
  } else {
    echo $result;
    return false;
  }
}

// checks if value is in an array and returns that,
// otherwise returns string 'Not Found'
// useful for data display
function da($data, $value) {
  $result = 'Not Found';
  if(isset($data[$value])) $result = $data[$value];
  return $result;
}

// checks if value is in an array and ECHOS that, otherwise ECHOS
// string 'Not Found' HTML with bad class
// useful for data display as HTML
function ha($data, $value) {
  $result = '<em class="bad">Not Found</em>';
  if(isset($data[$value])) $result = $data[$value];
  return $result;
}

// outputs html yes no
// can swap in 2nd parameter
function hyn($oneOrZero, $swapGoodBad = false) {
  return ($oneOrZero == 1
    ? '<span class="' . ($swapGoodBad ? 'bad' : 'good') . '">Yes</span>'
    : '<span class="' . ($swapGoodBad ? 'good' : 'bad') . '">No</span>');
}

// outputs plain text yes no
function yn($oneOrZero) {
  return ($oneOrZero == 1 ? 'Yes' : 'No');
}

// records a message in a stack ready to print to the 
// view on the next view load
function message($message, $class = 'neutral') {
  //
  Session::insert('Messages', array('message' => $message, 'class' => $class));
}

// records a debug in a stack ready to print to the
// view on the next view load
function debug($data, $title = 'Debug', $varDump = false) {
  if($varDump) {
    ob_start();
    var_dump($data);
    $dumpData = ob_get_clean();
    $message = $title . ' ' . $dumpData;
  } else {
    $message = $title . ' ' . print_r($data, true);
  }
  Session::insert('DebugData', array('message' => $message));
}

// strips slashes and tags from string
function strip($data)  {
  return stripslashes(strip_tags($data));
}

// strips slashes and tags from strings inside an array
function stripArray($data) {
  foreach($data as $k => $v) {
    $data[$k] = strip($data[$k]);
  }
  return $data;
}

// returns a nice date
function niceDate($sqlDateTime) {
  return date(Config::read('Nice.date'), strtotime($sqlDateTime));
}

// returns a nice date and time
function niceDateTime($sqlDateTime)  {
  return date(Config::read('Nice.datetime'), strtotime($sqlDateTime));
}

// returns a nice date and time
function niceTime($sqlDateTime)  {
  return date(Config::read('Nice.time'), strtotime($sqlDateTime));
}

// logs to the log file in the "logs" dir
// short for "site Log"
function slog($message)  {
  // set up strings for log files and dirs
  $logDir = $file = APP_ROOT . DS . 'logs' . DS;
  $logFile = $logDir . 'sitelog-' . date('Y-m-d') . '.log';
  
  // error checks
  if(!is_dir($logDir) or !is_writable($logDir)) {
    message('Logs directory does not exist or is not writable. Please contact system admin.');
    return false;
  } elseif(is_file($logFile) and !is_writable($logFile)) {
    message('Log file is not writable! Please contact system admin.');
    return false;
  }
  
  // check if file exists first so we don't try and get contents of nothing and crash the site
  if(!file_exists($logFile)) {
    $currentContents = '';
  } else {
    // Open the file to get existing content
    $currentContents = file_get_contents($logFile);
  }
  
  // Append a new person to the file
  $currentContents .= date('Y-m-d H:i:s') . ": " . $message . "\r\n";
  
  // Write the contents back to the file
  file_put_contents($logFile, $currentContents);
  
  // if we got this far, we must have been successful
  return true;
}

// "File load Error"
function flerror($filePath, $errorTitle = 'Missing core file', $description = null) {
  if($description == null) $description = $filePath;
  echo '<h2>' . $errorTitle . '</h2>
    <p>' . $description . '</p>
    <p>' . $filePath . '</p>';
}

// "Require Once With Error"
// checks if file exists, and if it does, requires it,
// if not, it renders a nice error.
// $filePath string : full path to file
// $errorTitle string : title of error, without h2 tags
// $description string : description of error, without P tags
// return : bool
function rowe($filePath, $errorTitle = 'Missing core file', $description = null) {
  if($description == null) {
    $description = $filePath;
  }
  if(file_exists($filePath)) {
    require_once($filePath);
    return true;
  } else {
    flerror($filePath, $errorTitle, $description);
    return false;
  }
}

// redirects to a destination
function redirect($destination) {
  // where to go after song has been added
  header('location: ' . Config::read('Website.home') . $destination);
  die();
}

// adds a script to the session so it can be added in the footer
// do not include .js in the name
function script($name) {
  Session::insert('Scripts', array('name' => $name));
}

// adds an extra title to the page
function title($name) {
  Session::write('PageTitle', $name);
}

// adds an extra title to the page
function description($description) {
  Session::write('PageDescription', $description);
}

// echos queued page scripts and then removes them from queue
function pageScripts() {
  $scripts = Session::read('Scripts');
  $scriptString = '';
  if(!empty($scripts)) {
    foreach($scripts as $script) {
      $scriptString .= sprintf('<script src="%s/js/%s.js"></script>',
      Config::read('Website.home'), $script['name']);
    }
    // clear messages from message thing
    Session::delete('Scripts');
  }
  return $scriptString;
}

//
function pageTitle() {
  $pageTitle = Session::read('PageTitle');
  if($pageTitle) {
    $pageTitle = $pageTitle . ' | ' . Config::read('Website.name');
  } else {
    $pageTitle = Config::read('Website.name') . ' - ' . Config::read('Website.slogan');
  }
  // clear messages from message thing
  Session::delete('PageTitle');
  return $pageTitle;
}

//
function pageDescription() {
  $pageDescription = Session::read('PageDescription');
  if(!$pageDescription) {
    $pageDescription = Config::read('Website.description');
  }
  // clear messages from message thing
  Session::delete('PageDescription');
  return $pageDescription;
}

// echo's the site's URL home page
function homeUrl() {
  return Config::read('Website.home');
}

// returns a slug version of a string
// eg: This Sucks -> this-sucks
function slugify($string)  {
  $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
  $slug = strtolower($slug);
  return $slug;
}

// Typical HTML nav
function nav($links = ['/' => 'Home']) {
  $out = '<ul>';
  foreach($links as $k => $v) {
    $class = $_SERVER['REQUEST_URI'] === $k ? ' class="active"' : '';
    $out .= '<li><a href="' . $k . '"' . $class . '>'
      . $v
      . '</a></li>';
  }
  $out .= '</ul>';
  return $out;
}

// creates a string for logging client's IP addresses
function ipGather() {
  $forwardedFor = '(none)';
  $ip = '(unknown)';
  // 111.111.111.111:11111
  if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'];
  if(isset($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
  return 'IP: ' . $ip . ' | forwarded for: ' . $forwardedFor . '.';
}

// renders a component
// because this is an include, we can use
// $componentData in the included file
function component($file, $componentData = false) {
  $filePath = COMPONENT_DIR . $file . '.php';
  $description = $file;
  if(file_exists($filePath)) {
    require_once($filePath);
    return true;
  } else {
    flerror($filePath, 'Component not found', $description);
    return false;
  }
}

// draws page messages stored in session
function pageMessages() {
  // get messages, or false if none
  $messages = Session::read('Messages');
  // if there's messages, load component for messages
  if($messages) {
    // load component
    component('messages', $messages);
    // clear messages from message thing
    Session::delete('Messages');
  }
}

// yah. draws debug messages stored in session
function pageDebugMessages() {
  if(Config::read('Debug')) {
    // get messages, or false if none
    $messages = Session::read('DebugData');
    // if there's messages, create div tag and print them
    if($messages) {
      // load component
      component('debugs', $messages);
      // clear messages from message thing
      Session::delete('DebugData');
    }
    // get messages, or false if none
    $messages = Session::read('BugData');
    // if there's messages, create div tag and print them
    if($messages) {
      // load component
      component('debugs', $messages);
      // clear messages from message thing
      Session::delete('BugData');
    }
  }
}

// function for outputting word-based time
function secondsToString($seconds, $displaySeconds = true) {
  $numDays = floor(($seconds % 31536000) / 86400);
  $numHours = floor((($seconds % 31536000) % 86400) / 3600);
  $numMinutes = floor(((($seconds % 31536000) % 86400) % 3600) / 60);
  $numSeconds = ((($seconds % 31536000) % 86400) % 3600) % 60;
  if($numDays > 0) {
    if($numDays > 1) {
      return $numDays . ' days' . ($numHours == 0 ? '' : ' ' . $numHours . ' h') . ($numMinutes == 0 ? '' : ' ' . $numMinutes . ' min');
    } else {
      return $numDays . ' day' . ($numHours == 0 ? '' : ' ' . $numHours . ' h') . ($numMinutes == 0 ? '' : ' ' . $numMinutes . ' min');
    }
  }
  if($numHours > 0) {
    return $numHours . ' h' . ($numMinutes == 0 ? '' : ' ' . $numMinutes . ' min');
  }
  if($displaySeconds) {
    if($numMinutes > 0) {
      return $numMinutes . ' min' . ($numSeconds == 0 ? '' : ' ' . $numSeconds . ' sec');
    }
    return $numSeconds . ' sec';
  } else {
    if($numMinutes > 0) {
      return $numMinutes . ' min';
    }
    return 'less than 1 min';
  }
}

// sends an email with default headers
function sendEmail($to, $subject, $message) {
  $fromName = Config::read('Website.from.name');
  $fromEmail = Config::read('Website.from.email');
  $replyName = Config::read('Website.reply.name');
  $replyEmail = Config::read('Website.reply.email');
  $phpVersion = phpversion();
  $headers = "From: $fromName <$fromEmail>\r\n" .
    "Reply-To: $replyName <$replyEmail>\r\n" .
    "X-Mailer: PHP/$phpVersion\r\n" . 
    "MIME-Version: 1.0\r\n" . 
    "Content-Type: text/html; charset=ISO-8859-1\r\n";
  $message = sprintf('<table width="100%%" border="0" cellspacing="0" cellpadding="0"><tr><td align="left">%s</td></tr><tr><td style="color:white;">(%s end of message.)</td></tr></table>', $message, date('Y-m-d H:i:s'));
  // TODO: turn off errors and readable to suppress warnings
  /*
    $errLevel = error_reporting(E_ALL ^ E_NOTICE);  // suppress NOTICEs
    mail(...);
    error_reporting($errLevel);  // restore old error levels
  */
  $mailResult = mail($to, $subject, $message, $headers);
  return $mailResult;
}
