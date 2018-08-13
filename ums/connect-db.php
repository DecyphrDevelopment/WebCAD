<?php

/*

CONNECT-DB.PHP

Allows PHP to connect to your database

*/

// Database Variables (edit with your own server information)
require_once(__DIR__ . "/../config.php");
$server = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;

// Connect to Database

//$connection = mysql_connect($server, $user, $pass)
$connection = mysqli_connect($server, $user, $pass, $db, $port)

or die ("Could not connect to server ... \n" . mysqli_error ());

//mysql_select_db($db)

//or die ("Could not connect to database ... \n" . mysql_error ());

?>