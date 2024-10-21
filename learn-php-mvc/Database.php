<?php

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
