<?php
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 1;
include '../config.php';
$_SESSION['return_url'] = BASE_URL . "/ums/edit";
include '../includes/menu.inc.php';
function renderForm($username, $password, $accesslevel, $error) {
	if ($accesslevel <= $_SESSION['cad_level'] && $_SESSION['cad_level'] != 0) {
		$_SESSION['ums_view_details'] = "cantEditUser";
		header("Location: view");
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<br>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
		<link rel="stylesheet" type="text/css" href="../css/buttons.css">
		<title><?php echo WEBSITE_TITLE; ?> | UMS</title>
		<h1>User Managment System - Edit User</h1>
	</head>
	<body>
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
		</script>
		<?php
		include('connect-db.php');
		if ($error != '') {
			echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
		}
		$uuid = $_GET["uuid"];
		?>
		<form action="" method="post">
			<input type="hidden" name="uuid" value="<?php echo $_GET["uuid"]; ?>"/>
				<table border='1' cellpadding='10' bordercolor='#13a9ff' style='border-radius=4px;-moz-border-radius:10px;-webkit-border-radius:10px;'>
					<tr> <th>Username</th> <th>Password</th> <th>Group</th> <th>UUID</th> </tr>
					<tr></tr>
					<?php
					$main_disable = "";
					$disable = "";
					if ($_SESSION['cad_level'] == 1) {
						$disable = 'disabled';
					}
					if ($accesslevel == 0 && $_SESSION['cad_level'] != 0) {
						$main_disable = 'disabled';
					}
					if ($accesslevel == 1 && $_SESSION['cad_level'] != 1) {
						$main_disable = 'disabled';
					}
					if ($_SESSION['cad_level'] == 0) {
						$disable = '';
						$main_disable = '';
					}
					echo '<tr>
					<td><input type=text id="username" name="username" class="form-control center" value="' . $username . '" placeholder="Username" style="width:100%; margin-top:0px;" ' . $main_disable . '></td>
					<td><input type=text id="password" name="password" class="form-control center" placeholder="Password" style="width:100%; margin-top:0px;" ' . $disable . $main_disable . '></td><td>';
					if ($accesslevel == 3) {
					echo '<input type="radio" name="accesslevel" value="3" checked ' . $main_disable . '>Dispatch Blacklisted<br>
						<input type="radio" name="accesslevel" value="2" ' . $main_disable . '> Dispatch<br>
						<input type="radio" name="accesslevel" value="1" ' . $disable . $main_disable . '>Administrator<br>
						<input type="radio" name="accesslevel" value="0" ' . $disable . $main_disable . '>Super Administrator</td>';
					}
					if ($accesslevel == 2) {
					echo '<input type="radio" name="accesslevel" value="3" ' . $main_disable . '>Dispatch Blacklisted<br>
						<input type="radio" name="accesslevel" value="2" checked ' . $main_disable . '>Dispatch<br>
						<input type="radio" name="accesslevel" value="1" ' . $disable . $main_disable . '>Administrator<br>
						<input type="radio" name="accesslevel" value="0" ' . $disable . $main_disable . '>Super Administrator</td>';
					}
					if ($accesslevel == 1) {
					echo '<input type="radio" name="accesslevel" value="3" ' . $main_disable . '>Dispatch Blacklisted<br>
						<input type="radio" name="accesslevel" value="2" ' . $main_disable . '>Dispatch<br>
						<input type="radio" name="accesslevel" value="1" checked ' . $disable . $main_disable . '>Administrator<br>
						<input type="radio" name="accesslevel" value="0" ' . $disable . $main_disable . '>Super Administrator</td>';
					}
					if ($accesslevel == 0) {
					echo '<input type="radio" name="accesslevel" value="3" ' . $main_disable . '>Dispatch Blacklisted<br>
						<input type="radio" name="accesslevel" value="2" ' . $main_disable . '>Dispatch<br>
						<input type="radio" name="accesslevel" value="1" ' . $disable . $main_disable . '>Administrator<br>
						<input type="radio" name="accesslevel" value="0" checked ' . $disable . $main_disable . '>Super Administrator</td>';
					}
					echo '<td><p>' . $uuid . '</p></td>
					</tr>';
					echo '</table><div class="center form-control" style="height:auto;width:auto"><input type="submit" name="submit" value="Append changes" class="btn btn-primary form-control center" style="width:25%; margin-top:6px; margin-bottom:6px;">
					<br>
					<input type="submit" name="cancel" value="Cancel" class="form-control-error btn-error btn-primary center" style="width:25%; margin-top:6px; margin-bottom:6px;"></div></form>';
					}
					?>
		<style>
		.button {
			appearance: button;
			-moz-appearance: button;
			-webkit-appearance: button;
			text-decoration: none; font: menu; color: ButtonText;
			display: inline-block; padding: 2px 8px;
		}
		</style>
		<br>
	</body>
</html>

<?php
include('connect-db.php');
include '../logging/log.php';
include '../includes/checks.php';
if (isset($_POST['cancel'])) {
	header('Location: .');
}
if (isset($_POST['submit'])) {
	$error = "";
	$uuid = $_POST['uuid'];
	$newUsername = mysqli_real_escape_string($connection, htmlspecialchars($_POST['username']));
	$password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['password']));
    $newHashedPass = password_hash($password, PASSWORD_DEFAULT);
	$newAccessLevel = $_POST['accesslevel'];
	$oldUsername = getUsername($uuid);
	$oldHashedPass = getHashedPassword($uuid);
	$oldAccessLevel = getLevel($uuid);

	if ($_SESSION['cad_level'] <= $oldAccessLevel && $_SESSION['cad_level'] != 0) {
		$error = "You don't have permission to edit this user!";
	} else {
		if ($newUsername != $oldUsername) {
			if (checkUsername($newUsername, $uuid) != 'valid') {
				if (checkUsername($newUsername, $uuid) == 'emptyString' || checkUsername($newUsername, $uuid) == 'nullString') {
					$error = "Username cannot be empty or null.";
				} else if (checkUsername($newUsername, $uuid) == 'userExists') {
					$error = "A user with that name already exists.";
				} else if (checkUsername($newUsername, $uuid) == 'containsSpecials') {
					$error = "Username cannot contain any special sharacters.";
				}
			} else {
				mysqli_query($connection, "UPDATE cad_users SET username='$newUsername' WHERE uuid='$uuid'") or die(mysqli_error());
				$error = "";
			}
		}
		if ($newHashedPass != $oldHashedPass && $password != "") {
			mysqli_query($connection, "UPDATE cad_users SET password='$newHashedPass' WHERE uuid='$uuid'") or die(mysqli_error());
			$error = "";
		}
		if ($newAccessLevel != $oldAccessLevel) {
			if ($newAccessLevel <= $_SESSION['cad_level']) {
				$error = "You don't have permission to assign that group!";
			} else {
				mysqli_query($connection, "UPDATE cad_users SET username='$newUsername' WHERE uuid='$uuid'") or die(mysqli_error());
				$error = "";
			}
		}

		logInfo("Edited user. Username: " . $oldUsername . " Access: " . $oldAccessLevel . " ; UUID: " . $uuid, 1);
	}

	header("Location: view");
} else {
	if (isset($_GET['uuid'])) {
		$uuid = $_GET['uuid'];
		renderForm(getUsername($uuid), getHashedPassword($uuid), getLevel($uuid), $error);
	} else {
		echo 'Error!';
	}
}

function getUsername($uuid) {
	$host = DB_HOST;
	$port = DB_PORT;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$db_name = DB_NAME;
		
	$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

	$sql_username =  mysqli_query($connection, "SELECT username FROM cad_users WHERE uuid='$uuid'");
	$value_username = mysqli_fetch_object($sql_username);
	$username = $value_username->username;
	return $username;
}
function getLevel($uuid) {
	$host = DB_HOST;
	$port = DB_PORT;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$db_name = DB_NAME;
		
	$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

	$sql_level =  mysqli_query($connection, "SELECT level FROM cad_users WHERE uuid='$uuid'");
	$value_level = mysqli_fetch_object($sql_level);
	$level = $value_level->level;
	return $level;
}
function getHashedPassword($uuid) {
	$host = DB_HOST;
	$port = DB_PORT;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$db_name = DB_NAME;
		
	$connection = mysqli_connect("$host", "$username", "$password", "$db_name", "$port") or die("cannot connect");

	$sql_pass =  mysqli_query($connection, "SELECT password FROM cad_users WHERE uuid='$uuid'");
	$value_pass = mysqli_fetch_object($sql_pass);
	$hashedPass = $value_pass->password;
	return $hashedPass;
}
?>