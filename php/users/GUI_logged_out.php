<?php
  //continue over the user session in order to then destroy it and log the user out.
  session_start();
  session_destroy();


?>

<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap code-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

  <link rel="shortcut icon" href="images/logo.png">
  <title>Logged out | GreenHome</title>
</head>
<body class="d-flex flex-column h-100">
  <main>
    <div class="container py-4">
      <header class="pb-3 mb-4 border-bottom">
        <a href="#" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="../../images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>
      </header>
  
      <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
          <h1 class="display-5 fw-bold">You have been logged out succesfully</h1>
          <p class="col-md-8 fs-4">close this tab or log back in</p>
          <a href="GUI_login.php" class="btn btn-primary btn-lg"> Log in</a>
        </div>
      </div>
  

    </div>
  </main>
</body>
</html>