<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 1;
include '../../config.php';
include '../../includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL . "/dispatch";
$server = DB_HOST;
$port = DB_PORT;
$db_user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $db_user, $pass, $db);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
function renderForm($ip2ban, $reason4ban, $error) {
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
		<form action="" method="post">
			<div id="sidebar" class="float-left" style="margin-top:0px;">
						<input type="submit" name="submit" value="Submit" class="btn btn-primary form-control center" style="width:100%; margin-top:0px; margin-bottom:0px;">
						<br>
						<input type="submit" name="cancel" value="Cancel" class="form-control-error btn-error btn-primary center" style="width:100%; margin-top:6px; margin-bottom:0px;">
				<div id="result" style="margin-top:20px;width:100%;height:auto;" class="center form-control"><font color="purple">Access: <?php if ($_SESSION['cad_level'] == 0) { echo "Super Admin"; } if ($_SESSION['cad_level'] == 1) { echo "Admin"; }?></font></div>
			</div>
			<div id="main_panel" class="float-right" style="">
				<?php if ($error != "") { echo '<div id="result" style="margin-top:6px;width:100%;height:auto;color:red;" class="center form-control">'.$error.'</div>'; } ?>
				<div id="main" style="margin-bottom:0px;height:90%;">
					<h2 id="log" style="margin-top:0px;">IP Ban Details</h2>
						<p style="margin-bottom:5px;">IP:</p>
						<input type=text id="ip" name="ip" class="form-control center" placeholder="IP" style="width:100%; margin-top:0px; text-align:left;" value="<?php echo $ip2ban; ?>">
						<p style="margin-bottom:5px;">Reason for ban:</p>
						<input type=text id="reason" name="reason" class="form-control center" placeholder="reason" style="width:100%; margin-top:0px; text-align:left;" value="<?php echo $reason4ban; ?>">

				</div>
			</div>
		</form>
	</body>
</html>
<?php
}
include '../../logging/log.php';
if (isset($_POST['submit'])) {
    $ip2ban = mysqli_real_escape_string($connection, htmlspecialchars($_POST['ip']));
    $reason4ban = mysqli_real_escape_string($connection, htmlspecialchars($_POST['reason']));
    if (!isset($_POST['ip'])) {
        $error = 'ERROR: Please fill in all required fields!';
        renderForm($ip2ban, $reason4ban, $error);
    }
    if ($ip2ban == '') {
        $error = 'ERROR: Please fill in all required fields!';
        renderForm($ip2ban, $reason, $error);
    } else if ($reason4ban == '') {
        $error = 'ERROR: Please fill in all required fields!';
        renderForm($ip2ban, $reason4ban, $error);
    } else {
        $sql = mysqli_query($connection, "SELECT * FROM banned_ips WHERE ip='$ip2ban'");
        $count = mysqli_num_rows($sql);
        if ($count >= 1) {
			$error = 'ERROR: That IP is already banned.';
			renderForm($ip2ban, $reason4ban, $error);
        }
        else {
			$ubid = uniqid();
			$user = $_SESSION['cad_user'];
			mysqli_query($connection, "INSERT INTO banned_ips VALUES (DEFAULT, '$ubid', '$ip2ban', '$reason4ban', '$user')")
			or die(mysqli_error($connection));
            logInfo("Added IP ban. IP: " . $ip2ban . " ; Reason: " . $reason4ban . " ; UBID: " . $ubid, 1);
        }
        header('Location: ../ip-bans/');
    }
} else {
    if (isset($_POST['cancel'])) {
        header('Location: ../ip-bans/');
    } else {
        renderForm('','','','','');
    }
}
?>
