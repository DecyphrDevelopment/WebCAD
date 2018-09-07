<?php
session_start();
ini_set('display_errors', 1);

ob_start();
$host = "198.71.225.65"; // Host name
$username = "pbslivestream"; // Mysql username
$password = "PBSeagles1981"; // Mysql password
$db_name = "database"; // Database name
$tbl_name = "cad_users"; // Table name

// Connect to server and select databse.
$connection = mysqli_connect("$host", "$username", "$password", "$db_name")or die("cannot connect");

// Define $myusername and $mypassword
$myusername = $_POST['myusername'];
$mypassword = $_POST['mypassword'];
$myaccesslevel = $_POST['myaccesslevel'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myaccesslevel = stripslashes($myaccesslevel);
$myusername = mysqli_real_escape_string($connection, $myusername);
$mypassword = mysqli_real_escape_string($connection, $mypassword);
$myaccesslevel = mysqli_real_escape_string($connection, $myaccesslevel);
$sql = "SELECT password FROM $tbl_name WHERE username='$myusername'";
$result_un = mysqli_query($connection, $sql);

$count = mysqli_num_rows($result_un);
echo $count;

if ($count == 1) {
	$sql_al = "SELECT password FROM $tbl_name WHERE username='$myusername'";
	$result_al = mysqli_query($connection, $sql_al);
	$value_al = mysqli_fetch_object($result_al);
	echo $value_al->password;
	$pass = $value_al->password;
	echo $pass;
	if (password_verify($mypassword, $pass)) {
		$_SESSION["cad_user"] = $myusername;
		$sql_al = "SELECT level FROM $tbl_name WHERE username='$myusername'";
		$result_al = mysqli_query($connection, $sql_al);
		$value_al = mysqli_fetch_object($result_al);
		$_SESSION["cad_level"] = $value_al->level;
		header('Location: ..');
	}
	else {
		echo "count == 1";
		$sql_al = "SELECT password FROM $tbl_name WHERE username='$myusername'";
		$result_al = mysqli_query($connection, $sql_al);
		$value_al = mysqli_fetch_object($result_al);
		header('Location: login');
	}
}
else {
	header('Location: login');
}
//// Mysql_num_row is counting table row
//$count=mysql_num_rows($result_un);

//// If result matched $myusername and $mypassword, table row must be 1 row
//if($count==1){

//// Register $myusername, $mypassword and redirect to file "login_success.php"
//$sql = "UPDATE members SET last-ip='test'";
//$sql = "UPDATE CURRENT_TIMESTAMP FROM $tbl_name";
//$_SESSION["myusername"] = $myusername;
//$_SESSION["mypassword"] = $mypassword;
////echo $_SESSION['myusername'];
////echo $_SESSION['mypassword'];
////echo $_SESSION['myaccesslevel'];
////. $_SESSION['return_url']
//}
//else {
//header('Location: login');
//}
ob_end_flush();
?>