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
$order_id = "";
$order_date = "";
$shipping_date = "";
$order_type = "1";
$employee_id = "";
$relation_id = "";
$company_id = "";
$is_finalized = "";


// declare variables for form handling when failing
$errorMessage = "";



if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // if the method is GET, show the data found in the db record

    if(empty($_GET["id"])){
    header("location: GUI_incoming.php");
    exit;
    }

    $id = $_GET["id"];

    // read the row of selected record by searching for the ID
    $sql = "SELECT * FROM orders WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    //exit and return to main page if no ID can be found
    if(!$row) {
      header("location: GUI_incoming.php");
      exit;
    }


    //Store the found data of the query to variables
    $order_id = $row["id"];
    $order_date = $row["order_date"];
    $shipping_date = $row["shipping_date"];
    $order_type = $row["order_type"];
    $employee_id = $row["employee_id"];
    $relation_id = $row["relation_id"];
    $company_id = $row["company_id"];
    $is_finalized = $row["is_finalized"];

} else {
  //if the method is post, update the data for the record

  $id = $_GET["id"];
  $order_id = $_POST['order_id'];
  $order_date = $_POST['order_date'];
  $shipping_date = $_POST['shipping_date'];
  $order_type = $_POST['order_type'];
  $employee_id = $_POST['employee_id'];
  $relation_id = $_POST['relation_id'];
  $company_id = $_POST['company_id'];
  $is_finalized = $_POST['is_finalized'];

  // check if all fields are filled
  do {
  
    echo $order_id . $order_date . $shipping_date . $order_type . $employee_id . $relation_id . $company_id . $is_finalized;
    if ( isset($order_id) || isset($order_date) || isset($shipping_date) || isset($order_type) || isset($employee_id) || isset($relation_id) || isset($company_id) || isset($is_finalized) ) {
        $errorMessage = "All fields are required";
        break;
    }


    //update the record or display error message
    try {  
      // prepare sql query for updating the record
      $sql =  "UPDATE orders " . 
            "SET id='$order_id',order_date='$order_date',shipping_date='$shipping_date',order_type='$order_type',employee_id='$employee_id',relation_id='$relation_id',company_id='$company_id',is_finalized='$is_finalized' " . 
            " WHERE id = $id;";

      //excecute sql query
      $result = mysqli_query($connection, $sql);
      echo $sql;
     
      // declare variable for logfile 
      $action = "edit";
      $object_type = "order";
      LogfileHandler::addLogfileRecord($action, $object_type, $order_id, $order_date, $shipping_date, $order_type, $employee_id, $relation_id, $company_id, $is_finalized);

    }catch(mysqli_sql_exception $e){ // Display error message when not able to perform sql query
      $errorMessage = "Invalid query: " . $e;
      break;
    }

    // return back to article overview after posting the record
    header("location: GUI_incoming.php");
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
    <title>edit <?php echo $order_id ?> | GreenHome</title>
  </head>
  <body>
    <div class="container my-5">
      <h2>Edit Order</h2>

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
        <input type="hidden" value="<?php echo $order_id; ?>" name="order_id">
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Order ID</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="order_id" value="<?php echo $order_id; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Order Date</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="order_date" value="<?php echo $order_date; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Shipping Date</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="shipping_date" value="<?php echo $shipping_date; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Order Type</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="order_type" value="<?php echo $order_type; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Employee ID</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="employee_id" value="<?php echo $employee_id; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Relation ID</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="relation_id" value="<?php echo $relation_id; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Company ID</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="company_id" value="<?php echo $company_id; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Is Finalized</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="is_finalized" value="<?php echo $is_finalized; //show the current value of the db record?>">
          </div>
        </div>
        <div class="row mb-3">
          <div class="offset-sm-3 col-sm-3 d-grid">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
          <div class="col-sm-3 d-grid">
            <a href="GUI_incoming.php" class="btn btn-outline-danger" role="button">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>