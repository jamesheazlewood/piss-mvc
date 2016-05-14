<?php

// Base controller class
class Controller {
  /**
   * @var null Database Connection
   */
  public $db = null;

  /**
   * @var array POST data ready for form fields
   */
	public $data = array();

	/**
	 * @var array site options
	 */
	public $siteOptions = array();

	/**
	 * Whenever a controller is created, open a database connection too. The idea behind is to have ONE connection
	 * that can be used by multiple models (there are frameworks that open one connection per model).
	 */
	function __construct() {
			$this->openDatabaseConnection();
	}

	/**
	 * Open the database connection with the credentials from application/config/config.php
	 */
	private function openDatabaseConnection() {
		// set the (optional) options of the PDO connection. in this case, we set the fetch mode to
		// "objects", which means all results will be objects, like this: $result->user_name !
		// For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
		// @see http://www.php.net/manual/en/pdostatement.fetch.php
		$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

		// generate a database connection, using the PDO connector
		// @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
		$this->db = new PDO(
			Config::read('DB.type') . ':host=' . Config::read('DB.host') . ';dbname=' . Config::read('DB.database'),
			Config::read('DB.username'),
			Config::read('DB.password'),
			$options
		);
	}

	// return model from model name
	// name is converted to lowercase and is then used as the filename
	// return : NEW model object, or false if not found
	public function loadModel($modelName) {
		//
		$found = rowe(MODEL_DIR . strtolower($modelName) . '.php');
		// return new model (and pass the database connection to the model)
		return ($found ? new $modelName($this->db) : false);
	}
	
	// basically just renders the template and view
	public function render($model, $viewFile = 'index', $templateFile = 'default') {
		$headerPath = TEMPLATE_DIR . $templateFile . DS . 'header.php';
		$viewPath = VIEW_DIR . $model . DS . $viewFile . '.php';
		$footerPath = TEMPLATE_DIR . $templateFile . DS . 'footer.php';
		if(file_exists($headerPath)) {
			require_once($headerPath);
		} else {
			flerror($headerPath, 'Missing template header file', 'The header file for <strong>' . $templateFile . '</strong> does not exist.');
		}
		if(file_exists($viewPath)) {
			require_once($viewPath);
		} else {
			flerror($viewPath, 'Missing view', 'The view file for <strong>' . $model . ' / ' . $viewFile . '</strong> does not exist.');
		}
		if(file_exists($footerPath)) {
			require_once($footerPath);
		} else {
			flerror($footerPath, 'Missing template footer file', 'The footer file for <strong>' . $templateFile . '</strong> does not exist.');
		}
	}
	
	// Simple json renderer
	public function jsonRender($array) {
		$result = json_encode($array);
		//slog('JSON reply: ' . $result);
		header('Content-Type: application/json');
		echo $result;
	}
}