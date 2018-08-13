<?php
$file_access = 9;
include 'config.php';
include 'includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL;
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/<?php echo STYLE; ?>">
		<link rel="stylesheet" type="text/css" href="css/buttons.css">
		<title><?php echo WEBSITE_TITLE; ?> | No Permission</title>
	</head>
	<br><br><br><br>
	<body>
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
		</script>
		<div class="center">
			<h2 style="color:tomato">You do not have access to that page!</h2>
			<a href="."><button id="disp" class="btn btn-primary form-control" style="width:25%">Return to Home Page</button></a>
		</div>
	</body>
</html>
