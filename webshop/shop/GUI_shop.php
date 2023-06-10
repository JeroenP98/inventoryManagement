<?php

  //session_start();
  $page_name = "Shop | GreenHome";
  require_once "../include/header.php";
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

  // retrieve the search input with a shorthand if statement
  $searchInput = isset($_GET['search_input']) ? $_GET['search_input'] : '';
  
  // retrieve article data
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

  SELECT articles.id AS 'id', articles.name AS 'article_name', articles.selling_price AS 'selling_price', articles.image_data AS 'article_image', articles.image_mime AS 'image_mime',
        COALESCE(SUM(total_incoming.incoming_stock), 0) - COALESCE(SUM(total_outgoing.outgoing_stock), 0) AS 'stock_level'
  FROM articles
  LEFT JOIN total_incoming
      ON articles.id = total_incoming.article_id
  LEFT JOIN total_outgoing
      ON articles.id = total_outgoing.article_id
  WHERE articles.is_active = 1";

  // Append the search input condition if it is set
  if (!empty($searchInput)) {
    $sql .= " AND articles.name LIKE '%$searchInput%'";
  }

  $sql .= " GROUP BY articles.id, articles.name
  LIMIT $records_per_page
  OFFSET $offset;";

  // Run the query
  $result = $connection->query($sql);


  require_once './AddToCartController.php';
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $cart = new AddToCartController;
  }

?>
<!-- Page content -->
<div class="container">
  <h2 class="text-center fw-bold"><?php echo translate('Artikelen')?></h2>
  <div class="row justify-content-center">
  <!-- article search -->
  <div class="col-md-9 mt-5">
    <form method="GET">
      <div class="input-group mb-3">
        <div class="form-floating">
          <input type="text" class="form-control" name="search_input" id="search_input" placeholder="Zoek een artikel" aria-label="Search term">
          <label for="search_input"><?php echo translate('Zoek een artikel')?></label>
        </div>
        <button class="btn btn-primary" type="submit"><?php echo translate('Zoek')?></button>
      </div>
      <?php
        if (isset($_GET['search_input'])) {
          $searchInput = $_GET['search_input'];
      ?>
          <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Zoekterm: <?php echo $searchInput; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="clearSearch()"></button>
          </div>
          <script>
            function clearSearch() {
              // Redirect to the same page without the search_input parameter
              window.location.href = window.location.pathname;
            }
          </script>
      <?php
        }
      ?>
    

    </form>
  </div>
  <!-- Article cards -->
  <?php 
    // create a card for each article found in the 
    if (mysqli_num_rows($result) > 0) {
      while ($row = $result->fetch_assoc()) { ?>  
      <div class="card col-md-3 px-0 mx-2 my-4">
        <div class="d-flex align-items-center justify-content-center">
        <style>
          .product_image {
            max-inline-size: 100%;
            block-size: auto;
          }
        </style>
        <?php
          // load the picture or displat a no image placeholder if none was found
          if (!empty($row['article_image'])) {
            echo '<img class="p-4 card-img-top object-fit-cover product_image" src="data:'.$row['image_mime'].';base64,' . base64_encode($row['article_image']) . '" alt="Article Image">';
          } else {
            echo '<img class="p-4 object-fit-cover product_image" src="../../images/No-Image-Placeholder.svg.png" alt="No Image">';
          }
        ?>
        </div>
        <div class="card-body mt-auto">
          <h5 class="card-title"><?=$row['article_name']?></h5>
          <p class="card-text">€<?=$row['selling_price']?></p>
          <p class="card-text"><?php echo translate('Stock status: ')?><?php if($row['stock_level'] >= 1):?>
          <span class="text-success"><?php echo translate('In stock')?></span></p>
          <?php else:?>
          <span class="text-danger"><?php echo translate('Out of stock')?></span></p>
          <?php endif; ?>
        </div>
        <div class="card-footer d-flex justify-content-center">
          <form method="POST" class="form-control">
            <div class="input-group">
              <span class="input-group-text"><?php echo translate('Pieces')?></span>
              <input type="number" step="1" class="form-control" name="quantity" min="1" required>
                <?php if($row['stock_level'] <= 0):?>
                <button type="submit" class="btn btn-secondary disabled form-control"><?php echo translate('Add')?></button>
                <?php else: ?>
                <button type="submit" class="btn btn-success form-control"><?php echo translate('Add')?></button>
                <?php endif; ?>
            </div>
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="article_id" value="<?=$row['id']?>">
            <input type="hidden" name="article_name" value="<?=$row['article_name']?>">
            <input type="hidden" name="selling_price" value="<?=$row['selling_price']?>">
          </form>
        </div>
      </div>
      <?php 
      } //while ($row = $result->fetch_assoc()) { 
    } else {
      // If no results were found, insert the search input into searches_not_found table
      $searchInputEscaped = mysqli_real_escape_string($connection, $searchInput);
    
      $insertQuery = "INSERT INTO searches_not_found (search_input, times_searched) 
      VALUES ('$searchInputEscaped', 1)
      ON DUPLICATE KEY UPDATE times_searched = times_searched + 1";
      mysqli_query($connection, $insertQuery);
      
    }
  ?>
  </div>
</div>
<!-- Pagination -->
<?php if ($total_pages > 1): ?>
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <?php if ($current_page > 1): ?>
        <li class="page-item"><a class="page-link" href="?page=<?= $current_page - 1 ?>"><?php echo translate('Previous')?></a></li>
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
        <li class="page-item"><a class="page-link" href="?page=<?= $current_page + 1 ?>"><?php echo translate('Next')?></a></li>
      <?php endif; ?>
    </ul>
  </nav>
<?php endif; ?>
<?php require_once "../include/footer.php"?>