<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 9;
include 'config.php';
include 'includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL;
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/<?php echo STYLE; ?>">
		<link rel="stylesheet" type="text/css" href="css/buttons.css">
		<title><?php echo WEBSITE_TITLE; ?> | Home</title>
	</head>
	<body>
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
		</script>
		<div class="center">
			<a href="dispatch/"><button id="disp" class="btn btn-primary form-control"style="width:51%;">Dispatch CAD</button></a>
			<a href="field/"><button id="field" class="btn btn-primary form-control" style="width:51%;margin-top:6px;">Field MDT</button></a>
			<a href="howto.php"><button id="field" class="btn btn-primary form-control" style="width:51%;margin-top:6px;">About</button></a>
			<div class="form-control center" style="height:auto; width:51%;">
				<?php
				if (isset($_SESSION['cad_user'])) {
					if($_SESSION['cad_level'] <= 1) {
						echo '<h1 style="margin-top:-6px;color:tomato;margin-bottom:6px;">Account</h1>';
						echo '<a href="account/"><button id="disp" class="btn btn-primary form-control" style="width:90%;margin-top:0px;">Account Details</button></a>';
						echo '<a href="account/logout"><button id="disp" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">Logout</button></a>';
						echo '<h1 style="color:tomato;margin-bottom:6px;">Admin Tools</h1>';
						if ($_SESSION['cad_level'] == 0) {
    						echo '<a href="admin/"><button class="btn btn-primary form-control center" style="width:90%; margin-top:0px;">Website Settings</button></a>';
							echo '<a href="ums/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">User Managment System</button></a>';
						} else {
							echo '<a href="ums/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:0px;">User Managment System</button></a>';
						}
						echo '<a href="ums/logs/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">Logs</button></a>';
						echo '<a href="ums/ip-bans/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">IP Bans</button></a>';
						echo '<a href="ums/ip-lookup/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">IP Lookup</button></a>';
						echo '<a href="ums/user-lookup/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">User Lookup</button></a>';
					} else {
						echo '<h1 style="margin-top:-6px;color:tomato;margin-bottom:6px;">Account</h1>';
						echo '<a href="account/"><button id="disp" class="btn btn-primary form-control" style="width:90%;margin-top:0px;">Account Details</button></a>';
						echo '<a href="account/logout"><button id="disp" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">Logout</button></a>';
					}
				} else {
					echo '<h1 style="margin-top:-6px;color:tomato;margin-bottom:6px;">Login/Signup</h1>';
					echo '<a href="account/login"><button id="disp" class="btn btn-primary form-control" style="width:90%;margin-top:0px;">Login</button></a>';
					echo '<a href="account/signup"><button id="disp" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">Signup</button></a>';
				}
				?>
			</div>
				<br><br><br>
		</div>
	</body>
</html>
