<?php
//if session was started, continue it so it can display the user name and enable logging out
session_start();

//check if user has logged in, in order to gain acces to the page
require_once '../include/loginCheck.php';

// create connection with database
require_once '../include/db_connect.php';
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
  <title>Stock | GreenHome</title>
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
          <li class="nav-item"><a href="../stock/GUI_stock.php" class="nav-link active" aria-current="page" >inventory</a></li>
          <li class="nav-item"><a href="../relations/GUI_relations.php" class="nav-link" aria-current="page" >Relations</a></li>
          <li class="nav-item"><a href="../incoming_orders/GUI_incoming.php" class="nav-link">Incoming orders</a></li>
          <li class="nav-item"><a href="../outgoing_orders/GUI_outgoing.php" class="nav-link" >Outgoing orders</a></li>
          <li class="nav-item"><a href="../users/GUI_users.php" class="nav-link">Users</a></li>
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
    <!-- Search bar-->
    <div class="d-flex nowrap align-items-center">
      <div class="input-group my-3 me-3">
        <span class="input-group-text" id="tableSearchBar">Search for article</span>
        <input type="text" class="form-control" id="searchInput" placeholder="Article name..." aria-label="articlename" aria-describedby="tableSearchBar" onkeyup="tableSearch()">
      </div>
      <a href="../include/exportData.php?report=exportStock" class="btn btn-success my-3">Export</a>
    </div>
      <!-- End search bar-->
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
      
      SELECT articles.id AS 'article_id', articles.name AS 'article_name', 
            COALESCE(SUM(total_incoming.incoming_stock), 0) - COALESCE(SUM(total_outgoing.outgoing_stock), 0) AS 'stock_level'
      FROM articles
      LEFT JOIN total_incoming
          ON articles.id = total_incoming.article_id
      LEFT JOIN total_outgoing
          ON articles.id = total_outgoing.article_id
      GROUP BY articles.id, articles.name
      LIMIT $records_per_page
      OFFSET $offset;";
      
      // execute the query
      $result = $connection->query($sql);

      // make a new table row for every row in database
      while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>$row[article_id]</td>
        <td>$row[article_name]</td>
        <td>$row[stock_level]</td>
        </tr>";

      }
      ?>

      <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
          <ul class="pagination">
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
      </tbody>
    </table>
  </div>
  <?php 
  // use php to use footer
  require_once '..\include\footer.php'?>
</body>
</html>