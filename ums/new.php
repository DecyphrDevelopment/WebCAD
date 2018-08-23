<?php
ob_start();
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 1;
include '../config.php';
$_SESSION['return_url'] = BASE_URL . "/ums/new";
include '../includes/menu.inc.php';
function renderForm($username, $password, $accesslevel, $error) {
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <br>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
        <link rel="stylesheet" type="text/css" href="../css/buttons.css">

		<title><?php echo WEBSITE_TITLE; ?> | UMS</title>
        <h1>User Managment System - New User</h1>

        <script>
            document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
            document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
        </script>
    </head>
    <body>

        <?php
        include('connect-db.php');
        if ($error != '') {
            echo '<div id="result" style="margin-top:6px;width:100%;height:auto;color:red;" class="center form-control">' . $error . '</div>';
        }
        if($password == '') {
            $password = md5(mt_rand());
        }
        ?>

        <form action="" method="post">
            <table border='1' cellpadding='10'>
                <tr> <th>Username</th> <th>Password</th> <th>Access Level</th> </tr>
                <tr></tr>
                <tr>
                    <td><input type=text id="username" name="username" class="form-control center" value="<?php echo $username; ?>" placeholder="Username" style="width:100%; margin-top:0px;"></td>
                    <td><input type=text id="password" name="password" class="form-control center" value="<?php echo $password; ?>" placeholder="Password" style="width:100%; margin-top:0px;"></td>
                    <td>
                        <?php
                        if ($_SESSION['cad_level'] == 3) {
                            $unit_disable = "disabled";
                            $disp_disable = "disabled";
                            $admin_disable = "disabled";
                            $sp_disable = "disabled";
                        }
                        if ($_SESSION['cad_level'] == 2) {
                            $unit_disable = "";
                            $disp_disable = "disabled";
                            $admin_disable = "disabled";
                            $sp_disable = "disabled";
                        }
                        if ($_SESSION['cad_level'] == 1) {
                            $unit_disable = "";
                            $disp_disable = "";
                            $admin_disable = "disabled";
                            $sp_disable = "disabled";
                        }
                        if ($_SESSION['cad_level'] == 0) {
                            $unit_disable = "";
                            $disp_disable = "";
                            $admin_disable = "";
                            $sp_disable = "";
                        }
                        ?>
                        <input type="radio" name="accesslevel" value="3" <?php echo $unit_disable?>> Dispatch Blacklisted<br>
                        <input type="radio" name="accesslevel" value="2" <?php echo $disp_disable?>> User<br>
                        <input type="radio" name="accesslevel" value="1" <?php echo $admin_disable?>> Administrator<br>
                        <input type="radio" name="accesslevel" value="0" <?php echo $sp_disable?>> Super Administrator
                    </td>
                </tr>
            </table>
            <div class="center form-control" style="height:auto;width:auto">
                <input type="submit" name="submit" value="Add User" class="btn btn-primary form-control center" style="width:25%; margin-top:6px; margin-bottom:6px;">
                <br>
                <input type="submit" name="cancel" value="Cancel" class="form-control-error btn-error btn-primary center" style="width:25%; margin-top:6px; margin-bottom:6px;">
            </div>
        </form>
        <style>
            .button {
                appearance: button;
                -moz-appearance: button;
                -webkit-appearance: button;
                text-decoration: none; font: menu; color: ButtonText;
                display: inline-block; padding: 2px 8px;
            }
        </style>
        <br>
    </body>
</html>

<?php
}
include('connect-db.php');
include '../logging/log.php';
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, htmlspecialchars($_POST['username']));
    $accesslevel = mysqli_real_escape_string($connection, htmlspecialchars($_POST['accesslevel']));
    $password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['password']));
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
    if (!isset($_POST['username'])) {
        $error = 'ERROR: Please fill in all required fields!';
        renderForm($username, $password, $accesslevel, $error);
    }
    if ($username == '') {
        $error = 'ERROR: Please fill in all required fields! "'.$_POST['username'].$username.'"';
        renderForm($username, $password, $accesslevel, $error);
    } else if ($password == '') {
        $error = 'ERROR: Please fill in all required fields! "pass"';
        renderForm($username, $password, $accesslevel, $error);
    } else {
        $sql = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$username'");
        $count = mysqli_num_rows($sql);
        if ($count >= 1) {
            $_SESSION['ums_view_details'] = "user_exists";
        }
        else {
            $uuid = uniqid();
            mysqli_query($connection, "INSERT cad_users SET username='$username', password='$hashed_pass', level='$accesslevel', uuid='$uuid', last_ip='', email=''")
            or die(mysqli_error($connection));
            $_SESSION['ums_view_details'] = "user_added";
            logInfo("Added user. Username: " . $username . " ; Access: " . $accesslevel . " ; UUID: " . $uuid, 1);
        }
        header('Location: view');
    }
} else {
    if (isset($_POST['cancel'])) {
        header('Location: view');
    } else {
        renderForm('','','','','');
    }
}

?>
