<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../logging/log.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (isset($_POST['uuid']) && $_POST['uuid'] != '') {
	$uuid = $_POST['uuid'];
	$status = $_POST['status'];
	
	$server = DB_HOST;
	$port = DB_PORT;
	$user = DB_USER;
	$pass = DB_PASSWORD;
	$db = DB_NAME;

	$connection = mysqli_connect($server, $user, $pass, $db, $port)
	or die ("Could not connect to server ... \n" . mysqli_error ());
	
	$user = $uuid;
	$uuid = uniqid();
	$sql = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
	$count = mysqli_num_rows($sql);
	if ($count >= 1) {
		echo json_encode(array('response' => 'exists'));
		exit();
	} else {
		session_destroy();
		if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
		$sql = mysqli_query($connection, "INSERT cad_users SET username='$user', password='', uuid='$uuid', level='3', status='1', last_ip='99'");
		if (!isset($_SESSION['cad_user'])) {
			$_SESSION['cad_user'] = $user;
			$_SESSION['cad_level'] = 3;
			$_SESSION['cad_uuid'] = $uuid;
		}
		echo json_encode(array('response' => 'success'));
		logInfo("Registered as unit. UUID: " . $uuid . " ; Name: " . $user, 0);
		exit();
	}
}
?>