<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 1;
include '../../config.php';
include '../../includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL . "/dispatch";
require '../../includes/check_access.inc.php';
?>
<html>
	<head>
		<script language="javascript" type="text/javascript" src="../../scripts/js/cookies.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
		<script language="javascript" type="text/javascript" src="user-lookup.js?v=1.1.8"></script>
		<link rel="stylesheet" type="text/css" href="../../css/<?php echo STYLE; ?>">
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
        </script>
		<title><?php echo WEBSITE_TITLE; ?> | IP Lookup</title>
	</head>
    <body>
		<script>
			$(function() {
				$("#unInput").autocomplete({
					source: '../../includes/autocomplete.php',
					select: function (event, ui) {
						$("#unInput").val(ui.item.label);
						lookup();
						return false;
					}
				});
			});
		</script>
		<br><br><br>
        <div id="sidebar" class="float-left" style="margin-top:0px;">
			<input type=text class="form-control center" id="unInput" placeholder="Username" style="width:100%; margin-top:0px; text-align:left;">
            <button id="unSubmit" onclick="lookup()" class="btn btn-primary form-control center" style="width:100%; margin-top:6px;">Search</button>
			<?php include '../../includes/admin_sidebar_buttons.inc.php'; ?>
        </div>
        <div id="main_panel" class="float-right" style="">
            <div id="main" style="margin-bottom:0px;height:90%;">
				<h2 id="log" style="margin-top:0px;">User Lookup</h2>
				<p style="margin-bottom:5px;" id="sd">Username: </p>
				<input type=text class="form-control center" id="returnUsername" placeholder="Username" style="width:100%; margin-top:0px; text-align:left;" value="" readonly>
				<p style="margin-bottom:5px;" id="sd">Group: </p>
				<input type=text class="form-control center" id="userGroup" placeholder="Username" style="width:100%; margin-top:0px; text-align:left;" value="" readonly>
				<p style="margin-bottom:5px;" id="sd">UUID: </p>
				<input type=text class="form-control center" id="userUUID" placeholder="UUID" style="width:100%; margin-top:0px; text-align:left;" value="" readonly>
				<p style="margin-bottom:5px;" id="sd">Unit Number: </p>
				<input type=text class="form-control center" id="userUN" placeholder="Unit Number" style="width:100%; margin-top:0px; text-align:left;" value="" readonly>
                <button class="btn btn-primary form-control center" onclick="goToLogs()" style="width:100%;">Search Logs From This User</button>
				<p style="margin-bottom:5px;" id="sd">Known IPs: </p>
				<div style="width:100%;height:15%;margin-top:0px;overflow-y:scroll;" id="returnIPs" class="form-control" contenteditable="false"></div>
				<p style="margin-bottom:5px;" id="sd">Known Aliases: </p>
				<div style="width:100%;height:15%;margin-top:0px;overflow-y:scroll;" id="returnAliases" class="form-control" contenteditable="false"></div>
            </div>
			<br><br><br>
        </div>
	</body>
</html>
