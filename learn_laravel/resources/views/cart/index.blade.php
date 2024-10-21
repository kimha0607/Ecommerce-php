<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>
  <link rel="stylesheet" href="{{ asset('css/cart.css') }}">

</head>

<body>
  <x-nav-bar />
  <div class="cart-container">
    <h1>Cart</h1>


    @if ($cart && count($cart) > 0)
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
      @foreach ($cart as $product_id => $item)
      <tr>
      <td>{{ htmlspecialchars($item['product_name']) }}</td>
      <td>{{ number_format($item['price']) }}đ</td>
      <td>{{ $item['quantity'] }}</td>
      <td>{{ number_format($item['price'] * $item['quantity']) }}đ</td>
      <td>
      <form action="{{ route('cart.destroy', $product_id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="remove-btn">Remove</button>
      </form>
      </td>
      </tr>
    @endforeach
      </tbody>
    </table>

    <div class="cart-summary">
      <h2>Total Amount: {{ number_format($totalAmount) }}đ</h2>
      <a href="{{ route('order.index') }}"><button class="checkout-btn">Proceed to Checkout</button></a>
    </div>
  @else
  <p>Giỏ hàng của bạn đang trống.</p>
@endif
  </div>
</body>

</html>