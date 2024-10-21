<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/products.css') }}">
  <title>Danh Sách Sản Phẩm</title>
</head>

<body>
  <div>
    <x-nav-bar />
    @if(session('success'))
    <div class="alert success">
      {{ session('success') }}
    </div>
  @endif
    <div class="container">
      <div class="grid-container">
        @foreach ($products as $product)
      <div class="card">
        <h3>{{ htmlspecialchars($product->product_name) }}</h3>
        <p>{{ htmlspecialchars($product->description) }}</p>
        <p class="price">{{ number_format($product->price) }}đ</p>
        <form action="{{ route('products.addToCart', $product) }}" method="POST" style="display:inline;">
        @csrf
        <button class="btn delete" type="submit">Add to Cart</button>
        </form>
      </div>
    @endforeach
      </div>

    </div>
    <div class="d-flex custom-pagination">
      {{ $products->links('pagination::bootstrap-4') }}
    </div>
  </div>
</body>

</html>