<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap code-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../images/logo.png">
    <script src="../../js/formValidator.js"></script>
    <script src="../../js/tableSearch.js"></script>
    <script src="../../js/darkMode.js"></script>
    <title>Accessibility | GreenHome</title>
  </head>
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
    UserController::addAccessibility();
  } elseif($action === "edit"){
    UserController::editAccessibility();
  } elseif($action === "delete"){
    UserController::deleteAccessibility();
  }

} else {
  header("Location: ../accessibilities/GUI_accessibilities.php");
  exit;
};

class UserController {


  public static function addAccessibility(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      //store form data under variables which have been sanitized

      $id = htmlspecialchars($_POST["id"]);
      $function_name = htmlspecialchars($_POST["function_name"]);
      $can_acces_orders = htmlspecialchars($_POST["can_acces_orders"]);
      $can_acces_relations = htmlspecialchars($_POST["can_acces_relations"]);
      $can_acces_articles = htmlspecialchars($_POST["can_acces_articles"]);
      $can_acces_employees = htmlspecialchars($_POST["can_acces_employees"]);


      //check if all fields are filled
      if ( empty($function_name) || empty($can_acces_orders) || empty($can_acces_relations) || empty($can_acces_articles) || empty($can_acces_employees) ) {
          $error_message = "All fields are required";
          echo $error_message;
      }


      try { //try to excecute sql query, or display error message
        //prepare sql query to insert data in the table
        $sql = "INSERT INTO `accessibilities`(`function_name`, `can_acces_orders`, `can_acces_relations`, `can_acces_articles`, `can_acces_employees`) VALUES ('$function_name', '$can_acces_orders', '$can_acces_relations', '$can_acces_articles', '$can_acces_employees')";

        echo $sql;
        //excecute sql query
        mysqli_query($connection, $sql);
        
        // add logfile record
        $action = "add";
        $object_type = "Accessibility";
        LogfileHandler::addLogfileRecord($action, $object_type, $name, "new Accessibility");
        
      }catch(mysqli_sql_exception $e){
        $error_message = "invalid query: " . $e;
        echo $error_message;
      }


      //return back to article overview
      header("location: GUI_accessibilities.php?action=add&status=succes&accessibility=$name");
      exit;
    }
  }


  public static function editAccessibility(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      //store form data under variables which have been sanitized
      $function_name = htmlspecialchars($_POST["function_name"]);
      $can_acces_orders = htmlspecialchars($_POST["can_acces_orders"]);
      $can_acces_relations = htmlspecialchars($_POST["can_acces_relations"]);
      $can_acces_articles = htmlspecialchars($_POST["can_acces_articles"]);
      $can_acces_employees = htmlspecialchars($_POST["can_acces_employees"]);

      //check if all fields are filled
      if ( empty($function_name) || empty($can_acces_orders) || empty($can_acces_relations) || empty($can_acces_articles) || empty($can_acces_employees) ) {
        $error_message = "All fields are required";
        echo $error_message;
    }

        //update the record or display error message
      try {  
        // prepare sql query for updating the record
        $sql = "UPDATE `accessibilities` SET `function_name`='$function_name',`can_acces_orders`='$can_acces_orders',
        `can_acces_relations`='$can_acces_relations',
        `can_acces_articles`='$can_acces_articles',
        `can_acces_employees`='$can_acces_employees'WHERE `id` = $id";


        //excecute sql query
        mysqli_query($connection, $sql);
  
        // add logfile record
        $action = "edit";
        $object_type = "Accessibility";
        LogfileHandler::addLogfileRecord($action, $object_type, $name, "edit Accessibility");
  
      }catch(mysqli_sql_exception $e){ // Display error message when not able to perform sql query
        $error_message = "Invalid query: " . $e;
        echo $error_message;
      }

      // return back to article overview after posting the record
      header("location: GUI_accessibilities.php?action=edit&status=succes&accessibility=$name");
      exit;
    
      } while(false);

    }
  

  public static function deleteAccessibility(){
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if(isset($_GET["id"])){

    // retrieve the ID from selected DB record from article overview
    $id = $_GET["id"];
  
    // declare variables for logging purposes before the article is deleted
    $stmt = "SELECT name FROM accessibilities WHERE id=$id";
    $result = $connection->query($stmt);
    $row = $result->fetch_assoc();
    $name = $row["name"];
  

    //prepare query to delete record
    $sql = "DELETE FROM accessibilities WHERE id=$id";

    // excecute sql query
    $connection->query($sql);

    // add logfile record
    $action = "delete";
    $object_type = "Accessibility";
    LogfileHandler::addLogfileRecord($action, $object_type, $name, "delete Accessibility");

    // return back to article overview
    header("location: GUI_accessibilities.php?action=delete&status=succes&accessibility=$name");
    exit;
    
      
    }
  }
}





?>
</html>