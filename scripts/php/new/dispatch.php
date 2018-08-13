<?php
require_once(__DIR__ . "/../../../config.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM units";
$result = mysqli_query($connection, $sql);

$tableHTML = '<table border="1" cellpadding="10" class="" id="tab"><tr> <th style="width:30%">Unit Number</th> <th style="width:30%">Status</th> <th style="width:40%">Update</th> </tr>';
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $un = $row['callsign'];
		$uuid = $row['uuid'];
		$status = $row['status'];
		$activity= $row['active'];

		$show = 1;
        $color = "";
        if ($status == 0) {
            $status = "Offline";
            $color = "<font color='purple'>";
            $show = 0;
        }
        if ($status == 1) {
            $status = "10-8 On Duty";
            $color = "<font color='green'>";
        }
        if ($status == 2) {
            $status = "10-6 Busy";
            $color = "<font color='yellow'>";
        }
        if ($status == 3) {
            $status = "10-7 Out of Service";
            $color = "<font color='red'>";
        }
        if ($status == 4) {
            $status = "10-11 On Traffic Stop";
            $color = "<font color='#ffb31c'>";
        }
        if ($status == 5) {
            $status = "10-80 In Pursuit";
            $color = "<font color='#ffb31c'>";
        }
		if ($activity >= 1) {
			$tableHTML = $tableHTML . "<tr><td style='width: 30 %'>" . $un . "</td><td style='width: 30 %' id='" . $uuid . "_status'>" . $color . $status . "</font></td><td style='width: 40 %'>" .
				"<button class='btn btn-flat btn-success status' id='" . $uuid . "' onClick='update_status(this.id, 1)'>10-8</button> <button class='btn btn-flat btn-warning status' id='" . $uuid . "' onClick='update_status(this.id, 2)'>10-6</button> <button class='btn btn-flat btn-danger status' id='" . $uuid . "' onClick='update_status(this.id, 3)'>10-7</button> <button class='btn btn-flat btn-warning status' id='" . $uuid . "' onClick='update_status(this.id, 4)'>10-11</button> <button class='btn btn-flat btn-warning status' id='" . $uuid . "' onClick='update_status(this.id, 5)'>10-80</button> <button class='btn btn-flat btn-delete status' id='" . $uuid . "' onClick='update_delete(this.id)'>Remove</button>" . "</td ></tr > ";
		}
    }
    $tableHTML = $tableHTML . "</table>";
	echo $tableHTML;
} else {
    echo '<table border="1" cellpadding="10" class="" id="tab"><tr> <th style="width:30%">Unit Number</th> <th style="width:30%">Status</th> <th style="width:40%">Update</th> </tr></table>';
}
?>
