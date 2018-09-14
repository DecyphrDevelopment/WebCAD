<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include '../config.php';
include '../includes/menu.inc.php';
if (!isset($_SESSION['cad_level']) || $_SESSION['cad_level'] > 0) {
	header('Location: ' . BASE_URL . '/no-permission');
}
function render($title, $license, $version, $tou, $info) {
?>
<html>
	<head>
		<script language="javascript" type="text/javascript" src="../scripts/js/cookies.js"></script>
		<script language="javascript" type="text/javascript" src="../scripts/js/jQuery.js"></script>
		<link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
        </script>
		<title><?php echo WEBSITE_TITLE; ?> | Settings</title>
	</head>
	<body>
		<br><br><br>
        <div id="sidebar" class="float-left" style="margin-top:0px;">
			<?php include '../includes/admin_sidebar_buttons.inc.php'; ?>
        </div>
        <div id="main_panel" class="float-right" style="">
            <div id="main" style="'height:auto;">
				<form action="" method="post">
                    <h2 style=color:tomato;font-weight:100;>Website Settings</h2>
                    <br>
                    <p class="col-md-12">
                        <b>Website Title</b>
                        <input type=text name="title" class="form-control" placeholder="Website Title" style="width:100%; margin-top: 0px;" value="<?php echo $title; ?>">
                    </p>
                    <p class="col-md-12">
                        <b>Version</b>
                        <input type=text name="version" class="form-control" placeholder="Version" style="width:100%; margin-top: 0px;" value="<?php echo $version; ?>" readonly>
                    </p>
                    <p class="col-md-12">
                        <b>License Key <u>(Never Change This)</u></b>
                        <input type=text name="license" class="form-control" placeholder="License" style="width:100%; margin-top: 0px;" value="<?php echo $license; ?>" readonly>
                    </p>
                    <p class="col-md-12">
						<b>Terms of Use</b>
						<textarea rows="10" name="tou" style="width:100%; margin-top: 0px; height:auto;" class="form-control"><?php echo $tou; ?></textarea>
                    </p>
                    <p class="col-md-12">
                        <input type="submit" id="submitBtn" name="submit" class="btn btn-primary form-control" style="width:100%; margin-top: 0px;" value="Apply">
                        <input type="submit" name="cancel" class="btn btn-error form-control-error" style="width:100%; margin-top: 6px;" value="Cancel">
                    </p>
                    <?php 
					if ($info != "" && $info != "changed") {
                        echo "<p><font color=green>".$info."</font></p>";
					}
                    ?>
                </form>
				<form action="" method="post" id="conTerms">
                <?php
                if ($info == "changed") { 
					echo '<input type="hidden" name="confirmTerms" value="1"/>
					<input type="hidden" name="terms" value="' . $tou . '"/>';
                    echo '<script>
                    function checkDeny(){
                        return confirm("Changing these terms will force all users to re-accept the Terms of Use no matter how small the change is.");
                    }
                    if (checkDeny()) {
                        document.getElementById("conTerms").submit();
                    }
                    </script>';
                }
                ?>
                </form>
            </div>
        </div>
	</body>
</html>

<?php
}
if (session_status() == PHP_SESSION_NONE) { 
    session_start();
}
ini_set('display_errors', 1);
    
ob_start();
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../logging/log.php");

$host = DB_HOST;
$port = DB_PORT;
$username = DB_USER;
$password = DB_PASSWORD;
$db_name = DB_NAME;
	
$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

if (isset($_POST['submit'])) {
	$oldTitle = getTitle();
	$version = getVersion();
	$oldTOU = getTOU();
	$newTitle = mysqli_real_escape_string($connection, stripslashes($_POST['title']));
	$newTOU = mysqli_real_escape_string($connection, stripslashes($_POST['tou']));
	$info = "";

	if ($newTitle != $oldTitle) {
		$updateTitle =  mysqli_query($connection, "UPDATE cad_settings SET website_title='$newTitle' WHERE reference='1'");
		$info = "Successfully updated title.";
	}
	if ($newTOU != $oldTOU) {
		$info = "changed";
	}

	render(getTitle(), getLicense(), getVersion(), $newTOU, $info);
} else if (isset($_POST['conTerms'])) {
	$oldTOU = getTOU();
	$newTOU = mysqli_real_escape_string($connection, stripslashes($_POST['terms']));

	if ($_POST['confirmTerms'] == 1) {
		if ($newTOU != $oldTOU) {
			$updateTOU =  mysqli_query($connection, "UPDATE cad_settings SET terms_of_use='$newTOU' WHERE reference='1'");
			$updateAcceptedUsers =  mysqli_query($connection, "UPDATE cad_users SET terms_accepted='0' WHERE terms_accepted='1'");
		}
		render(getTitle(), getLicense(), getVersion(), getTOU(), "Successfully updated Terms of Use.");
	}
} else {
	render(getTitle(), getLicense(), getVersion(), getTOU(), "");
}

function getTitle() {
	$host = DB_HOST;
	$port = DB_PORT;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$db_name = DB_NAME;
		
	$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

	$sql_title =  mysqli_query($connection, "SELECT website_title FROM cad_settings WHERE reference='1'");
	$value_title = mysqli_fetch_object($sql_title);
	$title = $value_title->website_title;
	return $title;
}
function getLicense() {
	$host = DB_HOST;
	$port = DB_PORT;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$db_name = DB_NAME;
		
	$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

	$sql_license =  mysqli_query($connection, "SELECT product_license FROM cad_settings WHERE reference='1'");
	$value_license = mysqli_fetch_object($sql_license);
	$license = $value_license->product_license;
	return $license;
}
function getVersion() {
	$host = DB_HOST;
	$port = DB_PORT;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$db_name = DB_NAME;
		
	$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

	$sql_version =  mysqli_query($connection, "SELECT website_version FROM cad_settings WHERE reference='1'");
	$value_version = mysqli_fetch_object($sql_version);
	$version = $value_version->website_version;
	return $version;
}
function getTOU() {
	$host = DB_HOST;
	$port = DB_PORT;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$db_name = DB_NAME;
		
	$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

	$sql_terms =  mysqli_query($connection, "SELECT terms_of_use FROM cad_settings WHERE reference='1'");
	$value_terms = mysqli_fetch_object($sql_terms);
	$TOU = $value_terms->terms_of_use;
	return $TOU;
}
?>