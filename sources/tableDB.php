<?php

require_once('config.php');
require_once('../libraries/sql.inc.php');
require_once('../libraries/debug.inc.php');
require_once('../sources/fieldType.php');

class tableDB {
  private $tableName;
  private $_fields;
    

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
    return ($this->tableName);
  }
  
  
  /**************************/
  /****   Constructor   *****/
  /**************************/
  
  public function __construct() {
    $sql = "DESCRIBE ".$this->getTableName();
    $params = array();
    $array = SQL::query('apps', $sql, $params);
    
    foreach ($array as $kField => $pField) {
      $fieldType = new fieldType();
      $fieldType->setName($pField['Field']);
      $fieldType->setType($pField['Type']);
      $fieldType->setNull($pField['Null']);
      $fieldType->setKey($pField['key']);
      $fieldType->setDefault($pField['Default']);
      $fieldType->setExtra($pField['Extra']);
      
      $this->_fields[$pField['Field']] = $fieldType;
    }
  }
  
  /**************************/
  /****   Overloading   *****/
  /**************************/
  
  public function __set($name, $value) {
    
  }
  
  public function __get($name) {
    
  }
  
  public function __isset($name) {
    
  }
  
  public function __empty($name) {
    
  }
  
  public function __call($name, $arguments) {
    echo "Appel de la méthode '$name' "
    . implode(', ', $arguments). "\n";
    
    if (preg_match('/^set(\w+)*/', $name, $matches)) {
      $this->setValue($matches[0], $arguments);
    } elseif (preg_match('/^get(\w+)*/', $name, $matches)) {
      return ($this->getValue($matches[0], $arguments));
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

  private function setField($name, $arguments) {
    
    foreach ($this->_fields as $kField => $pField) {
      $fieldName = strtolower($pField->getName());
      if ($fieldName == strtolower($words)) {
        $this->_fields[$kField]->setValue($arguments[0]);
      }
    }
  }
  
  private function getField($name, $argument) {
    debug::message('Do get call');
  }
}

?>