<?php
session_start();
include_once 'Controller/Cart.php';

$cartController = new CartController();

$cartController->checkUserLogin();

$cartController->handleRemoveProduct();

$cartData = $cartController->getCartDetails();
$cart_items = $cartData['cart_items'];
$total_amount = $cartData['total_amount'];
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
    <?php if (empty($cart_items)): ?>
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
          <?php foreach ($cart_items as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['product_name']); ?></td>
              <td><?php echo number_format($item['price']) . "đ"; ?></td>
              <td><?php echo $item['quantity']; ?></td>
              <td><?php echo number_format($item['total']) . "đ"; ?></td>
              <td>
                <a href="cart.php?action=remove&product_id=<?php echo $item['product_id']; ?>" class="remove-btn">Remove</a>
              </td>
            </tr>
          <?php endforeach; ?>
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