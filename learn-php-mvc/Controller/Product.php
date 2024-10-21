<?php

include_once 'Models/Product.php';
include_once 'Database.php';

class ProductController
{
  private $product;

  public function __construct()
  {
    $db = new Database();
    $db_conn = $db->connect();
    $this->product = new Product($db_conn);

    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    if (!isset($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
  }

  public function handleAddToCart()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
      $product_id = $_POST['product_id'];
      $product_data = $this->product->getProductById($product_id);

      if ($product_data) {
        if (isset($_SESSION['cart'][$product_id])) {
          $_SESSION['cart'][$product_id]++;
        } else {
          $_SESSION['cart'][$product_id] = 1;
        }
      }
    }
  }

  public function getProductListAndPagination()
  {
    $total_products = $this->product->getTotalProducts();
    $limit = 10;
    $total_pages = ceil($total_products / $limit);
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $productList = $this->product->getProductList($limit, $offset);

    return [
      'productList' => $productList,
      'total_pages' => $total_pages,
      'current_page' => $page
    ];
  }
}
