<?php
require_once(__DIR__ . "/../../config.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port)
or die ("Could not connect to server ... \n" . mysqli_error ());
 
if (isset($_POST['action'])) {
	if (isset($_POST['ubid'])) {
		$ubid = $_POST['ubid'];
		if ($_POST['action'] == 99) {
			$sql = mysqli_query($connection, "DELETE FROM cad_bolos WHERE ubid='$ubid'");
			echo json_encode(array('response' => 'success'));
			exit();
		}
	}
	if (isset($_POST['bolo'])) {
		if ($_POST['action'] == 101) {
			$bolo = $_POST['bolo'];
			$ubid = uniqid();
			$sql = mysqli_query($connection, "INSERT cad_bolos SET bolo='$bolo', ubid='$ubid'");
			echo json_encode(array('response' => 'success'));
			exit();
		}
	}
} else {
	$sql = @mysqli_query($connection, "SELECT * FROM cad_bolos");
 
	$rows = array();
	while($r = mysqli_fetch_assoc($sql)) {
	  $rows[] = $r;
	}

	echo json_encode($rows);
	exit();
}
?>