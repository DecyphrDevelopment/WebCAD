<?php
$file_access = 9;
include 'config.php';
$_SESSION['return_url'] = BASE_URL;
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/<?php echo STYLE; ?>">
		<link rel="stylesheet" type="text/css" href="css/buttons.css">
		<title><?php echo WEBSITE_TITLE; ?> | Maintenance</title>
	</head>
	<br><br><br><br>
	<body>
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
		</script>
		<div class="center">
			<h2 style="color:tomato">The website is currently undergoing maintenance!</h2>
			<h1>Please check back later.</h1>
			<button id="disp" class="btn btn-primary form-control" onclick="window.location.href='account/login'" style="width:25%">Admin Login</button>
		</div>
	</body>
</html>