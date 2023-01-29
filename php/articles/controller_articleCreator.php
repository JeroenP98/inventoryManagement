<?php

//if session was started, continue it so it
session_start();

//check if user has logged in, in order to gain acces to the page. this disallows user to reach the page by entering the correct url
require_once '../include/loginCheck.php';

// create database connection
require_once '../include/db_connect.php';

// add the php file for the action logging
require_once '../logging/controller_logfile.php';

// declare empty variables for form handling
$article_id = "";
$description = "";
$errorMessage = "";



// check if correct form method is used
if($_SERVER['REQUEST_METHOD'] == 'POST') {

  //store form data under variables which share the same name as db columns
  $article_id = $_POST["article_id"];
  $description = $_POST["description"];

  //check if all fields are filled
  do {
    if ( empty($article_id) || empty($description)) {
        $errorMessage = "All fields are required";
        break;
    }


    try { //try to excecute sql query, or display error message
      //prepare sql query to insert data in the table
      $sql = "INSERT INTO articlesgh (article_id, description)" . "VALUES ('$article_id', '$description')";

      //excecute sql query
      $result = mysqli_query($connection, $sql);

      // declare variable for logfile 
      $action = "add";
      $object_type = "article";
      LogfileHandler::addLogfileRecord($action, $object_type, $article_id, $description);
      
    }catch(mysqli_sql_exception $e){
      $errorMessage = "invalid query: " . $e;
      break;
    }

    $article_id = "";
    $description = "";

    // return back to article overview
    header("location: GUI_articles.php");
    exit;

  } while(false);
} 



?>


<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap code-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../images/logo.png">
    <script src="../../js/formValidator.js"></script>
    <title>New Article | GreenHome</title>
  </head>
  <body>
    <div class="container my-5">
      <h2>New Article</h2>

      <?php
        // display error message when failing to upload data
        if(!empty($errorMessage)){
          echo "
          
          <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong>$errorMessage</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>
          ";
        }
      ?>

      <form method="POST">
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Article ID</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="article_id" value="<?php echo $article_id; //used only in the article editor ?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Description</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="description" value="<?php echo $article_id; //used only in the article editor ?>">
          </div>
        </div>
        <div class="row mb-3">
          <div class="offset-sm-3 col-sm-3 d-grid">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
          <div class="col-sm-3 d-grid">
            <a href="GUI_articles.php" class="btn btn-outline-danger" role="button">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>