<?php
//if session was started, continue it so it can display the user name and enable logging out
session_start();

//check if user has logged in, in order to gain acces to the page
require_once '../include/loginCheck.php';

// create connection with database
require_once '../include/db_connect.php';

// Check if the error message should be displayed
$error = false;
if (isset($_GET['status']) && $_GET['status'] == 'error') {
    $error = true;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap code-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="../../js/darkMode.js"></script>
  <script src="../../js/tableSearch.js"></script>
  <link rel="shortcut icon" href="../../images/logo.png">
  <title>Functions | GreenHome</title>
</head>
<body>
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="../../images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 nav-pills">
          <li class="nav-item"><a href="../../dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="../articles/GUI_articles.php" class="nav-link" >Articles</a></li>
          <li class="nav-item"><a href="../stock/GUI_stock.php" class="nav-link" aria-current="page" >inventory</a></li>
          <li class="nav-item"><a href="../relations/GUI_relations.php" class="nav-link" aria-current="page" >Relations</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Orders</a>
            <ul class="dropdown-menu">
              <li><a href="../orders/GUI_incoming.php" class="dropdown-item">Incoming orders</a></li>
              <li><a href="../orders/GUI_outgoing.php" class="dropdown-item">Outgoing orders</a></li>
            </ul>
          </li>
          <li class="nav-item"><a href="../users/GUI_users.php" class="nav-link">Users</a></li>
          <li class="nav-item "><a class="nav-link" href="../companies/GUI_companies.php">Companies</a></li>
          <li class="nav-item "><a class="nav-link " href="../accessibilities/GUI_accessibilities.php"">Accessibility</a></li>
          <li class="nav-item "><a class="nav-link active" href="../functions/GUI_functions.php">Functions</a></li>
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
          <a href="../users/GUI_logged_out.php"><button type="button" class="btn btn-warning">Log out</button></a>
        </div>
      </div>
    </div>
  </div>
  <!-- end logout modal-->

<div class="container">
    <h1>Edit Function</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            Error: The function is currently in use and cannot be edited.
        </div>
        <a href="GUI_functions.php" class="btn btn-primary">Return</a>
    <?php else: ?>
        <form method="POST" action="controller_functions.php?action=edit&name=<?php echo $_GET['name']; ?>">
            <div class="row mb-3">
                <label class="col-form-label col-sm-2">Current Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="<?php echo $_GET['name']; ?>"
                           readonly>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-form-label col-sm-2">New Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="new_name" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="GUI_functions.php" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>


  <?php 
  // use php to include the footer
  require_once '../include/footer.php'?>
</html>