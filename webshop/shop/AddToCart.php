<?php

class AddtoCart {
  public function add_to_empty_cart($array){
    $_SESSION['cart'] = [];
    array_push($_SESSION['cart'], $array);
  }

  public function add_to_existing_cart($array, $key){
    $_SESSION['cart'][$key]['quantity'] += $array['quantity'];
  }

  public function add_new_to_existing_cart($array){
    array_push($_SESSION['cart'], $array);
  }
}

