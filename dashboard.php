<?php
//if session was started, continue it so it can display the user name and enable logging out
session_start();

//create user name variable which uppercases the firstl letter in the string
if(!empty($_SESSION['user_name'])){
  $name = ucfirst($_SESSION['user_name']);
}

require_once 'php/include/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en" class="h-100" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap code-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/darkMode.js"></script>
  <script src="js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="shortcut icon" href="images/logo.png">
  <title>Dashboard | GreenHome</title>
</head>
<body class="d-flex flex-column h-100">
  <header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="../../POC greenhome/dashboard.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none me-5">
          <img src="images/logo.png" alt="company logo" srcset="" width="40" height="40">
        </a>
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-cßenter mb-md-0 nav-pills">
          <li class="nav-item"><a href="dashboard.php" class="nav-link active" aria-current="page">Dashboard</a></li>
          <li class="nav-item"><a href="php/articles/GUI_articles.php" class="nav-link">Articles</a></li>
          <li class="nav-item"><a href="php/stock/GUI_stock.php" class="nav-link">inventory</a></li>
          <li class="nav-item"><a href="php/relations/GUI_relations.php" class="nav-link" aria-current="page" >Relations</a></li>
          <li class="nav-item"><a href="php/incoming_orders/GUI_incoming.php" class="nav-link">Incoming orders</a></li>
          <li class="nav-item"><a href="php/outgoing_orders/GUI_outgoing.php" class="nav-link">Outgoing orders</a></li>
          <li class="nav-item"><a href="php/users/GUI_users.php" class="nav-link">Users</a></li>
          <li class="nav-item"><a  href="php/companies/GUI_companies.php" class="nav-link">Companies</a></li>
          <li class="nav-item "><a  href="php/accessibilities/GUI_accessibilities.php" class="nav-link">Accesibility</a></li>
          <li class="nav-item "><a  href="php/functions/GUI_functions.php" class="nav-link">Functions</a></li>
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
          <a href="php/users/GUI_logged_out.php"><button type="button" class="btn btn-warning">Log out</button></a>
        </div>
      </div>
    </div>
  </div>
  <!-- end logout modal-->
  <main class=" d-flex justify-content-center py-4">
    <?php
    //different html body will be shown depending on of the user has logged in or not
    // use the shorthand sytax for if...else by hopping in and out of php mode 
    if(isset($_SESSION["user_id"])):?>
      <div class="container">
      <div class="card text-center">
        <div class="card-header">
          <h1 class="display-5 fw-bold">Quick acces</h1>
          <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
              <a class="nav-link active" href="#" onclick="showSection('section1', this)">Order management</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showSection('section2', this)">Relation management</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showSection('section3', this)">Administator</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showSection('section4', this)">Reports</a>
            </li>
          </ul>
        </div>
        <div class="card-body cardBodyJs" >
          <div id="section1">
            <div class="row row-cols-1 row-cols-md-3 mb-3 text-center sortable" id="sortable">
              <div class="col draggable">
                <div class="card mb-4 rounded-3 shadow-sm">
                  <div class="card-header py-3">
                    <h4 class="my-0 fw-normal">Articles</h4>
                  </div>
                  <div class="card-body cardBodyJs">
                    <p class="card-text">Gain quick acces to to article related pages with the buttons below</p>
                    <a class="w-100 btn btn-lg btn-outline-primary" href="php/articles/GUI_articles.php">SKU overview</a>
                  </div>
                </div>
              </div>
              <div class="col draggable">
                <div class="card mb-4 rounded-3 shadow-sm">
                  <div class="card-header py-3">
                    <h4 class="my-0 fw-normal">Orders</h4>
                  </div>
                  <div class="card-body cardBodyJs">
                    <p class="card-text">Gain quick acces to to order related pages with the buttons below</p>
                    <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="php/incoming_orders/GUI_incoming.php">Incoming orders</a>
                    <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="php/outgoing_orders/GUI_outgoing.php">Outgoing orders</a>
                    <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="#">Order lines</a>
                  </div>
                </div>
              </div>
              <div class="col draggable">
                <div class="card mb-4 rounded-3 shadow-sm">
                  <div class="card-header py-3">
                    <h4 class="my-0 fw-normal">Stock</h4>
                  </div>
                  <div class="card-body cardBodyJs">
                    <p class="card-text">Gain quick acces to the stock related pages with the buttons below</p>
                    <a class="w-100 btn btn-lg btn-outline-primary" href="php/stock/GUI_stock.php">Stock overview</a>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div id="section2" class="d-none">
          <div class="row row-cols-1 row-cols-md-3 mb-3 text-center sortable" id="sortable">
            <div class="col">
              <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header py-3">
                  <h4 class="my-0 fw-normal">Relations</h4>
                </div>
                <div class="card-body cardBodyJs">
                  <p class="card-text">Gain quick acces to to Relation related pages with the buttons below</p>
                  <a class="w-100 btn btn-lg btn-outline-primary" href="php/relations/GUI_relations.php">Relation overview</a>
                </div>
              </div>
            </div>
          </div>    
        </div>
        <div id="section3" class="d-none">
          <div class="row row-cols-1 row-cols-md-3 mb-3 text-center sortable" id="sortable">
            <div class="col">
              <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header py-3">
                  <h4 class="my-0 fw-normal">Users</h4>
                </div>
                <div class="card-body cardBodyJs">
                  <p class="card-text">Gain quick acces to to Users related pages with the buttons below</p>
                  <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="php/users/GUI_users.php">User overview</a>
                  <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="#">Functions overview</a>
                  <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="#">Accesibilities overview</a>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header py-3">
                  <h4 class="my-0 fw-normal">Companies</h4>
                </div>
                <div class="card-body cardBodyJs">
                  <p class="card-text">Gain quick acces to to company related pages with the buttons below</p>
                  <a class="w-100 btn btn-lg btn-outline-primary" href="#">Company overview</a>
                </div>
              </div>
            </div>
          </div>    
        </div>
        <div id="section4" class="d-none">
          <div class="row row-cols-1 row-cols-md-3 mb-3 text-center sortable" id="sortable">
            <div class="col">
              <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header py-3">
                  <h4 class="my-0 fw-normal">Users</h4>
                </div>
                <div class="card-body cardBodyJs">
                  <p class="card-text">Gain quick acces to the order related reports with the buttons below</p>
                  <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="#">User overview</a>
                  <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="#">Functions overview</a>
                  <a class="w-100 btn btn-lg btn-outline-primary mt-3" href="#">Accesibilities overview</a>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header py-3">
                  <h4 class="my-0 fw-normal">Companies</h4>
                </div>
                <div class="card-body cardBodyJs">
                  <p class="card-text">Gain quick acces to to company related pages with the buttons below</p>
                  <a class="w-100 btn btn-lg btn-outline-primary" href="#">Company overview</a>
                </div>
              </div>
            </div>
          </div> 
        </div> 
      </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col">
        <div class="card mt-4 d-flex justify-content-center">
            <div class="card-header">
                <h4>Top 10 Customers by Total Turnover</h4>
            </div>
            <div class="card-body" style="height: 400px;"> 
              <?php
                $sql = "SELECT
                CASE WHEN rank <= 10 THEN relations.name ELSE 'Other' END AS 'Buyer name',
                SUM(ROUND((order_lines.quantity * articles.selling_price),2)) AS 'Total turnover'
                FROM
                (
                  SELECT
                    orders.relation_id,
                    RANK() OVER (ORDER BY SUM(ROUND((order_lines.quantity * articles.selling_price),2)) DESC) AS rank
                  FROM orders
                  JOIN order_lines ON orders.id = order_lines.order_id
                  JOIN articles ON order_lines.article_id = articles.id
                  WHERE orders.is_finalized = 1 AND orders.order_type = 1
                  GROUP BY 1
                ) AS top_customers
                JOIN orders ON top_customers.relation_id = orders.relation_id
                JOIN relations ON orders.relation_id = relations.id
                JOIN order_lines ON orders.id = order_lines.order_id
                JOIN articles ON order_lines.article_id = articles.id
                WHERE orders.is_finalized = 1 AND orders.order_type = 1
                GROUP BY 1";

                $result = mysqli_query($connection, $sql);

                // initialize an array to store the results
                $data = array();

                // iterate over the result set and add each row to the data array
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }

                // encode the data as JSON
                $json_data = json_encode($data);

                    
                ?>
              <div>
                <canvas id="myChart"></canvas>
              </div>
              <script>
                // parse the JSON data
                var jsonData = <?php echo $json_data; ?>;
                // extract the labels and data from the JSON data
                var labels = jsonData.map(function(item) { return item['Buyer name']; });
                var data = jsonData.map(function(item) { return item['Total turnover']; });
                // create the chart using Chart.js
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                  type: 'pie',
                  data: {
                      labels: labels,
                      datasets: [{
                          data: data,
                          backgroundColor: [
                              '#FF6384',
                              '#36A2EB',
                              '#FFCE56',
                              '#8BC34A',
                              '#FF9800',
                              '#9C27B0',
                              '#009688',
                              '#607D8B',
                              '#FF5722',
                              '#F44336'
                          ]
                      }]
                  },
                  options: {
                    plugins : {
                    legend: {
                        position: 'right'
                    }
                  },
                  maintainAspectRatio: false,
                  responsive: true
                }
                });
              </script>
            </div>
        </div>
      </div>
      <div class="col">
        <div class="card mt-4 d-flex justify-content-center">
          <div class="card-header">
              <h4>Top 10 Employees by Total Turnover</h4>
          </div>
          <div class="card-body">
            <?php
              // set the default month to display
              $selected_month = 'January';
              $selected_year = date("Y");

              // if the form is submitted, update the selected month
              if (isset($_POST['month']) && isset($_POST['year'])) {
                  $selected_month = $_POST['month'];
                  $selected_year = $_POST['year'];
              }

              // generate the SQL query to get the data for the selected month
              $sql2 = "SELECT ROUND((order_lines.quantity * articles.selling_price),2) AS 'Total Sales', orders.employee_id AS 'Employee ID', CONCAT(employees.first_name, ' ', employees.last_name) AS 'Name', MONTHNAME(orders.order_date) AS 'Month', YEAR(orders.order_date) AS 'Year'
              FROM order_lines
              JOIN articles
                  ON order_lines.article_id = articles.id
              JOIN orders
                  ON order_lines.order_id = orders.id
              JOIN employees
                  ON orders.employee_id = employees.id
              WHERE orders.order_type = 1 AND orders.is_finalized = 1 AND MONTHNAME(orders.order_date) = '$selected_month' AND YEAR(orders.order_date) = '$selected_year'
              GROUP BY 2, 4
              ORDER BY `Month` ASC;";

              // execute the query and store the results in $result
              $result2 = mysqli_query($connection, $sql2);

              // initialize an array to store the results
              $data2 = array();
          
              // iterate over the result set and add each row to the data array
              while ($row2 = mysqli_fetch_assoc($result2)) {
                  $data2[] = $row2;
              }
          
              // encode the data as JSON
              $json_data2 = json_encode($data2);
              ?>

              <!-- display the selection box and the chart -->
              <form method="post" class="row mb-3">
                <div class="form-floating col-4">
                  <select id="month" name="month" class="form-select">
                      <?php
                      $months = [
                          'January',
                          'February',
                          'March',
                          'April',
                          'May',
                          'June',
                          'July',
                          'August',
                          'September',
                          'October',
                          'November',
                          'December'
                      ];
                      foreach ($months as $month) {
                          $selected = ($selected_month == $month) ? 'selected' : '';
                          echo '<option value="' . $month . '" ' . $selected . '>' . $month . '</option>';
                      }
                      ?>
                  </select>
                  <label for="month" class="form-label ms-2">Select month</label>
                </div>
                <div class="form-floating col-4">
                  <select id="year" name="year" class="form-select">
                    <?php
                    $sql_years = "SELECT DISTINCT YEAR(order_date) AS 'year' FROM orders;";
                    $result_years = mysqli_query($connection, $sql_years);
                    if (mysqli_num_rows($result_years) > 0) {
                      while ($row = mysqli_fetch_assoc($result_years)) {
                        echo '<option value="' . $row['year'] . '">' . htmlspecialchars($row['year']) . '</option>';
                      }
                    }
                    ?>
                  </select>
                  <label for="year" class="form-label ms-3">Select year</label>
                </div>
                <div class="col-3 d-flex align-items-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>

              </form>

                <div style="height: 400px;">
                  <canvas id="myChart2"></canvas>
                </div>

              <script>
                // parse the JSON data
                var jsonData = <?php echo $json_data2; ?>;
                // extract the labels and data from the JSON data
                var labels = jsonData.map(function(item) { return item['Name']; });
                var data = jsonData.map(function(item) { return item['Total Sales']; });
                // create the chart using Chart.js
                var ctx = document.getElementById('myChart2').getContext('2d');
                var myChart = new Chart(ctx, {
                  type: 'pie',
                  data: {
                    labels: labels,
                    datasets: [{
                      data: data,
                      backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#8BC34A',
                        '#FF9800',
                        '#9C27B0',
                        '#009688',
                        '#607D8B',
                        '#FF5722',
                        '#F44336'
                      ]
                    }]
                  },
                  options: {
                    maintainAspectRatio: false,
                    responsive: true
                  }
                });

                // update the chart data when the selection box is changed
                document.getElementById('month').addEventListener('change', function() {
                  var selectedMonth = this.value;
                  var selectedYear = document.getElementById('year').value;
                  // make an AJAX request to get the data for the selected month and year
                  var xhttp = new XMLHttpRequest();
                  xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                      // update the chart data
                      var jsonData = JSON.parse(this.responseText);
                      var labels = jsonData.map(function(item) { return item['Name']; });
                      var data = jsonData.map(function(item) { return item['Total Sales']; });
                      myChart.data.labels = labels;
                      myChart.data.datasets[0].data = data;
                      myChart.update();
                    }
                  };
                  xhttp.open('GET', 'get_data.php?month=' + selectedMonth + '&year=' + selectedYear, true);
                  xhttp.send();
                });
              </script>
            </div>
        </div>
      </div>

    </div>
    <?php else :?>    
      <div class='p-5 m-4 rounded-3'>
        <div class='container-fluid py-5'>
          <h1 class='display-5 fw-bold'>Hello stranger</h1>
          <p class='col-md fs-4'>You should login before continuing</p>
          <div class='list-group'>
            <a href='php/users/GUI_login.php' class='btn btn-primary btn-lg' type='button'>Log in</a>
          </div>
        </div>
      </div>
    <?php endif;?>
  </main>
  <?php require_once 'php\include\footer.php'?>
</body>
</html>