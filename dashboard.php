<?php
//if session was started, continue it so it can display the user name and enable logging out
session_start();

//create user name variable which uppercases the firstl letter in the string
if(!empty($_SESSION['user_name'])){
  $name = ucfirst($_SESSION['user_name']);
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap code-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/darkMode.js"></script>
  <link rel="shortcut icon" href="images/logo.png">
  <title>Dashboard | GreenHome</title>
</head>
<body class="d-flex flex-column h-100">
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../POC greenhome/dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 nav-pills">
          <li class="nav-item"><a href="dashboard.php" class="nav-link active" aria-current="page">Dashboard</a></li>
          <li class="nav-item"><a href="php/articles/GUI_articles.php" class="nav-link">Articles</a></li>
          <li class="nav-item"><a href="php/stock/GUI_stock.php" class="nav-link">inventory</a></li>
          <li class="nav-item"><a href="php/incoming_orders/GUI_incoming.php" class="nav-link">Incoming orders</a></li>
          <li class="nav-item"><a href="php/outgoing_orders/GUI_outgoing.php" class="nav-link">Outgoing orders</a></li>
          <li class="nav-item"><a href="php/users/GUI_users.php" class="nav-link">Users</a></li>
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
  <main class=" d-flex justify-content-center py-4">
    <!-- start logout Modal -->
    <div class="modal fade" id="logOutModal" tabindex="-1"     aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">You are about to log out!</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Keep me logged in</button>
            <a href="php/users/GUI_logged_out.php"><button type="button" class="btn btn-warning">Log out</button></a>
          </div>
        </div>
      </div>
    </div>
    <!-- end logout modal-->
    <!-- start action list jumbotron-->
    <div class="p-5 m-4 border rounded-3">
      <div class="container-fluid py-5">
        <?php
        //different html body will be shown depending on of the user has logged in or not
        // use the shorthand sytax for if...else by hopping in and out of php mode 
        if(isset($_SESSION["user_id"])):?>
          <h1 class='display-5 fw-bold'>Hello <?=$name?></h1>
          <p class='col-md-8 fs-4'>Welcome back, <br>what would you like to do?</p>
          <div class='list-group'>
            <a href='php/articles/GUI_articles.php' class='list-group-item list-group-item-action text-center'>Edit/add new articles</a>
            <a href='php/stock/GUI_stock.php' class='list-group-item list-group-item-action text-center'>View stock</a>
            <a href='php/incoming_orders/GUI_incoming.php' class='list-group-item list-group-item-action text-center'>Manage incoming orders</a>
            <a href='php/outgoing_orders/GUI_outgoing.php' class='list-group-item list-group-item-action text-center mb-5'>Manage outgoing orders</a>
            <button class='btn btn-outline-danger btn-lg' type='button' data-bs-toggle='modal' data-bs-target='#logOutModal'>Log out</button>
          </div>
        <?php else :?>       
          <div class='p-5 m-4 rounded-3'>
            <div class='container-fluid py-5'>
              <h1 class='display-5 fw-bold'>Hello stranger</h1>
              <p class='col-md fs-4'>You should login before continuing</p>
              <div class='list-group'>
                <a href='php/users/GUI_login.php' class='btn btn-primary btn-lg' type='button'>Log in</a>
              </div>
            </div>
          </div>
        <?php endif;?>
      </div>
    </div>
    <!-- end action list jumbotron-->
  </main>
  <?php 
  // use php to use footer
  require_once 'php\include\footer.php'?>
</body>
</html>


