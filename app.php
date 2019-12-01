<?php

// main app class. This is created in the main index.php file and everything is loaded from here.
class App
{
  /** @var null The controller */
  private $urlController = null;

  /** @var null The method (of the above controller), often also named "action" */
  private $urlAction = null;

  /** @var null Parameter one */
  private $urlParameter1 = null;
  private $urlParameter2 = null;
  private $urlParameter3 = null;
  private $urlParameter4 = null;
  private $urlParameter5 = null;
  private $urlParameter6 = null;

  // construct the App class
  public function __construct()
  {
    // start session
    Session::start();

    // create array with URL parts in $url
    if(isset($_GET['url'])) {
      // split URL
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);

      // Put URL parts into according properties
      // note the first one is always /root because the mod-rewrite sends us there
      $this->urlController =  (isset($url[0]) ? strtolower($url[0]) : null);
      $this->urlAction =      (isset($url[1]) ? strtolower($url[1]) : null);
      $this->urlParameter1 =  (isset($url[2]) ? strtolower($url[2]) : null);
      $this->urlParameter2 =  (isset($url[3]) ? strtolower($url[3]) : null);
      $this->urlParameter3 =  (isset($url[4]) ? strtolower($url[4]) : null);
      $this->urlParameter4 =  (isset($url[5]) ? strtolower($url[5]) : null);
      $this->urlParameter5 =  (isset($url[6]) ? strtolower($url[6]) : null);
      $this->urlParameter6 =  (isset($url[7]) ? strtolower($url[7]) : null);
    }

    // check for controller: does such a controller exist ?
    // Also make sure we are not trying to load / (index) because this will try and load the
    // plain controller.php and shit will die
    if(file_exists(CONTROLLER_DIR . $this->urlController . '-controller.php') && $this->urlController != '') {
      // get the controller file
      require_once(CONTROLLER_DIR . $this->urlController . '-controller.php');
      // create controller name by converting underscored words to camelcase
      $controllerName = str_replace(' ', '', ucwords(str_replace('-', ' ', $this->urlController))) . 'Controller';
      $this->urlController = new $controllerName();
      $action = str_replace('-', '_', $this->urlAction);
      // check for method: does such a method exist in the controller ?
      if(method_exists($this->urlController, $action)) {
        // call the method and pass the arguments to it
        if(isset($this->urlParameter1)) {
          if(isset($this->urlParameter2)) {
            if(isset($this->urlParameter3)) {
              if(isset($this->urlParameter4)) {
                if(isset($this->urlParameter5)) {
                  if(isset($this->urlParameter6)) {
                    // will translate to something like $this->home->method($param_1, $param_2, $param_3, $param_4, $param_5, $param_6);
                    $this->urlController->{$action}(
                        $this->urlParameter1, $this->urlParameter2, $this->urlParameter3,
                        $this->urlParameter4, $this->urlParameter5, $this->urlParameter6
                    );
                  } else {
                    // will translate to something like $this->home->method($param_1, $param_2, $param_3, $param_4, $param_5);
                    $this->urlController->{$action}($this->urlParameter1, $this->urlParameter2, $this->urlParameter3, $this->urlParameter4, $this->urlParameter5);
                  }
                } else {
                  // will translate to something like $this->home->method($param_1, $param_2, $param_3, $param_3);
                  $this->urlController->{$action}($this->urlParameter1, $this->urlParameter2, $this->urlParameter3, $this->urlParameter4);
                }
              } else {
                // will translate to something like $this->home->method($param_1, $param_2, $param_3);
                $this->urlController->{$action}($this->urlParameter1, $this->urlParameter2, $this->urlParameter3);
              }
            } else {
              // will translate to something like $this->home->method($param_1, $param_2);
              $this->urlController->{$action}($this->urlParameter1, $this->urlParameter2);
            }
          } else {
            // will translate to something like $this->home->method($param_1);
            $this->urlController->{$action}($this->urlParameter1);
          }
        } else {
          // if no parameters given, just call the method without parameters, like $this->home->method();
          $this->urlController->{$action}();
        }
      } else {
        // cause error if method not found or load index if blank
        if($action != '') {
          // make sure it exists, render it
          header('HTTP/1.0 404 Not Found');
          // get the controller file
          require_once(CONTROLLER_DIR . 'info-controller.php');
          $infoController = new InfoController();
          debug('<h2>Missing controller method</h2><p>The method <strong>'
            . $action
            . '()</strong> does not exist. Create method inside '
            . $controllerName
            . ' and make sure it has a view file.</p>');
          $infoController->error();
        } else {
          $this->urlController->index();
        }
      }
    } else {
      if(!isset($url)) {
        // invalid URL, so simply show home/index
        require_once(CONTROLLER_DIR . 'home-controller.php');
        $home = new HomeController();
        $home->index();
      } else {
        header('HTTP/1.0 404 Not Found');
        require_once(CONTROLLER_DIR . 'info-controller.php');
        $con = new InfoController();
        $con->error();
      }
    }

    // end session
    Session::close();
  }
}
