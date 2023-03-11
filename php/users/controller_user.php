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
    UserController::addUser();
  } elseif($action === "edit"){
    UserController::editUser();
  } elseif($action === "delete"){
    UserController::deleteUser();
  }

} else {
  header("Location: ../users/GUI_users.php");
  exit;
}


class UserController {


  public static function addUser(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      //store form data under variables which have been sanitized
      $first_name = htmlspecialchars(ucfirst(strtolower($_POST["first_name"])));
      $last_name = htmlspecialchars(ucfirst(strtolower($_POST["last_name"])));
      $email = filter_var(strtolower($_POST["email_adress"]), FILTER_SANITIZE_EMAIL);
      $password = (password_hash($_POST["password"], PASSWORD_DEFAULT));
      $company_id = $_POST["company_id"];
      $function_name = htmlspecialchars($_POST["function_name"]);

      //check if all fields are filled
      do {
        if ( empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($company_id) || empty($function_name)) {
            $errorMessage = "All fields are required";
            break;
        }


        try { //try to excecute sql query, or display error message
          //prepare sql query to insert data in the table
          $sql = "INSERT INTO employees (first_name, last_name, email_adress, password, company_id, function_name)" . "VALUES ('$first_name', '$last_name', '$email', '$password', '$company_id', '$function_name')";

          //excecute sql query
          $result = mysqli_query($connection, $sql);
          
          // add logfile record
          $action = "add";
          $object_type = "user";
          LogfileHandler::addLogfileRecord($action, $object_type, $first_name, $email);
          
        }catch(mysqli_sql_exception $e){
          $errorMessage = "invalid query: " . $e;
          break;
        }



        // return back to article overview
        header("location: GUI_users.php?action=add&status=succes&user=$first_name");
        exit;

      } while(false);
    }
  }


  public static function editUser(){
    
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

  public static function deleteUser(){
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