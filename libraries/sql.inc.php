<?php

//require_once("debug.inc.php");

class SQL {

  private function error($name) {
    global $sqlList;

    $link = $sqlList[$name]['link'];

    return ($link->error);
  }

  public function getMysqli($name = 'apps') {
    global $sqlList;
    $connected = self::connect($name);

    return $sqlList[$name]['link'];
  }

  public function connect($name = 'apps') {
    global $sqlList;
    global $config;

    if (!isset($config)) {
      debug::message('ERROR: Config variable not set, is config file loaded ?', 0);
    }

    if (!isset($sqlList[$name]) || !isset($sqlList[$name]['link'])) {
      $mysqli = new mysqli($config['db'][$name]['db_ip'],
        $config['db'][$name]['username'],
        $config['db'][$name]['password'],
        $config['db'][$name]['db_name']);
      debug::message('Mysql connection with ------\''.$name.'\'--------------', 50);

      /* checking the connection */
      if ($return = mysqli_connect_errno()) {
        printf("Mysql connection problem : %s\n", mysqli_connect_error());
        exit(2);
      }

      /* Modification du jeu de rultats en utf8 */
      if (!$mysqli->set_charset("utf8")) {
        printf("Erreur lors du chargement du jeu de caractres utf8 : %s\n", $mysqli->error);
      }

      $sqlList[$name]['link'] = &$mysqli;
    } else {
      if (!mysqli_ping($sqlList[$name]['link'])) {
	unset($sqlList[$name]['link']);
	SQL::connect($name);
      }
    }

    return (mysqli_connect_errno());

  }

  public function build_server_name($name, $sql, $forceServerName = false) {
    if ($forceServerName) {
      return ($name);
    }

    $temp = substr($name, -3);

    if (substr($name, -3) == '-rw' || substr($name, -3) == '-ro')
      $name = substr($name, 0, -3);

    $sql_array = explode(' ', $sql);

    switch ($sql_array[0]) {
      case 'SELECT':
        return ($name.'-ro');
        break;
      case 'UPDATE':
      case 'INSERT':
      case 'DELETE':
      default:
        return($name.'-rw');
        break;
    }
  }

  /*
   Function: mysqli_prepared_query()
  Executes prepared querys given query syntax, and bind parameters
  Returns data in array format

  Arguments:
  mysqli_link
  mysqli_prepare query
  mysqli_stmt_bind_param argmuent list in the form array($typeDefinitinonString, $var1 [, mixed $... ])

  Return values:
  When given SELECT, SHOW, DESCRIBE or EXPLAIN statements: returns table data in the form resultArray[row number][associated field name]
  Returns number of rows affacted when given other queries
  Returns FALSE on error

  */
  public function query($name = 'default', $sql = NULL, $bindParams = FALSE, $forceServerName = false) {
    global $sqlList;

    $name = self::build_server_name($name, $sql, $forceServerName);

    $connected = self::connect($name);

    $link = $sqlList[$name]['link'];

    if ($sql == NULL)
      return ($connected);

    if($stmt = mysqli_prepare($link,$sql)){
      if ($bindParams){
        $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');  //allows for call to mysqli_stmt->bind_param using variable argument list
        $bindParamsReferences = array();  //will act as arguments list for mysqli_stmt->bind_param

        $typeDefinitionString = array_shift($bindParams);
        foreach($bindParams as $key => $value){
          $bindParamsReferences[$key] = &$bindParams[$key];
        }

        array_unshift($bindParamsReferences,$typeDefinitionString); //returns typeDefinition as the first element of the string
        $bindParamsMethod->invokeArgs($stmt,$bindParamsReferences); //calls mysqli_stmt->bind_param suing $bindParamsRereferences as the argument list
      }
      if(mysqli_stmt_execute($stmt)){
        $resultMetaData = mysqli_stmt_result_metadata($stmt);
        if($resultMetaData){
          $stmtRow = array(); //this will be a result row returned from mysqli_stmt_fetch($stmt)
          $rowReferences = array();  //this will reference $stmtRow and be passed to mysqli_bind_results
          while ($field = mysqli_fetch_field($resultMetaData)) {
            $rowReferences[] = &$stmtRow[$field->name];
          }
          mysqli_free_result($resultMetaData);
          $bindResultMethod = new ReflectionMethod('mysqli_stmt', 'bind_result');
          $bindResultMethod->invokeArgs($stmt, $rowReferences); //calls mysqli_stmt_bind_result($stmt,[$rowReferences]) using object-oriented style
          $result = array();
          while(mysqli_stmt_fetch($stmt)){
            foreach($stmtRow as $key => $value){  //variables must be assigned by value, so $result[] = $stmtRow does not work (not really sure why, something with referencing in $stmtRow)
              $row[$key] = $value;
            }
            $result[] = $row;
          }
          mysqli_stmt_free_result($stmt);
        } else {
          $result = mysqli_stmt_affected_rows($stmt);
        }
        mysqli_stmt_close($stmt);
      } else {
        debug::message('WARNING : mysqli_stmt_execute '.self::error($name), 0);
        $result = FALSE;
      }
    } else {
      debug::message('WARNING : mysqli_prepare '.self::error($name), 0);
      $result = FALSE;
    }
    return $result;
  }

  public function query_proc_stock($name = 'default', $sql = NULL, $forceServerName = false) {
    global $sqlList;

    $name = self::build_server_name($name, $sql, $forceServerName);

    $connected = self::connect($name);

    $link = $sqlList[$name]['link'];

    if ($sql === NULL)
      return ($connected);

    if (!($stmt = mysqli_multi_query($link,$sql))) {
      echo "Echec lors de la préparation : (" . $link->errno . ") " . $link->error;
    }

    $results = array();
    do {
      if ($res = $link->store_result()) {
        $result = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
      } else {
        if (mysqli_connect_errno()) {
          echo "Echec de STORE : (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
        }
      }
      $results[] = $result;
    } while ($link->more_results() && $link->next_result());
    return $results;
  }

  public function get_last_insert_id($name) {
    global $sqlList;
    
    $name = self::build_server_name($name, 'INSERT');
    $link = $sqlList[$name]['link'];
    
    return ($link->insert_id);
  }
  
  public function close($name = 'default') {
    global $sqlList;

    if (isset($sqlList)) {
      foreach ($sqlList as $k => $v) {
        $v['link']->close();
        unset($sqlList[$k]);
      }
    }
  }

}


?>
