<?php
session_start();
include_once 'Controller/Product.php';

$productController = new ProductController();

$productController->handleAddToCart();

$data = $productController->getProductListAndPagination();
$productList = $data['productList'];
$total_pages = $data['total_pages'];
$page = $data['current_page'];

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product Page</title>
  <link rel="stylesheet" type="text/css" href="Assets/css/product.css">
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="container">
    <div class="grid-container">
      <?php foreach ($productList as $product) { ?>
        <div class="card">
          <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
          <p><?php echo htmlspecialchars($product['description']); ?></p>
          <p class="price"><?php echo number_format($product['price']) . "đ"; ?></p>

          <form method="POST" action="product.php">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit">Add to Cart</button>
          </form>
        </div>
      <?php } ?>
    </div>
  </div>

  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="?page=<?php echo $page - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
        <?php echo $i; ?>
      </a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
      <a href="?page=<?php echo $page + 1; ?>">Next</a>
    <?php endif; ?>
  </div>
</body>

</html>