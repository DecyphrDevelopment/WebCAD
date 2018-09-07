<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['cad_uuid'])) {
	header('Location: ../../..');
} else {
	require_once(__DIR__ . "/../../../config.php");
	$server = DB_HOST;
	$port = DB_PORT;
	$user = DB_USER;
	$pass = DB_PASSWORD;
	$db = DB_NAME;

	$connection = mysqli_connect($server, $user, $pass, $db, $port)
	or die ("Could not connect to server ... \n" . mysqli_error ());

	$uuid = $_SESSION['cad_uuid'];

	if (isset($_GET['status'])) {
		$result = @mysqli_query($connection, "SELECT status FROM cad_users WHERE uuid='$uuid'");
		$value = mysqli_fetch_object($result);
		$status = $value->status;
		echo $status;
	}
	if (isset($_GET['bolos'])) {
		$result = @mysqli_query($connection, "SELECT * FROM cad_bolos");
		$tableHTML = "<table border='1' cellpadding='10' class='' id='tab'><tr> <th style='width:100%'>Bolo</th> </tr>";
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$bolo = $row['bolo'];
				$tableHTML = $tableHTML . "<tr><td style='width:100%'>" . $bolo . "</td> </tr > ";
			}
		}
		$tableHTML = $tableHTML . "</table>";
		echo $tableHTML;
	}
}
?>