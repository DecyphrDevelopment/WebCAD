<?php
require_once(__DIR__ . "/../../config.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['cad_uuid'])) {
	header('Location: ..');
}
else {
	$server = DB_HOST;
	$port = DB_PORT;
	$user = DB_USER;
	$pass = DB_PASSWORD;
	$db = DB_NAME;

	$connection = mysqli_connect($server, $user, $pass, $db, $port)
	or die ("Could not connect to server ... \n" . mysqli_error ());
	
	$uuid = $_SESSION['cad_uuid'];
	$result = @mysqli_query($connection, "SELECT status FROM cad_users WHERE uuid='$uuid'");
	$value = mysqli_fetch_object($result);
	$status = $value->status;
	echo $status;
}
?>