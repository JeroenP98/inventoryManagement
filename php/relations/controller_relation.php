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
    <title>Relations | GreenHome</title>
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
    UserController::addRelation();
  } elseif($action === "edit"){
    UserController::editRelation();
  } elseif($action === "delete"){
    UserController::deleteRelation();
  }

} else {
  header("Location: ../users/GUI_relations.php");
  exit;
};

class UserController {


  public static function addrelation(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      //store form data under variables which have been sanitized

      $name = htmlspecialchars($_POST["name"]);
      $street = htmlspecialchars($_POST["street"]);
      $house_nr = htmlspecialchars($_POST["house_nr"]);
      $zip_code = htmlspecialchars($_POST["zip_code"]);
      $city = htmlspecialchars($_POST["city"]);
      $country_code = htmlspecialchars(strtoupper($_POST["country_code"]));
      $email_adress =  filter_var($_POST["email_adress"], FILTER_SANITIZE_EMAIL);
      $phone_number = htmlspecialchars($_POST["phone_number"]);

      //check if all fields are filled
      if ( empty($name) || empty($street) || empty($zip_code) || empty($city) || empty($country_code) || empty($email_adress) || empty($phone_number)) {
          $error_message = "All fields are required";
          echo $error_message;
      }


      try { //try to excecute sql query, or display error message
        //prepare sql query to insert data in the table
        $sql = "INSERT INTO `relations`(`name`, `street`, `house_nr`, `zip_code`, `city`, `country_code`, `email_adress`, `phone_number`) VALUES ('$name', '$street', '$house_nr', '$zip_code', '$city', '$country_code', '$email_adress', '$phone_number')";

        echo $sql;
        //excecute sql query
        mysqli_query($connection, $sql);
        
        // add logfile record
        $action = "add";
        $object_type = "Relation";
        LogfileHandler::addLogfileRecord($action, $object_type, $name, "new Relation");
        
      }catch(mysqli_sql_exception $e){
        $error_message = "invalid query: " . $e;
        echo $error_message;
      }


      //return back to article overview
      header("location: GUI_relations.php?action=add&status=succes&relation=$name");
      exit;
    }
  }


  public static function editRelation(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      //store form data under variables which have been sanitized
      $id = $_POST["id"];
      $name = htmlspecialchars($_POST["name"]);
      $street = htmlspecialchars($_POST["street"]);
      $house_nr = htmlspecialchars($_POST["house_nr"]);
      $zip_code = htmlspecialchars($_POST["zip_code"]);
      $city = htmlspecialchars($_POST["city"]);
      $country_code = htmlspecialchars(strtoupper($_POST["country_code"]));
      $email_adress =  filter_var($_POST["email_adress"], FILTER_SANITIZE_EMAIL);
      $phone_number = htmlspecialchars($_POST["phone_number"]);

      //check if all fields are filled
      if ( empty($name) || empty($street) || empty($zip_code) || empty($city) || empty($country_code) || empty($email_adress) || empty($phone_number)) {
        $error_message = "All fields are required";
        echo $error_message;
      }

        //update the record or display error message
      try {  
        // prepare sql query for updating the record
        $sql = "UPDATE `relations` SET `name`='$name',`street`='$street',`house_nr`='$house_nr',`zip_code`='$zip_code',`city`='$city',`country_code`='$country_code',`email_adress`='$email_adress',`phone_number`='$phone_number' WHERE `id` = $id";


        //excecute sql query
        mysqli_query($connection, $sql);
  
        // add logfile record
        $action = "edit";
        $object_type = "Relation";
        LogfileHandler::addLogfileRecord($action, $object_type, $name, "edit Relation");
  
      }catch(mysqli_sql_exception $e){ // Display error message when not able to perform sql query
        $error_message = "Invalid query: " . $e;
        echo $error_message;
      }

      // return back to article overview after posting the record
      header("location: GUI_relations.php?action=edit&status=succes&relation=$name");
      exit;
    
      } while(false);

    }
  

  public static function deleteRelation(){
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if(isset($_GET["id"])){

    // retrieve the ID from selected DB record from article overview
    $id = $_GET["id"];
  
    // declare variables for logging purposes before the article is deleted
    $stmt = "SELECT name FROM relations WHERE id=$id";
    $result = $connection->query($stmt);
    $row = $result->fetch_assoc();
    $name = $row["name"];
  

    //prepare query to delete record
    $sql = "DELETE FROM relations WHERE id=$id";

    // excecute sql query
    $connection->query($sql);

    // add logfile record
    $action = "delete";
    $object_type = "relation";
    LogfileHandler::addLogfileRecord($action, $object_type, $name, "delete relation");

    // return back to article overview
    header("location: GUI_relations.php?action=delete&status=succes&relation=$name");
    exit;
    
      
    }
  }
}





?>
</html>