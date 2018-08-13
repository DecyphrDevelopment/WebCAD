<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 2;
include '../config.php';
include '../includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL . "/dispatch";
require '../includes/check_access.inc.php';
function loadForm ($adminInfo) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<!--[if IE]><link rel="shortcut icon" href="images/favicon_IE.ico"><![endif]-->
		<link rel="shortcut icon" href="images/favicon_IE.ico">

		<!-- Touch Icons - iOS and Android 2.1+ 180x180 pixels in size. -->
		<link rel="apple-touch-icon-precomposed" href="images/favicon.png">

		<!-- Firefox, Chrome, Safari, IE 11+ and Opera. 196x196 pixels in size. -->
		<link rel="icon" href="images/favicon.png">
		<script language="javascript" type="text/javascript" src="../scripts/js/cookies.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
		<script language="javascript" type="text/javascript" src="scripts/dispatch.js?v52"></script>
		<link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
		<title><?php echo WEBSITE_TITLE; ?> | Dispatch CAD</title>
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
	<div id="sidebar" class="float-left" style="clear:both;height:auto;overflow-x:hidden;position:absolute;width:20%;margin-top:0px;margin-bottom:50px;z-index:1;">
		<h1 style="margin-top:0px">Add Unit</h1>
		<div id="input" class="form-control center" style="width:auto;height:auto;margin-top:0px;">
			<input type=text id="addunit" class="form-control center" placeholder="Unit Number" style="margin-top:0px;width:100%">
			<button id="addunit_btn" class="btn btn-primary form-control center" onclick="update_add(addunit.value)" style="width:100%; margin-top:6px;">Add Unit</button>
			<div id="result" style="clear:both;" class="center"></div>
		</div>
		<h1>Add Call</h1>
		<div id="calls_input" class="form-control center" style="width:auto;height:auto;margin-top:0px">
			<input type=text id="addcall" class="form-control center" placeholder="Call Description" style="margin-top:0px;width:100%">
			<button id="addcall_btn" class="btn btn-primary form-control center" onclick="addCall(addcall.value)" style="width:100%; margin-top:6px;">Add Call</button>
			<div id="call_result" style="clear:both;" class="center"></div>
		</div>
		<h1>Add Bolo</h1>
		<div id="bolos_input" class="form-control center" style="width:auto;height:auto;margin-top:0px">
			<input type=text id="addbolo" class="form-control center" placeholder="Bolo Description" style="margin-top:0px;width:100%">
			<button id="addbolo_btn" class="btn btn-primary form-control center" onclick="bolo_add(addbolo.value)" style="width:100%; margin-top:6px;">Add Bolo</button>
			<div id="bolo_result" style="clear:both;" class="center"></div>
		</div>
		<h1>Priority</h1>
		<div id="bolos_input" class="form-control center" style="width:auto;height:auto;margin-top:0px">
			<div id="prior" style="clear:both;" class="center"><font color='green'>No priority is currently in effect.</font></div>
			<label class="switch">
				<input id="priority" type="checkbox" onclick="priority();">
				<span class="slider round"></span>
			</label>
		</div>
		<?php
			if (isset($_SESSION['cad_level']) && $_SESSION['cad_level'] <= 1) {
				echo '
				<h1 style="color:tomato;">Admin Tools</h1>
        <form action="" method="post" id="adminTools">
				<div id="admin_tools" class="form-control center" style="width:auto;height:auto;margin-top:0px">
          <input type="submit" id="clearUnits" name="submit" class="btn btn-primary form-control" style="width:100%; margin-top: 6px;" value="Clear Units">
          <input type="submit" id="clearBolos" name="submit" class="btn btn-primary form-control" style="width:100%; margin-top: 6px;" value="Clear Bolos">
					<div id="admin_result" style="clear:both;" class="center">';
          if ($adminInfo != "") {
            if ($adminInfo == "bolos") {
              echo "<font color='green'>Cleared bolos.</font>";
            } else if ($adminInfo == "units") {
              echo "<font color='green'>Cleared units.</font>";
            } else if ($adminInfo == "error") {
              echo "<font color='red'>An unknown error has occured.</font>";
            }
          }
          echo '</div>
				</div>
				';
			}
		?>
	</div>
	<div id="main_panel" class="float-right" style="margin-top:0px">
		<div id="main" style="margin-bottom:25px">
			<h2 style="margin-top:0px">Units</h2>
			<div id="table" class="form-control center" style="width:auto;height:auto;margin-top:0px;margin-right:8px;">
				<table border='1' cellpadding='10' id='tab'>
				<tr> <th style="width:30%">Number</th> <th style="width:30%">Status</th> <th style="width:40%">Update</th> </tr>
				</table>
			</div>
		</div>
		<div id="calls" style="clear:both;">
			<h2 style="margin-top:0px">Calls</h2>
			<div id="calls_table" class="form-control center" style="width:auto;height:auto;margin-top:0px;margin-right:8px;">
				<table border='1' cellpadding='10' id='tab'>
				<tr> <th style="width:30%">Call Description</th> <th style="width:50%">Assigned Units</th> <th style="width:20%">Update</th> </tr>
				</table>
			</div>
		</div>
		<div id="bolos" style="clear:both;">
			<h2 style="margin-top:25px">Bolos</h2>
			<div id="bolo_table" class="form-control center" style="width:auto;height:auto;margin-top:0px;margin-right:8px;">
				<table border='1' cellpadding='10' id='tab'>
				<tr> <th style="width:70%">Bolo</th> <th style="width:30%">Update</th> </tr>
				</table>
			</div>
		</div>
	</div>

	</body>
</html>
<?php
}
if (isset($_POST['adminTools'])) {
  if (isset($_SESSION['cad_level']) && $_SESSION['cad_level'] <= 1) {
    if (isset($_POST['clearBolos'])) {
      $sqlQuery = mysqli_query($connection, "TRUNCATE TABLE cad_bolos");
      logInfo("Cleared bolos", 1);
      loadForm("bolos");
    } else if (isset($_POST['clearUnits'])) {
      $sqlQuery = mysqli_query($connection, "UPDATE units SET active='0' WHERE active='1'");
      $sqlQuery = mysqli_query($connection, "DELETE FROM units WHERE active='3'");
      logInfo("Cleared units", 1);
      loadForm("units");
    } else {
      loadForm("error");
    }
  }
} else {
  loadForm("");
}
?>
