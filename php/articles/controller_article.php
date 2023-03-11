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
    UserController::addArticle();
  } elseif($action === "edit"){
    UserController::editArticle();
  } elseif($action === "delete"){
    UserController::deleteArticle();
  }

} else {
  header("Location: ../users/GUI_users.php");
  exit;
};

class UserController {


  public static function addArticle(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      //store form data under variables which have been sanitized

      $name = htmlspecialchars($_POST["name"]);
      $description = htmlspecialchars($_POST["description"]);
      $purchase_price = htmlspecialchars(filter_var($_POST["purchase_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
      $selling_price = htmlspecialchars(filter_var($_POST["selling_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

      //check if all fields are filled
        if ( empty($name) || empty($description) || empty($purchase_price) || empty($selling_price)) {
            $errorMessage = "All fields are required";
            echo $errorMessage;
        }


        try { //try to excecute sql query, or display error message
          //prepare sql query to insert data in the table
          $sql = "INSERT INTO articles (`name`, `description`, `purchase_price`, `selling_price`) VALUES ('$name', '$description', '$purchase_price', '$selling_price')";

          //excecute sql query
          mysqli_query($connection, $sql);
          
          // add logfile record
          $action = "add";
          $object_type = "Article";
          LogfileHandler::addLogfileRecord($action, $object_type, $name, "new article");
          
        }catch(mysqli_sql_exception $e){
          $errorMessage = "invalid query: " . $e;
          echo $errorMessage;
        }

        //echo $sql;

        //return back to article overview
        header("location: GUI_articles.php?action=add&status=succes&article=$name");
        //exit;
    }
  }


  public static function editArticle(){
    
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      //store form data under variables which have been sanitized

      $name = htmlspecialchars($_POST["name"]);
      $description = htmlspecialchars($_POST["description"]);
      $purchase_price = htmlspecialchars(filter_var($_POST["purchase_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
      $selling_price = htmlspecialchars(filter_var($_POST["selling_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));


      // validate that the is_active status is between 0 and 1
      $options = array(
        "options" => array(
          "min_range"=>0, 
          "max_range"=>1
        )
      );

      $is_active = htmlspecialchars(filter_var($_POST["is_active"], FILTER_VALIDATE_INT, $options));

      //check if all fields are filled
      if ( empty($name) || empty($description) || empty($purchase_price) || empty($selling_price) || empty($is_active)) {
          $errorMessage = "All fields are required";
          echo $errorMessage;
      }


        //update the record or display error message
        try {  
          // prepare sql query for updating the record
          $sql =  "UPDATE `articles` SET `name`=$name,`description`=$description,`purchase_price`=$purchase_price,`selling_price`=$selling_price,`is_active`=$is_active";
          

          //excecute sql query
          mysqli_query($connection, $sql);
    
          // add logfile record
          $action = "edit";
          $object_type = "Article";
          LogfileHandler::addLogfileRecord($action, $object_type, $name, "edit article");
    
        }catch(mysqli_sql_exception $e){ // Display error message when not able to perform sql query
          $errorMessage = "Invalid query: " . $e;
          echo $errorMessage;
          break;
        }
    
        // return back to article overview after posting the record
        header("location: GUI_articles.php?action=edit&status=succes&article=$name");
        exit;
    
      } while(false);

    }
  

  public static function deleteArticle(){
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if(isset($_GET["id"])){

    // retrieve the ID from selected DB record from article overview
    $id = $_GET["id"];
  
    // declare variables for logging purposes before the article is deleted
    $stmt = "SELECT name FROM articles WHERE id=$id";
    $result = $connection->query($stmt);
    $row = $result->fetch_assoc();
    $name = $row["name"];
  

    //prepare query to delete record
    $sql = "DELETE FROM articles WHERE id=$id";

    // excecute sql query
    $connection->query($sql);

    // add logfile record
    $action = "delete";
    $object_type = "Article";
    LogfileHandler::addLogfileRecord($action, $object_type, $name, "delete article");

    // return back to article overview
    header("location: GUI_articles.php?action=delete&status=succes&article=$name");
    exit;
    
      
    }
  }
}





?>
