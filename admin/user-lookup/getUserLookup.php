<?php
include '../../config.php';
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port)
or die ("Could not connect to server ... \n" . mysqli_error($connection));

if (isset($_POST['username'])) {
    $usernamePost = $_POST['username'];
    $anyAliases = false;
    $anyIPs = false;
    $knownAliases = "";
    $knownIPs = "";
    $ip = "";
    $arr = array();
    $getIPSQL = mysqli_query($connection, "SELECT * FROM known_users WHERE username='$usernamePost'");
    if (mysqli_num_rows($getIPSQL) != 0) {
		while ($row = mysqli_fetch_array($getIPSQL)) {
            $knownIPs = $knownIPs . '<a id="link_bar" href="' . BASE_URL . '/admin/ip-lookup/?ip=' . $row['ip'] . '"> ' . $row['ip'] . "</a><br>";
            $ip = $row['ip'];

            $getAliasesSQL = mysqli_query($connection, "SELECT * FROM known_users WHERE ip='$ip'");
            if (mysqli_num_rows($getAliasesSQL) != 0) {
                while ($row = mysqli_fetch_array($getAliasesSQL)) {
                    $uuid = $row['uuid'];
                    $username = $row['username'];
                    $getAliasesLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE uuid='$uuid'");
                    if (mysqli_num_rows($getAliasesLevelSQL) != 0) {
                        while ($row = mysqli_fetch_array($getAliasesLevelSQL)) {
                            if ($row['level'] == 0) {
                                $group = "Super Admin";
                                $color = "#ff4f4f";
                            }
                            if ($row['level'] == 1) {
                                $group = "Admin";
                                $color = "#ffe100";
                            }
                            if ($row['level'] == 2) {
                                $group = "User";
                                $color = "#00d1a7";
                            }
                            if ($row['level'] == 3) {
                                $group = "Dispatch Blacklisted";
                                $color = "#930093";
                            }
                        }
                    }
                    if (in_array($username, $arr) == false) {
                        $arr[] = $username;
                        $knownAliases = $knownAliases . '<a id="link_bar" href="' . BASE_URL . '/admin/user-lookup/?user=' . $username . '">' . $username . "</a>" . " : <font color='" . $color . "'>" . $group . "</font><br>";
                    }
                }
                $anyAliases = true;
            }
        }
        $anyIPs = true;
    }
    $getLevelSQL = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$usernamePost'");
    if (mysqli_num_rows($getLevelSQL) != 0) {
        while ($row = mysqli_fetch_array($getLevelSQL)) {
            if ($row['level'] == 0) {
                $userGroup = "Super Admin";
            }
            if ($row['level'] == 1) {
                $userGroup = "Admin";
            }
            if ($row['level'] == 2) {
                $userGroup = "User";
            }
            if ($row['level'] == 3) {
                $userGroup = "Dispatch Blacklisted";
            }
            $userUUID = $row['uuid'];
        }
    }
    $getUnitNumberSQL = mysqli_query($connection, "SELECT callsign FROM units WHERE uuid='$userUUID'");
    if (mysqli_num_rows($getUnitNumberSQL) != 0) {
        while ($row = mysqli_fetch_array($getUnitNumberSQL)) {
            $userUN = $row['callsign'];
        }
    } else {
        $userUN = "No unit number.";
    }

    if ($anyAliases == false && $anyIPs == false) {
        echo json_encode(array('status' => 'invalid'));
    } else {
        echo json_encode(array('status' => 'valid',
        'username' => $usernamePost,
        'uuid' => $userUUID,
        'unitnumber' => $userUN,
        'group' => $userGroup,
        'ips' => $knownIPs,
        'aliases' => $knownAliases));
    }
}
?>
