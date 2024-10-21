<?php

class User
{
  private $table_name;
  private $conn;

  private $id;
  private $full_name;
  private $phone_number;
  private $username;

  private $user_address;


  private $email;



  function __construct($db_conn)
  {
    $this->conn = $db_conn;
    $this->table_name = "Users";
  }
  function init($id)
  {
    try {
      $sql = 'SELECT * FROM ' . $this->table_name . ' WHERE id=?';
      $stmt = $this->conn->prepare($sql);
      $res = $stmt->execute([$id]);
      if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        $this->username = $user['username'];
        $this->id = $user['id'];
        $this->phone_number = $user['phone_number'];
        $this->full_name = $user['full_name'];
        $this->user_address = $user['user_address'];
        $this->email = $user['email'];
        return 1;
      } else
        return 0;
    } catch (PDOException $e) {
      return 0;
    }
  }

  function insert($data)
  {
    try {
      $sql = 'INSERT INTO ' . $this->table_name . '(username, password, full_name, email, phone_number, user_address) VALUES(?,?,?,?,?, ?)';
      $stmt = $this->conn->prepare($sql);
      $res = $stmt->execute($data);
      return $res;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return 0;
    }
  }
  function is_username_unique($username)
  {
    try {
      $sql = 'SELECT username FROM ' . $this->table_name . ' WHERE username=?';
      $stmt = $this->conn->prepare($sql);
      $res = $stmt->execute([$username]);
      if ($stmt->rowCount() > 0)
        return 0;
      else
        return 1;
    } catch (PDOException $e) {
      return 0;
    }
  }

  function auth($username, $password)
  {
    try {
      $sql = 'SELECT * FROM ' . $this->table_name . ' WHERE username=?';
      $stmt = $this->conn->prepare($sql);
      $res = $stmt->execute([$username]);

      if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        $db_username = $user["username"];
        $db_password = $user["password"];
        $db_id = $user["id"];
        $db_phone_number = $user["phone_number"];
        $db_full_name = $user["full_name"];

        if ($db_username === $username) {
          if (password_verify($password, $db_password)) {
            $this->username = $db_username;
            $this->id = $db_id;
            $this->phone_number = $db_phone_number;
            $this->full_name = $db_full_name;
            return 1;
          } else
            return 0;
        } else
          return 0;
      } else
        return 0;
    } catch (PDOException $e) {
      return 0;
    }
  }

  function getUser()
  {
    $data = array(
      'id' => $this->id,
      'username' => $this->username,
      'full_name' => $this->full_name,
      'phone_number' => $this->phone_number,
      'user_address' => $this->user_address,
      'email' => $this->email
    );
    return $data;
  }

}