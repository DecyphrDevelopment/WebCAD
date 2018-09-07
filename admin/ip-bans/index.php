<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 1;
include '../../config.php';
include '../../includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL . "/ums/ip-bans";
include '../../includes/check_access.inc.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>


<head>

<link rel="stylesheet" type="text/css" href="../../css/<?php echo STYLE; ?>">
<link rel="stylesheet" type="text/css" href="../../css/buttons.css">
<script language="javascript" type="text/javascript" src="../../scripts/js/jQuery.js"></script>

<title><?php echo WEBSITE_TITLE; ?> | IP Bans</title>


</head>

<body>
<br><br><br>
<script>
	document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
	document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
</script>
<script language="JavaScript" type="text/javascript">
Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}
function checkDelete(){
    return confirm('Are you sure you want to delete this ban?');
}
function deleteBan(ubid) {
	if (checkDelete()) {
		var httpStatus = new XMLHttpRequest();
		var url = "delete.php";
		var statusParams = "ubid=" + ubid;
		httpStatus.open("GET", url + "?" + statusParams, true);
		httpStatus.onreadystatechange = function () {
			if (httpStatus.readyState == 4 && httpStatus.status == 200) {
				if (this.responseText == "1") {
					$("#det_result").html("<font color='green'>Successfully unbanned.</font>");
					document.getElementById(ubid).remove();
				} else if (this.responseText == "0") {
					$("#det_result").html("<font color='red'>Error!</font>");
				}
			}
		};
		httpStatus.send(null);
	} else {
		// Do nothing!
	}
}
</script>
<div id="sidebar" class="float-left" style="margin-top:0px;">
<button id="disp" class="btn btn-primary form-control center" onclick="window.location.href='newban'" style="width:100%;margin-top:0px;">Add a New IP Ban</button>
<?php include '../../includes/admin_sidebar_buttons.inc.php'; ?>
</div>
<div id="main_panel" class="float-right" style="">
<div id="main" style="margin-bottom:0px;height:90%;">
<h1 style="margin-top:0px;">IP Bans</h1>

<?php

/*

VIEW.PHP

Displays all data from 'players' table

*/

// connect to the database

include('../connect-db.php');

// get results from database

$result = mysqli_query($connection, "SELECT * FROM banned_ips")

or die(mysqli_error());

// display data in table

$p_word_row = '<input type="submit" name="submit" value="Submit">';

echo "

";

echo "<table border='1' cellpadding='10'>";

echo "<tr> <th>IP</th> <th>Reason</th> <th>Banned By</th> <th>UBID</th> <th></th> <th></th> </tr>";

// loop through results of database query, displaying them in the table

while($row = mysqli_fetch_array( $result )) {

// echo out the contents of each row into a table
	echo "<tr id='".$row['ubid']."'>";
	echo '<td>' . $row['ip'] . '</td>';
	echo '<td>' . $row['reason'] . '</td>';
	echo '<td>' . $row['banned_by'] . '</td>';
	echo '<td>' . $row['ubid'] . '</td>';
	echo '<td><button id="disp" class="btn btn-primary form-control center" onclick="window.location.href=' . "'details?ubid=" . $row['ubid'] . "'" . '" style="width:100%;margin-top:0px;">Details</button></td>';
	echo '<td><button id="disp" class="btn btn-primary form-control center" onclick="deleteBan('."'".$row['ubid']."'".');" style="width:100%;margin-top:0px;">Remove Ban</button></td>';
	echo "</tr>";

}

// close table>

echo "</table>";

?>
<style>
.button {
    appearance: button;
    -moz-appearance: button;
    -webkit-appearance: button;
    text-decoration: none; font: menu; color: ButtonText;
    display: inline-block; padding: 2px 8px;
}
</style>
<br>
</div>

</body>

</html>
