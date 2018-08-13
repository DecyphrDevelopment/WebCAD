<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 9;
include 'config.php';
$_SESSION['return_url'] = BASE_URL;
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/<?php echo STYLE; ?>">
		<link rel="stylesheet" type="text/css" href="css/buttons.css">
		<title><?php echo WEBSITE_TITLE; ?> | Banned</title>
	</head>
	<br><br><br><br>
	<body>
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
		</script>
		<div class="center form-control" style="height:auto;">
			<h2 style="color:tomato">You have been blacklisted from the Server 1 CAD.</h2>
			<h1>Contact an admin about getting unblacklisted.</h1>
			<h1>Reason:</h1>
			<p><?php echo $_SESSION['reason']; ?></p>
		</div>
	</body>
</html>