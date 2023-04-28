<?php

  session_start();
  require_once "../../php/include/db_connect.php";
  
  //pagination
  // set the number of records per page
  $records_per_page = 36;
  
  // get the total number of records
  $sql_count = "SELECT COUNT(*) AS count FROM articles WHERE is_active = 1";
  $result_count = $connection->query($sql_count);
  $row_count = $result_count->fetch_assoc();
  $total_records = $row_count['count'];
  
  // calculate the total number of pages
  $total_pages = ceil($total_records / $records_per_page);
  
  // get the current page number
  $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
  
  // calculate the offset for the query
  $offset = ($current_page - 1) * $records_per_page;
  
  // retrieve article data
  /*$sql = "SELECT * FROM articles 
  LIMIT $records_per_page
  OFFSET $offset;";*/

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
  
  SELECT articles.id AS 'id', articles.name AS 'article_name', articles.selling_price AS 'selling_price',
        COALESCE(SUM(total_incoming.incoming_stock), 0) - COALESCE(SUM(total_outgoing.outgoing_stock), 0) AS 'stock_level'
  FROM articles
  LEFT JOIN total_incoming
      ON articles.id = total_incoming.article_id
  LEFT JOIN total_outgoing
      ON articles.id = total_outgoing.article_id
  WHERE articles.is_active = 1
  GROUP BY articles.id, articles.name
  LIMIT $records_per_page
  OFFSET $offset;";
  
  // Run the query
  $result = $connection->query($sql);
  
  ?>
<!DOCTYPE html>
<html lang="en" class="h-100" data-bs-theme="light">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Greenhome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
      integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"
      defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
      integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"
      defer></script>
    <script src="https://kit.fontawesome.com/65fb36ce5e.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <link rel="shortcut icon" href="../images/logo-green.svg">
  </head>
  <body class="d-flex flex-column h-100">
    <!-- Nav begin -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <a class="navbar-brand" href="../Index.php">
      <img src="../images/logo-green.svg" width="30" height="30" class="d-inline-block align-top" alt="">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">COLLECTIE <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="GUI_shop.php">SHOP <span class="sr-only"></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">DUURZAAMHEID</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">OVER ONS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">CONTACT</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <!-- shoppingcart -->
          <a class="navIcon shopIcon nav-item collection present-on-mobile" href="winkelwagen.html"
            aria-label="Link naar het winkelmandje">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" id="shopBasket" class="navitem shop-icon">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
          </a>
          <!-- user profile -->
          <a class="navIcon userIcon nav-item collection present-on-mobile" href="login.html"
            aria-label="Link naar het gebruikersprofiel">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="navitem user-icon">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </a>
          <li class="nav-item hidden-on-mobile">
            <a id="iconText-shop" class="iconText navitem collection nav-link" href="winkelwagen.html"
              aria-label="Link naar het winkelmandje">WINKELWAGEN</a>
          </li>
          <li class="nav-item hidden-on-mobile">
            <a id="iconText-user" class="iconText navitem collection nav-link" href="login.html"
              aria-label="Link naar het gebruikersprofiel">PROFIEL</a>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Nav end -->
    <!-- Page content -->
    <div class="container">
        <h2 class="text-center fw-bold">Articles</h2>
        <div class="row justify-content-center">
            <?php while ($row = $result->fetch_assoc()) {?>  
            <div class="card col-md-3 px-0 mx-2 my-4">
            <img src="../../php/articles/article_images/product2.png" class="card-img-top" alt="Item image">
                <div class="card-body">
                    <h5 class="card-title"><?=$row['article_name']?></h5>
                    <p class="card-text">€<?=$row['selling_price']?></p>
                    <p class="card-text">Stock status: <?php if($row['stock_level'] >= 1):?>
                    <span class="text-success">In stock</span></p>
                    <?php else:?>
                    <span class="text-danger">Out of stock</span></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer d-flex justify-content-center">
                    <form action="controller_cart.php" method="POST" class="form-control">
                    <div class="input-group">
                      <span class="input-group-text">Pieces</span>
                      <input type="number" step="1" class="form-control" name="quantity" min="1" required>
                        <?php if($row['stock_level'] <= 0):?>
                        <button type="submit" class="btn btn-secondary disabled form-control">Add</button>
                        <?php else: ?>
                        <button type="submit" class="btn btn-success form-control">Add</button>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="article_id" value="<?=$row['id']?>">
                    <input type="hidden" name="article_name" value="<?=$row['article_name']?>">
                    <input type="hidden" name="selling_price" value="<?=$row['selling_price']?>">
                    </form>
                </div>
            </div>
            <?php } //while ($row = $result->fetch_assoc()) { ?>
        </div>
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
    <?php require_once "../include/footer.php"?>