<?php
ob_start();
include '../config.php';
include '../includes/menu.inc.php';
$file_access = 3;
$_SESSION['return_url'] = BASE_URL . "/field";
require '../includes/check_access.inc.php';
?>
<html>
	<!--[if IE]><link rel="shortcut icon" href="images/favicon_IE.ico"><![endif]-->
	<link rel="shortcut icon" href="images/favicon_IE.ico">

	<!-- Touch Icons - iOS and Android 2.1+ 180x180 pixels in size. -->
	<link rel="apple-touch-icon-precomposed" href="images/favicon.png">

	<!-- Firefox, Chrome, Safari, IE 11+ and Opera. 196x196 pixels in size. -->
	<link rel="icon" href="images/favicon.png">

	<head>
		<script language="javascript" type="text/javascript" src="../scripts/js/cookies.js"></script>
		<script language="javascript" type="text/javascript" src="../scripts/js/jQuery.js"></script>
		<script language="javascript" type="text/javascript" src="scripts/field.js?v=<?php echo VERSION; ?>v"></script>
		<link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
		<title><?php echo WEBSITE_TITLE; ?> | Field MDT</title>
	</head>
	<body style="z-index:1;">
		<br><br><br>
		<script>
		document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
		document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
		$("div.pace").remove();
		paceOptions = {
			ajax: false, // disabled
			document: true // disabled
		};
	pace.stop();
		</script>
		<div id="sidebar" class="float-left" style="margin-top:0px;">
			<h2 style="margin-top:0px;">Priority</h1>
			<div id="prior" class="center form-control" style="height:auto;margin-top:0px;margin-right:8px;width:auto">
				Priority Status:
			</div>
			<h1>Add Call</h1>
			<div id="calls_input" class="form-control center" style="height:auto;margin-top:0px;margin-right:8px;width:auto">
				<input type=text id="addcall" class="form-control center" placeholder="Call Description" style="margin-top:0px;width:100%">
				<button id="addcall_btn" class="btn btn-primary form-control center" onclick="addCall(addcall.value)" style="width:100%; margin-top:6px;">Add Call</button>
				<div id="call_result" style="clear:both;" class="center"></div>
			</div>
			<h2>Info</h1>
			<div id="info" class="center form-control" style="height:auto;margin-top:0px;margin-right:8px;width:auto">
				To go online, update your status.
			</div>
		</div>
		<div id="main_panel" class="float-right">
			<div class="center" style="margin-top:0px;height:auto;">
				<h1 id="status" style="margin-top:0px;">Current Status: </h1>
				<?php
				echo '<button id="8_' . $_SESSION['cad_uuid'] . '" class="statusButton btn btn-primary form-control" style="margin-right:3px;" onClick="update_status(this.id, 1)">10-8</button>';
				echo '<button id="6_' . $_SESSION['cad_uuid'] . '" class="statusButton btn btn-primary form-control hideable1" style="margin-left:3px;margin-right:3px;display:none;" onClick="update_status(this.id, 2)">10-6</button>';
				echo '<button id="7_' . $_SESSION['cad_uuid'] . '" class="statusButton btn btn-primary form-control hideable1" style="margin-left:3px;margin-right:3px;display:none;" onClick="update_status(this.id, 3)">10-7</button>';
				echo '<button id="11_' . $_SESSION['cad_uuid'] . '" class="statusButton btn btn-primary form-control" style="margin-left:3px;margin-right:3px;display:none;" onClick="update_status(this.id, 4)">10-11</button>';
				echo '<button id="80_' . $_SESSION['cad_uuid'] . '" class="statusButton btn btn-primary form-control" style="margin-left:3px;margin-right:3px;display:none;" onClick="update_status(this.id, 5)">10-80</button>';
				echo '<button id="97_' . $_SESSION['cad_uuid'] . '" class="statusButton btn btn-primary form-control hideable2" style="margin-left:3px;margin-right:3px;display:none;" onClick="update_status(this.id, 6)" hidden>10-97</button>';
				echo '<button id="23_' . $_SESSION['cad_uuid'] . '" class="statusButton btn btn-primary form-control hideable2" style="margin-left:3px;margin-right:3px;display:none;" onClick="update_status(this.id, 7)" hidden>10-23</button>';
				echo '<button id="od_' . $_SESSION['cad_uuid'] . '" class="statusButton btn btn-primary form-control hideable1" style="margin-left:3px;display:none;" onClick="update_offduty(this.id)">Off Duty</button>';
				?>
		  </div>
			<div id="call" style="clear:both;" class="center">
				<h2 style="margin-top:0px;">Current Call</h2>
				<div id="call_table" class="center form-control" style="width:auto;height:auto;margin-top:0px;margin-right:8px;background:transparent;border:none;box-shadow:none;">
					<table border='1' cellpadding='10' id='tab'>
					<tr> <th style="width:75%">Call Description</th> <th style="width:25%">Other Assigned Units</th> <th>Delete</th></tr>
					</table>
				</div>
			</div>
			<div id="bolos" style="clear:both;" class="center">
				<h2 style="margin-top:25px;">Bolos</h2>
				<div id="bolo_table" class="center form-control" style="width:auto;height:auto;margin-top:0px;margin-right:8px;background:transparent;border:none;box-shadow:none;">
					<table border='1' cellpadding='10' id='tab'>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
