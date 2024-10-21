<?php
include "../Utils/Validation.php";
include "../Utils/Util.php";
include "../Database.php";
include "../Models/User.php";

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
		Util::redirect("../signup.php", "error", $em, $data);
	} else if (!Validation::password($password)) {
		$em = "Invalid Password";
		Util::redirect("../signup.php", "error", $em, $data);
	} else if (!Validation::match($password, $re_password)) {
		$em = "Password and confirm password not match";
		Util::redirect("../signup.php", "error", $em, $data);
	} else {
		$db = new Database();
		$conn = $db->connect();
		$user = new User($conn);
		if ($user->is_username_unique($username)) {
			// password hash
			$password = password_hash($password, PASSWORD_DEFAULT);
			$user_data = [$username, $password, $full_name, $email, $phone_number, $user_address];
			$res = $user->insert($user_data);
			if ($res) {
				$sm = "Successfully registered!";
				Util::redirect("../signup.php", "success", $sm);
			} else {
				$em = "An error occurred1";
				Util::redirect("../signup.php", "error", $em, $data);
			}
		} else {
			$em = "The username ($username) is already taken";
			Util::redirect("../signup.php", "error", $em, $data);
		}
	}

} else {
	$em = "An error occurred2";
	Util::redirect("../signup.php", "error", $em);
}