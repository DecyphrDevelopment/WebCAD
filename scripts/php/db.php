<?php
require_once(__DIR__ . "/../../config.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['cad_user'])) {
	header('Location: ../../ums/login');
}
else {
	if($_SESSION['cad_level'] != 0 && $_SESSION['cad_level'] != 1 && $_SESSION['cad_level'] != 2) {
		header('Location: ../../ums/login');
	} else {
		$server = DB_HOST;
		$port = DB_PORT;
		$user = DB_USER;
		$pass = DB_PASSWORD;
		$db = DB_NAME;

		$connection = mysqli_connect($server, $user, $pass, $db, $port)
		or die ("Could not connect to server ... \n" . mysqli_error ());
		
		$sql = @mysqli_query($connection, "SELECT * FROM cad_users");
		
		$rows = array();
		while($r = mysqli_fetch_assoc($sql)) {
			$rows[] = $r;
		}
		
		echo json_encode($rows);
	}
}
?>