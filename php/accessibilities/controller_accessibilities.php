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
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $function_name = htmlspecialchars($_POST["function_name"]);
      $can_acces_orders = htmlspecialchars($_POST["can_access_orders"]);
      $can_acces_relations = htmlspecialchars($_POST["can_access_relations"]);
      $can_acces_articles = htmlspecialchars($_POST["can_access_articles"]);
      $can_acces_employees = htmlspecialchars($_POST["can_access_employees"]);

      if (!isset($function_name) || !isset($can_acces_orders) || !isset($can_acces_relations) || !isset($can_acces_articles) || !isset($can_acces_employees)) {
        $error_message = "All fields are required";
        echo $error_message;
      }

      try {
        $sql = "INSERT INTO `accessibilities`(`function_name`, `can_acces_orders`, `can_acces_relations`, `can_acces_articles`, `can_acces_employees`) VALUES ('$function_name', '$can_acces_orders', '$can_acces_relations', '$can_acces_articles', '$can_acces_employees')";
        mysqli_query($connection, $sql);
        
        echo $sql;
        $action = "add";
        $object_type = "Accessibility";
        LogfileHandler::addLogfileRecord($action, $object_type, $function_name, "new Accessibility");
        
      } catch(mysqli_sql_exception $e){
        $error_message = "invalid query: " . $e;
        echo $error_message;
      }

      header("location: GUI_accessibilities.php?action=add&status=succes&accessibility=$function_name");
      exit;
    }
  }


  public static function editAccessibility(){
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $function_name = htmlspecialchars($_POST["function_name"]);
      $can_access_orders = htmlspecialchars($_POST["can_access_orders"]);
      $can_access_relations = htmlspecialchars($_POST["can_access_relations"]);
      $can_access_articles = htmlspecialchars($_POST["can_access_articles"]);
      $can_access_employees = htmlspecialchars($_POST["can_access_employees"]);

      if (!isset($function_name) || !isset($can_access_orders) || !isset($can_access_relations) || !isset($can_access_articles) || !isset($can_access_employees)) {
        $error_message = "All fields are required";
        echo $error_message;
      }

      try {
        $sql = "UPDATE `accessibilities` SET `function_name`='$function_name',`can_acces_orders`='$can_access_orders',`can_acces_relations`='$can_access_relations',`can_acces_articles`='$can_access_articles',`can_acces_employees`='$can_access_employees' WHERE `function_name` = '$function_name'";
        mysqli_query($connection, $sql);

        $action = "edit";
        $object_type = "Accessibility";
        LogfileHandler::addLogfileRecord($action, $object_type, $function_name, "edit Accessibility");

      } catch(mysqli_sql_exception $e){
        $error_message = "Invalid query: " . $e;
        echo $error_message;
      }

      header("location: GUI_accessibilities.php?action=edit&status=succes&accessibility=$function_name");
      exit;
    
      } while(false);

    }
  

    public static function deleteAccessibility() {
      // Retrieve the connection from the global scope to be used in the function
      global $connection;
  
      if (isset($_GET["function_name"])) {
  
          // Retrieve the function_name from the selected DB record
          $function_name = $_GET["function_name"];
  
          // Declare variables for logging purposes before the accessibility is deleted
          $stmt = "SELECT function_name FROM accessibilities WHERE function_name='$function_name'";
          $result = $connection->query($stmt);
          $row = $result->fetch_assoc();
          $name = $row["function_name"];
  
          // Prepare query to delete record
          $sql = "DELETE FROM accessibilities WHERE function_name='$function_name'";
  
          // Execute sql query
          $connection->query($sql);
  
          // Add logfile record
          $action = "delete";
          $object_type = "Accessibility";
          LogfileHandler::addLogfileRecord($action, $object_type, $name, "delete Accessibility");
  
          // Return back to article overview
          header("location: GUI_accessibilities.php?action=delete&status=succes&accessibility=$name");
          exit;
      }
  }
}





?>
</html>