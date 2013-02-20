<?php

class fieldType {
    private $name;
    private $type;
    private $null;
    private $key;
    private $default;
    private $extra;
    private $value;

  /**************************/
  /*****  SETTER GETTER *****/
  /**************************/
  
  public function getName($type) {
    return ($this->buildGetName($this->name, $type));
  }
  
  public function setName($name) {
    $this->name = $this->buildSetName($name);
  }
  
  public function getType() {
    return ($this->type);
  }
  
  public function setType($type) {
    $this->type = $this->buildSetType($type);
  }
  
  public function getNull() {
    return ($this->null);
  }
  
  public function setNull($null) {
    $this->null = $null;
  }
  
  public function getKey() {
    return ($this->key);
  }
  
  public function setKey($key) {
    $this->key = $key;
  }
  
  public function getDefault() {
    return ($this->default);
  }
  
  public function setDefault($default) {
    $this->default = $default;
  }
  
  public function getExtra() {
    return ($this->extra);
  }
  
  public function setExtra($extra) {
    $this->extra = $extra;
  }
  
  public function getValue() {
    return ($this->value);
  }
  
  public function setValue($value) {
    $this->value = $value;
  }
  
  /**************************/
  /****   Constructor   *****/
  /**************************/
  
  public function __construct() {

  }
    
  /**************************/
  /*** Particularities   ****/
  /**************************/
  
  public function buildGetName($words, $type) {
    $string = "";
    $firstWord = true;
    
    foreach ($words as $kWord => $pWord) {
      if ($type == FIELDTYPE_NAMING_OBJECT) {
        $string .= ($firstWord) ? $pWord : ucfirst($pWord);
      } elseif ($type == FIELDTYPE_NAMING_SQL) {
        $string .= ($firstWord) ? $pWord : '_'.$pWord;
      }
      $firstWord = ($firstWord) ? false : $firstWord;
    }
    return ($string);
  }
  
  public function buildSetName($string) {
    if (preg_match('/_+/', $string)) {
      $words = preg_split('/[\s_]+/', $string);
    } elseif (!preg_match_all('/[A-Z]/', $string)) {
      $words = array($string);
    } else {
      preg_match_all('/((?:^|[A-Z])[a-z]+)/', $string, $matches);
      $words = $matches[0];
    }
    foreach ($words as $kWord => $pWord) {
      $words[$kWord] = strtolower($pWord);
    }
    return ($words);
  }
}

?>