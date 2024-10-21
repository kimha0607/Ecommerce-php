<?php  

class Validation{
	static function clean($str){
		$str = trim($str);
		$str = stripcslashes($str);
		$str = htmlspecialchars($str);
		return $str;
	}
    
    static function name($str){
		$name_regex = "/^([a-zA-Z' ]+)$/";
		if (preg_match($name_regex, $str)) return true;
    else return false;
	}
	static function password($str){
		$password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{4,}$/"; 

		if (preg_match($password_regex, $str)) return true;
    else return false;
	}
	static function match($str1, $str2){
		if ($str1 === $str2) return true;
    else return false;
	}
}