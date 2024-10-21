<?php

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
