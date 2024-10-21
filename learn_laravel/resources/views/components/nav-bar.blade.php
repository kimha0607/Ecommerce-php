<div class="nav-bar">
  <ul>
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('products.index') }}">Product</a></li>
    <li><a href="{{ route('cart.index') }}">Cart</a></li>
    <li><a href="{{ route('order.index') }}">Order</a></li>
    <li><a href="{{ route('logout.index') }}">Logout</a></li>
  </ul>
</div>

<style>
  .nav-bar {
    background-color: #333;
    overflow: hidden;
  }

  .nav-bar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
  }

  .nav-bar ul li {
    float: left;
  }

  .nav-bar ul li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
  }

  .nav-bar ul li a:hover {
    background-color: #ddd;
    color: black;
  }
</style>