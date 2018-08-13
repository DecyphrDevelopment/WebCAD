<?php
require_once(__DIR__ . "/../../config.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port)
or die ("Could not connect to server ... \n" . mysqli_error ());

if (isset($_POST['ip'])) {
    $ip = $_POST['ip'];
    $isBanned = false;
    $anyAliases = false;
    $banReason = "";
    $bannedBy = "";
    $knownAliases = "";
    $arr = array();
    $getBanStatsSQL = mysqli_query($connection, "SELECT * FROM banned_ips WHERE ip='$ip'");
    if (mysqli_num_rows($getBanStatsSQL) != 0) {
		while ($row = mysqli_fetch_array($getBanStatsSQL)) {
			$banReason = $row['reason'];
			$bannedBy = $row['banned_by'];
        }
        $isBanned = true;
    }
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
                $knownAliases = $knownAliases . "<a id='link_bar' href='" . BASE_URL . "/admin/user-lookup/?user=" . $username . "'>" . $username . "</a> : <font color='" . $color . "'>" . $group . "</font><br>";
            }
        }
        $anyAliases = true;
    }
    if ($isBanned == false && $anyAliases == false) {
        echo json_encode(array('status' => 'invalid'));
    } else {
        echo json_encode(array('status' => 'valid', 
        'ip' => $ip, 
        'isBanned' => $isBanned, 
        'banReason' => $banReason, 
        'bannedBy' => $bannedBy, 
        'aliases' => $knownAliases));
    }
}
?>