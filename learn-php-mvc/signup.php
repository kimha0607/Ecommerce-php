<?php
include "Utils/Validation.php";

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
			<form class="form" action="Action/signup.php" method="POST">
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
					<input type="text" name="password" placeholder="Password">
				</div>
				<div class="form-group">
					<input type="text" name="re_password" placeholder="Confirm Password">
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