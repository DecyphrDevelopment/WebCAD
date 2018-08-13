<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 1;
include '../../config.php';
include '../../includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL . "/dispatch";
require '../../includes/check_access.inc.php';

$server = DB_HOST;
$port = DB_PORT;
$db_user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $db_user, $pass, $db);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['ubid'])) {
	$ubid = $_GET['ubid'];
	$result = mysqli_query($connection, "SELECT * FROM banned_ips WHERE ubid='$ubid'") or die(mysqli_error($connection));
	if (mysqli_num_rows($result) != 0) {
		while ($row = mysqli_fetch_array($result)) {
			$bannedIP = $row['ip'];
			$banReason = $row['reason'];
			$bannedBy = $row['banned_by'];
		}
		$usersText = "";
	  	$result2 = mysqli_query($connection, "SELECT username FROM known_users WHERE ip='$bannedIP'") or die(mysqli_error($connection));
		if (mysqli_num_rows($result2) != 0) {
			while ($row2 = mysqli_fetch_array($result2)) {
				$usersText = $usersText.$row2['username']."&#13;&#10;";
			}
		}
	}
}
?>
<html>

	<head>
		<script language="javascript" type="text/javascript" src="../../scripts/js/cookies.js"></script>
		<script language="javascript" type="text/javascript" src="../../scripts/js/jQuery.js"></script>
		<link rel="stylesheet" type="text/css" href="../../css/<?php echo STYLE; ?>">
		<script>
			$(window).on("load", function () {
				setLevel(<?php echo $_SESSION['cad_level']; ?>);
			});
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
        </script>
		<title><?php echo WEBSITE_TITLE; ?> | Log</title>
	</head>
	<body>
		<br><br><br>
        <div id="sidebar" class="float-left" style="margin-top:0px;">
            <button id="clear_btn" class="btn btn-error form-control-error center" onclick="window.location.href='../ip-bans/'" style="width:100%; margin-top:0px;">Back</button>
			<div id="result" style="margin-top:20px;width:100%;height:auto;" class="center form-control"><font color="purple">Access: <?php if ($_SESSION['cad_level'] == 0) { echo "Super Admin"; } if ($_SESSION['cad_level'] == 1) { echo "Admin"; }?></font></div>
        </div>
        <div id="main_panel" class="float-right" style="">
            <div id="main" style="margin-bottom:0px;height:90%;">
				<h2 id="log" style="margin-top:0px;">IP Ban Details</h2>
				<p style="margin-bottom:5px;">IP:</p>
				<input type=text class="form-control center" placeholder="IP" style="width:100%; margin-top:0px; text-align:left;" value="<?php echo $bannedIP; ?>" readonly>
				<p style="margin-bottom:5px;">Reason for ban:</p>
				<input type=text class="form-control center" placeholder="Reason" style="width:100%; margin-top:0px; text-align:left;" value="<?php echo $banReason; ?>" readonly>
				<p style="margin-bottom:5px;">Banned By::</p>
				<input type=text class="form-control center" placeholder="Banned By" style="width:100%; margin-top:0px; text-align:left;" value="<?php echo $bannedBy; ?>" readonly>
				<p style="margin-bottom:5px;">Known usernames:</p>
                <textarea style="width:100%;height:25%;margin-top:0px;" rows="6" class="form-control" readonly><?php echo $usersText; ?></textarea>
            </div>
        </div>
	</body>
</html>
