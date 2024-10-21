<?php

include_once 'Models/Product.php';
include_once 'Models/Order.php';
include_once 'Models/User.php';
include_once 'Database.php';
include "Utils/Util.php";

class OrderController
{
  private $product;
  private $order;
  private $user;

  public function __construct()
  {
    $db = new Database();
    $db_conn = $db->connect();
    $this->product = new Product($db_conn);
    $this->order = new Order($db_conn);
    $this->user = new User($db_conn);

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

  public function getUserData()
  {
    if (isset($_COOKIE['user_id'])) {
      $this->user->init($_COOKIE['user_id']);
      return $this->user->getUser();
    }
    return null;
  }

  public function getTotalAmount()
  {
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
      $product_data = $this->product->getProductById($product_id);
      if ($product_data) {
        $total_amount += $product_data['price'] * $quantity;
      }
    }
    return $total_amount;
  }

  public function createOrder()
  {
    $total_amount = $this->getTotalAmount();
    $user_id = $_COOKIE['user_id'];

    // Tạo đơn hàng mới
    $order_id = $this->order->createOrder($user_id, $total_amount);

    // Thêm thông tin chi tiết đơn hàng
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
      $product_data = $this->product->getProductById($product_id);
      if ($product_data) {
        $this->order->addOrderDetail($order_id, $product_id, $quantity, $product_data['price']);
      }
    }

    // Xóa giỏ hàng sau khi đặt hàng thành công
    unset($_SESSION['cart']);
    return "Your order has been placed successfully!";
  }

  public function isCartEmpty()
  {
    return !isset($_SESSION['cart']) || empty($_SESSION['cart']);
  }
}
