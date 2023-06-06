<?php
session_start();
$page_name = "Order confirmation | GreenHome";
require_once "../include/header.php";
require_once "../../php/include/db_connect.php";

$order_id = $_GET['order_id'];


?>


<main>
  <div class="container justify-content-center">
    <div class="row justify-content-center">
      <h1 class="fw-bold text-center mb-5">Bestelling geplaatst!</h1>
      <?php if(isset($_GET['error'])):?>
        <div class="alert alert-warning alert-dismissible fade show col-6 justify-content-center" role="alert">
          <p><strong>Oops!</strong> Je bestelling is geplaats maar we konden je bevestigings-email niet versturen</p>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif?>
    </div>
    <div class="row text-center">
      <h5 class="mb-5 text-black">
        Harterlijk dank voor uw bestelling met nummer: <strong style="color: #508279;"><?=$order_id?></strong><br> Zie uw details hieronder:
      </h5>
      <hr>
    </div>
    <div class="row">
      <h5 class="fw-bold">Verzend informatie</h5>
      <div class="row">
        <div class="col-md-6">
          <?php
            // get order data
            $sql = "SELECT name, street, house_nr, zip_code, city, country_code, email_adress, phone_number, orders.order_date AS 'order_date', orders.shipping_date as 'shipping_date'
            FROM relations
            JOIN orders
              ON orders.relation_id = relations.id
            WHERE orders.id = $order_id;";

            $result = $connection->query($sql);
            $row = mysqli_fetch_assoc($result);

          ?>
          <p><strong>Naam:</strong> <?php echo $row['name']; ?></p>
          <p><strong>Straat:</strong> <?php echo $row['street']; ?></p>
          <p><strong>Huisnummer:</strong> <?php echo $row['house_nr']; ?></p>
          <p><strong>Postcode:</strong> <?php echo $row['zip_code']; ?></p>
          <p><strong>Stad:</strong> <?php echo $row['city']; ?></p>
          <p><strong>Land:</strong> <?php echo $row['country_code']; ?></p>
        </div>
        <div class="col-md-6">
          <p><strong>Email adres:</strong> <?php echo $row['email_adress']; ?></p>
          <p><strong>Telefoon:</strong> <?php echo $row['phone_number']; ?></p>
          <p><strong>Besteldatum:</strong> <?php echo $row['order_date']; ?></p>
          <p><strong>Verwachte verzenddatum:</strong> <?php echo $row['shipping_date']; ?></p>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <table class="table table-bordered table-striped table-hover" id="table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Article</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          
        <?php 
          
          // get order data
          $sql = "SELECT articles.name AS 'article_name', order_lines.quantity AS 'quantity', articles.selling_price AS 'selling_price', articles.image_data AS 'article_image', articles.image_mime AS 'image_mime'
          FROM order_lines
          JOIN articles
            ON articles.id = order_lines.article_id
          WHERE order_lines.order_id = $order_id
          ;";

          $result = $connection->query($sql);
          
          while($row = $result->fetch_assoc()): ?>
            <tr>
              <td>
                <?php if(!empty($row['article_image'])):?>
                  <img src="data: <?=$row['image_mime']?>;base64, <?= base64_encode($row['article_image'])?>" class="img-thumbnail" alt="Item image" style="width: 120px; height: 120px;">
                <?php else: ?>
                  <img class="p-4 object-fit-cover" width="300px" height="300px" src="../../images/No-Image-Placeholder.svg.png" alt="No Image">
                <?php endif; ?>
              </td>
              <td><?=$row['article_name']?></td>
              <td><?=$row['quantity']?></td>
              <td>€<?=$row['quantity'] * $row['selling_price']?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="row justify-content-center">
      <div class="col-6 text-center">
        <a href="../index/Index.php" class="btn btn-primary w-100">Terug naar Home</a>
      </div>
    </div>


  </div>
</main>

<?php require_once "../include/footer.php";?>