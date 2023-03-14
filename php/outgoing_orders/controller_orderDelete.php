<?php

//if session was started, continue it so it
session_start();

//check if user has logged in, in order to gain acces to the page. this disallows user to reach the page by entering the correct url
require_once '../include/loginCheck.php';

// add the php file for the action logging
require_once '../logging/controller_logfile.php';

if(isset($_GET["id"])){

  // retrieve the ID from selected DB record from article overview
  $id = $_GET["id"];

  // create database connection
  require_once '../include/db_connect.php';

  // declare variables for logging purposes before the article is deleted
  $stmt = "SELECT id, order_date, shipping_date, order_type, employee_id, relation_id, company_id, is_finalized FROM orders WHERE id=$id";
  $result = $connection->query($stmt);
  $row = $result->fetch_assoc();
  echo $stmt;
  echo $row;

  $order_id = $row["id"];
  $order_date = $row["order_date"];
  $shipping_date = $row["shipping_date"];
  $order_type = $row["order_type"];
  $employee_id = $row["employee_id"];
  $order_date = $row["order_date"];
  $relation_id = $row["relation_id"];
  $company_id = $row["company_id"];
  $is_finalized = $row["is_finalized"];

  
  //prepare query to delete record
  $sql = "DELETE FROM orders WHERE id=$id";

  // excecute sql query
  $connection->query($sql);


  // declare variable for logfile 
  $action = "delete";
  $object_type = "order";
  LogfileHandler::addLogfileRecord($action, $object_type, $order_id, $order_date,$shipping_date, $order_type, $employee_id, $relation_id,$company_id,$is_finalized);

}

// return back to article overview
header("location: GUI_outgoing.php");
exit;

?>