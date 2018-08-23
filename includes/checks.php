<?php
// Assuming config.php is already included




function checkUsername($username, $uuid) {
  $server = DB_HOST;
  $port = DB_PORT;
  $db_user = DB_USER;
  $pass = DB_PASSWORD;
  $db = DB_NAME;
    $connection = mysqli_connect($server, $db_user, $pass, $db);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $return = '';
    $username = mysqli_real_escape_string($connection, htmlspecialchars($username));
    $checkExistingUsers = mysqli_query($connection, "SELECT username,uuid FROM cad_users WHERE username='$username'") or die(mysqli_error($connection));
    $checkUUID = mysqli_fetch_object($checkExistingUsers);

    if ($username == '') {
        $return = 'emptyString';
    } else if ($username == null) {
        $return = 'nullString';
    } else if (mysqli_num_rows($checkExistingUsers) >= 1 && $checkUUID->uuid != $uuid) {
        $return = 'userExists';
    } else if (containsSpecials($username)) {
        $return = 'containsSpecials';
    } else {
        $return = 'valid';
    }
    return $return;
}

function checkCallsign($callsign, $uuid) {
  $server = DB_HOST;
  $port = DB_PORT;
  $db_user = DB_USER;
  $pass = DB_PASSWORD;
  $db = DB_NAME;
    $connection = mysqli_connect($server, $db_user, $pass, $db);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $return = '';
    $username = mysqli_real_escape_string($connection, htmlspecialchars($username));
    $checkExistingUsers = mysqli_query($connection, "SELECT callsign,uuid FROM cad_units WHERE callsign='$callsign'") or die(mysqli_error($connection));
    $checkUUID = mysqli_fetch_object($checkExistingUsers);

    if ($username == '') {
        $return = 'emptyString';
    } else if ($username == null) {
        $return = 'nullString';
    } else if (mysqli_num_rows($checkExistingUnits) >= 1 && $checkUUID->uuid != $uuid) {
        $return = 'exists';
    } else if (containsSpecials($username)) {
        $return = 'containsSpecials';
    } else {
        $return = 'valid';
    }
    return $return;
}

function containsSpecials($string) {
    $illegal = "`~!@#$%^&*()=+{}[]\|;:',<>/?\\\"";
    if (false === strpbrk($string, $illegal)) {
        return false;
    } else {
        return true;
    }
}

function stripString($string) {
    return mysqli_real_escape_string($connection, htmlspecialchars($string));
}
?>
