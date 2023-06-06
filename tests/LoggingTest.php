<?php

include './php/logging/controller_logfile.php';
use PHPUnit\Framework\TestCase;

class LoggingTest extends TestCase {

  public function testIfLoggingWorks(){
    $logfilehandler = new LogfileHandler;

    $action = 'UnitTest';
    $object_type = 'UnitTest';
    $id = 'UnitTest';
    $id_description = 'Unit test performed';
    if(!isset($_SESSION["full_name"])){
      $_SESSION["full_name"] = 'JeroenPPP';
    }

    $result = $logfilehandler->addLogfileRecord($action, $object_type, $id, $id_description);
    echo $result;

    $this->assertTrue($result, 'Failed to add log file record');
  }
}

