<?php
// File: signup.php

class Validation
{
	static function clean($str)
	{
		return htmlspecialchars(stripslashes(trim($str)));
	}

	static function name($str)
	{
		return preg_match("/^([a-zA-Z' ]+)$/", $str);
	}

	static function password($str)
	{
		return preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{4,}$/", $str);
	}

	static function match($str1, $str2)
	{
		return $str1 === $str2;
	}
}

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

	function insert($data)
	{
		try {
			$sql = 'INSERT INTO ' . $this->table_name . '(username, password, full_name, email, phone_number, user_address) VALUES(?,?,?,?,?, ?)';
			$stmt = $this->conn->prepare($sql);
			return $stmt->execute($data);
		} catch (PDOException $e) {
			return 0;
		}
	}

	function is_username_unique($username)
	{
		try {
			$sql = 'SELECT username FROM ' . $this->table_name . ' WHERE username=?';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute([$username]);
			return ($stmt->rowCount() > 0) ? 0 : 1;
		} catch (PDOException $e) {
			return 0;
		}
	}
}

// Xử lý hiển thị form và xử lý đăng ký
$fname = $uname = $phone_number = "";
if (isset($_GET["fname"])) {
	$fname = $_GET["fname"];
}
if (isset($_GET["uname"])) {
	$uname = $_GET["uname"];
}
if (isset($_GET["phone_number"])) {
	$phone_number = $_GET["phone_number"];
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = Validation::clean($_POST["username"]);
	$full_name = Validation::clean($_POST["fullname"]);
	$email = Validation::clean($_POST["email"]);
	$phone_number = Validation::clean($_POST["phone_number"]);
	$user_address = Validation::clean($_POST["user_address"]);
	$password = Validation::clean($_POST["password"]);
	$re_password = Validation::clean($_POST["re_password"]);

	$data = "fname=" . $full_name . "&uname=" . $username . "&phone_number=" . $phone_number;

	if (!Validation::name($full_name)) {
		$em = "Invalid full name";
		Util::redirect("signup.php", "error", $em, $data);
	} else if (!Validation::password($password)) {
		$em = "Invalid Password";
		Util::redirect("signup.php", "error", $em, $data);
	} else if (!Validation::match($password, $re_password)) {
		$em = "Password and confirm password do not match";
		Util::redirect("signup.php", "error", $em, $data);
	} else {
		$db = new Database();
		$conn = $db->connect();
		$user = new User($conn);
		if ($user->is_username_unique($username)) {
			$password = password_hash($password, PASSWORD_DEFAULT);
			$user_data = [$username, $password, $full_name, $email, $phone_number, $user_address];
			$res = $user->insert($user_data);
			if ($res) {
				$sm = "Successfully registered!";
				Util::redirect("signup.php", "success", $sm);
			} else {
				$em = "An error occurred during registration.";
				Util::redirect("signup.php", "error", $em, $data);
			}
		} else {
			$em = "The username ($username) is already taken.";
			Util::redirect("signup.php", "error", $em, $data);
		}
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign Up</title>
	<link rel="stylesheet" type="text/css" href="Assets/css/style.css">
</head>

<body>
	<div class="wrapper">
		<div class="form-holder">
			<h2>Create New Account</h2>
			<?php
			if (isset($_GET['error'])) { ?>
				<p class="error"><?= Validation::clean($_GET['error']) ?></p>
			<?php }
			?>
			<?php
			if (isset($_GET['success'])) { ?>
				<p class="success"><?= Validation::clean($_GET['success']) ?></p>
			<?php }
			?>
			<form class="form" action="" method="POST">
				<div class="form-group">
					<input type="text" name="fullname" placeholder="Full name" value="<?= $fname ?>">
				</div>
				<div class="form-group">
					<input type="text" name="username" placeholder="User name" value="<?= $uname ?>">
				</div>
				<div class="form-group">
					<input type="text" name="email" placeholder="Email">
				</div>
				<div class="form-group">
					<input type="text" name="phone_number" placeholder="Phone number" value="<?= $phone_number ?>">
				</div>
				<div class="form-group">
					<input type="text" name="user_address" placeholder="Address">
				</div>
				<div class="form-group">
					<input type="password" name="password" placeholder="Password">
				</div>
				<div class="form-group">
					<input type="password" name="re_password" placeholder="Confirm Password">
				</div>
				<div class="form-group">
					<button type="submit">Sign Up</button>
				</div>
				<div class="form-group text-end">
					You have an account? <a href="login.php">Sign In</a>
				</div>
			</form>
		</div>
	</div>
</body>

</html>