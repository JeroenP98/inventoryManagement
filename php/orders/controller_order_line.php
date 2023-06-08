<?php
  
//if session was started, continue it so it
session_start();

//check if user has logged in, in order to gain acces to the page. this disallows user to reach the page by entering the correct url
require_once '../include/loginCheck.php';

// create database connection with variables as parameters
require_once '../include/db_connect.php';

// add the php file for the action logging
require_once '../logging/controller_logfile.php';

if(isset($_GET["action"])){
  $action = $_GET["action"];
  if($action === "add"){
    OrderController::addOrderLine();
  } elseif($action === "edit"){
    OrderController::editOrderLine();
  } elseif($action === "delete"){
    OrderController::deleteOrderLine();
  }
} else {
  if($_GET["order_type"] === "incoming"){
    header("Location: GUI_incoming.php");
  } elseif($_GET["order_type"] === "outgoing"){
    header("Location: GUI_outgoing.php");
  } else {
    header("Location: ../../dashboard.php");
  }
  exit;
}

// Declare the ID for redirecting users back


class OrderController {
  
  public static function addOrderLine(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      //store form data under variables which have been sanitized
      $article_id = htmlspecialchars($_POST["article_id"]);
      $quantity = htmlspecialchars(filter_var($_POST["quantity"], FILTER_SANITIZE_NUMBER_INT));
      $order_id = $_POST["order_id"];

      echo "order ID = " . $order_id . "\n";
      echo "article id  = " . $article_id . "\n";
      echo "quantity = " . $quantity . "\n";

      //check if all fields are filled
      do {
        if ( !isset($article_id) || !isset($quantity) || !isset($order_id)) {
          $errorMessage = "All fields are required";
          echo $errorMessage;
          break;
        }


        try { //try to excecute sql query, or display error message
          //prepare sql query to insert data in the table
          $sql = "INSERT INTO `order_lines` (`article_id`, `quantity`, `order_id`) VALUES ($article_id, $quantity, $order_id)";

          //excecute sql query
          $result = mysqli_query($connection, $sql);

          // add logfile record
          $action = "add";
          $object_type = "order_line";
          LogfileHandler::addLogfileRecord($action, $object_type, $order_id, $article_id);

        }catch(mysqli_sql_exception $e){
          $errorMessage = "invalid query: " . $e;
          break;
        }



        // return back to article overview
        header("Location: GUI_orderEdit.php?status=succes&id=$order_id&action=add_line");
        exit;

      } while(false);
    }
  }


  public static function editOrderLine(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      //store form data under variables which have been sanitized
      $article_id = htmlspecialchars($_POST["article_id"]);
      $quantity = htmlspecialchars(filter_var($_POST["quantity"], FILTER_SANITIZE_NUMBER_INT));
      $order_id = $_POST["order_id"];
      $order_line = $_POST["order_line"];

      //check if all fields are filled
      do {
        if ( !isset($article_id) || !isset($quantity) || !isset($order_id) || !isset($order_line)) {
          $errorMessage = "All fields are required";
          echo $errorMessage;
          break;
        }
    
        //update the record or display error message
        try {  
          // prepare sql query for updating the record
          $sql =  "UPDATE `order_lines` SET `article_id`=$article_id,`quantity`=$quantity WHERE order_line = $order_line AND order_id = $order_id";
          
          echo $sql;
          //excecute sql query
          $result = mysqli_query($connection, $sql);
    
          // add logfile record
          $action = "edit";
          $object_type = "order_line";
          LogfileHandler::addLogfileRecord($action, $object_type, $order_id, $order_line);
    
        }catch(mysqli_sql_exception $e){ // Display error message when not able to perform sql query
          $errorMessage = "Invalid query: " . $e;
          echo $errorMessage;
          break;
        }
    
        // return back to article overview after posting the record
        header("Location: GUI_orderEdit.php?status=succes&id=$order_id&action=editLine");
        exit;
    
      } while(false);

    }
  }

  public static function deleteOrderLine(){
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if(isset($_GET["order_id"])){

    // retrieve the ID from selected DB record from article overview
    $order_line = $_GET["order_line"];
    $order_id = $_GET["order_id"];

    //prepare query to delete record
    $sql = "DELETE FROM order_lines WHERE order_line=$order_line AND order_id=$order_id";

    // excecute sql query
    $connection->query($sql);

    // add logfile record
    $action = "delete";
    $object_type = "order Line";
    LogfileHandler::addLogfileRecord($action, $object_type, $order_id, $order_line);

    // return back to article overview
    header("location: GUI_orderEdit.php?action=delete&status=succes&id=$order_id");
    exit;
    
      
    }
  }


}
?>
