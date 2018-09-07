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
		<script language="javascript" type="text/javascript" src="../../scripts/js/jQuery.js"></script>
		<script language="javascript" type="text/javascript" src="ip-lookup.js?v=1.1.0"></script>
		<link rel="stylesheet" type="text/css" href="../../css/<?php echo STYLE; ?>">
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
        </script>
		<title><?php echo WEBSITE_TITLE; ?> | IP Lookup</title>
	</head>
    <body>
		<script>
			if (getUrlParameter('ip') != undefined) {
				$("#ipInput").val(getUrlParameter('ip'));
				lookup();
			}
		</script>
		<br><br><br>
        <div id="sidebar" class="float-left" style="margin-top:0px;">
			<input type=text class="form-control center" id="ipInput" placeholder="IP" style="width:100%; margin-top:0px; text-align:left;">
            <button id="ipSubmit" onclick="lookup()" class="btn btn-primary form-control center" style="width:100%; margin-top:6px;">Search</button>
			<?php include '../../includes/admin_sidebar_buttons.inc.php'; ?>
        </div>
        <div id="main_panel" class="float-right" style="">
            <div id="main" style="margin-bottom:0px;height:90%;">
				<h2 id="log" style="margin-top:0px;">IP Lookup</h2>
				<p style="margin-bottom:5px;">IP:</p>
				<input type=text class="form-control center" id="returnIP" placeholder="IP" style="width:100%; margin-top:0px; text-align:left;" value="" readonly>
				<p style="margin-bottom:5px;">Known Aliases:</p>
				<div style="width:100%;height:25%;margin-top:0px;overflow-y:scroll;" id="returnUsers" class="form-control" contenteditable="false"></div>
                <button class="btn btn-primary form-control center" onclick="goToLogs()" style="width:100%;">Search Logs From This IP</button>
				<p style="margin-bottom:5px;" id="isIpBanned">Is IP Banned: </p>
				<p style="margin-bottom:5px;">Banned By:</p>
				<input type=text class="form-control center" id="bannedBy" placeholder="Banned By" style="width:100%; margin-top:0px; text-align:left;" value="" readonly>
				<p style="margin-bottom:5px;">Ban Reason:</p>
				<input type=text class="form-control center" id="banReason" placeholder="Ban Reason" style="width:100%; margin-top:0px; text-align:left;" value="" readonly>
            </div>
        </div>
	</body>
</html>
