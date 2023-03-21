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
$order_id = "";
$order_date = date('Y-m-d');
$shipping_date = date('Y-m-d');
$order_type = "0";
$employee_id = "";
$relation_id = "";
$company_id = "1";
$is_finalized = "0";

$errorMessage = "";



// check if correct form method is used
if($_SERVER['REQUEST_METHOD'] == 'POST') {

  //store form data under variables which share the same name as db columns
  $shipping_date = $_POST["shipping_date"];
  $employee_id = $_POST["employee_id"];
  $relation_id = $_POST["relation_id"];
  $is_finalized = $_POST["is_finalized"];

  //check if all fields are filled
  do {
   // var_dump($order_id, $shipping_date, $order_type, $employee_id, $relation_id, $company_id, $is_finalized);
   if ( !isset($shipping_date) || !isset($employee_id) || !isset($relation_id) || !isset($is_finalized) ) {
        $errorMessage = "All fields are required";
       // break;
   }


    try { //try to excecute sql query, or display error message
      //prepare sql query to insert data in the table
      $sql = "INSERT INTO orders (id, order_date, shipping_date, order_type, employee_id,relation_id,company_id,is_finalized)" . "VALUES ('$order_id', '$order_date' , '$shipping_date' , '$order_type' , '$employee_id' , '$relation_id' , '$company_id' , '$is_finalized')";

      //excecute sql query
      $result = mysqli_query($connection, $sql);

      // declare variable for logfile 
      $action = "add";
      $object_type = "order";
      LogfileHandler::addLogfileRecord($action, $object_type, $order_id, $order_date,$shipping_date, $order_type, $employee_id, $relation_id,$company_id,$is_finalized);
      
    }catch(mysqli_sql_exception $e){
      $errorMessage = "invalid query: " . $e;
      break;
    }

    $order_id = "";
    $order_date = date('Y-m-d');
    $shipping_date = date('Y-m-d');
    $order_type = "0";
    $employee_id = "";
    $relation_id = "";
    $company_id = "1";
    $is_finalized = "0";

    // return back to article overview
    header("location: GUI_outgoing.php");
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
    <title>New Order | GreenHome</title>
  </head>
  <body>
    <div class="container my-5">
      <h2>New Order</h2>

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
          <label class="col-form-label col-sm-3">Order Date</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="order_date" value="<?php echo $order_date;  ?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Shipping Date</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="shipping_date" value="<?php echo $shipping_date;  ?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Employee</label>
          <div class="col-sm-6">
            <select class="form-select" name="employee_id" required>
            <option value="">-- Select employee --</option>
              <?php
                $sql = "SELECT id, first_name FROM employees ORDER BY first_name";
                $result = mysqli_query($connection, $sql);
                  if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                     echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['first_name']) . '</option>';
                    }
                  }
              ?>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Relation</label>
          <div class="col-sm-6">
            <select class="form-select" name="relation_id" required>
            <option value="">-- Select customer --</option>
              <?php
                $sql = "SELECT id, name FROM relations ORDER BY name";
                $result = mysqli_query($connection, $sql);
                  if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                     echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                  }
              ?>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Finalized</label>
          <div class="col-sm-6">
            <select class="form-select" name="is_finalized" required>
            <option value="">-- Select --</option>
            <option value="0">Not Finalized</option>
            <option value="1">Finalized</option>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="offset-sm-3 col-sm-3 d-grid">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
          <div class="col-sm-3 d-grid">
            <a href="GUI_outgoing.php" class="btn btn-outline-danger" role="button">Cancel</a>
          </div>
        </div>
      </form>
    </div>
    <hr class="row mb-3"/>

    <div class="container my-5">
     <h2>Orderline</h2
     class="offset-sm-1 col-sm-2">
     <a href"" class="btn btn-primary">Add Orderline</a>
    </div>
  </body>
  <?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</html>