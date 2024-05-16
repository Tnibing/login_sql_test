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
		// The editor can't see it hence it marks it as an error, it does work!
		global $connection;

		$mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);

		$pass = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

		if (empty($mail) || empty($pass)) {
			echo '<script type="text/javascript">alert("Please enter a valid email and password!")</script>';
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
			echo '<script type="text/javascript">alert("User not registered!\nPlease register to log in!")</script>';
			return;
		}

		if (!password_verify($pass, $row["password"])) {
			echo '<script type="text/javascript">alert("Incorrect password!")</script>';
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
			echo '<script type="text/javascript">alert("Please, enter a valid email and password!")</script>';
			return;
		}

		$hash = password_hash($pass, PASSWORD_DEFAULT);

		$query = "INSERT INTO users (user_mail, password) VALUES ('$mail', '$hash')";

		try {
			mysqli_query($connection, $query);

			echo '<script type="text/javascript">alert("User registered!")</script>';
		} catch (mysqli_sql_exception) {
			echo '<script type="text/javascript">alert("That email is already registered!")</script>';

			return;
		}

		mysqli_close($connection);
	}
?>
