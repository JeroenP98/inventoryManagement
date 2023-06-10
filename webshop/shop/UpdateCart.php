<?php


class UpdateCart {

  public function increase_quantity($key){
    $_SESSION['cart'][$key]['quantity']++;
  }

  public function decrease_quantity($key){
    $_SESSION['cart'][$key]['quantity']--;
  }

  public function delete_cart($key){
    unset($_SESSION['cart'][$key]);
  }
}

