<?php
$file_access = 2;
ini_set('display_errors', 1);
require_once(__DIR__ . "/../../logging/log.php");
require_once(__DIR__ . "/../../config.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['getUnits'])) {
  $sql = "SELECT * FROM units";
	$result = mysqli_query($connection, "SELECT * FROM units");

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
          $color = "<font color='#f98500'>";
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
        if ($status == 6) {
          $status = "10-97 Responding";
          $color = "<font color='#ffb31c'>";
        }
        if ($status == 7) {
          $status = "10-23 On Scene";
          $color = "<font color='#ffb31c'>";
        }
  		  if ($activity >= 1) {
  		  	$tableHTML = $tableHTML . "<tr id='" . $un . "' draggable='true' ondragstart='drag(event)'><td style='width: 30 %'>" . $un . "</td><td style='width: 30 %' id='" . $uuid . "_status'>" . $color . $status . "</font></td><td style='width: 40 %'>" .
  				"<button class='btn btn-flat btn-success status' id='" . $uuid . "' onClick='update_status(this.id, 1)'>10-8</button> <button class='btn btn-flat btn-warning status' id='" . $uuid . "' onClick='update_status(this.id, 2)' style='background:#f98500'>10-6</button> <button class='btn btn-flat btn-danger status' id='" . $uuid . "' onClick='update_status(this.id, 3)'>10-7</button>
          <button class='btn btn-flat btn-delete status' id='" . $uuid . "' onClick='remUnitFromCalls(this.id)'>Remove From Calls</button><button class='btn btn-flat btn-delete status' style='margin-left:5px;' id='" . $uuid . "' onClick='update_delete(this.id)'>Off Duty</button>" . "</td ></tr > ";
  		  }
    }
    $tableHTML = $tableHTML . "</table>";
  	echo $tableHTML;
  } else {
      echo '<table border="1" cellpadding="10" class="" id="tab"><tr> <th style="width:30%">Unit Number</th> <th style="width:30%">Status</th> <th style="width:40%">Update</th> </tr></table>';
  }
} else if (isset($_GET['getBolos'])) {
  $result = mysqli_query($connection, "SELECT * FROM cad_bolos");
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
} else if (isset($_GET['getCalls'])) {
  $callsRes = mysqli_query($connection, "SELECT * FROM cad_calls");
	$tableHTML = "<table border='1' cellpadding='10' class='' id='tab'><tr> <th style='width:30%'>Call Description</th> <th style='width:50%'>Assigned Units</th> <th style='width:20%'>Update</th> </tr>";
	if (mysqli_num_rows($callsRes) > 0) {
		while($row = mysqli_fetch_assoc($callsRes)) {
			$description = $row['call_description'];
			$ucid = $row['ucid'];

			$unitsHTML = '';
			$unitsRes = mysqli_query($connection, "SELECT * FROM units WHERE oncall_ucid='$ucid'");
	      if (mysqli_num_rows($unitsRes) > 0) {
		            while($unitRow = mysqli_fetch_assoc($unitsRes)) {
				$unitsHTML = $unitsHTML . $unitRow['callsign'] . ', ';
			    }
			} else {
			    $unitsHTML = '<font color="red">None</font>';
			}
			$tableHTML = $tableHTML . "<tr id='" . $ucid . "' ondrop='drop(event)' ondragover='allowDrop(event)'><td id='" . $ucid . "' ondrop='drop(event)' ondragover='allowDrop(event)' style='width:30%'>" . $description . "</td> <td style='width:50%'>" . $unitsHTML . "</td> <td style='width:20%' data-id='" . $ucid . "_td'>" . '<input type=text class="form-control center unInput" id="' . $ucid . '" placeholder="Unit Number" style="width:100%; margin-top:0px; text-align:left;background:transparent;box-shadow:none">' . "<button class='btn btn-flat btn-delete status' id='" . $ucid . "' onClick='remCall(this.id)'>Remove Call</button></td></tr>";
		}
	}
	$tableHTML = $tableHTML . "</table>";
	echo $tableHTML;
} else if (isset($_GET['updateUnit']) && isset($_GET['uuid']) && isset($_GET['status'])) {
	$uuid = $_GET['uuid'];
	$status = $_GET['status'];
	if ($status == 1 || $status == 2 || $status == 3 || $status == 4 || $status == 5) {
		$sqlQuery = mysqli_query($connection, "UPDATE units SET status='$status' WHERE uuid='$uuid'");
		echo "success";
		logInfo("Updated unit status of " . $uuid . " to " . $status, 0);
	} else {
		echo "notValidStatus";
	}
} else if (isset($_GET['addUnitToCall']) && isset($_GET['ucid']) && isset($_GET['callsign'])) {
	$ucid = $_GET['ucid'];
	$callsign = $_GET['callsign'];
	$sqlQuery = mysqli_query($connection, "UPDATE units SET oncall_ucid='$ucid' WHERE callsign='$callsign'");
	echo "success";
} else if (isset($_GET['remUnitFromCalls']) && isset($_GET['uuid'])) {
	$uuid = $_GET['uuid'];
	$sqlQuery = mysqli_query($connection, "UPDATE units SET oncall_ucid='' WHERE uuid='$uuid'");
	echo "success";
} else if (isset($_GET['addCall']) && isset($_GET['desc'])) {
	$desc = $_GET['desc'];
	$ucid = uniqid();
	$sqlQuery = mysqli_query($connection, "INSERT INTO cad_calls (ucid, call_description) VALUES ('$ucid', '$desc')") or die(mysqli_error($connection));
	echo "success";
} else if (isset($_GET['remCall']) && isset($_GET['ucid'])) {
	$ucid = $_GET['ucid'];
	$sqlQuery = mysqli_query($connection, "DELETE FROM cad_calls WHERE ucid='$ucid'");
  $sqlQuery = mysqli_query($connection, "UPDATE units SET oncall_ucid='' WHERE oncall_ucid='$ucid'");
	echo "success";
} else if (isset($_GET['removeUnit']) && isset($_GET['uuid'])) {
	$uuid = $_GET['uuid'];
    $sql_unit = mysqli_query($connection, "SELECT active FROM units WHERE uuid='$uuid'") or die(mysqli_error($connection));
	$unit_result = mysqli_fetch_object($sql_unit);
	if ($unit_result->active == 3) {
		$sqlQuery = mysqli_query($connection, "DELETE FROM units WHERE uuid='$uuid'");
		logInfo("Removed unit: " . $uuid, 0);
	} else {
		$sqlQuery = mysqli_query($connection, "UPDATE units SET active='0' WHERE uuid='$uuid'");
		logInfo("Updated duty status to: " . "0" . " ; UUID: " . $uuid, 0);
	}
	echo "success";
} else if (isset($_GET['addUnit']) && isset($_GET['name'])) {
	$name = $_GET['name'];
	$uuid = uniqid();
	$sqlQuery = mysqli_query($connection, "SELECT * FROM units WHERE callsign='$name'");
	$count = mysqli_num_rows($sqlQuery);
	if ($count >= 1) {
		echo "exists";
	} else {
		$sqlQuery = mysqli_query($connection, "INSERT units SET uuid='$uuid', callsign='$name', status='1', active='3', oncall_ucid=''");
		echo "success";
		logInfo("Added unit. UUID: " . $uuid . " ; Name: " . $name, 0);
	}
} else if (isset($_GET['addBolo']) && isset($_GET['bolo'])) {
	$bolo = $_GET['bolo'];
	$ubid = uniqid();
	$sqlQuery = mysqli_query($connection, "INSERT cad_bolos SET bolo='$bolo', ubid='$ubid'");
	logInfo("Added bolo. UUID: " . $ubid . " ; Bolo: " . $bolo, 0);
	echo "success";
} else if (isset($_GET['removeBolo']) && isset($_GET['ubid'])) {
	$ubid = $_GET['ubid'];
	$sql = mysqli_query($connection, "DELETE FROM cad_bolos WHERE ubid='$ubid'");
	logInfo("Removed bolo: " . $ubid, 0);
	echo "success";
} else if (isset($_GET['getPriority'])) {
	$result = @mysqli_query($connection, "SELECT is_priority FROM cad_extra WHERE priority='0'");
	$value = mysqli_fetch_object($result);
	$status = $value->is_priority;
	echo $status;
} else if (isset($_GET['updatePriority']) && isset($_GET['status'])) {
	$status = $_GET['status'];
	$sqlQuery = mysqli_query($connection, "UPDATE cad_extra SET is_priority='$status' WHERE priority='0'");
	logInfo("Updated priority status to " . $status, 0);
	echo "successprior";
} else {
	echo "unknownFunction";
}
?>
