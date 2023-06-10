<?php
require_once './AddToCart.php';

class AddToCartController{

  public $action;
  public $article_to_be_added;
  public $cart;


  public function __construct()
  {
    $this->action = $_POST['action'];
    $this->article_to_be_added = $_POST;
    $this->cart = new AddtoCart;
    $this->action_redirect($this->action);
  }

  public function action_redirect($action){

    if($action == 'add'){
      if(empty($_SESSION['cart'])){
        $this->cart->add_to_empty_cart($_POST);
      } else{
        $match = false;
        foreach($_SESSION['cart'] as $key => $cart_item){
          if ($cart_item['article_id'] == $_POST['article_id']){;
            $this->cart->add_to_existing_cart($_POST, $key);
            $match = true;
          }
        }
        // if no match was found, add new array to the cart
        if(!$match) {
          $this->cart->add_new_to_existing_cart($_POST);
        }
      }
    }

    echo "<script>window.location.href='GUI_cart.php';</script>";
  }

}
