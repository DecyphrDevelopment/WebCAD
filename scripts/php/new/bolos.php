<?php
require_once(__DIR__ . "/../../../config.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port)
or die ("Could not connect to server ... \n" . mysqli_error ());

if (isset($_GET['action'])) {
	if (isset($_GET['ubid'])) {
		$ubid = $_GET['ubid'];
		if ($_GET['action'] == 99) {
			$sql = mysqli_query($connection, "DELETE FROM cad_bolos WHERE ubid='$ubid'");
			echo "99_success";
		}
	}
	if (isset($_GET['bolo'])) {
		if ($_GET['action'] == 101) {
			$bolo = $_GET['bolo'];
			$ubid = uniqid();
			$sql = mysqli_query($connection, "INSERT cad_bolos SET bolo='$bolo', ubid='$ubid'");
			echo "101_success";
		}
	}
} else {
	$result = @mysqli_query($connection, "SELECT * FROM cad_bolos");
	$tableHTML = "<table border='1' cellpadding='10' class='' id='tab'><tr> <th style='width:80%'>Bolo</th> <th style='width:20%'>Update</th> </tr>";
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$bolo = $row['bolo'];
			$ubid = $row['ubid'];
			
            $tableHTML = $tableHTML . "<tr><td style='width:80%'>" . $bolo . "</td> <td style='width:20%'>" .
				"<button class='btn btn-flat btn-delete status' id='" . $ubid . "' onClick='bolo_delete(this.id)'>Remove</button>" . "</td></tr> ";
		}
	}
	$tableHTML = $tableHTML . "</table>";
	echo $tableHTML;
}
?>