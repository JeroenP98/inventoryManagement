<?php
//if session was started, continue it so it
session_start();

//check if user has logged in, in order to gain access to the page. this disallows user to reach the page by entering the correct url
require_once '../include/loginCheck.php';

// connect to database
require_once '../include/db_connect.php';

// add the php file for the action logging
require_once '../logging/controller_logfile.php';

// declare empty variables for form handling
$name = "";
$description = "";
$purchase_price = "";
$selling_price = "";
$is_active = "";

// declare variables for form handling when failing
$errorMessage = "";

$imageSrc = "";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // if the method is GET, show the data found in the db record

    if(!isset($_GET["id"])){
    header("location: GUI_articles.php");
    exit;
    }

    $id = $_GET["id"];

    // read the row of selected record by searching for the ID
    $sql = "SELECT * FROM articles WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    //exit and return to main page if no ID can be found
    if(!$row) {
      header("location: GUI_articles.php");
      exit;
    }


    //Store the found data of the query to variables
    $name = $row["name"];
    $description = $row["description"];
    $purchase_price = $row["purchase_price"];
    $selling_price = $row["selling_price"];
    $is_active = $row["is_active"];

    // Fetch and encode the image data
    $imageData = base64_encode($row['image_data']);
    $imageSrc = 'data:image/png;base64,' . $imageData;
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
    <title>edit <?php echo $name ?> | GreenHome</title>
  </head>
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="../../images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 nav-pills">
          <li class="nav-item"><a href="../../dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="../articles/GUI_articles.php" class="nav-link active" >Articles</a></li>
          <li class="nav-item"><a href="../stock/GUI_stock.php" class="nav-link" >inventory</a></li>
          <li class="nav-item"><a href="../relations/GUI_relations.php" class="nav-link" aria-current="page" >Relations</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Orders</a>
            <ul class="dropdown-menu">
              <li><a href="../orders/GUI_incoming.php" class="dropdown-item">Incoming orders</a></li>
              <li><a href="../orders/GUI_outgoing.php" class="dropdown-item">Outgoing orders</a></li>
            </ul>
          </li>
          <li class="nav-item"><a href="../users/GUI_users.php" class="nav-link" aria-current="page">Users</a></li>
          <li class="nav-item "><a class="nav-link" href="../companies/GUI_companies.php">Companies</a></li>
          <li class="nav-item "><a class="nav-link" href="../accessibilities/GUI_accessibilities.php">Accessibility</a></li>
          <li class="nav-item "><a  href="../functions/GUI_functions.php" class="nav-link">Functions</a></li>
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
      <h2 class="mb-5">Edit Article</h2>

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

<?php
  // Fetch image_data and image_mime from the database for the article
  $sql = "SELECT image_data, image_mime FROM articles WHERE id = $id";
  $result = mysqli_query($connection, $sql);
  
  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $image_data = $row['image_data'];
    $image_mime = $row['image_mime'];
  } else {
    $image_data = null;
    $image_mime = null;
  }
?>

<form method="POST" action="controller_article.php?action=edit" enctype="multipart/form-data">
  <input type="hidden" value="<?php echo $id; ?>" name="id">

  <div class="row mb-3">
    <div class="col-sm-3">
      <label class="col-form-label">Article Image</label>
    </div>
    <div class="col-sm-6">
      <?php
        if (!empty($image_data)) {
          echo '<img id="articleImage" src="data:'.$image_mime.';base64,' . base64_encode($image_data) . '" alt="Article Image" width="400" height="400">';
        } else {
          echo '<img id="articleImage" src="" alt="No Image" width="400" height="300">';
        }
      ?>
    </div>
    <div class="col-sm-3">
    <input type="file" class="form-control" id="article_image" name="article_image">
    </div>
  </div>

  <div class="row mb-3">
    <label class="col-form-label col-sm-3">Name</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label class="col-form-label col-sm-3">Description</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="description" value="<?php echo $description; ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label class="col-form-label col-sm-3">Purchase price</label>
    <div class="col-sm-3">
      <input type="number" step=".01" class="form-control" name="purchase_price" value="<?php echo $purchase_price; ?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label class="col-form-label col-sm-3">Selling price</label>
    <div class="col-sm-3">
      <input type="number" step=".01" class="form-control" name="selling_price" value="<?php echo $selling_price; ?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label class="col-form-label col-sm-3">Active</label>
    <div class="col-sm-6">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" <?php if($is_active == 1) echo "checked"; ?>>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-sm-3"></div>
    <div class="col-sm-3 d-grid">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    <div class="col-sm-3 d-grid">
      <a href="GUI_articles.php" class="btn btn-outline-danger" role="button">Cancel</a>
    </div>
  </div>
</form>



</div>
  </body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#articleImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    $("input[type=file]").change(function() {
      readURL(this);
    });
  </script>
</html>