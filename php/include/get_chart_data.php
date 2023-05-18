<?php

require_once "./db_connect.php";

// Check if the month and year parameters are set
if (isset($_GET['month']) && isset($_GET['year'])) {
  // Sanitize and retrieve the month and year values
  $selected_month = $_GET['month'];
  $selected_year = $_GET['year'];

  // Generate the SQL query to get the data for the selected month and year
  $sql = "SELECT ROUND((order_lines.quantity * articles.selling_price), 2) AS 'Total Sales', orders.employee_id AS 'Employee ID', CONCAT(employees.first_name, ' ', employees.last_name) AS 'Name', MONTHNAME(orders.order_date) AS 'Month', YEAR(orders.order_date) AS 'Year'
          FROM order_lines
          JOIN articles ON order_lines.article_id = articles.id
          JOIN orders ON order_lines.order_id = orders.id
          JOIN employees ON orders.employee_id = employees.id
          WHERE orders.order_type = 1 AND orders.is_finalized = 1 AND MONTHNAME(orders.order_date) = '$selected_month' AND YEAR(orders.order_date) = '$selected_year'
          GROUP BY 2, 4
          ORDER BY `Month` ASC";

  // Execute the query and store the results in $result
  $result = mysqli_query($connection, $sql);

  // Initialize an array to store the results
  $data = array();

  // Iterate over the result set and add each row to the data array
  while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
  }

  // Encode the data as JSON and return it
  echo json_encode($data);
} else {
  // Return an error response if the month and year parameters are not set
  echo json_encode(['error' => 'Invalid parameters']);
}
?>

