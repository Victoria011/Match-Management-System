<?php
	$db_host = 'localhost';
	$db_username = 'zy18600';
	$db_password = 'vic1997';
	$db_database = 'db_zy18600';

	// cs-linux specific connection string
	$dsn = 'mysql:dbname='.$db_database.';host='.$db_host;
	// Open a connection using credentials
	$pdo = new PDO($dsn,$db_username,$db_password);
	// Enable exceptions (so we can identify failures)
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
?>
