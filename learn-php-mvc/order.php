<?php
session_start();

if (!isset($_COOKIE['user_id'])) {
  $em = "Please login first";
  Util::redirect("login.php", "error", $em);
}

include "Models/Product.php";
include "Models/Order.php";
include "Models/User.php";
include "Database.php";


$db = new Database();
$db_conn = $db->connect();
$product = new Product($db_conn);
$order = new Order($db_conn);
$user = new User($db_conn);
$user->init($_COOKIE['user_id']);
$user_data = $user->getUser();

if (!isset($_SESSION['cart']) || empty($_COOKIE['cart'])) {
  $em = "Your cart is empty. Please add items to your cart before checking out.";
}

$total_amount = 0;

foreach ($_SESSION['cart'] as $product_id => $quantity) {
  $product_data = $product->getProductById($product_id);
  if ($product_data) {
    $total_amount += $product_data['price'] * $quantity;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_id = $_COOKIE['user_id'];

  $order_id = $order->createOrder($user_id, $total_amount);

  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_data = $product->getProductById($product_id);
    if ($product_data) {
      $order->addOrderDetail($order_id, $product_id, $quantity, $product_data['price']);
      $total_amount = 0;
    }
  }

  unset($_SESSION['cart']);

  $success_msg = "Your order has been placed successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmation</title>
  <link rel="stylesheet" type="text/css" href="Assets/css/order.css">
</head>

<body>
  <?php include 'navbar.php'; ?>
  <div class="order-container">
    <h1>Order Confirmation</h1>

    <div class="user-info">
      <h2>Your Information</h2>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user_data['full_name']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_data['phone_number']); ?></p>
      <p><strong>Address:</strong> <?php echo htmlspecialchars($user_data['user_address']); ?></p>
    </div>

    <p>Total Amount: <?php echo number_format($total_amount) . "đ"; ?></p>

    <form method="POST" action="order.php">
      <button type="submit" class="checkout-btn">Confirm Order</button>
    </form>
  </div>
</body>

</html>