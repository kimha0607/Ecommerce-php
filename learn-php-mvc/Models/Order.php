<?php
class Order
{
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function createOrder($user_id, $total_amount)
  {
    $query = "INSERT INTO Orders (user_id, total_amount) VALUES (:user_id, :total_amount)";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':total_amount', $total_amount);

    if ($stmt->execute()) {
      return $this->conn->lastInsertId();
    } else {
      return false;
    }
  }

  public function addOrderDetail($order_id, $product_id, $quantity, $price)
  {
    $total_amount = $quantity * $price;
    $query = "INSERT INTO Order_Details (order_id, product_id, quantity, price, total_amount) VALUES (:order_id, :product_id, :quantity, :price, :total_amount)";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':total_amount', $total_amount);

    return $stmt->execute();
  }
}
