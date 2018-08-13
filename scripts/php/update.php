<?php
require_once(__DIR__ . "/../../config.php");
if (isset($_POST['uuid']) && isset($_POST['status'])) {
	$uuid = $_POST['uuid'];
	$status = $_POST['status'];
	
	$server = DB_HOST;
	$port = DB_PORT;
	$user = DB_USER;
	$pass = DB_PASSWORD;
	$db = DB_NAME;

	$connection = mysqli_connect($server, $user, $pass, $db, $port)
	or die ("Could not connect to server ... \n" . mysqli_error ());
	
	if ($status == 99) {
		$sql = mysqli_query($connection, "DELETE FROM cad_users WHERE uuid='$uuid'");
			echo json_encode(array('response' => 'success'));
			exit();
	}
	if ($status == 101) {
		$user = $uuid;
		$uuid = uniqid();
		$sql = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
		$count = mysqli_num_rows($sql);
		if ($count >= 1) {
			echo json_encode(array('response' => 'exists'));
			exit();
		} else {
			$sql = mysqli_query($connection, "INSERT cad_users SET username='$user', password='', uuid='$uuid', level='3', status='1'");
			echo json_encode(array('response' => 'success'));
			exit();
		}
	} else {
		$sql = @mysqli_query($connection, "UPDATE cad_users SET status='$status' WHERE uuid='$uuid'");
	}
}
?>