<?php

	session_start();

	include("../html/index.html");

	include("./database.php");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (isset($_POST["login"])) {

			processLogin();

		} elseif (isset($_POST["register"])) {

			processRegister();
		}
	}

	function processLogin() {

		// This connection param comes from the include("./database.php");
		// The editor can't see it hence it markes it as an error, it does work!
		global $connection;

		$mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);

		$pass = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

		if (empty($mail) || empty($pass)) {
			echo "Please enter a valid email and password!<br>";
			return;

		}

		$query = "SELECT password FROM users WHERE user_mail = '{$mail}'";

		// DOES NOT NEED a try catch block, mysql exceptions are not enabled
		$result = mysqli_query($connection, $query);
	
		if (!$result) {
			echo "Error executing the query!<br>";

			return;
		}

		$row = mysqli_fetch_assoc($result);

		if (!$row) {
			echo "User not registered!<br>Please register to log in!<br>";
			return;
		}

		if (!password_verify($pass, $row["password"])) {
			echo "Incorrect password!<br>";
			return;
		}

		mysqli_close($connection);

		$_SESSION["user"] = $mail;
		$_SESSION["password"] = $pass;

		header("Location: home.php");
	}

	function processRegister() {

		global $connection;

		$mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);

		$pass = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

		if (empty($mail) || empty($pass)) {
			echo "Please, enter a valid email and password!";
			return;
		}

		$hash = password_hash($pass, PASSWORD_DEFAULT);

		$query = "INSERT INTO users (user_mail, password) VALUES ('$mail', '$hash')";

		try {
			mysqli_query($connection, $query);

			echo "User registered!<br>";
		} catch (mysqli_sql_exception) {
			echo "That email is already registered!<br>";

			return;
		}

		mysqli_close($connection);
	}
?>
