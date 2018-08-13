<?php
$file = "login";
$file_access = 9;
include 'config.php';
include 'includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL;
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/<?php echo STYLE; ?>">
		<link rel="stylesheet" type="text/css" href="css/buttons.css">
		<title><?php echo WEBSITE_TITLE; ?> | About</title>
	</head>
	<br><br>
	<body>
		<style>
		p {
			color: #ccc;
            margin-left: 26px;
		}
		.modal {
			display: none;
			position: fixed;
			z-index: 3;
			left: 0;
			top: 0;
			width: 100%;
			height: calc(100% - 20px);
			overflow: hidden;
			background-color: rgb(0,0,0);
			background-color: rgba(0,0,0,0.4);
		}

		.modal-content {
			background-color: #fefefe;
			margin: auto auto;
			overflow: auto;
			border: 1px solid #888;
			width: 80%;
			-webkit-animation: fadein 0.4s;
			-moz-animation: fadein 0.4s;
			-ms-animation: fadein 0.4s;
			-o-animation: fadein 0.4s;
			animation: fadein 0.4s;
			top: 50%;
			transform: perspective(1px) translateY(-50%);
			position: relative;
		}

		@keyframes fadein {
			from { opacity: 0; }
			to   { opacity: 1; }
		}
		@-moz-keyframes fadein {
			from { opacity: 0; }
			to   { opacity: 1; }
		}
		@-webkit-keyframes fadein {
			from { opacity: 0; }
			to   { opacity: 1; }
		}
		@-ms-keyframes fadein {
			from { opacity: 0; }
			to   { opacity: 1; }
		}
		@-o-keyframes fadein {
			from { opacity: 0; }
			to   { opacity: 1; }
		}

		.close {
			color: #d6d6d6;
			background-color: #262836;
			float: right;
			font-size: 30px;
			font-weight: bold;
			line-height: 1.1;
			margin-top: 0px;
		}

		.close:hover,
		.close:focus {
			color: #262836;
			background-color: #d6d6d6;
			text-decoration: none;
			cursor: pointer;
		}
		.progress {
			width: calc(100% - 26px);
			background-color: #ddd;
            margin-left: 26px;
		}

		.bar {
			width: 1%;
			height: 30px;
			background-color: #4CAF50;
			padding-top: 6px;
		}

		</style>
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
		</script>
		<div class="">
			<h2 style="color:white">About the AFS Server 1 CAD System</h2>
			<p>The server 1 CAD was engineered by <strong>decyphr</strong> to be friendly with a public server environment. This being said, some things may not be so self explanatory.</p>
			<p>If you are caught abusing the system you will be blacklisted from the website.</p>
            <br>
			<h1 style="color:white">Dispatch</h1>
			<p>The dispatch page is primarily to be used by only dispatchers. There you can mark units 10-8, 10-7, 10-6, or make them off duty.</p>
			<p>Also, on the dispatch page you can add/remove bolos, manually add/remove units, and update the priority status.</p>
            <br>
			<h1 style="color:white">Field MDT</h1>
			<p>For new accounts, you are automatically set to off duty and your unit is not show on the dispatch panel.</p>
			<p>To go on duty, just update your status to 10-8, 10-7, or 10-6. To go off duty and remove yourself from the dispatch panel, Click the "Off Duty" button.</p>
            <br>
			<h1 style="color:white">Contact</h1>
			<p>View the developers information by clicking the button below.</p>
            <button id="openBox" class="btn btn-primary form-control" style="width:15%;margin-top:14px;display:block;margin-bottom:14px;margin-left:26px;">Open Details</button>
            <div id="decyphr" class="modal">
				<div class="modal-content" style="background-color:#11141c; height:auto; border:1px solid #11141c; border-radius:4px;">
					<div style="background-color:#262836;width:100%;height:auto;">
						<h2 style="color:#ccc;margin-top:0px;margin-left:4px;display:inline;">Decyphr</h1>
						<span class="close" id="closeSpan" style="display:inline;margin-top:0px;margin-left:8px;width:32px;text-align:center">&times;</span>
					</div>
					<div style="width:100%;height:auto;padding:20px;">
						<h2 style="margin-top:0px;margin-bottom:0px;">Contact</h2>
						<p style="margin-top:4px;"><font color="#7289DA"><strong><u>Discord:</u> </strong>decyphr#1065</font></p>
						<p><font color="#00adee"><strong><u>Steam Name (IGN):</u> </strong>DadDecyphr</font></p>
						<p><font color="#00adee"><strong><u>Steam ID:</u> </strong>76561198304354640</font></p>
						<h2 style="margin-top:20px;margin-bottom:0px;">Web Programming Knowledge</h2>
						<p style="margin-top:4px;">HTML/CSS</p>
						<div id="htmlProgress" class="progress">
							<div id="htmlBar" class="bar"></div>
						</div>
						<p>JS/jQuery</p>
						<div id="jsProgress" class="progress">
							<div id="jsBar" class="bar"></div>
						</div>
						<p>PHP</p>
						<div id="phpProgress" class="progress">
							<div id="phpBar" class="bar"></div>
						</div>
					</div>
				</div>
            </div>
            <br>
			<h1 style="color:white">Terms of Use</h1>
            <p>Copyright © <?php echo date("Y"); ?> AFS Roleplay. All rights reserved.</p>
			<p>Copyright © <?php echo date("Y"); ?> decyphr. All rights reserved.</p>
			<p>View the Terms of Use by clicking the button below.</p>
			<button id="openTermsBox" class="btn btn-primary form-control" style="width:15%;margin-top:14px;display:block;margin-bottom:14px;margin-left:26px;">Open Terms of Use</button>
            <div id="terms" class="modal">
				<div class="modal-content" style="background-color:#11141c; height:auto; border:1px solid #11141c; border-radius:4px;">
					<div style="background-color:#262836;width:100%;height:auto;">
						<h2 style="color:#ccc;margin-top:0px;margin-left:4px;display:inline;">Terms of Use</h1>
						<span class="close" id="closePISpan" style="display:inline;margin-top:0px;margin-left:8px;width:32px;text-align:center">&times;</span>
					</div>
					<div style="width:100%;height:auto;padding:20px;">
					<?php
                        $server = DB_HOST;
                        $user = DB_USER;
                        $pass = DB_PASSWORD;
                        $db = DB_NAME;
                        $connection = mysqli_connect($server, $user, $pass, $db);
                        if (!$connection) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                            $terms_of_use_sql =  mysqli_query($connection, "SELECT terms_of_use FROM cad_settings WHERE reference='1'");
                            $terms_of_use_fetch = mysqli_fetch_object($terms_of_use_sql);
                            echo $terms_of_use_fetch->terms_of_use;
                    ?>
					</div>
				</div>
            </div>
            <br>
			<h1 style="color:white">Private License Purchase Information</h1>
			<p>View the purchase terms by clicking the button below.</p>
			<button id="openPIBox" class="btn btn-primary form-control" style="width:15%;margin-top:14px;display:block;margin-bottom:14px;margin-left:26px;">Open Purchase Terms</button>
            <div id="purchase" class="modal">
				<div class="modal-content" style="background-color:#11141c; height:auto; border:1px solid #11141c; border-radius:4px;">
					<div style="background-color:#262836;width:100%;height:auto;">
						<h2 style="color:#ccc;margin-top:0px;margin-left:4px;display:inline;">Purchase Terms</h1>
						<span class="close" id="closePISpan" style="display:inline;margin-top:0px;margin-left:8px;width:32px;text-align:center">&times;</span>
					</div>
					<div style="width:100%;height:auto;padding:20px;">
						<h2 style="margin-top:0px;margin-bottom:0px;">Contact</h2>
						<p>If you wish to use the AFS CAD software for commercial or noncommercial use, contact the AFS CAD developer via Discord at <font color="#7289DA"><strong>decyphr#1065</strong></font>.</p>
						<h2 style="margin-top:20px;margin-bottom:0px;">Terms</h2>
						<p>The AFS administration team can choose a price, set terms, and choose to provide a warranty or not to provide a warranty per purchase agreement.</p>
						<p>If you have been given written consent or bought rights to use this software, and you remove licensing and/or credit information you're license to use the software will be revoked without refund.</p>
						<p>If you have been given written consent or bought rights to use this software, the AFS administration or development team is not liable to provide warranty services unless specified otherwise in your specific purchase agreement.</p>
						<p>If you have been given written consent or bought rights to use this software, the AFS administration or development team can revoke your license to use the software at any time if it is deemed suitable for license revocation.</p>
					</div>
				</div>
            </div>
            <br>
            <br>
		</div>
		
		<script>
		var modal = document.getElementById('decyphr');
		var btn = document.getElementById("openBox");
		var span = document.getElementsByClassName("close")[0];
		btn.onclick = function() {
			modal.style.display = "block";
			move();
		}
		span.onclick = function() {
			modal.style.display = "none";
		}
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
		var modal2 = document.getElementById('terms');
		var btn2 = document.getElementById("openTermsBox");
		var span2 = document.getElementsByClassName("close")[1];
		btn2.onclick = function() {
			modal2.style.display = "block";
		}
		span2.onclick = function() {
			modal2.style.display = "none";
		}
		window.onclick = function(event) {
			if (event.target == modal2) {
				modal2.style.display = "none";
			}
		}
		var modal3 = document.getElementById('purchase');
		var btn3 = document.getElementById("openPIBox");
		var span3 = document.getElementsByClassName("close")[2];
		btn3.onclick = function() {
			modal3.style.display = "block";
		}
		span3.onclick = function() {
			modal3.style.display = "none";
		}
		window.onclick = function(event) {
			if (event.target == modal3) {
				modal3.style.display = "none";
			}
		}
		</script>
		<script>
		function move() {
			var htmlelem = document.getElementById("htmlBar");
			var jselem = document.getElementById("jsBar");
			var phpelem = document.getElementById("phpBar");
			var width = 1;
			var id = setInterval(frame, 10);
			function frame() {
				if (width <= 95) {
					htmlelem.style.width = width + '%'; 
					htmlelem.innerHTML = "<p class='center' style='padding:0px;'>" + width + "%</p>";
				}
				if (width <= 90) {
					jselem.style.width = width + '%'; 
					jselem.innerHTML = "<p class='center' style='padding:0px;'>" + width + "%</p>";
				}
				if (width <= 90) {
					phpelem.style.width = width + '%'; 
					phpelem.innerHTML = "<p class='center' style='padding:0px;'>" + width + "%</p>";
				}
				width++; 
			}
		}
		</script>
	</body>
</html>
