<?php

//declare false variable at start of script. if the script reaches the end it will display an error message in the HTMl because  if the correct credentials were applied, the user will already have been redirected to another page.
$is_invalid = false;



if($_SERVER['REQUEST_METHOD'] === 'POST'){
  
  // connect to database and check if the required tables exist and make one if not. if the database wouldn't exist, the user cannot login. the file closes the connection to the database
  require_once '../include/database.php';

  //reconnect to the established database
  require_once '../include/db_connect.php';
  
  
  //prepare sql statement to find the database record which matches the email string
  $sql = sprintf("SELECT * FROM employees
          WHERE email_adress = '%s'",
          $connection->real_escape_string(strtolower($_POST["email"])));
  
  //execute sql query
  $result = mysqli_query($connection, $sql);

  //store database record in variable
  $user = $result->fetch_assoc();

  //check if there was a record found in the database
  if($user) {

    //check if the password entered by the user matches the password in the databse. this uses a build in php method to decrypt the hash password stored in the database
    if (password_verify($_POST['password'], $user['password'])) {
    
      //if the password matches, it sill start a session
      session_start();

      //store session variables to be used later
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["user_name"] = $user["first_name"];
      $_SESSION["full_name"] = $user["first_name"] . " " . $user["last_name"];
      

      //redirect user to the dashboard
      header("Location: ../../dashboard.php");
    }
  }

  // if the php script reaches this point, it will display an error message to the user stating that their credentials are invalid
  $is_invalid = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap code-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../../css/login.css">
  <link rel="shortcut icon" href="../../images/logo.png">
  <title>Login | GreenHome</title>
</head>
<body class="text-center">
  <main class="form-signin w-100 m-auto">
    <form method="post">
      <img class="mb-4" src="../../images/logo.png" alt="" width="72" height="72">
      <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
      
      <?php
        // if the user is redirected while trying to acces a page while not logged in, the page will have the url ending in ?failed=true. it will display a specific error message to the user
        if(isset($_GET['failed']) && $_GET['failed']) {
          echo "
            <div class='alert alert-warning alert-dismissible' role='alert'>
            Please login before continuing
            <button type='button' class='btn-close btn-sm' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
          ";
        }

        //if the user credentials are invalid, it will display the error message here
        if($is_invalid){
          echo "
          <div class='alert alert-danger alert-dismissible' role='alert'>
            Incorrect login
            <button type='button' class='btn-close btn-sm' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
          ";
        }
      ?>


       <div class="form-floating">
        <input type="email" name="email" class="form-control" placeholder="email" value="<?php 
        
        //keep the user email in the form after a failed login attempt. does not work yet
        htmlspecialchars($_POST["email"] ?? ""); ?>" required>
        <label for="email">E-mail</label>
      </div>
      <div class="form-floating">
        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
        <label for="floatingPassword">Password</label>
      </div>
      <button class="w-100 btn btn-lg btn-primary" type="submit">Log in</button>
    </form>
  </main>
</body>
</html>

