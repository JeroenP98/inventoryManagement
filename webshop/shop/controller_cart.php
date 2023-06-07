<?php

session_start();


if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_SESSION['cart'])){
  // if no instance of the cart array was found, initialize it and push the form data to it
  $_SESSION['cart'] = [];
  array_push($_SESSION['cart'], $_POST);
  
  
} else {

  //check if the post data contains an item that is already in the cart and adds to the quantity if so.
  $match = false;
  foreach($_SESSION['cart'] as $key => $cart_item){
    if ($cart_item['article_id'] == $_POST['article_id']){;
      $_SESSION['cart'][$key]['quantity'] += $_POST['quantity'];
      $match = true;
      break;
    }
  }

  // if no match was found, add new array to the cart
  if(!$match) {
    array_push($_SESSION['cart'], $_POST);
  }

}


header('Location: GUI_cart.php');
exit;
?>