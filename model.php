<?php
// base Model class
class Model {
	// constructor
	function __construct($db) {
    try {
      $this->db = $db;
    } catch(PDOException $e) {
      exit('Database connection could not be established.');
    }
  }

  // Inserts to database
  public function insert($data, $tableName) {
    // build statement out of data key values.
    // make sure they are correct programmer minion
    $sql = "";
    $sql .= "INSERT INTO $tableName (";
    $count = 0;
    foreach($data as $k => $v) {
      $sql .= ($count > 0 ? ',' : '') . $k;
      $count ++;
    }
    $sql .= ") VALUES (";
    $count = 0;
    foreach($data as $k => $v) {
      $sql .= ($count > 0 ? ',' : '') . ':' . $k;
      $count ++;
    }
    $sql .= ")";

    // create the execute array
    $executeData = array();
    foreach($data as $k => $v) {
      $executeData[':' . $k] = $v;
    }

    // prepare
    $statement = $this->db->prepare($sql);
    $error = false;
    try {
      $statement->execute($executeData);
    } catch(Exception $e) {
      message('Caught exception: ' . $e->getMessage(), 'bad');
      $error = true;
    }

    // return ID of newly created entry
    if(!$error) {
      return $this->db->lastInsertId();
    }
    
    return 0;
  }

  //
  public function update($data, $tableName = null, $id = null) {

    // unset ID because we don't need to update it
    unset($data['id']);

    // if at this stage, after ID is gone,
    // we have no data, go away
    if(empty($data)) {
      return true;
    }

    // build statement out of data key values.
    // make sure they are correct programmer minion
    $sql = "";
    $sql .= "UPDATE $tableName SET ";
    $count = 0;
    foreach($data as $k => $v) {
      $sql .= ($count > 0 ? ',' : '') . $k . ' = :' . $k;
      $count ++;
    }
    $sql .= " WHERE id = :id LIMIT 1";

    // create the execute array
    $executeData = array();
    foreach($data as $k => $v) {
      $executeData[':' . $k] = $v;
    }
    $executeData[':id'] = $id;

    // prepare
    $statement = $this->db->prepare($sql);
    $error = false;
    try {
      $statement->execute($executeData);
    } catch(Exception $e) {
      message('Caught exception: ' . $e->getMessage(), 'bad');
      $error = true;
    }

    // return ID of newly created entry
    if($error) {
      return false;
    }

    return true;
  }

  // validates an array of values based on an array of rules for those values
  public function validateWithMessages($data, $messages) {
    // for every validation error message
    foreach($messages as $k => $v) {
      // if the field wasn't even set in the data array, and its required,
      // we want to trigger a required error
      if(!isset($data[$k])) {
        // if data is not
        if(isset($v['required'])) {
          $data['_ValidationErrors'][$k] = $v['required'];
        } else {
          $data['_ValidationErrors'][$k] = 'This field is required.';
        }
      } else {
        // if the field is set, and we have an error message,
        // check which error message and trigger appropriate error

        // empty string
        if($data[$k] == '') {
          if(isset($v['required'])) {
            $data['_ValidationErrors'][$k] = ($v['required'] === true ? 'This field is required.' : $v['required']);
          }
        }
        // other for when field data is there
        if(isset($v['email'])) {
          if(!validEmail($data[$k])) $data['_ValidationErrors'][$k] = ($v['email'] === true ? 'Enter a valid email.' : $v['email']);
        }
        // int
        if(isset($v['int'])) {
          if(!is_int($data[$k])) $data['_ValidationErrors'][$k] = ($v['int'] === true ? 'Enter a valid whole number.' : $v['int']);
        }
        // number
        if(isset($v['number'])) {
          if(!is_numeric($data[$k])) $data['_ValidationErrors'][$k] = $v['number'];
          if(!is_numeric($data[$k])) $data['_ValidationErrors'][$k] = ($v['number'] === true ? 'Enter a valid number.' : $v['number']);
        }
        // password (must have 'password' in data
        if(isset($v['confirm_password'])) {
          if(!isset($data['password']) || $data[$k] != $data['password']) $data['_ValidationErrors'][$k] = $v['confirm_password'];
          if(!isset($data['password']) || $data[$k] != $data['password']) {
            $data['_ValidationErrors'][$k] = ($v['confirm_password'] === true ? 'Passwords do not match.' : $v['confirm_password']);
          }
        }
      }
    }
    return $data;
  }

  //
  public function validateModel($data, $modelName, $messages) {
    // make sure this is actually set
    // if not return empty array
    if(isset($data[$modelName])) {
      $dataFields = $data[$modelName];

      // strip shit out of all fields
      $dataFields = stripArray($dataFields);

      // loop through data to validate (most likely _POST)
      $dataFields = $this->validateWithMessages($dataFields, $messages);

      return $dataFields;
    } else {
      return array();
    }
  }
}
