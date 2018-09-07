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
		<script language="javascript" type="text/javascript" src="log.js?v=1.1.4"></script>
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
            <button id="action_btn" class="btn btn-primary form-control center" onclick="getActionLog()" style="width:100%; margin-top:0px;">Action Log</button>
            <button id="login_btn" class="btn btn-primary form-control center" onclick="getLoginLog()" style="width:100%; margin-top:6px;">Login Log</button>
            <button id="admin_btn" class="btn btn-primary form-control center" onclick="getAdminLog()" style="width:100%; margin-top:6px;">Admin Log</button>
            <button id="clear_btn" class="btn btn-error form-control-error center" onclick="clearCurrentLog()" style="width:100%; margin-top:6px;">Clear Current Log</button>
			<div id="result" style="margin-top:20px;width:100%;height:auto;" class="center form-control"></div>
			<?php include '../../includes/admin_sidebar_buttons.inc.php'; ?>
        </div>
        <div id="main_panel" class="float-right" style="">
            <div id="main" style="margin-bottom:75px;height:60%;">
                <h2 id="log" style="margin-top:0px;">Log</h2>
				<div style="width:100%;height:100%;margin-top:0px;overflow-y:scroll;" id="log_area" class="form-control" contenteditable="false"></div>
            </div>
        </div>
	</body>
</html>
