<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestUtility
 *
 * @author nehalsyed
 */
class TestUtility {
    //put your code here

  private static $token;  
  
  public static function getRamdomString($length = 10)
  {
      if(empty(TestUtility::$token))
      {
          TestUtility::$token = TestUtility::generateRandomString($length);
      }
      return TestUtility::$token;
  }
  
    
  public static function generateRandomString($length = 10) 
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }
    
    
    
}

?>
