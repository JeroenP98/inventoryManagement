<?php

require_once './webshop/shop/UpdateCart.php';

use PHPUnit\Framework\TestCase;

class UpdateCartTest extends TestCase
{
  public function testIncreaseQuantity()
  {
    $cart = new UpdateCart();

    $_SESSION['cart'] = [
      [
        'article_id' => 1,
        'quantity' => 2,
      ],
      [
        'article_id' => 2,
        'quantity' => 3,
      ],
    ];

    $key = 0;

    $cart->increase_quantity($key);

    $this->assertEquals(3, $_SESSION['cart'][$key]['quantity']);
  }

  public function testDecreaseQuantity()
  {
    $cart = new UpdateCart();

    $_SESSION['cart'] = [
      [
        'article_id' => 1,
        'quantity' => 2,
      ],
      [
        'article_id' => 2,
        'quantity' => 3,
      ],
    ];

    $key = 1;

    $cart->decrease_quantity($key);

    $this->assertEquals(2, $_SESSION['cart'][$key]['quantity']);
  }

  public function testDeleteCart()
  {
    $cart = new UpdateCart();

    $_SESSION['cart'] = [
      [
        'article_id' => 1,
        'quantity' => 2,
      ],
      [
        'article_id' => 2,
        'quantity' => 3,
      ],
    ];

    $key = 0;

    $cart->delete_cart($key);

    $this->assertCount(1, $_SESSION['cart']);
    $this->assertArrayNotHasKey($key, $_SESSION['cart']);
  }
}