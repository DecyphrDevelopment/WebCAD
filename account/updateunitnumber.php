<?php
ob_start();
include '../config.php';
include '../includes/menu.inc.php';
include '../logging/log.php';
$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
function renderForm($unitNumber, $error) {
?>
<html>
    <head>
        <script language="javascript" type="text/javascript" src="../scripts/js/jQuery.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/buttons.css">
        <link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
		<title><?php echo WEBSITE_TITLE; ?> | Register</title>
        <style>
        #submitBtn {
            border-color:#13ff13;
        }
        </style>
    </head>
	<body>
        <br>
        <br>
        <div>
            <div class="login-box" id="login-box">
                <form action="" method="post">
                    <h2 style=text-align:center;color:tomato;font-weight:100;>Server 1 CAD Registration</h2>
                    <br>
                    <p class="col-md-12 center">
                        We have recently updated our API and we are requiring all users to enter their unit number.
                    </p>
                    <p class="col-md-12">
                        <b>Unit Number</b> (Ex: D-446 / SU-446)
                        <input type=text name="unitNumber" class="form-control" placeholder="Unit Number" style="width:100%; margin-top: 0px;" value="<?php echo $unitNumber; ?>">
                    </p>
                    <p class="col-md-12">
                        <input type="submit" name="submit" id="submitBtn" class="btn-submit btn form-control" style="width:100%; margin-top: 0px;" value="Submit">
                    </p>
                    <?php
                        if ($error != "") {
                            echo '
                            <p class="col-md-12 center" style="padding:0px;">
                                <font color="red">' . $error . '</font>
                            </p>
                            ';
                        }
                    ?>
                </form>
            </div>
        </div>
	</body>
</html>
<?php
}
$server = DB_HOST;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;
$connection = mysqli_connect($server, $user, $pass, $db);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $unitNumber = mysqli_real_escape_string($connection, htmlspecialchars($_POST['unitNumber']));    if ($unitNumber == "") {
        $error = "Please enter a valid unit number.";
        renderForm($unitNumber, $error);
    } else {
        $uuid = $_SESSION['cad_uuid'];
        $sql = mysqli_query($connection, "SELECT callsign FROM units WHERE callsign='$unitNumber'")
        or die(mysqli_error($connection));
        if (mysqli_num_rows($sql) >= 1) {
            $error = "That unit number is already taken.";
            renderForm($unitNumber, $error);
        } else {
            $sql = mysqli_query($connection, "INSERT INTO units VALUES ('$uuid', '$unitNumber', DEFAULT, DEFAULT, '')")
            or die(mysqli_error($connection));
			$_SESSION["cad_unitnumber"] = $unitNumber;
            header("Location: ../");
        }
    }
}
else {
    renderForm("", "");
}
?>
