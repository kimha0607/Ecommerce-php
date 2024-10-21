<?php
session_start();

class Util
{
  static function redirect($location, $type, $em, $data = "")
  {
    header("Location: $location?$type=$em&$data");
    exit;
  }
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

class User
{
  private $conn;
  private $table_name = "Users";
  private $id, $full_name, $phone_number, $username, $user_address, $email;

  function __construct($db_conn)
  {
    $this->conn = $db_conn;
  }

  function init($id)
  {
    $stmt = $this->conn->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch();
      $this->id = $user['id'];
      $this->username = $user['username'];
      $this->phone_number = $user['phone_number'];
      $this->full_name = $user['full_name'];
      $this->user_address = $user['user_address'];
      $this->email = $user['email'];
      return 1;
    }
    return 0;
  }

  function getUser()
  {
    return [
      'id' => $this->id,
      'username' => $this->username,
      'full_name' => $this->full_name,
      'phone_number' => $this->phone_number,
      'user_address' => $this->user_address,
      'email' => $this->email,
    ];
  }
}

class Product
{
  private $conn;
  private $table_name = "Products";

  function __construct($db_conn)
  {
    $this->conn = $db_conn;
  }

  public function getProductById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}

class Order
{
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function createOrder($user_id, $total_amount)
  {
    $stmt = $this->conn->prepare("INSERT INTO Orders (user_id, total_amount) VALUES (:user_id, :total_amount)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':total_amount', $total_amount);
    if ($stmt->execute()) {
      return $this->conn->lastInsertId();
    }
    return false;
  }

  public function addOrderDetail($order_id, $product_id, $quantity, $price)
  {
    $total_amount = $quantity * $price;
    $stmt = $this->conn->prepare("INSERT INTO Order_Details (order_id, product_id, quantity, price, total_amount) VALUES (:order_id, :product_id, :quantity, :price, :total_amount)");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':total_amount', $total_amount);
    return $stmt->execute();
  }
}

// Main logic
$db = new Database();
$db_conn = $db->connect();
$product = new Product($db_conn);
$order = new Order($db_conn);
$user = new User($db_conn);

if (!isset($_COOKIE['user_id'])) {
  Util::redirect("login.php", "error", "Please login first");
}

$user->init($_COOKIE['user_id']);
$user_data = $user->getUser();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  $em = "Your cart is empty. Please add items to your cart before checking out.";
}

$total_amount = 0;

foreach ($_SESSION['cart'] as $product_id => $quantity) {
  $product_data = $product->getProductById($product_id);
  if ($product_data) {
    $total_amount += $product_data['price'] * $quantity;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $order_id = $order->createOrder($_COOKIE['user_id'], $total_amount);
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_data = $product->getProductById($product_id);
    if ($product_data) {
      $order->addOrderDetail($order_id, $product_id, $quantity, $product_data['price']);
    }
  }
  unset($_SESSION['cart']);
  $success_msg = "Your order has been placed successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmation</title>
  <link rel="stylesheet" type="text/css" href="Assets/css/order.css">
</head>

<body>
  <?php include 'navbar.php'; ?>
  <div class="order-container">
    <h1>Order Confirmation</h1>
    <div class="user-info">
      <h2>Your Information</h2>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user_data['full_name']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_data['phone_number']); ?></p>
      <p><strong>Address:</strong> <?php echo htmlspecialchars($user_data['user_address']); ?></p>
    </div>

    <p>Total Amount: <?php echo number_format($total_amount) . "đ"; ?></p>

    <form method="POST" action="">
      <button type="submit" class="checkout-btn">Confirm Order</button>
    </form>
  </div>
</body>

</html>