<?php

	session_start();

	include("../html/home.html");

	$user = $_SESSION["user"];
	$password = $_SESSION["password"];

	echo "<br>Hello {$user}, your password is {$password}";

	// The post method gets triggered when the user clicks on the log out button
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		session_destroy();

		header("Location: index.php");
	}

?>
