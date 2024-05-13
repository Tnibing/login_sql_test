<?php

	session_start();

	include("../html/home.html");

	$user = $_SESSION["user"];
	$password = $_SESSION["password"];

	echo "<br>Hello {$user}, your password is {$password}";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		session_destroy();

		header("Location: index.php");
	}

?>
