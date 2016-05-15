<?php
  // define a shorter more usable constant for directory seperators.
  // DIRECTORY_SEPARATOR is a php global constant that returns either / or \ depending if its
  // Windows or unix. Bill Gates has ruined us all. But not any more.
  if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

  // the base root that contains the piss folder and the app folder
  // example win: E:\wamp\www\my-piss-app (no trailing slash)
  // example unix: /var/www/my-piss-app (no trailing slash)
  if(!defined('ROOT')) define('ROOT', dirname(__FILE__) . DS . '..');

  // the root of the piss folder
  // example win: E:\wamp\www\my-piss-app\piss (no trailing slash)
  // example unix: /var/www/my-piss-app/piss (no trailing slash)
  if(!defined('PISS_ROOT')) define('PISS_ROOT', ROOT . DS . 'piss');

  // FULL path duirectory of the application's root (before the web root)
  // example win: E:\wamp\www\my-piss-app\app (no trailing slash)
  // example unix: /var/www/my-piss-app/app (no trailing slash)
  if(!defined('APP_ROOT')) define('APP_ROOT', ROOT . DS . 'app');

  // FULL path directory where the web root is, /img /js and /css from here
  // Does not have trailing slash
  if(!defined('WEB_ROOT')) define('WEB_ROOT', APP_ROOT . DS . 'webroot');

  // vendor directory
  // example: E:\wamp\www\my-piss-app\vendor\ (with trailing slash)
  if(!defined('VENDOR_DIR')) define('VENDOR_DIR', ROOT . 'vendor' . DS);

  // controllers directory
  // example: E:\wamp\www\my-piss-app\app\controller\
  if(!defined('CONTROLLER_DIR')) define('CONTROLLER_DIR', APP_ROOT . DS . 'controller' . DS);

  // models directory
  // example: E:\wamp\www\my-piss-app\app\model\
  if(!defined('MODEL_DIR')) define('MODEL_DIR', APP_ROOT . DS . 'model' . DS);

  // views directory
  // example: E:\wamp\www\my-piss-app\app\conf\
  if(!defined('VIEW_DIR')) define('VIEW_DIR', APP_ROOT . DS . 'view' . DS);

  // template directory
  // example: E:\wamp\www\my-piss-app\app\view\template\
  if(!defined('TEMPLATE_DIR')) define('TEMPLATE_DIR', VIEW_DIR . 'template' . DS);

  // element directory
  // example: E:\wamp\www\my-piss-app\app\view\elements\
  if(!defined('ELEMENT_DIR')) define('ELEMENT_DIR', VIEW_DIR . 'elements' . DS);

  // get core and database config
  require_once(PISS_ROOT . DS . 'config.php');
  require_once(APP_ROOT . DS . 'conf' . DS . 'app-config.php');
  require_once(APP_ROOT . DS . 'conf' . DS . 'env-config.php');
  require_once(APP_ROOT . DS . 'conf' . DS . 'db-config.php');
  require_once(PISS_ROOT . DS . 'db-select.php');
  require_once(PISS_ROOT . DS . 'helpers.php');
  require_once(PISS_ROOT . DS . 'forms.php');
  require_once(PISS_ROOT . DS . 'app.php');
  require_once(PISS_ROOT . DS . 'model.php');
  require_once(PISS_ROOT . DS . 'controller.php');
  require_once(CONTROLLER_DIR . 'app-controller.php');