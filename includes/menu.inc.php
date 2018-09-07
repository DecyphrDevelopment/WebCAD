<head>
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo BASE_URL; ?>/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo BASE_URL; ?>/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo BASE_URL; ?>/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo BASE_URL; ?>/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo BASE_URL; ?>/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo BASE_URL; ?>/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo BASE_URL; ?>/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo BASE_URL; ?>/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo BASE_URL; ?>/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL; ?>/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo BASE_URL; ?>/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URL; ?>/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo BASE_URL; ?>/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<?php echo BASE_URL; ?>/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119361034-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-119361034-2');
</script>
<?php

include(__DIR__ . "/footer.inc.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (IS_IN_MAINTENANCE == "true") {
  if (isset($file)) {
    if ($file != "login") {
      if (!isset($_SESSION['cad_level'])) {
        header("Location: " . BASE_URL . "/maintenance");
      } else {
        if ($_SESSION['cad_level'] > 1) {
          header("Location: " . BASE_URL . "/maintenance");
      	}
      }
    }
  } else {
      if (!isset($_SESSION['cad_level'])) {
        header("Location: " . BASE_URL . "/maintenance");
      } else {
        if ($_SESSION['cad_level'] > 1) {
          header("Location: " . BASE_URL . "/maintenance");
      	}
      }
   }
}
if (isset($_SESSION['cad_uuid'])) {
  if (isset($file)) {
    if ($file != "terms" && $file != "login") {
      $uuid = $_SESSION['cad_uuid'];
      $server = DB_HOST;
      $port = DB_PORT;
      $db_user = DB_USER;
      $pass = DB_PASSWORD;
      $db = DB_NAME;

      $connection = mysqli_connect($server, $db_user, $pass, $db);
      if (!$connection) {
          die("Connection failed: " . mysqli_connect_error());
      }
      $sql_terms =  mysqli_query($connection, "SELECT terms_accepted FROM cad_users WHERE uuid='$uuid'");
      $value_terms = mysqli_fetch_object($sql_terms);
      if ($value_terms->terms_accepted == 0) {
        header('Location: ' . BASE_URL . '/account/terms');
      }
    }
  }
}

$host = DB_HOST;
$port = DB_PORT;
$username = DB_USER;
$password = DB_PASSWORD;
$db_name = DB_NAME;

$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

$sql_title =  mysqli_query($connection, "SELECT website_title FROM cad_settings WHERE reference='1'");
$value_title = mysqli_fetch_object($sql_title);
$title = $value_title->website_title;

echo '<title>' . $title . '</title>';
?>
<nav id="primary_nav_wrap" style="z-index:2;">
<ul style="z-index:2;">
  <li style="z-index:2;"><a href="<?php echo BASE_URL ?>">Home</a></li>
  <li style="z-index:2;"><a href="<?php echo BASE_URL ?>/dispatch/">Dispatch CAD</a></li>
  <li style="z-index:2;"><a href="<?php echo BASE_URL ?>/field/">Field CAD</a></li>
  <li style="z-index:2;"><a href="<?php echo BASE_URL ?>/about">About</a></li>
<?php
if (!isset($_SESSION['cad_user'])) {
  echo '<li style="float:right" id="sett"><a href="' . BASE_URL . '/account/signup">Signup</a></li>
  <li style="float:right" id="sett"><a href="' . BASE_URL . '/account/login">Login</a></li></ul></nav>';
} else {
  if ($_SESSION['cad_level'] <= 1) {
    echo '<li style="float:right;z-index:2;" id="tools"><a href="#" class="parent" style="color:tomato;">Admin Tools</a><ul id="tools_set">';
    echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/ums/" class="child">User Managment System</a></li>';
    echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/admin/logs/" class="child">Logs</a></li>';
    echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/admin/ip-bans/" class="child">IP Bans</a></li>';
    echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/admin/ip-lookup/" class="child">IP Lookup</a></li>';
    echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/admin/user-lookup/" class="child">User Lookup</a></li>';
    if ($_SESSION['cad_level'] == 0) {
      echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/admin" class="child">Website Settings</a></li>';
    }
    echo '</ul></li>';
  }
  echo '<li style="float:right;z-index:2;" id="sett"><a href="' . BASE_URL . '/account/" class="parent">' . $_SESSION['cad_user'] . ' ['; if (isset($_SESSION['cad_unitnumber'])) { echo $_SESSION['cad_unitnumber']; } else { echo ""; } echo ']</a><ul id="settt">';
	echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/account/terms" class="child">Terms of Use</a></li>';
	echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/account/" class="child">Edit Account</a></li>';
  echo '<li style="z-index:2;" class="child"><a href="' . BASE_URL . '/account/logout" class="child">Logout</a></li></ul></li></ul></nav>';

}
?>
