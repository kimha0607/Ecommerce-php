<?php
session_start();

// Database class
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

// Utility class
class Util
{
	static function redirect($location, $type, $em, $data = "")
	{
		header("Location: $location?$type=$em&$data");
		exit;
	}
}

// User class
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
			} else {
				return 0;
			}
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
			return $stmt->rowCount() === 0 ? 1 : 0;
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
				$db_password = $user["password"];
				if (password_verify($password, $db_password)) {
					$this->username = $user["username"];
					$this->id = $user["id"];
					$this->phone_number = $user["phone_number"];
					$this->full_name = $user["full_name"];
					return 1;
				}
			}
			return 0;
		} catch (PDOException $e) {
			return 0;
		}
	}

	function getUser()
	{
		return [
			'id' => $this->id,
			'username' => $this->username,
			'full_name' => $this->full_name,
			'phone_number' => $this->phone_number,
			'user_address' => $this->user_address,
			'email' => $this->email
		];
	}
}

// Main logic
$db = new Database();
$conn = $db->connect();
$user = new User($conn);

if (isset($_COOKIE['user_id'])) {
	$user->init($_COOKIE['user_id']);
	$user_data = $user->getUser();
	?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Home Page</title>
		<link rel="stylesheet" type="text/css" href="Assets/css/style.css">
	</head>

	<body>
		<?php include 'navbar.php'; ?>
		<div class="wrapper">
			<div class="form-holder">
				<h2>Welcome <?= htmlspecialchars($user_data['full_name']) ?> !</h2>
				<form class="form" action="logout.php" method="GET">
					<h4>Username: <?= htmlspecialchars($user_data['username']) ?> !</h4>
				</form>
			</div>
		</div>
	</body>

	</html>
	<?php
} else {
	$em = "First login ";
	Util::redirect("login.php", "error", $em);
}
?>