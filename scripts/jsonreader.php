<?php
	$DatabaseServer   = "localhost";
	$DatabaseUser     = "s1cad";
	$DatabasePassword = "Usf7yS0Lbf44Cd8Z";
	$DatabaseName     = "s1cad";
 
	$mysqli = new mysqli($DatabaseServer, $DatabaseUser, $DatabasePassword, $DatabaseName);
	if ($mysqli->connect_errno) 
	{
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
    }
    if (isset($_GET['users'])) {
        $result = $mysqli->query("SELECT * FROM cad_users;");
    }
    if (isset($_GET['logs'])) {
        $result = $mysqli->query("SELECT * FROM action_log;");
    }
	while($row = mysqli_fetch_assoc($result)) 
	{
		$rows[] = $row;
	}
	$result->close();
	$mysqli->close();
	print(json_encode($rows, JSON_NUMERIC_CHECK));
?>