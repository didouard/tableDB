<?php

require_once('sources/config.php');
require_once('libraries/sql.inc.php');
require_once('libraries/debug.inc.php');

class tableDB {
  private $tableName;
    

  /**************************/
  /*****  SETTER GETTER *****/
  /**************************/
  
  public function setTableName($tableName = null) {
    if (!isset($tableName) && !isset($this->tableName)) {
      if (preg_match('/([a-zA-Z0-9]*)TableDB/', get_class($this), $matches)) {
        $this->tableName = $matches[1];
      } else {
        debug::message('ERROR : Impossible to retrieve table name, set it before get it', 0);
      }
    } elseif (isset($tableName)) {
      $this->tableName = $tableName;
    }
  }
  
  public function getTableName() {
    if (!isset($this->tableName)) {
      $this->setTableName();
    }
    return ($this->setTableName());
  }
  
  /**************************/
  /****   Constructor   *****/
  /**************************/
  
  public function __construct() {
    $sql = "DESCRIBE ?";
    $params = array('s', $this->getDBName());
    $array = SQL::query('apps', $sql, $params);
    
    debug::message(print_r($array), true);
    foreach ($array as $kfield => $pField) {
      $this->createField($pField);
    }
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
  
  private function createField($field) {
    $this->createSetter();
  }
  
}

?>