<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/order.css">
  <title>Order Confirmation</title>
</head>

<body>
  <x-nav-bar />
  <div class="order-container">
    <h1>Order Confirmation</h1>
    <div class="user-info">
      <h2>Your Information</h2>
      <p><strong>Name:</strong> {{ $user->full_name }}</p>
      <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
      <p><strong>Address:</strong> {{ $user->user_address }}</p>
    </div>
    <p>Total Amount: {{ number_format($totalAmount) }}đ</p>
    <form method="POST" action="{{ route('order.placeOrder') }}">
      @csrf
      <button type="submit" class="checkout-btn">Confirm Order</button>
    </form>
  </div>
</body>

</html>