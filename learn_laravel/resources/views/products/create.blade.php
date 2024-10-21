<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="/css/products.css">
  <title>Thêm Sản Phẩm</title>
</head>

<body>
  <div class="container">
    <h1 class="title">Thêm Sản Phẩm</h1>
    <form action="{{ route('products.store') }}" method="POST" class="product-form">
      @csrf
      <div class="form-group">
        <label for="name">Tên Sản Phẩm:</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" name="price" step="0.01" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="description">Mô Tả:</label>
        <textarea name="description" class="form-control"></textarea>
      </div>
      <div class="bottom-group">
        <a href="{{ route('products.index') }}" class="btn back-btn">Trở lại</a>
        <button type="submit" class="btn">Thêm Sản Phẩm</button>
      </div>
    </form>
  </div>
</body>

</html>