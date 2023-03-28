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

      echo $order_date;

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
          
          // add logfile record
          $action = "add";
          $object_type = "order";
          LogfileHandler::addLogfileRecord($action, $object_type, $relation_id, $employee_id);

          //retrieve the ID from the record just added, so you are send to the correct edit page
          if($result){
            $order_id = mysqli_insert_id($connection);
          }

        }catch(mysqli_sql_exception $e){
          $errorMessage = "invalid query: " . $e;
          break;
        }



        // return back to article overview
        header("location: GUI_orderEdit.php?id=$order_id");
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
      $first_name = htmlspecialchars(ucfirst(strtolower($_POST["first_name"])));
      $last_name = htmlspecialchars(ucfirst(strtolower($_POST["last_name"])));
      $email = filter_var(strtolower($_POST["email_adress"]), FILTER_SANITIZE_EMAIL);
      $company_id = $_POST["company_id"];
      $function_name = htmlspecialchars($_POST["function_name"]);
      // validate that the is_active status is between 0 and 1
      $options = array(
        "options" => array(
          "min_range"=>0, 
          "max_range"=>1
        )
      );

      $is_active = htmlspecialchars(filter_var($_POST["is_active"], FILTER_SANITIZE_NUMBER_INT, $options));

      do {
        if ( empty($first_name) || empty($last_name) || empty($email) || empty($company_id) || empty($function_name)) {
            $errorMessage = "All fields are required";
            break;
        }
    
        //update the record or display error message
        try {  
          // prepare sql query for updating the record
          $sql =  "UPDATE employees SET first_name='$first_name', last_name='$last_name',email_adress='$email', company_id=$company_id, function_name='$function_name', is_active=$is_active WHERE id = $id";
          

          //excecute sql query
          $result = mysqli_query($connection, $sql);
    
          // add logfile record
          $action = "edit";
          $object_type = "user";
          LogfileHandler::addLogfileRecord($action, $object_type, $first_name, $email);
    
        }catch(mysqli_sql_exception $e){ // Display error message when not able to perform sql query
          $errorMessage = "Invalid query: " . $e;
          echo $errorMessage;
          break;
        }
    
        // return back to article overview after posting the record
        header("location: GUI_users.php?action=edit&status=succes&user=$first_name");
        exit;
    
      } while(false);

    }
  }

  public static function deleteOrder(){
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if(isset($_GET["id"])){

    // retrieve the ID from selected DB record from article overview
    $id = $_GET["id"];
  
    // declare variables for logging purposes before the article is deleted
    $stmt = "SELECT first_name, email_adress FROM employees WHERE id=$id";
    $result = $connection->query($stmt);
    $row = $result->fetch_assoc();
    $first_name = $row["first_name"];
    $email = $row["email_adress"];

    //prepare query to delete record
    $sql = "DELETE FROM employees WHERE id=$id";

    // excecute sql query
    $connection->query($sql);

    // add logfile record
    $action = "delete";
    $object_type = "user";
    LogfileHandler::addLogfileRecord($action, $object_type, $first_name, $email);

    // return back to article overview
    header("location: GUI_users.php?action=delete&status=succes&user=$first_name");
    exit;
    
      
    }
  }


}
?>