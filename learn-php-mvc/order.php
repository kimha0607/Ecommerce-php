<?php
session_start();
include_once 'Controller/Order.php';

$orderController = new OrderController();

$orderController->checkUserLogin();

if ($orderController->isCartEmpty()) {
  $em = "Your cart is empty. Please add items to your cart before checking out.";
  Util::redirect("cart.php", "error", $em);
}

$user_data = $orderController->getUserData();

$total_amount = $orderController->getTotalAmount();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $success_msg = $orderController->createOrder();
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

    <?php if (isset($success_msg)): ?>
      <p class="success-msg"><?php echo htmlspecialchars($success_msg); ?></p>
    <?php endif; ?>

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