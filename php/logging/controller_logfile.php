<?php


class LogfileHandler {

  public static function addLogfileRecord($action, $object_type, $id, $id_description) {
    // Declare empty message variable. This message will be added to the logfile
    $message = "";

    // Declare the current date to add as a timestamp
    $timestamp = date("Y-m-d \@ H:i:s");

    // Get the path to the test file
    $testFilePath = realpath(dirname(__FILE__));
    
    // Locate the logfile file 
    $filename = $testFilePath . '/../../logfiles/logfile.txt';

    // prepare message variable to be added in the logfile
    $message = "{$timestamp} [{$_SESSION["full_name"]}] Performed the action: {$action} {$object_type} with id: {$id} / {$id_description}\n";
        
    try {
      $result = file_put_contents($filename, $message, FILE_APPEND);
      return $result !== false;
    } catch (Exception $e) {
      echo 'Caught exception: ' .  $e->getMessage();
      return false;
    }

  }
}

?>