<?php

use PHPUnit\Framework\TestCase;
require_once './webshop/shop/AddToCart.php';

class AddtoCartTest extends TestCase
{
    public function testAddToEmptyCart()
    {
        $cart = new AddtoCart();

        $item = [
            'article_id' => 1,
            'quantity' => 2,
        ];

        $cart->add_to_empty_cart($item);

        $this->assertNotEmpty($_SESSION['cart']);
        $this->assertEquals($item, $_SESSION['cart'][0]);
    }

    public function testAddToExistingCart()
    {
        $cart = new AddtoCart();

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

        $item = [
            'article_id' => 1,
            'quantity' => 3,
        ];

        $key = 0;

        $cart->add_to_existing_cart($item, $key);

        $this->assertEquals(5, $_SESSION['cart'][0]['quantity']);
        $this->assertEquals(3, $_SESSION['cart'][1]['quantity']);
    }

    public function testAddNewToExistingCart()
    {
        $cart = new AddtoCart();

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

        $item = [
            'article_id' => 3,
            'quantity' => 4,
        ];

        $cart->add_new_to_existing_cart($item);

        $this->assertCount(3, $_SESSION['cart']);
        $this->assertEquals($item, $_SESSION['cart'][2]);
    }
}
