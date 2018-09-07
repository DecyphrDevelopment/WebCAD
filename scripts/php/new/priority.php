<?php

require_once(__DIR__ . "/../../../config.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port)
or die ("Could not connect to server ... \n" . mysqli_error ());

if (isset($_GET['update']) && isset($_POST['status'])) {
	$status = $_POST['status'];
	$sql = @mysqli_query($connection, "UPDATE cad_extra SET is_priority='$status' WHERE priority='0'");
	echo "update_success";
}
if (isset($_GET['get'])) {
	$result = @mysqli_query($connection, "SELECT is_priority FROM cad_extra WHERE priority='0'");
	$value = mysqli_fetch_object($result);
	$status = $value->is_priority;
	echo $status;
}
?>
