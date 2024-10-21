<?php
session_start();

if (!isset($_COOKIE['user_id'])) {
  $em = "Please login first";
  header("Location: login.php?error=" . urlencode($em));
  exit;
}

class Database
{
  private $host = "localhost";
  private $dbName = "learn-php";
  private $uName = "postgres";
  private $pass = "030177";
  private $conn;

  public function connect()
  {
    $this->conn = null;
    try {
      $this->conn = new PDO('pgsql:host=' . $this->host . ';dbname=' . $this->dbName, $this->uName, $this->pass);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection error: " . $e->getMessage();
    }
    return $this->conn;
  }
}

class Product
{
  private $table_name;
  private $conn;

  function __construct($db_conn)
  {
    $this->conn = $db_conn;
    $this->table_name = "Products";
  }

  public function getAllProducts()
  {
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getProductById($id)
  {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getTotalProducts()
  {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  }

  public function getProductList($limit, $offset)
  {
    $query = "SELECT * FROM " . $this->table_name . " LIMIT :limit OFFSET :offset";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

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