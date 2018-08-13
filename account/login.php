<?php
$file = "login";
include '../config.php';
include '../includes/menu.inc.php';
function renderForm($username, $password, $error) {
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/buttons.css">
        <link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
		<title><?php echo WEBSITE_TITLE; ?> | Login</title>
        <style>
        #submitBtn {
            border-color:#13ff13;
        }
        a:link {
            text-decoration: none;
        }
        a:visited {
    text-decoration: none;
}
a:hover {
    text-decoration: none;
}

a:active {
    text-decoration: none;
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
            <div class="login-box">
                <h2 style=text-align:center;color:tomato;font-weight:100;>Admin/Dispatch Login</h2>
                <form name="form1" method="post" action="">
                    <br>
                    <p class="col-md-12">
                        <b>Username</b>
                        <input type=text name="username" class="form-control" placeholder="Username" style="width:100%; margin-top: 0px;" value="<?php echo $username; ?>">
                    </p>
                    <p class="col-md-12">
                        <b>Password</b>
                        <input type=password name="password" class="form-control" placeholder="Password" style="width:100%; margin-top: 0px;" value="<?php echo $password; ?>">
                    </p>
                    <br style=clear:both;><br>
                    <p class="col-md-12">
                        <input type="submit" id="submitBtn" name="submit" value="Login" class="btn btn-primary form-control" style="width:100%; margin-top:6px; margin-bottom:6px;">
                    </p>
                    <p class="col-md-12 center" style="padding:0px;">
                        <font color="#ccc">Don't have an account? </font><a href="signup" style="color:#ccc">Signup</a>
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
if (isset($_POST['submit'])) {
    ini_set('display_errors', 1);
    
    ob_start();
    require_once(__DIR__ . "/../config.php");
    require_once(__DIR__ . "/../logging/log.php");
    $host = DB_HOST;
    $port = DB_PORT;
    $username = DB_USER;
    $password = DB_PASSWORD;
    $db_name = DB_NAME;
    $tbl_name = "cad_users";
    
    $connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port")or die("cannot connect");
    
    $myusername = $_POST['username'];
    $mypassword = $_POST['password'];
    $myusername = stripslashes($myusername);
    $mypassword = stripslashes($mypassword);
    $myusername = mysqli_real_escape_string($connection, $myusername);
    $mypassword = mysqli_real_escape_string($connection, $mypassword);
    $sql = "SELECT password FROM $tbl_name WHERE username='$myusername'";
    $result_un = mysqli_query($connection, $sql);
    
    $count = mysqli_num_rows($result_un);
    
    if ($count == 1) {
        $sql_al = "SELECT password FROM $tbl_name WHERE username='$myusername'";
        $result_al = mysqli_query($connection, $sql_al);
        $value_al = mysqli_fetch_object($result_al);
        $pass = $value_al->password;
        if (password_verify($mypassword, $pass)) {
            $_SESSION["cad_user"] = $myusername;
            $sql_al = "SELECT level FROM $tbl_name WHERE username='$myusername'";
            $result_al = mysqli_query($connection, $sql_al);
            $value_al = mysqli_fetch_object($result_al);
            $sql_id = "SELECT uuid FROM $tbl_name WHERE username='$myusername'";
            $result_id = mysqli_query($connection, $sql_id);
            $value_id = mysqli_fetch_object($result_id);
            $_SESSION["cad_level"] = $value_al->level;
            $_SESSION["cad_uuid"] = $value_id->uuid;
            $ip = getRealIpAddr();
            mysqli_query($connection, "UPDATE cad_users SET last_ip='$ip' WHERE uuid='$value_id->uuid'") or die(mysql_error($connection));
            $sql_terms =  mysqli_query($connection, "SELECT terms_accepted FROM cad_users WHERE uuid='$value_id->uuid'");
            $value_terms = mysqli_fetch_object($sql_terms);
            if ($value_terms->terms_accepted == 0) {
                $sql_unit = mysqli_query($connection, "SELECT callsign FROM units WHERE uuid='$value_id->uuid'");
                if (mysqli_num_rows($sql_unit) == 1) {
                    $unit_result = mysqli_fetch_object($sql_unit);
                    $_SESSION["cad_unitnumber"] = $unit_result->callsign;
                }
                ob_start();
                ob_end_flush();
                header("Location: terms");
            } else {
                $sql_unit = mysqli_query($connection, "SELECT callsign FROM units WHERE uuid='$value_id->uuid'");
                if (mysqli_num_rows($sql_unit) == 0) {
                    header("Location: updateunitnumber");
                } else {
                    $unit_result = mysqli_fetch_object($sql_unit);
                    $_SESSION["cad_unitnumber"] = $unit_result->callsign;
                    ob_start();
                    ob_end_flush();
                    if(isset($_SESSION['return_url'])) {
                        header('Location: ..');
                    }
                    else {
                        header('Location: ..');
                    }
                }
            }
        } else {
            renderForm($myusername, $mypassword, "Incorrect password.");
        }
    } else {
        renderForm($myusername, $mypassword, "User does not exist.");
    }
} else {
    renderForm("", "", "");
}

?>