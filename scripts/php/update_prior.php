<?php
require_once(__DIR__ . "/../../config.php");
if (isset($_POST['status'])) {
	$status = $_POST['status'];
	
	$server = DB_HOST;
	$port = DB_PORT;
	$user = DB_USER;
	$pass = DB_PASSWORD;
	$db = DB_NAME;

	$connection = mysqli_connect($server, $user, $pass, $db, $port)
	or die ("Could not connect to server ... \n" . mysqli_error ());
	
	$sql = @mysqli_query($connection, "UPDATE cad_extra SET is_priority='$status' WHERE priority='0'");
}
?>