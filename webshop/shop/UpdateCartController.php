<?php

require_once './UpdateCart.php';
/*
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $cart = new CartController;
}
*/
class UpdateCartController {

  public $action;
  public $key;
  public $cart;


  public function __construct()
  {
    $this->action = $_POST['action'];
    if(isset($_POST['key'])){
      $this->key = $_POST['key'];
    }
    $this->cart = new UpdateCart;
    $this->action_redirect($this->action);
  }

  public function action_redirect($action){
    if ($action == 'increase') {
      $this->cart->increase_quantity($this->key);
    } elseif ($action == 'decrease') {
      if($_SESSION['cart'][$this->key]['quantity'] > 1){
        $this->cart->decrease_quantity($this->key);
      } else {
        $this->cart->delete_cart($this->key);
      }
    } elseif ($action == 'delete') {
      unset($_SESSION['cart'][$this->key]);
      $this->cart->delete_cart($this->key);
    }

  }



/*
  public function action_redirect($action){
    if ($action == 'increase') {
      $_SESSION['cart'][$this->key]['quantity']++;
    } elseif ($action == 'decrease' && $_SESSION['cart'][$this->key]['quantity'] > 1) {
      $_SESSION['cart'][$this->key]['quantity']--;
    } elseif ($action == 'delete') {
      unset($_SESSION['cart'][$this->key]);
    }
  }
*/
}






?>
