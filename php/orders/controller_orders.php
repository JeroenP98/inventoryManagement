<?php
  
//if session was started, continue it so it
session_start();

//check if user has logged in, in order to gain acces to the page. this disallows user to reach the page by entering the correct url
require_once '../include/loginCheck.php';

// create database connection with variables as parameters
require_once '../include/db_connect.php';

// add the php file for the action logging
require_once '../logging/controller_logfile.php';

// asses which action was used and call the corresponding class method
if(isset($_GET["action"])){
  $action = $_GET["action"];
  if($action === "add"){
    OrderController::addOrder();
  } elseif($action === "edit"){
    OrderController::editOrder();
  } elseif($action === "delete"){
    OrderController::deleteOrder();
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

class OrderController {
  
  public static function addOrder(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      //store form data under variables which have been sanitized
      $order_date = date('Y-m-d', strtotime($_POST["order_date"]));
      $shipping_date = date('Y-m-d', strtotime($_POST["shipping_date"]));
      $order_type = htmlspecialchars($_POST["order_type"]);
      $employee_id = htmlspecialchars($_POST["employee_id"]);
      $relation_id = htmlspecialchars($_POST["relation_id"]);
      $company_id = htmlspecialchars($_POST["company_id"]);
      
      //check if all fields are filled
      do {
        if ( !isset($order_date) || !isset($shipping_date) || !isset($order_type) || !isset($employee_id) || !isset($relation_id)) {
          $errorMessage = "All fields are required";
          echo $errorMessage;
          if($_GET["order_type"] === "incoming"){
            header("Location: GUI_incoming.php");
          } elseif($_GET["order_type"] === "outgoing"){
            header("Location: GUI_outgoing.php");
          } else {
            header("Location: ../../dashboard.php");
          }
          break;
        }


        try { //try to excecute sql query, or display error message
          //prepare sql query to insert data in the table
          $sql = "INSERT INTO `orders` (`order_date`, `shipping_date`, `order_type`, `employee_id`, `relation_id`, `company_id`) VALUES ('$order_date', '$shipping_date',$order_type, $employee_id, $relation_id, $company_id)";

          echo $sql;
          //excecute sql query
          $result = mysqli_query($connection, $sql);
          


          //retrieve the ID from the record just added, so you are send to the correct edit page
          if($result){
            $order_id = mysqli_insert_id($connection);
          }

          // add logfile record
          $action = "add";
          $object_type = "order";
          LogfileHandler::addLogfileRecord($action, $object_type, $order_id, $order_date);

        }catch(mysqli_sql_exception $e){
          $errorMessage = "invalid query: " . $e;
          break;
        }



        // return back to article overview
        header("Location: GUI_orderEdit.php?status=succes&id=$order_id&action=add");
        exit;

      } while(false);
    }
  }


  public static function editOrder(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      //store form data under variables which have been sanitized
      $id = $_POST['id'];
      $order_date = date('Y-m-d', strtotime($_POST["order_date"]));
      $shipping_date = date('Y-m-d', strtotime($_POST["shipping_date"]));
      $employee_id = htmlspecialchars($_POST["employee_id"]);
      $relation_id = htmlspecialchars($_POST["relation_id"]);
      $company_id = htmlspecialchars($_POST["company_id"]);
      $is_finalized = htmlspecialchars($_POST["is_finalized"]);

      //check if all fields are filled
      do {
        if ( !isset($order_date) || !isset($shipping_date) || !isset($employee_id) || !isset($relation_id)) {
          $errorMessage = "All fields are required";
          echo $errorMessage;
          if($_GET["order_type"] === "incoming"){
            header("Location: GUI_incoming.php");
          } elseif($_GET["order_type"] === "outgoing"){
            header("Location: GUI_outgoing.php");
          } else {
            header("Location: ../../dashboard.php");
          }
          break;
        }
    
        //update the record or display error message
        try {  
          // prepare sql query for updating the record
          $sql =  "UPDATE `orders` SET `order_date`='$order_date',`shipping_date`='$shipping_date',`employee_id`=$employee_id,`relation_id`=$relation_id, `company_id`= $company_id , `is_finalized`=$is_finalized WHERE id = $id";
          
          echo $sql;
          //excecute sql query
          $result = mysqli_query($connection, $sql);
    
          // add logfile record
          $action = "edit";
          $object_type = "Order";
          LogfileHandler::addLogfileRecord($action, $object_type, $id, $order_date);
    
        }catch(mysqli_sql_exception $e){ // Display error message when not able to perform sql query
          $errorMessage = "Invalid query: " . $e;
          echo $errorMessage;
          break;
        }
    
        // return back to article overview after posting the record
        header("Location: GUI_orderEdit.php?status=succes&id=$id&action=edit");
        exit;
    
      } while(false);

    }
  }

  public static function deleteOrder(){
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if(isset($_GET["id"])){

    // retrieve the ID from selected DB record from order overview
    $id = $_GET["id"];
  
    // declare variables for logging purposes before the order is deleted
    $stmt = "SELECT id, order_date FROM orders WHERE id=$id";
    $result = $connection->query($stmt);
    $row = $result->fetch_assoc();
    $id = $row["id"];
    $order_date = $row["order_date"];

    //prepare query to delete record
    $sql = "DELETE FROM orders WHERE id=$id";

    // excecute sql query
    $connection->query($sql);

    // add logfile record
    $action = "delete";
    $object_type = "user";
    LogfileHandler::addLogfileRecord($action, $object_type, $id, $order_date);

    // return back to order overview
    if($_GET["order_type"] === "incoming"){
      header("Location: GUI_incoming.php");
    } elseif($_GET["order_type"] === "outgoing"){
      header("Location: GUI_outgoing.php");
    } else {
      header("Location: ../../dashboard.php");
    }
    exit;
    
      
    }
  }


}
?>