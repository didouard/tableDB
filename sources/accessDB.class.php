<?php

require_once('class/sql.inc.php');
require_once('class/debug.inc.php');
require_once('config/config.php');

class accessDB {
   

  /**************************/
  /*****  SETTER GETTER *****/
  /**************************/
  
  
  
  /**************************/
  /****   Constructor   *****/
  /**************************/
   
  public function __construct() {

  }
  
  /**************************/
  /*****      CRUD      *****/
  /**************************/

  public function create() {
  
  }

  public function read() {
    
  }
  
  public function update() {
    
  }
  
  public function delete() {
    
  }
  
  /**************************/
  /*** Particularities   ****/
  /**************************/
  
  public function test() {
    $sql = "DESCRIBE `apps`";
    $params = array();
    $array = SQL::query('apps', $sql, $params);
    
    print_r($array);
  }
  
}

?>