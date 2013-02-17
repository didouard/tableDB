<?php
/**

Generic class for debug
Debug Level
Flag for SEVERE, ERROR, INFO
Depth of execution

TODO: manage display on stdout, stderr, syslog

**/

class debug {

  public function debugType($type = null) {
    global $debug;

    if (!isset($debug) || !isset($debug['type'])) {
      $debug['type'] = ($type === null) ? 'web' : $type;
    }

    if ($type != null) {
      $debug['type'] = $type;
    }

    return ($debug['type']);
  }
  
  public function debugFilename($filename = null) {
    global $debug;
  
    if (!isset($debug) || !isset($debug['filename'])) {
      $debug['filename'] = ($filename === null) ? 'web' : $filename;
    }
  
    if ($filename != null) {
      $debug['filename'] = $filename;
    }
  
    return ($debug['filename']);
  }

  public function debugLevel($debugLevel = null) {
    global $debug;

    if (!isset($debug) || ! isset($debug['debugLevel'])) {
      $debug['debugLevel'] = 42;
    }

    if ($debugLevel !== null)
      $debug['debugLevel'] = $debugLevel;
    return $debug['debugLevel'];
  }
  
  public function debugDestination($destination = null, $option = null) {
    global $debug;

    if (!isset($debug) || !isset($debug['$destination'])) {
      $debug['$destination'] = 'stdout';
    }

    if ($destination != null) {
    
      if ($destination == 'file') {
        $debug['$destination'] = $destination;
        self::debugFilename($option);
      } elseif ($destination == 'stdout') {
        $debug['$destination'] = $destination;
      } else {
        debug::message('ERROR: Destination inconnue (stdout/file)', 0, 0, 'stdout');
      }
    }
    
    return $debug['$destination'];
  }

  public function displayDepth($depth)
  {
    $str = "";
    for ($i = 0; $i < $depth; $i++) {
      $str .= "\t";
    }
    return ($str);
  }

  public function displayDebugLevel($debugLevel)
  {
    switch ($debugLevel)
    {
      case "0":
      case "ERROR":
        return "[x] ";
        break ;
      case "1":
      case "WARNING":
        return "[!] ";
        break ;
      case "INFO":
      case "2":
        return "[-] ";
        break ;
      default:
        return "[?] ";
    }
  }

  private function displayDebugType() {
    return ((self::debugType() == 'web') ? '<br/>' : "\n");
  }

  private function openFile($filename = null, $forceOpen = false) {
    global $debug;
    
    if ($filename == null) {
      $filename = self::debugFilename();
    }
    
    if (!isset($debug[$filename]['fd']) || $forceOpen != false) {
      if (!is_dir(dirname($filename))) {
        mkdir(dirname($filename), 0755);
      }
      
      if (($debug[$filename]['fd'] = @fopen($filename, 'a')) == false) {
        debug::message("ERROR : impossible d'ouvrir le fichier : ".$filename, 1, 0, 'stdout');
        return (false);
      }
    }
    return ($debug[$filename]['fd']);
  }
  
  private function writeToFile($message, $filename = null) {
    
    if (($fd = self::openFile($filename)) == false) {
      debug::message("ERROR: Le message n'a pas pu être écrit : ".$message, 1, 0, 'stdout');
      return (false);
    }
    
    
    if (@fwrite($fd, $message) == false) {
        if (($fd = self::openFile($filename)) == false) {
          debug::message("ERROR: Le message n'a pas pu être écrit : ".$message, 1, 0, 'stdout');
        }
        fwrite($fd, $message);
    }
  }
  
  public function message($message, $debugLevel = 2, $depth = 0, $destination = null, $option = null)
  {
    global $config;

    if (self::debugLevel() < $debugLevel)
      return ;

    $sDepth = self::displayDepth($depth);
    $sDebugLevel = self::displayDebugLevel($debugLevel);
    $sDebugType = self::displayDebugType();

    if ($destination == null) {
     $destination = self::debugDestination();
    }
    
    if ($destination == 'stdout') {
      echo $sDepth.$sDebugLevel.$message.$sDebugType;
    } elseif ($destination == 'file') {
      self::writeToFile($sDepth.$sDebugLevel.$message.$sDebugType, $option);
    }

    if ($debugLevel == 0) {
      echo '[x] Dying on Error !'.$sDebugType;
        if (self::debugType() == 'web') {
          echo '<pre>';
        }
        print_r(debug_print_backtrace());
      exit(2);
    }
  }

  public function displayDate() {

    debug::message("##################################################################");
    debug::message("################  ".date(DATE_COOKIE)."   ###############");
    debug::message("##################################################################");

  }

  public function writeTrace($filename) {
    $NL = self::displayDebugType();

    $str  = "##################################################################".$NL;
    $str .= "#############  ".date(DATE_COOKIE)."   ###############".$NL;
    $str .= "##################################################################".$NL;
    file_put_contents($filename, $str, FILE_APPEND);

    $dbgTrace = debug_backtrace();
    $dbgMsg = $NL."Debug backtrace begin:$NL";
    foreach($dbgTrace as $dbgIndex => $dbgInfo) {
      $dbgMsg .= "\t at $dbgIndex  ".$dbgInfo['file'].
      " (line {$dbgInfo['line']}) -> ".
      ((isset($dbgInfo['class'])) ? $dbgInfo['class'] : '').
      ((isset($dbgInfo['type'])) ? $dbgInfo['type'] : '').
      "{$dbgInfo['function']}(".
      // if args are an array, it provokes an PHP notice.
      // I blind this notice waiting for a real debug_backtraec parsing
      @join(",",$dbgInfo['args']).")$NL";
    }
    $dbgMsg .= "Debug backtrace end".$NL.$NL;

    file_put_contents($filename, $dbgMsg, FILE_APPEND);
  }

  public function rmdir($path) {
    //    self::writeTrace('/var/log/exploit/trace_rmdir.log');
    return (rmdir($path));
  }

  public function unlink($path) {
    //    self::writeTrace('/var/log/exploit/trace_rmdir.log');
    return (unlink($path));
  }

}

?>
