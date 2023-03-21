<?php

// connect with database
require_once '../include/db_connect.php';

// if and switch block to determine which report to turn out
if(isset($_GET["report"])){
  $report = $_GET["report"];
  $output = new ExportData;
  switch($report){
    case "exportStock":
      $output->exportStock();
      break;
    case "exportArticles":
      $output->exportArticles();
      break;
    case "exportOrders":
      $output->exportOrders();
      break;
    case "exportUsers":
      $output->exportUsers();
      break;
    case "exportRelations":
      $output->exportRelations();
      break;
  }
} else{
  exit;
}

class ExportData {

  public $f;
  public $delimiter;

  // set variables that do not change througout the methods
  public function __construct()
  {
    $this->f = fopen('php://memory', 'w');
    $this->delimiter = ";";
  }

  //declare methods for each report
  public function exportStock(){
    global $connection;
    $sql = 
    "WITH total_incoming AS (
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
    GROUP BY articles.id, articles.name;";

    // execute the query
    $result = $connection->query($sql);

    if($result->num_rows > 0){

    $file_name = "Stock overview " . date('Y-m-d H:i:s') . ".csv"; 


    $fields = array('Article ID', 'Article name', 'Stock');
    fputcsv($this->f, $fields, $this->delimiter); 

    while($row = $result->fetch_assoc()){
      $lineData = array($row['article_id'], $row['article_name'], $row['stock_level']);
      fputcsv($this->f, $lineData, $this->delimiter); 
    }


    // Move back to beginning of file 
    fseek($this->f, 0); 

    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $file_name . '";'); 
      
    //output all remaining data on a file pointer 
    fpassthru($this->f); 
    } 
    exit; 
  }

  public function exportArticles(){
    global $connection;
    $sql = 
    "SELECT id, name, CONCAT(LEFT(description, 25),'...') AS 'description', purchase_price, selling_price, IF(is_active = 1, 'Active', 'Inactive') AS 'active_status'
    FROM articles";

    // execute the query
    $result = $connection->query($sql);

    if($result->num_rows > 0){

    $file_name = "Article overview " . date('Y-m-d H:i:s') . ".csv"; 


    $fields = array('Article ID', 'Name', 'Description', 'Purchase price', 'Selling price',  'Active');
    fputcsv($this->f, $fields, $this->delimiter); 

    while($row = $result->fetch_assoc()){
      $lineData = array($row['id'], $row['name'], $row['description'], $row['purchase_price'], $row['selling_price'], $row['active_status']);
      fputcsv($this->f, $lineData, $this->delimiter); 
    }


    // Move back to beginning of file 
    fseek($this->f, 0); 

    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $file_name . '";'); 
      
    //output all remaining data on a file pointer 
    fpassthru($this->f); 
    } 
    exit; 
  }

  public function exportUsers(){
    global $connection;
    $sql = 
    "SELECT employees.id, employees.first_name, employees.last_name, employees.email_adress, employees.function_name, companies.name, IF(employees.is_active = 1, 'Active', 'Non-active') AS 'is_active'
    FROM employees
    JOIN companies 
      ON employees.company_id  = companies.id;";

    // execute the query
    $result = $connection->query($sql);

    if($result->num_rows > 0){

    $file_name = "User overview " . date('Y-m-d H:i:s') . ".csv"; 


    $fields = array('User ID', 'First name', 'Last name', 'E-mail', 'Function', 'Company', 'Active');
    fputcsv($this->f, $fields, $this->delimiter); 

    while($row = $result->fetch_assoc()){
      $lineData = array($row['id'], $row['first_name'], $row['last_name'], $row['email_adress'], $row['function_name'], $row['name'], $row['is_active'],);
      fputcsv($this->f, $lineData, $this->delimiter); 
    }


    // Move back to beginning of file 
    fseek($this->f, 0); 

    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $file_name . '";'); 
      
    //output all remaining data on a file pointer 
    fpassthru($this->f); 
    } 
    exit; 
  }

  public function exportOrders(){
    global $connection;
  }

  public function exportRelations(){
    global $connection;
    $sql = "SELECT `id`, `name`, CONCAT(street, ' ', house_nr) AS 'address', `zip_code`, `city`, `country_code`, `email_adress`, `phone_number` FROM `relations`";

    // execute the query
    $result = $connection->query($sql);

    if($result->num_rows > 0){

    $file_name = "Relation overview " . date('Y-m-d H:i:s') . ".csv"; 


    $fields = array('Relation ID', 'Name', 'Address', 'Zip code', 'City', 'Country', 'E-mail', 'Phone');
    fputcsv($this->f, $fields, $this->delimiter); 

    while($row = $result->fetch_assoc()){
      $lineData = array($row['id'], $row['name'], $row['address'], $row['zip_code'], $row['city'], $row['country_code'], $row['email_adress'], $row['phone_number']);
      fputcsv($this->f, $lineData, $this->delimiter); 
    }


    // Move back to beginning of file 
    fseek($this->f, 0); 

    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $file_name . '";'); 
      
    //output all remaining data on a file pointer 
    fpassthru($this->f); 
    } 
    exit; 
  }

}
?>