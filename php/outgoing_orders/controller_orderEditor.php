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
$order_type = "0";
$employee_id = "";
$relation_id = "";
$company_id = "1";
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
      header("location: GUI_outgoing.php");
      exit;
    }


    //Store the found data of the query to variables
    $order_id = $row["id"];
    $order_date = $row["order_date"];
    $shipping_date = $row["shipping_date"];
    $employee_id = $row["employee_id"];
    $relation_id = $row["relation_id"];
    $company_id = $row["company_id"];
    $is_finalized = $row["is_finalized"];

} else {
  //if the method is post, update the data for the record

  $id = $_GET["id"];
  $order_id = $_GET["id"];
  $order_date = $_POST['order_date'];
  $shipping_date = $_POST['shipping_date'];
  $employee_id = $_POST['employee_id'];
  $relation_id = $_POST['relation_id'];
  $is_finalized = $_POST['is_finalized'];

  // check if all fields are filled
  do {
  

    if ( !isset($order_date) || !isset($shipping_date) || !isset($employee_id) || !isset($relation_id) || !isset($is_finalized) ) {
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
     
      // declare variable for logfile 
      $action = "edit";
      $object_type = "order";
      LogfileHandler::addLogfileRecord($action, $object_type, $order_id, $order_date, $shipping_date, $order_type, $employee_id, $relation_id, $company_id, $is_finalized);

    }catch(mysqli_sql_exception $e){ // Display error message when not able to perform sql query
      $errorMessage = "Invalid query: " . $e;
      break;
    }

    // return back to article overview after posting the record
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
    <title>edit <?php echo $order_id ?> | GreenHome</title>
  </head>
  <body>
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="../../images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-cßenter mb-md-0 nav-pills">
          <li class="nav-item"><a href="../../dashboard.php" class="nav-link" aria-current="page">Dashboard</a></li>
          <li class="nav-item"><a href="../articles/GUI_articles.php" class="nav-link">Articles</a></li>
          <li class="nav-item"><a href="../stock/GUI_stock.php" class="nav-link">inventory</a></li>
          <li class="nav-item"><a href="../relations/GUI_relations.php" class="nav-link" aria-current="page" >Relations</a></li>
          <li class="nav-item"><a href="../incoming_orders/GUI_incoming.php" class="nav-link">Incoming orders</a></li>
          <li class="nav-item"><a href="../outgoing_orders/GUI_outgoing.php" class="nav-link active">Outgoing orders</a></li>
          <li class="nav-item"><a href="../users/GUI_users.php" class="nav-link">Users</a></li>
          <li class="nav-item"><a  href="../companies/GUI_companies.php" class="nav-link">Companies</a></li>
          <li class="nav-item "><a  href="../accessibilities/GUI_accessibilities.php" class="nav-link">Accesibility</a></li>
          <li class="nav-item "><a  href="../functions/GUI_functions.php" class="nav-link">Functions</a></li>
        </ul>
        <?php
          //either display the users first name when logged in or give the option to log themselves in
          if(isset($_SESSION['user_id'])):?>
            <div class='dropdown text-end'>
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?=$_SESSION['user_name']?>
          </button>
            <ul class='dropdown-menu text-small'>
              <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#logOutModal'>Sign out</a></li>
              <div class="form-check form-switch ms-3">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" onclick="toggleTheme()">
                <label class="form-check-label" for="flexSwitchCheckDefault">Color theme</label>
              </div>
            </ul>
          </div>
          <?php else :?>
            <div class='nav-item ml-auto'>
            <a href='php/users/GUI_login.php' class='btn btn-outline-primary'>Login</a>
            </div>
          <?php endif;?>
      </div>
    </div>
  </header>
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
  </body>
<?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</html>

