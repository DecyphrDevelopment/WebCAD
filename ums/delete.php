<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include '../config.php';
include '../logging/log.php';
$_SESSION['return_url'] = BASE_URL . "/ums/delete";
$file_access = 1;
include('connect-db.php');
if (isset($_GET['uuid'])) {
    $uuid = $_GET['uuid'];
    if ($_SESSION['cad_uuid'] == $uuid) {
        $_SESSION['ums_view_details'] = "del_same_uuid";
        header("Location: view.php");
    }
    else {
		$sql_al = "SELECT level FROM cad_users WHERE uuid='$uuid'";
		$result_al = mysqli_query($connection, $sql_al);
		$value_al = mysqli_fetch_object($result_al);
        if ($value_al->level <= $_SESSION['cad_level'] && $_SESSION['cad_level'] != 0) {
            $_SESSION['ums_view_details'] = "no_permissions_del";
            header("Location: view.php");
        } else {
            $sql_al = "SELECT username FROM cad_users WHERE uuid='$uuid'";
            $result_al = mysqli_query($connection, $sql_al);
            $value_al = mysqli_fetch_object($result_al);
            $user = $value_al->username;
            $result = mysqli_query($connection, "DELETE FROM cad_users WHERE uuid='$uuid'")
            or die(mysqli_error());
            $_SESSION['ums_view_details'] = "user_deleted";
            logInfo("Deleted user. Username: " . $user . " ; UUID: " . $uuid, 1);
            header("Location: view.php");
        }
    }
} else {
    header("Location: view.php");
}
?>