<?php
	include '../menu.php';
if (!isset($_SESSION['cad_user'])) {
	header('Location: .');
}
?>
<html>
	<br>
	<!--[if IE]><link rel="shortcut icon" href="images/favicon_IE.ico"><![endif]-->
	<link rel="shortcut icon" href="images/favicon_IE.ico">

	<!-- Touch Icons - iOS and Android 2.1+ 180x180 pixels in size. -->
	<link rel="apple-touch-icon-precomposed" href="images/favicon.png">

	<!-- Firefox, Chrome, Safari, IE 11+ and Opera. 196x196 pixels in size. -->
	<link rel="icon" href="images/favicon.png">

	<head>
		<script>
		window.paceOptions = {
			restartOnRequestAfter: false,
			restartOnPushState: false
		}
		</script>
		<script language="javascript" type="text/javascript" src="../scripts/js/cookies.js"></script>
		<script language="javascript" type="text/javascript" src="../scripts/js/jQuery.js"></script>
		<script language="javascript" type="text/javascript" src="../scripts/js/pace.js"></script>
		<script> Pace.on('done', function() {
			Pace.stop();
		}); </script>
		<script language="javascript" type="text/javascript" src="scripts/field.js"></script>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		
		<script>
		$(window).on("unload", function () {
    $.ajax({
        type: 'POST',
        async: false,
        url: 'scripts/kill.php',
        success: function (data) {
            alert(data.response);
        },
        error: function () {
        }
    });
});
		</script>
	</head>
	<body>
		<div id="sidebar" class="ad-left" style="clear:both;background-color:#003c47;height:auto;overflow-x:hidden;position:fixed;width:20%;margin-top:50px;margin-bottom:50px">
			<h2 style="margin-top:0px">Status</h2>
			<div id="status" class="center form-control" style="height:auto;margin-top:0px;margin-right:8px;width:auto">
				Current Status:
			</div>
			<h2>Update Status</h1>
			<div id="input" class="form-control center" style="margin-top:0px;padding: 6px 6px; width:auto;">
				<?php
					echo "<button class='btn btn-flat btn-success status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_10_8(this.id)'>10-8</button> <button class='btn btn-flat btn-warning status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_10_6(this.id)'>10-6</button> <button class='btn btn-flat btn-danger status' id='" . $_SESSION['cad_uuid'] . "' onClick='update_10_7(this.id)'>10-7</button>";
					echo "<br>UUID: " . $_SESSION["cad_uuid"];
				?>
			</div>
			<h2>Priority</h1>
			<div id="prior" class="center form-control" style="height:auto;margin-top:0px;margin-right:8px;width:auto">
				Priority Status:
			</div>
		</div>
		<div id="main_panel" class="ad-right" style="height:auto;overflow-x:hidden;margin-top:0px;position:relative;width:75%;">
			<div id="bolos" style="clear:both;">
				<h2>Bolos</h2>
				<div id="bolo_table" class="center form-control" style="width:auto;height:auto;margin-top:0px;margin-right:8px;">
					<table border='1' cellpadding='10' id='tab'>
					<tr> <th style="width:100%">Bolo</th> </tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>