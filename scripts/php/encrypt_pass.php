<?php
require_once(__DIR__ . "/../../config.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $user, $pass, $db, $port)
or die ("Could not connect to server ... \n" . mysqli_error ());

$uuid = $_GET['uuid'];
echo $uuid;
$sql = "SELECT password FROM cad_users WHERE uuid='$uuid'";
$result = mysqli_query($connection, $sql);
$count = mysqli_num_rows($result);
echo $count;
if ($count == 1) {
    $value = mysqli_fetch_object($result);
    $pass = $value->password;
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $sql = @mysqli_query($connection, "UPDATE cad_users SET password='$hash' WHERE uuid='$uuid'");
    echo "Changed " . $uuid . " 's password to " . $hash;
}
echo "done";
?>