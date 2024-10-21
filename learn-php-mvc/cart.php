<?php
session_start();

if (!isset($_COOKIE['user_id'])) {
  $em = "Please login first";
  Util::redirect("login.php", "error", $em);
}

include "Models/Product.php";
include "Database.php";

$db = new Database();
$db_conn = $db->connect();
$product = new Product($db_conn);

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['product_id'])) {
  $product_id = $_GET['product_id'];
  unset($_SESSION['cart'][$product_id]);
}

$total_amount = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>
  <link rel="stylesheet" type="text/css" href="Assets/css/cart.css">
</head>

<body>
  <?php include 'navbar.php'; ?>
  <div class="cart-container">
    <h1>Cart</h1>
    <?php if (empty($_SESSION['cart'])): ?>
      <p>Your cart is empty.</p>
    <?php else: ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
            <?php
            $product_data = $product->getProductById($product_id);
            if ($product_data) {
              $product_price = $product_data['price'];
              $total = $product_price * $quantity;
              $total_amount += $total;
              ?>
              <tr>
                <td><?php echo htmlspecialchars($product_data['product_name']); ?></td>
                <td><?php echo number_format($product_price) . "đ"; ?></td>
                <td><?php echo $quantity; ?></td>
                <td><?php echo number_format($total) . "đ"; ?></td>
                <td>
                  <a href="cart.php?action=remove&product_id=<?php echo $product_id; ?>" class="remove-btn">Remove</a>
                </td>
              </tr>
              <?php
            }
            ?>
          <?php endforeach;
          ?>
        </tbody>
      </table>
      <div class="cart-summary">
        <h2>Total Amount: <?php echo number_format($total_amount) . "đ"; ?></h2>
        <a href="order.php"><button class="checkout-btn">Proceed to Checkout</button></a>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>