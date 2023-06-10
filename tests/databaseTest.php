<?php

use PHPUnit\Framework\TestCase;


class DatabaseTest extends TestCase
{
  protected static $mysqli;
  protected static $last_insert;

  public static function setUpBeforeClass(): void
  {
      
      $host = 'localhost';
      $dbname = 'greenhome';
      $username = 'root';
      $password = '';
      
      self::$mysqli = new mysqli($host, $username, $password, $dbname);

      if (self::$mysqli->connect_errno) {
          echo "Failed to connect to MySQL: " . self::$mysqli->connect_error;
          exit();
      }
  }
  
  public function testConnection()
  {
      $this->assertInstanceOf(mysqli::class, self::$mysqli);
  }
  

  public function testInsertData()
  {
    $tableName = 'articles';
    $data = [
        'name' => 'unitTest',
        // Add more columns and values as needed
    ];

    $columns = implode(',', array_keys($data));
    $values = "'" . implode("','", array_values($data)) . "'";

    $query = "INSERT INTO $tableName ($columns) VALUES ($values)";

    $result = self::$mysqli->query($query);

    $this->assertTrue($result);

    self::$last_insert = self::$mysqli->insert_id;
  }




  public function  testEditData(){
    $tableName = 'articles';
    $sql = "UPDATE $tableName SET `name` = 'UnitTest edited' WHERE id = " . self::$last_insert;
    $result = self::$mysqli->query($sql);

    $this->assertTrue($result);
  }

  public function testDeleteData(){
    $tableName = 'articles';
    $sql = "DELETE FROM $tableName WHERE id = " . self::$last_insert;

    $result = self::$mysqli->query($sql);

    $this->assertTrue($result);
  }
  
}