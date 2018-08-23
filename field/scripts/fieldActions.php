<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../logging/log.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['updateUnit'])) {
	$uuid = $_SESSION['cad_uuid'];
	if (isset($_GET['status'])) {
		$status = $_GET['status'];
		if ($status == 1 || $status == 2 || $status == 3 || $status == 4 || $status == 5 || $status == 6 || $status == 7) {
			$sqlQuery = mysqli_query($connection, "UPDATE units SET status='$status', active='1' WHERE uuid='$uuid'");
			logInfo("Updated status to: " . $status . " ; UUID: " . $uuid, 0);
			echo "success";
		} else {
			echo "notValidStatus";
		}
	}
	if (isset($_GET['duty'])) {
		$sqlQuery = mysqli_query($connection, "UPDATE units SET active='0' WHERE uuid='$uuid'");
		logInfo("Updated duty status to: " . "0" . " ; UUID: " . $uuid, 0);
		echo "success";
	}
} else if (isset($_GET['getStatus'])) {
	$uuid = $_SESSION['cad_uuid'];
	if (!isset($_SESSION['cad_uuid'])) {
		echo "noUser";
		exit;
	}
	$result = @mysqli_query($connection, "SELECT * FROM units WHERE uuid='$uuid'");
	$count = mysqli_num_rows($result);
	$value = mysqli_fetch_object($result);
	if ($value->active == 1) {
		$status = $value->status;
		echo $status;
	} else if ($value->active == 0) {
		echo "inactive";
		exit;
	}
} else if (isset($_GET['getBolos'])) {
	$result = @mysqli_query($connection, "SELECT * FROM cad_bolos");
	$tableHTML = "";
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$bolo = $row['bolo'];
			$tableHTML = $tableHTML . "<tr><td style='width:100%'>" . $bolo . "</td> </tr > ";
		}
	}
	$tableHTML = $tableHTML . "</table>";
	echo $tableHTML;
} else if (isset($_GET['getCalls'])) {
  $none = false;
	$uuid = $_SESSION['cad_uuid'];
	$result = @mysqli_query($connection, "SELECT oncall_ucid FROM units WHERE uuid='$uuid'");
	$ucid = "";
	if (mysqli_num_rows($result) > 0) {
		while($resultRow = mysqli_fetch_assoc($result)) {
			$ucid = $resultRow['oncall_ucid'];
		}
	}
	$unitsHTML = "";
	$unitsRes = mysqli_query($connection, "SELECT * FROM units WHERE oncall_ucid='$ucid'");
	if (mysqli_num_rows($unitsRes) > 0) {
		while($unitRow = mysqli_fetch_assoc($unitsRes)) {
			$unitsHTML = $unitsHTML . $unitRow['callsign'] . ', ';
		}
	} else {
		$unitsHTML = '<font color="red">None</font>';
	}
	$description = "None";
	if ($ucid != "") {
		$callsRes = mysqli_query($connection, "SELECT * FROM cad_calls WHERE ucid='$ucid'");
		if (mysqli_num_rows($callsRes) > 0) {
			while($row = mysqli_fetch_assoc($callsRes)) {
				$description = $row['call_description'];
			}
		}
	} else {
		$description = "<font color='red'>None</font>";
		$unitsHTML = "<font color='red'>n/a</font>";
    $none = true;
	}
	$tableHTML = "<table border='1' cellpadding='10' class='' id='tab'><tr> <th style='width:75%'>Call Description</th> <th style='width:75%'>Other Assigned Units</th> <th>Delete</th></tr>";
	$tableHTML = $tableHTML . "<tr><td style='width:75%'>" . $description . "</td><td style='width:25%'>" . $unitsHTML . "</td> <td><button class='btn btn-flat btn-delete status' id='" . $ucid . "' onClick='remCall(this.id)'>Remove Call</button></td></tr > ";
	$tableHTML = $tableHTML . "</table>";
  if ($none) {
    echo "<td>No active call.</td>";
  } else {
  	echo $tableHTML;
  }
} else if (isset($_GET['getPriority'])) {
	$result = @mysqli_query($connection, "SELECT is_priority FROM cad_extra WHERE priority='0'");
	$value = mysqli_fetch_object($result);
	$status = $value->is_priority;
	echo $status;
} else if (isset($_GET['addCall']) && isset($_GET['desc'])) {
	$desc = $_GET['desc'];
	$ucid = uniqid();
	$sqlQuery = mysqli_query($connection, "INSERT INTO cad_calls (ucid, call_description) VALUES ('$ucid', '$desc')") or die(mysqli_error($connection));
  $uuid = $_SESSION['cad_uuid'];
  $sqlQuery = mysqli_query($connection, "UPDATE units SET oncall_ucid='$ucid' WHERE uuid='$uuid'");
	echo "success";
} else if (isset($_GET['remCall']) && isset($_GET['ucid'])) {
	$ucid = $_GET['ucid'];
	$sqlQuery = mysqli_query($connection, "DELETE FROM cad_calls WHERE ucid='$ucid'");
  $uuid = $_GET['cad_uuid'];
  $sqlQuery = mysqli_query($connection, "UPDATE units SET oncall_ucid='' WHERE oncall_ucid='$ucid'");
	echo "success";
} else {
	echo "unknownFunction";
}
?>
