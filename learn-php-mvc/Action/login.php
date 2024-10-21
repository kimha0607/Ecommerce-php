<?php
session_start();
include "../Utils/Validation.php";
include "../Utils/Util.php";
include "../Database.php";
include "../Models/User.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $username = Validation::clean($_POST["username"]);
  $password = Validation::clean($_POST["password"]);

  if (!Validation::password($password)) {
    $em = "Invalid Password";
    Util::redirect("../login.php", "error", $em);
  } else {
    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);
    $auth = $user->auth($username, $password);
    if ($auth) {
      $user_data = $user->getUser();
      setcookie('user_id', $user_data['id'], time() + (86400 * 30), "/");
      $sm = "logged in!";
      Util::redirect("../index.php", "success", $sm);
    } else {
      $em = "Incorrect username or password";
      Util::redirect("../login.php", "error", $em);
    }
  }
} else {
  $em = "An error occurred";
  Util::redirect("../login.php", "error", $em);
}