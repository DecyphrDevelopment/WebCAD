<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$file = "login";
include 'config.php';
function renderForm($username, $password, $error) {
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/buttons.css">
        <link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
		<title><?php echo WEBSITE_TITLE; ?></title>
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
                <h2 style=text-align:center;font-weight:100;>Login</h2>
                <form name="form1" method="post" action="">
                    <br>
                    <p class="col-md-12" style="text-align:left;">
                        <b>Username</b>
                        <input type=text name="username" class="form-control" placeholder="Username" style="width:100%; margin-top: 0px;" value="<?php echo $username; ?>">
                    </p>
                    <p class="col-md-12" style="text-align:left;">
                        <b>Password</b>
                        <input type=password name="password" class="form-control" placeholder="Password" style="width:100%; margin-top: 0px;" value="<?php echo $password; ?>">
                    </p>
                    <p class="col-md-12">
                        <input type="submit" id="submitBtn" name="submit" value="Login" class="btn btn-primary form-control" style="width:100%; margin-top:6px; margin-bottom:6px;">
                    </p>
                    <p class="col-md-12 center" style="padding:0px;">
                        <font color="#ccc">Don't have an account? </font><a href="account/signup" style="color:#ccc">Signup</a>
                        <small>
                          <strong>
                            <br><font color="#ccc">Powered by Xenon Hosting • Made by Daddy Decyphr#1065</font>
                            <br><font color="#ccc">Version: BETA-004</font>
                            <br><font color="#ccc">Official Discord server: <a href="https://discord.gg/zv37ynx">https://discord.gg/zv37ynx</a></font>
                          </strong>
                        </small>
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
    require_once(__DIR__ . "/config.php");
    require_once(__DIR__ . "/logging/log.php");
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
                header("Location: account/terms");
            } else {
                $sql_unit = mysqli_query($connection, "SELECT callsign FROM units WHERE uuid='$value_id->uuid'");
                if (mysqli_num_rows($sql_unit) == 0) {
                    header("Location: account/updateunitnumber");
                } else {
                    $unit_result = mysqli_fetch_object($sql_unit);
                    $_SESSION["cad_unitnumber"] = $unit_result->callsign;
                    ob_start();
                    ob_end_flush();
                    if(isset($_SESSION['return_url'])) {
                        header('Location: .');
                    }
                    else {
                        header('Location: .');
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
  if (isset($_SESSION["cad_user"])) {
    renderHomePage();
  } else {
    renderForm("", "", "");
  }
}

?>

<?php
function renderHomePage() {
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  $file_access = 9;
  include 'config.php';
  include 'includes/menu.inc.php';
  $_SESSION['return_url'] = BASE_URL;
  ?>
  <html>
  	<head>
  		<link rel="stylesheet" type="text/css" href="css/<?php echo STYLE; ?>">
  		<link rel="stylesheet" type="text/css" href="css/buttons.css">
  		<title><?php echo WEBSITE_TITLE; ?> | Home</title>
  	</head>
  	<body>
  		<script>
  			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
  			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
  		</script>
  		<div class="center login-box" style="background:transparent;border:none">
  			<a href="dispatch/"><button id="disp" class="center btn btn-primary form-control"style="width:200;height:60">Dispatch CAD</button></a>
        <br>
  			<a href="field/"><button id="field" class="btn btn-primary form-control" style="width:200;height:60">Field CAD</button></a>
        <br>
  			<a href="about"><button id="field" class="btn btn-primary form-control" style="width:200;height:60">About</button></a>

  				<?php
  				if (isset($_SESSION['cad_user'])) {
  					if($_SESSION['cad_level'] <= 1) {
              echo '<div class="form-control center" style="height:auto; width:51%;">';
  						echo '<h1 style="color:tomato;margin-top:-6px">Admin Tools</h1>';
  						if ($_SESSION['cad_level'] == 0) {
      				  echo '<a href="admin/"><button class="btn btn-primary form-control center" style="width:90%; margin-top:0px;">Website Settings</button></a>';
  							echo '<a href="ums/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">User Managment System</button></a>';
  						} else {
  							echo '<a href="ums/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:0px;">User Managment System</button></a>';
  						}
  						echo '<a href="admin/logs/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">Logs</button></a>';
  						echo '<a href="admin/ip-bans/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">IP Bans</button></a>';
  						echo '<a href="admin/ip-lookup/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">IP Lookup</button></a>';
  						echo '<a href="admin/user-lookup/"><button id="admin_area" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">User Lookup</button></a>';
              echo '</div>';
  					}
  				} else {
  					echo '<h1 style="margin-top:-6px;color:tomato;margin-bottom:6px;">Login/Signup</h1>';
  					echo '<a href="account/login"><button id="disp" class="btn btn-primary form-control" style="width:90%;margin-top:0px;">Login</button></a>';
  					echo '<a href="account/signup"><button id="disp" class="btn btn-primary form-control" style="width:90%;margin-top:6px;">Signup</button></a>';
  				}
  				?>

  				<br><br><br>
  		</div>
  	</body>
  </html>
  <?php
}
?>
