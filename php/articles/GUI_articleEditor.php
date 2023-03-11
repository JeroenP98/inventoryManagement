<?php

//if session was started, continue it so it
session_start();

//check if user has logged in, in order to gain acces to the page. this disallows user to reach the page by entering the correct url
require_once '../include/loginCheck.php';

// connect to database
require_once '../include/db_connect.php';

// add the php file for the action logging
require_once '../logging/controller_logfile.php';

// declare empty variables for form handling
$name = "";
$description = "";
$purchase_price = "";
$selling_price = "";
$is_active = "";

// declare variables for form handling when failing
$errorMessage = "";



if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // if the method is GET, show the data found in the db record

    if(!isset($_GET["id"])){
    header("location: GUI_articles.php");
    exit;
    }

    $id = $_GET["id"];

    // read the row of selected record by searching for the ID
    $sql = "SELECT * FROM articles WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    //exit and return to main page if no ID can be found
    if(!$row) {
      header("location: GUI_articles.php");
      exit;
    }


    //Store the found data of the query to variables
    $name = $row["name"];
    $description = $row["description"];
    $purchase_price = $row["purchase_price"];
    $selling_price = $row["selling_price"];
    $is_active = $row["is_active"];

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
    <title>edit <?php echo $name ?> | GreenHome</title>
  </head>
  <body>
    <div class="container my-5">
      <h2 class="mb-5">Edit Article</h2>

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

      <form method="POST" action="controller_article.php?action=edit">
      <input type="hidden" value="<?php echo $id; ?>" name="id">
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Name</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="name" value="<?php echo $name; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Description</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="description" value="<?php echo $description; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Purchase price</label>
          <div class="col-sm-3">
            <input type="number" step=".01" class="form-control" name="purchase_price" value="<?php echo $purchase_price; //show the current value of the db record?>" required>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Selling price</label>
          <div class="col-sm-3">
            <input type="number" step=".01" class="form-control" name="selling_price" value="<?php echo $selling_price; //show the current value of the db record?>" required>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Active</label>
          <div class="col-sm-6">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" <?php if($is_active == 1) echo "checked"; ?>>
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