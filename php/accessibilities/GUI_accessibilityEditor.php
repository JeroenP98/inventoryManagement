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
$street = "";
$house_nr = "";
$zip_code = "";
$city = "";
$country_code = "";
$email_adress =  "";
$phone_number = "";

// declare variables for form handling when failing
$errorMessage = "";



if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // if the method is GET, show the data found in the db record

    if(!isset($_GET["function_name"])){
    header("location: GUI_accessibilities.php");
    exit;
    }

    $function_name = $_GET["function_name"];

    // read the row of selected record by searching for the function name
    $sql = "SELECT * FROM accessibilities WHERE function_name = '$function_name'";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    //exit and return to main page if no ID can be found
    if(!$row) {
      header("location: GUI_accessibilities.php");
      exit;
    }


    //Store the found data of the query to variables
    $name = $row["name"];
    $street = $row["street"];
    $house_nr = $row["house_nr"];
    $zip_code = $row["zip_code"];
    $city = $row["city"];
    $country_code = $row["country_code"];
    $email_adress =  $row["email_adress"];
    $phone_number = $row["phone_number"];

} 
?>


<html lang="en">
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
    <title>edit <?php echo $name ?> | GreenHome</title>
  </head>
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="../../images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 nav-pills">
          <li class="nav-item"><a href="../../dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="../articles/GUI_articles.php" class="nav-link" >Articles</a></li>
          <li class="nav-item"><a href="../stock/GUI_stock.php" class="nav-link" >inventory</a></li>
          <li class="nav-item"><a href="../relations/GUI_relations.php" class="nav-link " aria-current="page" >Relations</a></li>
          <li class="nav-item"><a href="../incoming_orders/GUI_incoming.php" class="nav-link">Incoming orders</a></li>
          <li class="nav-item"><a href="../outgoing_orders/GUI_outgoing.php" class="nav-link" >Outgoing orders</a></li>
          <li class="nav-item"><a href="../users/GUI_users.php" class="nav-link" aria-current="page">Users</a></li>
          <li class="nav-item "><a class="nav-link active" href="../accessibilities/GUI_accessibilities.php"">Accessibility</a></li>
          <li class="nav-item "><a class="nav-link" href="../functions/GUI_functions.php"">Functions</a></li>
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
  <body>
    <div class="container my-5">
      <h2 class="mb-5">Edit Function</h2>

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

      <form method="POST" action="controller_accessibilities.php?action=edit">
        <input type="hidden" value="<?php echo $function_name; ?>" name="function_name">
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Function name</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="name" value="<?php echo $name; //show the current value of the db record?>" required>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Street</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="street" required value="<?php echo $street;?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">House Nr.</label>
          <div class="col-sm-3">
            <input type="text" maxlength="10" class="form-control" name="house_nr" value="<?php echo $house_nr;?>">
          </div>
          <label class="col-form-label col-sm-3">Zip code</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" name="zip_code" required value="<?php echo $zip_code;?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">City</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="city" required value="<?php echo $city;?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Country code</label>
          <div class="col-sm-3">
            <input type="text"  maxlength="2" class="form-control" name="country_code" required value="<?php echo $country_code;?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Email address</label>
          <div class="col-sm-9">
            <input type="email" class="form-control" name="email_adress" required value="<?php echo $email_adress;?>">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Tel. number</label>
          <div class="col-sm-9">
            <input type="tel" pattern="^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$" class="form-control" name="phone_number" required value="<?php echo $phone_number;?>">
          </div>
        </div>
        <div class="row mb-3">
          <div class="offset-sm-3 col-sm-3 d-grid">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
          <div class="col-sm-3 d-grid">
            <a href="GUI_accessibilities.php" class="btn btn-outline-danger" role="button">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </body>
  <?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</html>