<?php
session_start();
require_once "../../php/include/db_connect.php";



if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
// data validation

//retrieve all form data and store into variables
$name = htmlspecialchars($_POST["first_name"]) . " " . htmlspecialchars($_POST["last_name"]);
$street = htmlspecialchars($_POST["street"]);
$house_nr = htmlspecialchars($_POST["house_nr"]);
$zip_code = htmlspecialchars($_POST["zip_code"]);
$city = htmlspecialchars($_POST["city"]);
$country_code = htmlspecialchars(strtoupper($_POST["country_code"]));
$email_adress =  filter_var($_POST["email_adress"], FILTER_SANITIZE_EMAIL);
$phone_number = htmlspecialchars($_POST["phone_number"]);

//check if all variables are present
if ( empty($name) || empty($street) || empty($zip_code) || empty($city) || empty($country_code) || empty($email_adress) || empty($phone_number) || empty($house_nr)) {
  header("Location: GUI_cart.php?error_message=" . urlencode("Niet alle velden ingevuld"));
  exit;
}

// data insertion section

try {
  //define sql statement
  $sql = "INSERT INTO `relations`(`name`, `street`, `house_nr`, `zip_code`, `city`, `country_code`, `email_adress`, `phone_number`) VALUES ('$name', '$street', '$house_nr', '$zip_code', '$city', '$country_code', '$email_adress', '$phone_number')";

  //excecute sql query
  mysqli_query($connection, $sql);
  
  
}catch(mysqli_sql_exception $e){
  $error_message = "invalid query: " . $e;
  header("Location: GUI_cart.php?error_message=" . urlencode($error_message));
  exit;
}

// store the customer ID in a variable
$relation_id = mysqli_insert_id($connection);


// create the order
$order_date = date("Y-m-d");
$shipping_date = date('Y-m-d', strtotime($order_date . ' +1 Weekday'));
$order_type = 1;
$employee_id = 1;
$company_id = 1;

try {
  //define sql statement
  $sql = "INSERT INTO `orders` (`order_date`, `shipping_date`, `order_type`, `employee_id`, `relation_id`, `company_id`) VALUES ('$order_date', '$shipping_date',$order_type, $employee_id, $relation_id, $company_id)";
    
  //excecute sql query
  mysqli_query($connection, $sql);

}catch(mysqli_sql_exception $e) {
  $errorMessage = "invalid query: " . $e;
  header("Location: GUI_cart.php?error_message=" . urlencode($error_message));
  exit;
}

$order_id = mysqli_insert_id($connection);


// add order lines
$cart = $_SESSION['cart'];
foreach($cart as $key => $cart_item){
  $article_id = $cart_item['article_id'];
  $quantity = $cart_item['quantity'];

  try {
    //define sql statement
    $sql = "INSERT INTO `order_lines` (`order_id`, `article_id`, `quantity`) VALUES ($order_id, $article_id, $quantity);";
    //excecute sql query
    mysqli_query($connection, $sql);

  }catch(mysqli_sql_exception $e) {
    $errorMessage = "invalid query: " . $e;
    header("Location: GUI_cart.php?error_message=" . urlencode($error_message));
    exit;
  }
}




unset($_SESSION['cart']);



header("Location: phpmailer.php?order_id=$order_id");
exit;

// redirect to order confirmation
} else {
  header("Location: GUI_cart.php");
  exit;
}

?>