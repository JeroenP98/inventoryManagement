<?php
//if session was started, continue it so it can display the user name and enable logging out
session_start();

//check if user has logged in, in order to gain acces to the page
require_once '../include/loginCheck.php';

// create connection with database
require_once '../include/db_connect.php';
?>


<!DOCTYPE html>
<html lang="en" class="h-100" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap code-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="../../js/darkMode.js"></script>
  <link rel="shortcut icon" href="../../images/logo.png">
  <title>Incoming | GreenHome</title>
</head>
<body class="d-flex flex-column h-100">
<body class="d-flex flex-column h-100">
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
            <a href='php/users/GUI_login.php' class='btn btn-outline-primary'>Login</a>
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

    <!-- start new order Modal -->
    <div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create a new Order</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="controller_orders.php?action=add&order_type=incoming">
            <input type="hidden" value="0" name="order_type">
              <div class="row mb-3">
                <label class="col-form-label col-sm-3">Customer</label>
                <div class="col-sm-6">
                  <select class="form-select" name="relation_id" required>
                    <option value="">-- Select Customer --</option>
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
                <label class="col-form-label col-sm-3">Order date</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control" name="order_date" required>
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-form-label col-sm-3">Shipping date</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control" name="shipping_date" required>
                </div>
              </div>
              <div class="row mb-3">
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
              <div class="row mb-3">
                <label class="col-form-label col-sm-3">Company</label>
                <div class="col-sm-6">
                  <select class="form-select" name="company_id" required>
                  <?php
                    // Get data to display the current users company as the pre-displayed result

                    $id = $_SESSION["user_id"];
                    // prepare the SQL query with a parameter placeholder for $id
                    $stmt = $connection->prepare(
                      "SELECT companies.id, companies.name 
                      FROM companies 
                      JOIN employees
                        ON employees.company_id = companies.id
                      WHERE employees.id = ?");

                    // bind the $id variable to the parameter placeholder
                    $stmt->bind_param("i", $id);

                    // execute the query
                    $stmt->execute();

                    // bind the result variables to the query result columns
                    $stmt->bind_result($company_id, $company_name);

                    // fetch the first row
                    if ($stmt->fetch()) {
                      // the $company_id and $company_name variables now contain the values from the query
                    }
                    // close the statement
                    $stmt->close();
                  ?>
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
    <!-- end new order modal-->

    <!--Page data-->
    <div class="container">
      <h1>Incoming orders</h1>
      <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#newOrderModal'>Add new</button>
      <br>

      <?php if(isset($_GET['action']) && $_GET["status"] == "succes" && $_GET["action"] == "add"):?>
          <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <p><strong>Succes!</strong> You added: <?=$_GET['order']?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif (isset($_GET['action']) && $_GET["status"] == "succes" && $_GET["action"] == "edit"): ?>
          <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <p><strong>Succes!</strong> You edited: <?=$_GET['order']?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif (isset($_GET['action']) && $_GET["status"] == "succes" && $_GET["action"] == "delete"): ?>
          <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <p><strong>Succes!</strong> You deleted: <?=$_GET['order']?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php endif ?>

      <?php
      if(isset($_GET["error_message"])){
        echo $e;
      }
      ?>

    <!-- Actions bar-->
    <div class="d-flex align-items-center mb-5">
      <div class="input-group my-3 me-3">
        <span class="input-group-text" id="tableSearchBar">DOES NOT WORK</span>
        <input type="text" class="form-control" id="searchInput" placeholder="Article name..." aria-label="articlename" aria-describedby="tableSearchBar" onkeyup="tableSearch()">
      </div>
      <a href="../include/exportData.php?report=exportArticles" class="btn btn-success my-3">DOES NOT WORK</a>
      <div class="container d-flex align-items-center justify-content-end my-3 me-3">
        <form method="get">
          <div class="form-group row align-items-center">
            <label for="order_by" class="col-sm-3 col-form-label">DOES NOT WORK</label>
            <div class="col-sm-6">
              <select class="form-control" id="order_by" name="order_by">
                <option value="id_asc">ID (Ascending)</option>
                <option value="id_desc">ID (Descending)</option>
                <option value="name_asc">Name (Ascending)</option>
                <option value="name_desc">Name (Descending)</option>
                <option value="selling_price_asc">Selling price (Ascending)</option>
                <option value="selling_price_desc">Selling price (Descending)</option>
              </select>
            </div>
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- End actions bar-->
      <table class="table table-striped table-sm" id="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Emailadres</th>
            <th>Cellphone</th>
            <th>Order date</th>
            <th>Shipping date</th>
            <th>Salesman</th>
            <th>Finalized</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
        <?php

        // set the number of records per page
        $records_per_page = 25;

        // get the total number of records
        $sql_count = "SELECT COUNT(*) AS count FROM orders WHERE order_type = 0";
        $result_count = $connection->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_records = $row_count['count'];

        // calculate the total number of pages
        $total_pages = ceil($total_records / $records_per_page);

        // get the current page number
        $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

        // calculate the offset for the query
        $offset = ($current_page - 1) * $records_per_page;


        // prepare SQL statement
        $sql = "SELECT orders.id as id, relations.name as name, relations.email_adress as email_adress, relations.phone_number as phone_number, orders.order_date as order_date, orders.shipping_date as shipping_date,employees.first_name as verkoper_name, IF(orders.is_finalized = 1, 'Yes', 'No') as is_finalized 
        FROM orders
        JOIN relations
        ON orders.relation_id = relations.id
        JOIN employees
        ON orders.employee_id = employees.id
        WHERE order_type = 0
        ORDER BY id
        LIMIT $records_per_page
        OFFSET $offset;";

        // Run the query
        $result = $connection->query($sql);

        // make a new table row for every row in database
        while($row = $result->fetch_assoc()) {
          echo "<tr>
          <td>$row[id]</td>
          <td>$row[name]</td>
          <td>$row[email_adress]</td>
          <td>$row[phone_number]</td>
          <td>$row[order_date]</td>
          <td>$row[shipping_date]</td>
          <td>$row[verkoper_name]</td>
          <td>$row[is_finalized]</td>
          <td>
            <a class='btn btn-primary' href='GUI_orderEdit.php?id=$row[id]'>Edit</a>
            <a class='btn btn-danger' href='controller_orders.php?id=$row[id]&action=delete'>Delete</a>
          </td>
          </tr>";

        }


        ?>
        </tbody>
      </table>
      <?php if ($total_pages > 1): ?>
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <?php if ($current_page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $current_page - 1 ?>">Previous</a></li>
              <?php endif; ?>
              <?php 
                $start_page = max(1, $current_page - 5);
                $end_page = min($total_pages, $current_page + 5);
                for ($i = $start_page; $i <= $end_page; $i++): 
              ?>
                <li class="page-item<?= $current_page == $i ? ' active' : '' ?>">
                  <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
              <?php if ($current_page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $current_page + 1 ?>">Next</a></li>
              <?php endif; ?>
            </ul>
          </nav>
        <?php endif; ?>
    </div>
</body>
<?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</html>