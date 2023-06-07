<?php
session_start();

$key = $_POST['key'];
$action = $_POST['action'];

if ($action == 'increase') {
  $_SESSION['cart'][$key]['quantity']++;
} elseif ($action == 'decrease' && $_SESSION['cart'][$key]['quantity'] > 1) {
  $_SESSION['cart'][$key]['quantity']--;
} elseif ($action == 'delete') {
  unset($_SESSION['cart'][$key]);
}


header("Location: GUI_cart.php");
exit;

?>
