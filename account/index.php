<?php
include '../config.php';
include '../logging/log.php';
$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
function renderForm($usernamer, $email, $unitNumber, $password, $passwordConfirm, $error, $info) {
include '../includes/menu.inc.php';
?>
<html>
    <head>
        <script language="javascript" type="text/javascript" src="../scripts/js/jQuery.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
        <link rel="stylesheet" type="text/css" href="../css/buttons.css">
		<title><?php echo WEBSITE_TITLE; ?> | Account</title>
        <style>
        #submitBtn {
            border-color:#13ff13;
        }
        </style>
    </head>
	<body>
		<script>
            document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
            document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
		</script>
        <br>
        <br>
        <div>
            <div class="login-box" id="login-box">
                <form action="" method="post" autocomplete="off">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    <h2 style=text-align:center;color:tomato;font-weight:100;>Account Details</h2>
                    <br>
                    <p class="col-md-12">
                        <b>Username</b>
                        <input type=text name="username" class="form-control" placeholder="Username" style="width:100%; margin-top: 0px;" value="<?php echo $usernamer ?>" autocomplete="new-password">
                    </p>
                    <p class="col-md-12">
                        <b>E-Mail</b>
                        <input type=text name="email" class="form-control" placeholder="E-Mail" style="width:100%; margin-top: 0px;" value="<?php echo $email ?>" autocomplete="new-password">
                    </p>
                    <p class="col-md-12">
                        <b>Unit Number</b>
                        <input type=text name="unitNumber" class="form-control" placeholder="Unit Number" style="width:100%; margin-top: 0px;" value="<?php echo $unitNumber ?>" autocomplete="new-password">
                    </p>
                    <p class="col-md-12">
                        <b>Password</b>
                        <input type=password name="password" class="form-control" placeholder="Password" style="width:100%; margin-top: 0px;" value="<?php echo $password ?>" autocomplete="new-password">
                    </p>
                    <p class="col-md-12">
                        <b>Confirm Password</b>
                        <input type=password name="passwordConfirm" class="form-control" placeholder="Confirm Password" style="width:100%; margin-top: 0px;" value="<?php echo $passwordConfirm ?>" autocomplete="new-password">
                    </p>
                    <p class="col-md-12">
                        <input type="submit" id="submitBtn" name="submit" class="btn btn-primary form-control" style="width:100%; margin-top: 0px;" value="Apply">
                        <input type="submit" name="cancel" class="btn btn-error form-control-error" style="width:100%; margin-top: 6px;" value="Cancel">
                    </p>
                    <?php
                    if ($error != "") {
                        echo "<p><font color=red>".$error."</font></p>";
                    }
                    if ($info != "") {
                        echo "<p class='center'><font color=green>".$info."</font></p>";
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
$userr = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;
$connection = mysqli_connect($server, $userr, $pass, $db);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $run = true;
    $error = "";
    $uuid = $_SESSION['cad_uuid'];
    $username = mysqli_real_escape_string($connection, htmlspecialchars($_POST['username']));
    $email = mysqli_real_escape_string($connection, htmlspecialchars($_POST['email']));
    $unitNumber = mysqli_real_escape_string($connection, htmlspecialchars($_POST['unitNumber']));
    $password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['password']));
    $passwordConfirm = mysqli_real_escape_string($connection, htmlspecialchars($_POST['passwordConfirm']));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if ($username == "" && $run == true) {
        $error = "Please enter a valid username.";
        $run = false;
        renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error, "");
    } else if ($email == "" && $run == true) {
        $error = "Please enter a valid E-Mail.";
        $run = false;
        renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error, "");
    } else if ($password == "" && $passwordConfirm == "" && $run == true) {
        $users_sql = mysqli_query($connection, "SELECT * FROM cad_users WHERE uuid='$uuid'") or die(mysqli_error($connection));
        $users_result = mysqli_fetch_object($users_sql);
        $hashedPassword = $users_result->password;
    } else if ($password != $passwordConfirm && $run == true) {
        $error = "Your passwords do not match.";
        $run = false;
        renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error, "");
    } else if ($run == true) {
        $users_sql = mysqli_query($connection, "SELECT * FROM cad_users WHERE uuid='$uuid'") or die(mysqli_error($connection));
        $unit_sql = mysqli_query($connection, "SELECT * FROM units WHERE uuid='$uuid'") or die(mysqli_error($connection));
        $users_result = mysqli_fetch_object($users_sql);
        $unit_result = mysqli_fetch_object($unit_sql);
        $oldUsername = $users_result->username;
        $oldEmail = $users_result->email;
        $oldUnitNumber = $unit_result->callsign;
        if ($username != $oldUsername && $run == true) {
            $sql = mysqli_query($connection, "SELECT uuid FROM cad_users WHERE username='$username'")
            or die(mysqli_error($connection));
            if (mysqli_num_rows($sql) >= 1) {
                $uuidres = mysqli_fetch_object($sql);
                if ($uuid != $uuidres->uuid) {
                    $error = "That username is already taken.";
                    $run = false;
                    renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error, "");
                }
            }
        }
        if ($unitNumber != $oldUnitNumber && $run == true) {
            $sql = mysqli_query($connection, "SELECT uuid FROM units WHERE callsign='$unitNumber'")
            or die(mysqli_error($connection));
            if (mysqli_num_rows($sql) >= 1) {
                $uuidres = mysqli_fetch_object($sql);
                if ($uuid != $uuidres->uuid) {
                    $error = "That unit number is already taken.";
                    renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error, "");
                }
            }
        }
        if ($email != $oldEmail && $run == true) {
            $sql = mysqli_query($connection, "SELECT uuid FROM cad_users WHERE email='$email'")
            or die(mysqli_error($connection));
            if (mysqli_num_rows($sql) >= 1) {
                $uuidres = mysqli_fetch_object($sql);
                if ($uuid != $uuidres->uuid) {
                    $error = "That email is already taken.";
                    $run = false;
                    renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error, "");
                }
            }
        }
    }
    if ($run == true) {
        $update_sql = mysqli_query($connection, "UPDATE cad_users SET username='$username', password='$hashedPassword', email='$email' WHERE uuid='$uuid'")
        or die(mysqli_error($connection));
        $update_sql = mysqli_query($connection, "UPDATE units SET callsign='$unitNumber' WHERE uuid='$uuid'")
        or die(mysqli_error($connection));
        $_SESSION["cad_user"] = $username;
        $_SESSION["cad_unitnumber"] = $unitNumber;
        renderForm($username, $email, $unitNumber, "", "", $error, "Successfully updated account info.");
    }
}
else {
    $uuid = $_SESSION['cad_uuid'];
    $users_sql = mysqli_query($connection, "SELECT * FROM cad_users WHERE uuid='$uuid'") or die(mysqli_error($connection));
    $unit_sql = mysqli_query($connection, "SELECT callsign FROM units WHERE uuid='$uuid'") or die(mysqli_error($connection));
    $users_result = mysqli_fetch_object($users_sql);
    $unit_result = mysqli_fetch_object($unit_sql);
    $usernamer = $users_result->username;
    $email = $users_result->email;
    $unitNumber = $unit_result->callsign;
    renderForm($usernamer, $email, $unitNumber, "", "", "", "");
}
?>
