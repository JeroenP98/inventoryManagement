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
$article_description = "";

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
    $sql = "SELECT orders.order_date, orders.shipping_date, orders.order_type, orders.employee_id, orders.relation_id, orders.company_id, orders.is_finalized, relations.name AS relation_name, CONCAT(relations.street, ' ', relations.house_nr, ', ', relations.zip_code, ', ', relations.city, ', ', relations.country_code) AS relation_adress, companies.name AS company_name, CONCAT(employees.first_name,' ',employees.last_name) AS `employee_name`
    FROM `orders` 
    JOIN relations
      ON relations.id = orders.relation_id
    JOIN companies
      ON companies.id = orders.company_id
    JOIN employees
      ON employees.id = orders.employee_id
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

    //Store the found data of the query to variables. Variables are used to display current order data
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
    $employee_name = $row["employee_name"];
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
    <script src="../../js/descriptionSearch.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <title>edit order <?php echo $id ?> | GreenHome</title>
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

  <!-- start new order line Modal -->
  <div class="modal fade" id="newOrderLineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Create a new order line</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="controller_order_line.php?action=add">
            <input type="hidden" value="<?=$id?>" name="order_id">
            <div class="row mb-3">
              <label class="col-form-label col-sm-3">Article</label>
              <div class="col-sm-6">
                  <select class="form-select" name="article_id" id="article_id" required>
                    <option value="">-- Select Article --</option>
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
              <div class="col-sm-9">
                <fieldset disabled>
                <input type="text" class="form-control" id="article_description" disabled></input>
                </fieldset>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-form-label col-sm-3">Quantity</label>
              <div class="col-sm-3">
                <input type="number" step="1" class="form-control" name="quantity" required>
              </div>
            </div>
            <div class="modal-footer">
              <div class="offset-sm-3 col-sm-3 d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
              <div class="col-sm-3 d-grid">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end new order line modal-->

  <body class="d-flex flex-column h-100">

    <div class="container my-5">
      <!-- status messages -->
      <?php if(isset($_GET['action']) && $_GET["status"] == "succes" && $_GET["action"] == "add"):?>
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
          <p><strong>Succes!</strong> You added order <?=$_GET['id']?></p>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php elseif (isset($_GET['action']) && $_GET["status"] == "succes" && $_GET["action"] == "edit"): ?>
          <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <p><strong>Succes!</strong> You edited order <?=$_GET['id']?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php endif ?>
      <!-- end status messages -->
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
      <!-- Order data -->
      <form method="POST" action="controller_orders.php?action=edit&order_type=incoming" class="row g-3">
        <input type="hidden" value="<?=$id?>" name="id">
        <!--order meta data-->
        <div class="row mb-4">
          <h3>Edit order</h3>
        </div>
        <div class="row mb-2">
          <div class="col-md-3">
            <h4>Reference:</h4>
          </div>
          <div class="col-md-3">
            <fieldset disabled>
              <input type="text" class="form-control"  value="<?=$id?>"></input>
            </fieldset>
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-md-3">
            <h4>Order type:</h4>
          </div>
          <div class="col-md-3">
            <fieldset disabled>
                  <input type="text" class="form-control"  value="<?php
                  if($order_type == 1){
                    echo "Outgoing";
                  } elseif($order_type == 0){
                    echo "Incoming";
                  } else {
                    echo "invalid ordertype";
                  }?>"
                  ></input>
            </fieldset>
          </div>
        </div>
        <!--customer data-->
        <div class="row mt-5">
          <label class="col-form-label col-md-3">Customer</label>
          <div class="col-md-3">
            <select class="form-select" name="relation_id" id="relation_id" required>
              <option value="<?=$relation_id?>"><?=$relation_name?></option>
              <?php
                $sql = "SELECT id, name FROM relations ORDER BY name";
                $result = mysqli_query($connection, $sql);
                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option      value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                  }
                }
              ?>
            </select>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-6">
            <fieldset disabled>
              <input type="text" class="form-control" id="relation_address" value="<?=$relation_adress?>"></input>
            </fieldset>
          </div>
        </div>
        <!--order date data-->
        <div class="row mt-4">
          <div class="col-md-3">
            <label class="form-label">Order date</label>
            <input type="date" class="form-control" name="order_date" required value="<?=$order_date?>">
          </div>
          <div class="col-md-3">
          <label class="form-label">Shipping date</label>
            <input type="date" class="form-control" name="shipping_date" required value="<?=$shipping_date?>">
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-3">
            <label class="col-form-label col-sm-3">Employee</label>
          </div>
            <div class="col-md-3">
              <select class="form-select" name="employee_id" required>
                <option value="<?=$employee_id?>"><?=$employee_name?></option>
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
        <!--company data-->
        <div class="row mt-4">
          <div class="col-md-3">
            <label class="col-form-label col-sm-3">Company</label>
          </div>
          <div class="col-md-3">
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
        <!--order finalized data-->
        <div class="row mt-4">
          <div class="col-md-3">
            <div class="form-check">
              <input type="hidden" name="is_finalized" value="0">
              <input type="checkbox" name="is_finalized" value="1" class="form-check-input" <?php if($is_finalized == 1) echo "checked"; ?>>
              <label class="form-check-label">Is the order finalized?</label>
            </div>
          </div>
        </div>
        <div class="row-mt-5">
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100 mb-3">Save changes</button>
          </div>
          <div class="col-md-3">
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
      <!-- End Order data -->
    </div>

    <!-- Order lines data -->
    <div class="container">
    <table class="table table-striped table-sm" id="table">
      <h2 class="mb-3"><u>Order lines</u></h2>
      <div class="row">
        <div class="col-md-3">          
          <button class='btn btn-primary mb-3 w-100' data-bs-toggle='modal' data-bs-target='#newOrderLineModal'>Add new line</button>
        </div>
      </div>
      <thead>
        <tr>
          <th>Order line ID</th>
          <th>Article ID</th>
          <th>Article name</th>
          <th>Description</th>
          <th>Quantity</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>

      <tbody id="orderlines">
        <?php
        // prepare SQL statement
        $sql = "SELECT order_lines.order_id AS 'order_id', order_lines.order_line AS 'order_line', articles.id AS 'article_id', articles.name, order_lines.quantity, CONCAT(LEFT(articles.description, 25),'...') AS 'description'
        FROM order_lines
        JOIN articles
          ON order_lines.article_id = articles.id
        JOIN orders
          ON order_lines.order_id = orders.id
        WHERE orders.id = $id;";

        // Run the query
        $result = $connection->query($sql);

        // make a new table row for every row in database
        while($row = $result->fetch_assoc()) {
          echo "<tr>
          <td>$row[order_line]</td>
          <td>$row[article_id]</td>
          <td>$row[name]</td>
          <td>$row[description]</td>
          <td>$row[quantity]</td>
          <td>
            <a class='btn btn-primary' href='GUI_order_lineEdit.php?order_line=$row[order_line]&order_id=$row[order_id]&action=edit'>Edit</a>
            </td>
          <td>
            <a class='btn btn-danger' href='controller_order_line.php?order_line=$row[order_line]&order_id=$row[order_id]&action=delete'>Delete</a>
          </td
          </tr>";
        }
        ?>
      </tbody>
    </table>
    </div>
    <!-- End Order lines data -->
  </body>
  <?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</html>