<?php
ini_set('display_errors', 1);
ob_start();
$file = "login";
$file_access = 9;
include '../config.php';
include '../includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL;
function renderForm($status, $error) {
?>
<html>
    <head>
        <script language="javascript" type="text/javascript" src="../scripts/js/jQuery.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/buttons.css">
        <link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
		<title><?php echo WEBSITE_TITLE; ?> | Terms of Use</title>
        <style>
        #acceptBtn {
            border-color:#13ff13;
        }
        </style>
    </head>
	<body>
        <br>
        <br>
        <div>
            <div class="login-box" id="login-box" style="width:70%; height: 90%;overflow-y:auto">
                <form action="" method="post">
                    <h2 style=text-align:center;color:tomato;font-weight:100;>Server 1 CAD Terms of Use</h2>
                    <br>
                    <p class="col-md-12 center">
                        We have recently updated our system and now require all users to accept the new Terms of Use.
                    </p>
                    <p class="col-md-12">
                        <b>Terms of Use</b>
				        <div style="width:100%;height:auto;margin-top:0px;" id="returnUsers" class="form-control" contenteditable="false">
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
                    </p>
                        <?php
                        $server = DB_HOST;
                        $user = DB_USER;
                        $pass = DB_PASSWORD;
                        $db = DB_NAME;
                        $connection = mysqli_connect($server, $user, $pass, $db);
                        if (!$connection) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                        if (isset($_SESSION['cad_uuid'])) {
                            echo '<p class="col-md-12">';
                            $uuid = $_SESSION['cad_uuid'];
                            $sql_terms =  mysqli_query($connection, "SELECT terms_accepted FROM cad_users WHERE uuid='$uuid'");
                            $value_terms = mysqli_fetch_object($sql_terms);
                            if ($value_terms->terms_accepted == 0) {
                                echo '<input type="submit" name="accept" id="acceptBtn" class="btn-primary btn form-control" style="width:100%; margin-top: 0px;" value="Agree">
                                <input type="submit" name="deny" id="denyBtn" class="btn-error btn form-control-error" style="width:100%; margin-top: 6px;" value="Deny">
                                Denial of these terms will result in termination of your account.';
                            } else {
                                echo 'You have already accepted the Terms of Use.';
                            }
                            echo '</p>';
                        }
                        ?>
                    <?php
                        if ($error == "denied") {
                            echo '
                            <p class="col-md-12 center" style="padding:0px;">
                                <font color="red">' . $error . '</font>
                            </p>
                            ';
                        }
                    ?>
                </form>
                <form action="" method="post" id="conDeny">
                <?php
                if ($error == "denied") {
                    echo '<input type="hidden" name="confirmDeny" value="1"/>';
                    echo '<script>
                    function checkDeny(){
                        return confirm("Are you sure you want to deny these terms? Your account will be terminated!");
                    }
                    if (checkDeny()) {
                        document.getElementById("conDeny").submit();
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
if (isset($_SESSION['cad_uuid'])) {
    $uuid = $_SESSION['cad_uuid'];
    $server = DB_HOST;
    $user = DB_USER;
    $pass = DB_PASSWORD;
    $db = DB_NAME;
    $connection = mysqli_connect($server, $user, $pass, $db);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST['confirmDeny'])) {
        if ($_POST['confirmDeny'] == 1) {
            $deleteUser = mysqli_query($connection, "DELETE FROM cad_users WHERE uuid='$uuid'")
            or die(mysqli_error($connection));
            $deleteUnit = mysqli_query($connection, "DELETE FROM units WHERE uuid='$uuid'")
            or die(mysqli_error($connection));
            session_destroy();
            header("Location: login");
        }
    } else if (isset($_POST['accept'])) {
        mysqli_query($connection, "UPDATE cad_users SET terms_accepted='1' WHERE uuid='$uuid'")
        or die(mysqli_error());
        session_destroy();
        header("Location: ..");
    } else if (isset($_POST['deny'])) {
        renderForm("", "denied");
    } else {
        renderForm("", "");
    }
} else {
    renderForm("", "");
}
?>
