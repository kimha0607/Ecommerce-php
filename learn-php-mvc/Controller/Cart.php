<?php

include_once 'Models/Product.php';
include_once 'Database.php';
include "Utils/Util.php";

class CartController
{
  private $product;

  public function __construct()
  {
    $db = new Database();
    $db_conn = $db->connect();
    $this->product = new Product($db_conn);

    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }
  }

  public function checkUserLogin()
  {
    if (!isset($_COOKIE['user_id'])) {
      $em = "Please login first";
      Util::redirect("login.php", "error", $em);
    }
  }

  public function handleRemoveProduct()
  {
    if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['product_id'])) {
      $product_id = $_GET['product_id'];
      unset($_SESSION['cart'][$product_id]);
    }
  }

  public function getCartDetails()
  {
    $total_amount = 0;
    $cart_items = [];

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
      $product_data = $this->product->getProductById($product_id);

      if ($product_data) {
        $product_price = $product_data['price'];
        $total = $product_price * $quantity;
        $total_amount += $total;

        $cart_items[] = [
          'product_name' => $product_data['product_name'],
          'price' => $product_price,
          'quantity' => $quantity,
          'total' => $total,
          'product_id' => $product_id
        ];
      }
    }

    return [
      'cart_items' => $cart_items,
      'total_amount' => $total_amount
    ];
  }
}
