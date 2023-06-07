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
$order_line = "";
$article_id = "";
$quantity = "";

// declare variables for form handling when failing
$errorMessage = "";



if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // if the method is GET, show the data found in the db record

    if(!isset($_GET["order_id"])){
    header("location: dashboard.php");
    exit;
    }

    $order_id = $_GET["order_id"];
    $order_line = $_GET["order_line"];

    // read the row of selected record by searching for the ID
    $sql = "SELECT order_id, order_line, article_id, quantity, articles.name AS article_name, articles.description AS article_description FROM order_lines 
    JOIN articles
      ON articles.id = order_lines.article_id
    WHERE order_id=$order_id AND order_line=$order_line";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    //exit and return to main page if no ID can be found
    if(!$row) {
      header("location: dashboard.php");
      exit;
    }


    //Store the found data of the query to variables
    $order_id = $row["order_id"];
    $order_line = $row["order_line"];
    $article_id = $row["article_id"];
    $quantity = $row["quantity"];
    $article_name = $row["article_name"];
    $article_description = $row["article_description"];

} 
?>


<html lang="en" class="h-100" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/formValidator.js"></script>
    <script src="../../js/darkMode.js"></script>
    <script src="../../js/relationSearch.js"></script>
    <script src="../../js/descriptionSearch.js"></script>
    <link rel="shortcut icon" href="../../images/logo.png">
    <title>edit order <?php echo $order_id ?> | GreenHome</title>
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
            <a class="nav-link active dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Orders</a>
            <ul class="dropdown-menu">
              <li><a href="../orders/GUI_incoming.php" class="dropdown-item">Incoming orders</a></li>
              <li><a href="../orders/GUI_outgoing.php" class="dropdown-item">Outgoing orders</a></li>
            </ul>
          </li>
          <li class="nav-item"><a href="../users/GUI_users.php" class="nav-link" aria-current="page">Users</a></li>
          <li class="nav-item"><a href="../companies/GUI_companies.php" class="nav-link">Companies</a></li>
          <li class="nav-item"><a href="../accessibilities/GUI_accessibilities.php" class="nav-link">Accessibility</a></li>
          <li class="nav-item"><a href="../functions/GUI_functions.php" class="nav-link">Functions</a></li>
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
  <body class="d-flex flex-column h-100">
    <div class="container my-5">
      <h2 class="mb-5">Edit Order line</h2>

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

      <form method="POST" action="controller_order_line.php?action=edit">
        <input type="hidden" value="<?=$order_id?>" name="order_id">
        <input type="hidden" value="<?=$order_line?>" name="order_line">
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Order</label>
          <div class="col-sm-3">
            <fieldset disabled>
              <input type="text" class="form-control" value="<?=$order_id?>">
            </fieldset>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Order line</label>
          <div class="col-sm-3">
            <fieldset disabled>
              <input type="text" class="form-control" value="<?=$order_line?>">
            </fieldset>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Article</label>
          <div class="col-sm-3">
            <select class="form-select" name="article_id" id="article_id" required>
              <option value="<?=$article_id?>"><?=$article_name?></option>
              <?php
              $sql = "SELECT id, name FROM articles ORDER BY name";
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
          <label class="col-form-label col-sm-3">Description</label>
          <div class="col-sm-3">
            <fieldset disabled>
            <input type="text" class="form-control" id="article_description" value="<?=$article_description?>"></input>
            </fieldset>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-form-label col-sm-3">Quantity</label>
          <div class="col-sm-3">
            <input type="number" step="1" class="form-control" name="quantity" value="<?=$quantity?>" required>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-sm-3">
            <button type="submit" class="btn btn-primary w-100">Submit</button>
          </div>
          <div class="col-sm-3">
            <a href="GUI_orderEdit.php?id=<?=$order_id?>" class="btn btn-danger  w-100">Close</a>
          </div>
        </div>
      </form>
    </div>
  </body>
  <?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</html>