<?php
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once(__DIR__ . "/../config.php");
$file_access = 9;
require __DIR__ . '/../includes/check_access.inc.php';
$server = DB_HOST;
$port = DB_PORT;
$db_user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$logg = "logg";

$connection = mysqli_connect($server, $db_user, $pass, $db);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
function logInfo($message, $type) {
    global $connection;
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $dt = new DateTime("now", new DateTimeZone('America/Chicago'));

    $timeStamp = $dt->format('m/d/Y @ g:i:sA');
    $username = $_SESSION['cad_user'];
    $uuid = $_SESSION['cad_uuid'];
    $ip = getRealIpAddr();
    if ($type == 1) {
        $type = "admin"; } else if ($type == 0) {
        $type = "action"; } else if ($type == 2) {
        $type = "login";
    }
    if ($type != "login") {
        mysqli_query($connection, "INSERT INTO ".$type."_log VALUES (DEFAULT, '$username', '$ip', '$message', '$timeStamp CST')")
        or die(mysqli_error($connection));
    } else {
        mysqli_query($connection, "INSERT INTO ".$type."_log VALUES (DEFAULT, '$username', '$uuid', '$ip', '$timeStamp CST')")
        or die(mysqli_error($connection));
    }
    $result = mysqli_query($connection, "SELECT * FROM known_users WHERE ip='$ip' AND username='$username'") or die(mysqli_error($connection));
    if (mysqli_num_rows($result) == 0) {
        mysqli_query($connection, "INSERT INTO known_users VALUES ('$uuid', '$username', '$ip')")
        or die(mysqli_error($connection));
    }
}
function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
if (isset($_GET['getActionLog'])) {
    if (isset($_GET['ip'])) {
        $ip = $_GET['ip'];
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM action_log WHERE ip='$ip'")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            } else {
                $level = 9;
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a>  : " . $row['action'] . "<br>";
        }
        echo $logText;
    } else if (isset($_GET['username'])) {
        $username = $_GET['username'];
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM action_log WHERE user='$username'")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a>  : " . $row['action'] . "<br>";
        }
        echo $logText;
    } else {
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM action_log")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a>  : " . $row['action'] . "<br>";
        }
        echo $logText;
    }
}
if (isset($_GET['getAdminLog'])) {
    if (isset($_GET['ip'])) {
        $ip = $_GET['ip'];
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM admin_log WHERE ip='$ip'")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a>  : " . $row['action'] . "<br>";
        }
        echo $logText;
    } else if (isset($_GET['username'])) {
        $username = $_GET['username'];
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM admin_log WHERE user='$username'")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a>  : " . $row['action'] . "<br>";
        }
        echo $logText;
    } else {
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM admin_log")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a>  : " . $row['action'] . "<br>";
        }
        echo $logText;
    }
}
if (isset($_GET['getLoginLog'])) {
    if (isset($_GET['ip'])) {
        $ip = $_GET['ip'];
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM login_log WHERE ip='$ip'")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> from <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a><br>";
        }
        echo $logText;
    } else if (isset($_GET['username'])) {
        $username = $_GET['username'];
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM login_log WHERE user='$username'")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> from <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a><br>";
        }
        echo $logText;
    } else {
        $logText = "";
        $sql = mysqli_query($connection, "SELECT * FROM login_log")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $user = $row['user'];
            $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$user'");
            if (mysqli_num_rows($getLevelSQL) != 0) {
                while ($row2 = mysqli_fetch_array($getLevelSQL)) {
                    $level = $row2['level'];
                }
            }
            $logText = $logText . $row['timestamp'] . " <a id='link_bar_" . $level . "' href='" . BASE_URL . "/admin/user-lookup/?user=" . $row['user'] . "'>" . $row['user'] . "</a> from <a id='link_bar' href='" . BASE_URL . "/admin/ip-lookup/?ip=" . $row['ip'] . "'>" . $row['ip'] . "</a><br>";
        }
        echo $logText;
    }
}
if (isset($_GET['clearLog'])) {
    if ($_SESSION['cad_level'] != 0) {
        echo "99noPermissions";
    } else {
        $logText = "";
        if ($_GET['clearLog'] == "Admin") { $table = "admin_log"; }
        if ($_GET['clearLog'] == "Action") { $table = "action_log"; }
        if ($_GET['clearLog'] == "Login") { $table = "login_log"; }
        $sql = mysqli_query($connection, "TRUNCATE TABLE " . $table)
        or die(mysqli_error());
        logInfo("Cleared " . $_GET['clearLog'] . " log.", 1);
        $sql = mysqli_query($connection, "SELECT * FROM " . $table)
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $sql )) {
            $logText = $logText . $row['timestamp'] . " [" . $row['user'] . " [" . $row['ip'] . "  : " . $row['action'] . "&#13;&#10;";
        }
        echo $logText;
    }

}
?>
