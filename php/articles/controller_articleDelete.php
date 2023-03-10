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
  $stmt = "SELECT article_id, description FROM articlesgh WHERE id=$id";
  $result = $connection->query($stmt);
  $row = $result->fetch_assoc();
  $article_id = $row["article_id"];
  $description = $row["description"];

  
  //prepare query to delete record
  $sql = "DELETE FROM articlesgh WHERE id=$id";

  // excecute sql query
  $connection->query($sql);


  // declare variable for logfile 
  $action = "delete";
  $object_type = "article";
  LogfileHandler::addLogfileRecord($action, $object_type, $article_id, $description);
  

}

// return back to article overview
header("location: GUI_articles.php");
exit;

?>