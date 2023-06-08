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
$first_name = "";
$last_name = "";
$email_adress = "";
$password = "";
$company_id = "";
$function_name = "";


// declare variables for form handling when failing
$errorMessage = "";



if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // if the method is GET, show the data found in the db record

    if(!isset($_GET["id"])){
    header("location: GUI_users.php");
    exit;
    }

    $id = $_GET["id"];

    // read the row of selected record by searching for the ID
    $sql = 
    "SELECT employees.id AS 'employees.id', employees.first_name, employees.last_name, employees.email_adress, employees.function_name, companies.name, companies.id AS 'companies.id', is_active
    FROM employees
    JOIN companies 
      ON employees.company_id  = companies.id
    WHERE employees.id = $id;";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    //exit and return to main page if no ID can be found
    if(!$row) {
      header("location: GUI_users.php");
      exit;
    };

    //Store the found data of the query to variables
    $first_name = $row["first_name"];;
    $last_name = $row["last_name"];
    $email = $row["email_adress"];
    $company_id = $row["companies.id"];
    $function_name = $row["function_name"];
    $is_active = $row["is_active"];

};
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
    <title>Edit <?php echo "$first_name " . "$last_name"?> | GreenHome</title>
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
          <li class="nav-item"><a href="../relations/GUI_relations.php" class="nav-link" aria-current="page" >Relations</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Orders</a>
            <ul class="dropdown-menu">
              <li><a href="../orders/GUI_incoming.php" class="dropdown-item">Incoming orders</a></li>
              <li><a href="../orders/GUI_outgoing.php" class="dropdown-item">Outgoing orders</a></li>
            </ul>
          </li>
          <li class="nav-item"><a href="../users/GUI_users.php" class="nav-link active" aria-current="page">Users</a></li>
          <li class="nav-item "><a class="nav-link" href="../companies/GUI_companies.php">Companies</a></li>
          <li class="nav-item "><a class="nav-link" href="../accessibilities/GUI_accessibilities.php">Accessibility</a></li>
          <li class="nav-item "><a  href="../functions/GUI_functions.php" class="nav-link">Functions</a></li>
          <li class="nav-item "><a  href="../searchesNotFound/GUI_searchesNotFound.php" class="nav-link">Searches</a></li>
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
      <h2>Edit <?php echo "$first_name " . "$last_name" ?></h2>

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

      <form method="POST" action="controller_user.php?action=edit">
        <input type="hidden" value="<?php echo $id; ?>" name="id">
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">first name</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; //show the current value of the db record?>" required>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Last name</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="last_name" value="<?php echo $last_name; //show the current value of the db record?>" required>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">E-mail</label>
          <div class="col-sm-6">
            <input type="email" class="form-control" name="email_adress" value="<?php echo $email; //show the current value of the db record?>" required>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Company</label>
          <div class="col-sm-6">
            <select class="form-select" name="company_id" required>   
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
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Function</label>
          <div class="col-sm-6">
            <select class="form-select" name="function_name" required>
              <?php
                $sql = "SELECT name FROM functions ORDER BY name";
                $result = mysqli_query($connection, $sql);
                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['name'] . '">' . htmlspecialchars($row['name']) . '</option>';
                  }
                }
              ?>
            </select>
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
            <a href="GUI_users.php" class="btn btn-outline-danger" role="button">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </body>
  <?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</html>