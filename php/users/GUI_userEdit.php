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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../images/logo.png">
    <script src="../../js/formValidator.js"></script>
    <title>Edit <?php echo "$first_name " . "$last_name"?> | GreenHome</title>
  </head>
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
</html>