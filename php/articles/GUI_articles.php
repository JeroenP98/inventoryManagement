<?php
//if session was started, continue it so it can display the user name and enable logging out
session_start();

//check if user has logged in, in order to gain acces to the page
require_once '../include/loginCheck.php';

?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap code-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../images/logo.png">
    <script src="../../js/formValidator.js"></script>
    <script src="../../js/tableSearch.js"></script>
    <script src="../../js/darkMode.js"></script>
    <title>Articles | GreenHome</title>
  </head>

  <!--Header-->
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="../../images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 nav-pills">
          <li class="nav-item"><a href="../../dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="../articles/GUI_articles.php" class="nav-link active" aria-current="page">Articles</a></li>
          <li class="nav-item"><a href="../stock/GUI_stock.php" class="nav-link" >inventory</a></li>
          <li class="nav-item"><a href="../incoming_orders/GUI_incoming.php" class="nav-link">Incoming orders</a></li>
          <li class="nav-item"><a href="../outgoing_orders/GUI_outgoing.php" class="nav-link">Outgoing orders</a></li>
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

  <!--Body-->
  <body>
    
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
 
        <!-- start new article Modal -->
        <div class="modal fade" id="newArticleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create a new article</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="controller_article.php?action=add">
              <div class="row mb-3">
                <label class="col-form-label col-sm-3">Article name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="name" required>
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-form-label col-sm-3">Description</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="description" required>
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-form-label col-sm-3">Purchase price</label>
                <div class="col-sm-3">
                  <input type="number" step=".01" class="form-control" name="purchase_price" required>
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-form-label col-sm-3">Selling price</label>
                <div class="col-sm-3">
                  <input type="number" step=".01" class="form-control" name="selling_price" required>
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
    <!-- end new article modal-->
  
    <!--Page data-->
    <div class="container">
      <h1>Articles</h1>
      <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#newArticleModal'>Add new</button>
      <br>

      <?php if(isset($_GET['action']) && $_GET["status"] == "succes" && $_GET["action"] == "add"):?>
          <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <p><strong>Succes!</strong> You added: <?=$_GET['article']?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif (isset($_GET['action']) && $_GET["status"] == "succes" && $_GET["action"] == "edit"): ?>
          <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <p><strong>Succes!</strong> You edited: <?=$_GET['article']?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif (isset($_GET['action']) && $_GET["status"] == "succes" && $_GET["action"] == "delete"): ?>
          <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <p><strong>Succes!</strong> You deleted: <?=$_GET['article']?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php endif ?>


      <!-- Search bar-->
      <div class="input-group my-3">
        <span class="input-group-text" id="tableSearchBar">Search for article</span>
        <input type="text" class="form-control" id="searchInput" placeholder="Article name..." aria-label="articlename" aria-describedby="tableSearchBar" onkeyup="tableSearch()">
      </div>
      <!-- End search bar-->
      <table class="table table-striped table-sm" id="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Article ID</th>
            <th>Description</th>
            <th>Purchase price</th>
            <th>Selling price</th>
            <th>Active</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
        <?php
        // create connection with database
        require_once '../include/db_connect.php';
        // select database
        $sql = "SELECT id, name, CONCAT(LEFT(description, 25),'...') AS 'description', CONCAT('€ ', purchase_price) AS purchase_price, CONCAT('€ ', selling_price) AS selling_price, IF(is_active = 1, 'Active', 'Inactive') AS 'active_status'
        FROM articles;";
        $result = $connection->query($sql);

        // make a new table row for every row in database
        while($row = $result->fetch_assoc()) {
          echo "<tr>
          <td>$row[id]</td>
          <td>$row[name]</td>
          <td>$row[description]</td>
          <td>$row[purchase_price]</td>
          <td>$row[selling_price]</td>
          <td>$row[active_status]</td>
          <td>
          <a class='btn btn-primary' href='GUI_articleEditor.php?id=$row[id]'>Edit</a>
          <a class='btn btn-danger' href='controller_article.php?action=delete&id=$row[id]'>Delete</a>
          </td>
          </tr>";

        }


        ?>
        </tbody>
      </table>
    </div>
  </body>
</html>