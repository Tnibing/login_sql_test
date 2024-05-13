<?php

	session_start();

	include("../html/index.html");

	include("./database.php");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (isset($_POST["login"])) {

			$mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);

			$pass = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

			if (!empty($mail) && !empty($pass)) {

				$query = "SELECT password FROM users WHERE user_mail = '{$mail}'";

				try{
					$result = mysqli_query($connection, $query);

					if ($result) {
						$row = mysqli_fetch_assoc($result);

						if ($row) {

							if (password_verify($pass, $row["password"])) {

								$_SESSION["user"] = $mail;
								$_SESSION["password"] = $pass;
				
								header("Location: home.php");

							}
							else {
								echo "Incorrect password!<br>";
							}

						}
						else {
							echo "User not registered!<br>";
						}
					}
					else {
						echo "Error executing query!<br>";
					}
				}
				catch (mysqli_sql_exception) {
					echo "No user with the mail {$mail} registered!<br>";
				}

			}

			else {
				echo "Please, enter a valid mail and password!";
			}

		} elseif (isset($_POST["register"])) {

			$mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);
			$pass = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

			if (!empty($mail) && !empty($pass)) {

				$user = $mail;

				$hash = password_hash($pass, PASSWORD_DEFAULT);
	
				$query = "INSERT INTO users (user_mail, password) VALUES ('$user', '$hash')";
	
				try {
					// This connection param comes from the include("./database.php");
					// The editor can't see it hence it markes it as an error, it does work!
					mysqli_query($connection, $query);
					echo "User registered!<br>";
				} catch (mysqli_sql_exception) {
					echo "That email is already registered!";
				}
	
				mysqli_close($connection);
			}
			else {
				echo "Error, you need a valid mail and password to register!<br>";
			}
		}
	}
?>
