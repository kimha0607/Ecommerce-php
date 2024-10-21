<?php
session_start();

function redirect($location, $type, $em)
{
  header("Location: $location?$type=$em");
  exit;
}

session_unset();
session_destroy();

$em = "Logged out!";
redirect("login.php", "error", $em);
?>