<?php
// config class
// access with Config::read and Config::write
class Config
{
  //
  private static $data = array();
  
  // adds data to the config
  public static function write($key, $value)
  {
    self::$data[$key] = $value;
  }
  
  // read data out of config
  public static function read($key)
  {
    return self::$data[$key];
  }
  
  // returns the entire variable
  public static function readAll()
  {
    return self::$data;
  }
}
