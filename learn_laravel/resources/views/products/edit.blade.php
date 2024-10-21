<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="/css/products.css">
  <title>Sửa Sản Phẩm</title>
</head>

<body>
  <div class="container">
    <h1 class="title">Sửa Sản Phẩm</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" class="product-form">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="name">Tên Sản Phẩm:</label>
        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
      </div>

      <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" name="price" step="0.01" class="form-control" value="{{ $product->price }}" required>
      </div>

      <div class="form-group">
        <label for="description">Mô Tả:</label>
        <textarea name="description" class="form-control">{{ $product->description }}</textarea>
      </div>
      <div class="bottom-group">
        <a href="{{ route('products.index') }}" class="btn back-btn">Trở lại</a>
        <button type="submit" class="btn">Cập Nhật</button>
      </div>
    </form>

  </div>
</body>

</html>