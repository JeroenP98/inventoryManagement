<?php


class LogfileHandler {

  public static function addLogfileRecord($action, $object_type, $id, $id_description): void {
    // Declare empty message variable. This message will be added to the logfile
    $message = "";

    // Declare the current date to add as a timestamp
    $timestamp = date("Y-m-d \@ H:i:s");

    // Locate the logfile file 
    $filename = "../../logfiles/logfile.txt";

    // prepare message variable to be added in the logfile
    $message = "{$timestamp} [{$_SESSION["full_name"]}] Performed the action: {$action} {$object_type} with id: {$id} / {$id_description}\n";
        
    try {
      file_put_contents($filename, $message, FILE_APPEND);
    } catch (Exception $e) {
      echo 'Caught exception: ' .  $e->getMessage();
    }

  }
}


?>