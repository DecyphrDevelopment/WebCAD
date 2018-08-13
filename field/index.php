<?php
include '../config.php';
include '../includes/menu.inc.php';
$file_access = 2;
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
		<script language="javascript" type="text/javascript" src="scripts/field.js?v=6"></script>
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
			<h2 style="margin-top:0px">Status</h2>
			<div id="status" class="center form-control" style="height:auto;margin-top:0px;margin-right:8px;width:auto">
				Current Status:
			</div>
			<h2>Update Status</h1>
			<div id="input" class="form-control center" style="height:auto;margin-top:0px;padding: 6px 6px; width:auto;">
				<?php
					echo "<button class='btn btn-flat btn-success status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_status(this.id, 1)'>10-8</button> <button class='btn btn-flat btn-warning status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_status(this.id, 2)' style='background:#5942f4;'>10-6</button> <button class='btn btn-flat btn-danger status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_status(this.id, 3)'>10-7</button>";
					echo "<br><button class='btn btn-flat btn-warning status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_status(this.id, 4)'>10-11</button> <button class='btn btn-flat btn-warning status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_status(this.id, 5)'>10-80</button>";
					echo "<br><button class='btn btn-flat btn-delete status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_offduty(this.id)'>Off Duty</button>";
				?>
			</div>
			<h2>Priority</h1>
			<div id="prior" class="center form-control" style="height:auto;margin-top:0px;margin-right:8px;width:auto">
				Priority Status:
			</div>
			<h2>Info</h1>
			<div id="info" class="center form-control" style="height:auto;margin-top:0px;margin-right:8px;width:auto">
				To go online, update your status.
			</div>
		</div>
		<div id="main_panel" class="float-right">
			<div id="call" style="clear:both;">
				<h2 style="margin-top:0px;">Current Call</h2>
				<div id="call_table" class="center form-control" style="width:auto;height:auto;margin-top:0px;margin-right:8px;">
					<table border='1' cellpadding='10' id='tab'>
					<tr> <th style="width:75%">Call Description</th><th style="width:25%">Other Assigned Units</th> </tr>
					</table>
				</div>
			</div>
			<div id="bolos" style="clear:both;">
				<h2 style="margin-top:25px;">Bolos</h2>
				<div id="bolo_table" class="center form-control" style="width:auto;height:auto;margin-top:0px;margin-right:8px;">
					<table border='1' cellpadding='10' id='tab'>
					<tr> <th style="width:100%">Bolo</th> </tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
