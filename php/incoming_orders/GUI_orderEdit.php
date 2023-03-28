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
$order_date = "";
$shipping_date = "";
$order_type = "";
$employee_id = "";
$relation_id = "";
$company_id = "";

// declare variables for form handling when failing
$errorMessage = "";



if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // if the method is GET, show the data found in the db record

    if(!isset($_GET["id"])){
      if($_GET["order_type"] === "incoming"){
        header("Location: GUI_incoming.php");
      } elseif($_GET["order_type"] === "outgoing"){
        header("Location: GUI_outgoing.php");
      } else {
        header("Location: ../../dashboard.php");
      }
      exit;
    }

    $id = $_GET["id"];

    // read the row of selected record by searching for the ID
    $sql = "SELECT `order_date`, `shipping_date`, `order_type`, `employee_id`, `relation_id`, `company_id`, `is_finalized`, relations.name AS relation_name, CONCAT(relations.street, ' ', relations.house_nr, ', ', relations.zip_code, ', ', relations.city, ', ', relations.country_code) AS relation_adress, companies.name AS company_name
    FROM `orders` 
    JOIN relations
      ON relations.id = orders.relation_id
    JOIN companies
      ON companies.id = orders.company_id
    WHERE orders.id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    //exit and return to main page if no ID can be found
    if(!$row) {
      if($_GET["order_type"] === "incoming"){
        header("Location: GUI_incoming.php");
      } elseif($_GET["order_type"] === "outgoing"){
        header("Location: GUI_outgoing.php");
      } else {
        header("Location: ../../dashboard.php");
      }
      exit;
    }


    //Store the found data of the query to variables
    $order_date = $row["order_date"];
    $shipping_date = $row["shipping_date"];
    $order_type = $row["order_type"];
    $employee_id = $row["employee_id"];
    $relation_id = $row["relation_id"];
    $company_id = $row["company_id"];
    $company_name = $row["company_name"];
    $relation_name = $row["relation_name"];
    $relation_adress = $row["relation_adress"];
    $is_finalized = $row["is_finalized"];
  



} 
?>


<html lang="en" class="h-100" data-bs-theme="light">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap code-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../images/logo.png">
    <script src="../../js/formValidator.js"></script>
    <script src="../../js/darkMode.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js"
  integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
  crossorigin="anonymous"></script>
    <script src="../../js/relationSearch.js"></script>
    <title>edit order<?php echo $id ?> | GreenHome</title>
  </head>
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="../../images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-c�enter mb-md-0 nav-pills">
          <li class="nav-item"><a href="../../dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="../articles/GUI_articles.php" class="nav-link" >Articles</a></li>
          <li class="nav-item"><a href="../stock/GUI_stock.php" class="nav-link" >inventory</a></li>
          <li class="nav-item"><a href="../relations/GUI_relations.php" class="nav-link" aria-current="page" >Relations</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link active dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Orders</a>
            <ul class="dropdown-menu">
              <li><a href="../incoming_orders/GUI_incoming.php" class="dropdown-item">Incoming orders</a></li>
              <li><a href="../outgoing_orders/GUI_outgoing.php" class="dropdown-item">Outgoing orders</a></li>
            </ul>
          </li>
          <li class="nav-item"><a href="../users/GUI_users.php" class="nav-link" aria-current="page">Users</a></li>
          <li class="nav-item"><a href="../companies/GUI_companies.php" class="nav-link">Companies</a></li>
          <li class="nav-item"><a href="../accessibilities/GUI_accessibilities.php" class="nav-link">Accessibility</a></li>
          <li class="nav-item"><a href="../functions/GUI_functions.php" class="nav-link">Functions</a></li>
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
            <a href='../users/GUI_login.php' class='btn btn-outline-primary'>Login</a>
            </div>
          <?php endif;?>
      </div>
    </div>
  </header>
  <body class="d-flex flex-column h-100">
    <div class="container my-5">
      <h2 class="mb-5">Edit Order</h2>

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
      <form method="POST" action="controller_orders.php?action=add&order_type=incoming" class="row g-3">
        <input type="hidden" value="<?=$order_type?>" name="order_type">
          <div class="col-md-12">
            <label class="col-form-label col-sm-3">Customer</label>
            <div class="col-sm-6">
              <select class="form-select" name="relation_id" id="relation_id" required>
                <option value="<?=$relation_id?>"><?=$relation_name?></option>
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
          <fieldset disabled>
          <div class="col-md-12 d-flex">
            <input type="text" class="form-control" id="relation_address" value="<?=$relation_adress?>"></input>
          </div>
          </fieldset>
          <div class="col-md-6">
            <label class="col-form-label col-sm-3">Order date</label>
            <div class="col-sm-6">
              <input type="date" class="form-control" name="order_date" required value="<?=$order_date?>">
            </div>
          </div>
          <div class="col-md-6">
            <label class="col-form-label col-sm-3">Shipping date</label>
            <div class="col-md-6">
              <input type="date" class="form-control" name="shipping_date" required value="<?=$shipping_date?>">
            </div>
          </div>
          <div class="col-md-6">
            <label class="col-form-label col-sm-3">Employee</label>
            <div class="col-sm-6">
              <select class="form-select" name="employee_id" required>
                <option value="<?=$_SESSION["user_id"]?>"><?=$_SESSION["full_name"]?></option>
                <?php
                  $sql = "SELECT id, CONCAT(first_name,' ',last_name) AS `name` FROM employees ORDER BY name";
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
          <div class="col-md-6">
            <label class="col-form-label col-sm-3">Company</label>
            <div class="col-sm-6">
              <select class="form-select" name="company_id" required>
                <option value="<?=$company_id?>"><?=$company_name?></option>
                <?php
                  $sql = "SELECT id, name FROM companies ORDER BY name";
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
          <div class="col-md-6">
            <div class="col-sm-6 form-check">
              <input type="hidden" name="is_finalized" value="0">
              <input type="checkbox" name="is_finalized" value="1" class="form-check-input" <?php if($is_finalized == 1) echo "checked"; ?>>
              <label class="form-check-label">Is the order finalized?</label>
            </div>
          </div>
          <div class="container text-center row justify-content-around mt-5">
            <div class="col-3">
              <button type="submit" class="btn btn-primary w-100">Submit</button>
            </div>
            <div class="col-3">
              <a href="<?php
                if($order_type == 0){
                  echo "GUI_incoming.php";
                } elseif($order_type == 1){
                  echo "GUI_outgoing.php";
                }
                ?>" class="btn btn-danger w-100">Close
              </a>
            </div>
          </div>

        </form>
    </div>
  </body>
  <?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</html>