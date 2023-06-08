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
  <script src="../../js/tableSearch.js"></script>
  <link rel="shortcut icon" href="../../images/logo.png">
  <title>Stock | GreenHome</title>
</head>
<body class="d-flex flex-column h-100">
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="../../images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 nav-pills">
          <li class="nav-item"><a href="../../dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="../articles/GUI_articles.php" class="nav-link" >Articles</a></li>
          <li class="nav-item"><a href="../stock/GUI_stock.php" class="nav-link active" aria-current="page" >inventory</a></li>
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
    <h1>Stock overview</h1>
  </div>

  <div class="container">
    <!-- Actions bar-->
    <div class="d-flex align-items-center mb-5">
      <div class="input-group my-3 me-3">
        <span class="input-group-text" id="tableSearchBar">Search for article</span>
        <input type="text" class="form-control" id="searchInput" placeholder="Article name..." aria-label="articlename" aria-describedby="tableSearchBar" onkeyup="tableSearch()">
      </div>
      <a href="../include/exportData.php?report=exportStock" class="btn btn-success my-3">Export</a>
      <div class="container d-flex align-items-center justify-content-end my-3 me-3">
        <form method="get">
          <div class="form-group row align-items-center">
            <label for="order_by" class="col-sm-3 col-form-label">Order by:</label>
            <div class="col-sm-6">
              <select class="form-control" id="order_by" name="order_by">
                <option value="id_asc">ID (Ascending)</option>
                <option value="id_desc">ID (Descending)</option>
                <option value="article_name_asc">Name (Ascending)</option>
                <option value="article_name_desc">Name (Descending)</option>
                <option value="stock_level_asc">Stock (Ascending)</option>
                <option value="stock_level_desc">Stock (Descending)</option>
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
          <th>Article name</th>
          <th>Stock level</th>
        </tr>
      </thead>

      <tbody>
      <?php

      // set the number of records per page
      $records_per_page = 25;

      // get the total number of records
      $sql_count = "SELECT COUNT(*) AS count FROM articles";
      $result_count = $connection->query($sql_count);
      $row_count = $result_count->fetch_assoc();
      $total_records = $row_count['count'];

      // calculate the total number of pages
      $total_pages = ceil($total_records / $records_per_page);

      // get the current page number
      $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

      // calculate the offset for the query
      $offset = ($current_page - 1) * $records_per_page;

      // Set order by value
      if(isset($_GET["order_by"])){
        $get_order_by = $_GET["order_by"];
        switch($get_order_by) {
          case "article_name_asc":
            $order_by = "article_name ASC"; 
            break;
          case "article_name_desc":
            $order_by = "article_name DESC"; 
            break;
          case "id_asc":
            $order_by = "id ASC"; 
            break;
          case "id_desc":
            $order_by = "id DESC"; 
            break;
          case "stock_level_asc":
            $order_by = "stock_level ASC"; 
            break;
          case "stock_level_desc":
            $order_by = "stock_level DESC"; 
            break;
          default:
            $order_by = "id ASC";
        }
      } else {
        $order_by = "id ASC";
      }

      // prepare sql statement
      $sql = "WITH total_incoming AS (
        SELECT articles.id AS article_id, SUM(order_lines.quantity) AS incoming_stock
        FROM order_lines
        JOIN orders
            ON order_lines.order_id = orders.id
        JOIN articles
            ON articles.id = order_lines.article_id
        WHERE orders.order_type = 0 
        GROUP BY articles.id
      ), total_outgoing AS (
        SELECT articles.id AS article_id, SUM(order_lines.quantity) AS outgoing_stock
        FROM order_lines
        JOIN orders
            ON order_lines.order_id = orders.id
        JOIN articles
            ON articles.id = order_lines.article_id
        WHERE orders.order_type = 1 
        GROUP BY articles.id
      )
      
      SELECT articles.id AS 'id', articles.name AS 'article_name', 
            COALESCE(SUM(total_incoming.incoming_stock), 0) - COALESCE(SUM(total_outgoing.outgoing_stock), 0) AS 'stock_level'
      FROM articles
      LEFT JOIN total_incoming
          ON articles.id = total_incoming.article_id
      LEFT JOIN total_outgoing
          ON articles.id = total_outgoing.article_id
      GROUP BY articles.id, articles.name
      ORDER BY $order_by
      LIMIT $records_per_page
      OFFSET $offset;";
      
      // execute the query
      $result = $connection->query($sql);

      // make a new table row for every row in database
      while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>$row[id]</td>
        <td>$row[article_name]</td>
        <td>$row[stock_level]</td>
        </tr>";

      }
      ?>
      </tbody>
    </table>
  </div>
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
  <?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</body>
</html>