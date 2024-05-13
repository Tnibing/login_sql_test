<?php

	$db_server = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "php_test";
	$connection = "";

	try{
		$connection = mysqli_connect($db_server, 
								 	 							 $db_user, 
								 	 							 $db_pass, 
								 	 							 $db_name);

		echo "Connected to DDBB<br>";
	}
	catch (mysqli_sql_exception){
		echo "Could NOT connect<br>";
	}

?>
