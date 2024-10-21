<?php
session_start();

class Validation
{
	static function clean($str)
	{
		$str = trim($str);
		$str = stripslashes($str);
		$str = htmlspecialchars($str);
		return $str;
	}

	static function password($str)
	{
		$password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{4,}$/";
		if (preg_match($password_regex, $str))
			return true;
		else
			return false;
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
	private $conn;
	private $table_name = 'Users';

	private $id;
	private $username;
	private $full_name;
	private $phone_number;
	private $user_address;
	private $email;

	public function __construct($db_conn)
	{
		$this->conn = $db_conn;
	}

	public function auth($username, $password)
	{
		try {
			$sql = 'SELECT * FROM ' . $this->table_name . ' WHERE username=?';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute([$username]);

			if ($stmt->rowCount() == 1) {
				$user = $stmt->fetch();
				if (password_verify($password, $user['password'])) {
					$this->id = $user['id'];
					$this->username = $user['username'];
					$this->full_name = $user['full_name'];
					$this->phone_number = $user['phone_number'];
					$this->user_address = $user['user_address'];
					$this->email = $user['email'];
					return true;
				}
			}
			return false;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function getUser()
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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = Validation::clean($_POST["username"]);
	$password = Validation::clean($_POST["password"]);

	if (!Validation::password($password)) {
		Util::redirect("login.php", "error", "Invalid Password");
	} else {
		$db = new Database();
		$conn = $db->connect();
		$user = new User($conn);
		if ($user->auth($username, $password)) {
			$user_data = $user->getUser();
			setcookie('user_id', $user_data['id'], time() + (86400 * 30), "/");
			Util::redirect("index.php", "success", "logged in!");
		} else {
			Util::redirect("login.php", "error", "Incorrect username or password");
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="Assets/css/style.css">
</head>

<body>
	<div class="wrapper">
		<div class="form-holder">
			<h2>SIGN IN</h2>
			<?php if (isset($_GET['error'])) { ?>
				<p class="error"><?= Validation::clean($_GET['error']) ?></p>
			<?php } ?>
			<form class="form" action="" method="POST">
				<div class="form-group">
					<input type="text" name="username" placeholder="User name" required>
				</div>
				<div class="form-group">
					<input type="password" name="password" placeholder="Password" required>
				</div>
				<div class="form-group">
					<button type="submit">Login</button>
				</div>
				<div class="form-group text-end">
					You don't have an account? <a href="signup.php">Sign Up</a>
				</div>
			</form>
		</div>
	</div>
</body>

</html>