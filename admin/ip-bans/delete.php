<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include '../../config.php';
include '../../logging/log.php';
$_SESSION['return_url'] = BASE_URL . "/ums/delete";
$file_access = 1;
include('../connect-db.php');
if (isset($_GET['ubid'])) {
    $ubid = $_GET['ubid'];
    $sql_al = "SELECT ip FROM banned_ips WHERE ubid='$ubid'";
    $result_al = mysqli_query($connection, $sql_al);
    $value_al = mysqli_fetch_object($result_al);
    $ip = $value_al->ip;
    $result = mysqli_query($connection, "DELETE FROM banned_ips WHERE ubid='$ubid'")
    or die(mysqli_error());
    $_SESSION['ban_view_details'] = "ipban_deleted";
    logInfo("Deleted IP ban. IP: " . $ip . " ; UBID: " . $ubid, 1);
    echo "1";
} else {
    echo "0";
}
?>