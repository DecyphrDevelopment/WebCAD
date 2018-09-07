<?php
require_once(__DIR__ . "/../config.php");
$server = DB_HOST;
$port = DB_PORT;
$db_user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

$connection = mysqli_connect($server, $db_user, $pass, $db);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
function getIP() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
  } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['cad_level'])) {
  $ip = getIP();
  $result = mysqli_query($connection, "SELECT reason FROM banned_ips WHERE ip='$ip'") or die(mysqli_error($connection));
  if (mysqli_num_rows($result) != 0) {
    while ($row = mysqli_fetch_array($result)) {
      $_SESSION['reason'] = $row['reason'];
      header('Location: ' . BASE_URL . '/banned');
      exit();
    }
  }
  if ($file_access != 9) {
    header('Location: ' . BASE_URL . '/no-permission');
  }
}
else {
  $uuid = $_SESSION['cad_uuid'];
  $sql_terms =  mysqli_query($connection, "SELECT terms_accepted FROM cad_users WHERE uuid='$uuid'");
  $value_terms = mysqli_fetch_object($sql_terms);
  if ($value_terms->terms_accepted == 0) {
    header('Location: ' . BASE_URL . '/account/terms');
  } else {
    $ip = getIP();
    $result = mysqli_query($connection, "SELECT * FROM banned_ips WHERE ip='$ip'") or die(mysqli_error($connection));
    if (mysqli_num_rows($result) != 0) {
      while ($row = mysqli_fetch_array($result)) {
        $_SESSION['reason'] = $row['reason'];
        header('Location: ' . BASE_URL . '/banned');
        exit();
      }
    }
    if ($_SESSION['cad_level'] > $file_access) {
      header('Location: ' . BASE_URL . '/no-permission');
    }
  }
}
?>