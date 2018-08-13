<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file_access = 1;
include '../config.php';
include '../includes/menu.inc.php';
$_SESSION['return_url'] = BASE_URL . "/ums";
include '../includes/check_access.inc.php';
$isFiltered = false;
$isNameFiltered = false;
$filterLevel = 9;
if (isset($_POST['groupFilter'])) {
	if (isset($_POST['groupLevel'])) {
		$isFiltered = true;
		$filterLevel = $_POST['groupLevel'];
	}
}
if (isset($_POST['userSearch'])) {
	if (isset($_POST['searchUser'])) {
		$isNameFiltered = true;
		$filterName = $_POST['searchUser'];
		if ($_POST['searchUser'] == "") {
			$isNameFiltered = false;
		}
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>


<head>

<link rel="stylesheet" type="text/css" href="../css/<?php echo STYLE; ?>">
<link rel="stylesheet" type="text/css" href="../css/buttons.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<script language="javascript" type="text/javascript" src="fade_div.js?v=5"></script>

<title>User Managment - View/Edit</title>

</head>

<body>
<br><br><br>
<script>
	document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
	document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
	$(function() {
		$("#searchUser").autocomplete({
					source: '../includes/autocomplete.php',
					select: function (event, ui) {
						$("#searchUser").val(ui.item.label);
						return false;
					}
				});
			});
</script>
<script language="javascript" type="text/javascript">
function checkDelete(){
    return confirm('Are you sure you want to delete this user?');
}
</script>
<div id="sidebar" class="float-left" style="margin-top:0px;">
	<button id="disp" class="btn btn-primary form-control center" onclick="window.location.href='new'" style="width:100%;margin-top:0px;">Add a New User</button>
	<?php include '../includes/admin_sidebar_buttons.inc.php'; ?>
</div>
<div id="main_panel" class="float-right" style="">
<div id="main" style="margin-bottom:0px;height:90%;">
<h1 style="margin-top:0px;">User Managment System</h1>
<?php
if (isset($_SESSION['ums_view_details'])) {
	echo '<div id="result" style="margin-top:6px;width:100%;height:auto;" class="center form-control">';
	if ($_SESSION['ums_view_details'] == "del_same_uuid") {
		echo "<font color='red'>Cannot delete your own account!</font>";
	}
	if ($_SESSION['ums_view_details'] == "no_permissions_del") {
		echo "<font color='red'>Insuffencient permissions to delete that user!</font>";
	}
	if ($_SESSION['ums_view_details'] == "user_deleted") {
		echo "<font color='green'>User deleted!</font></div>";
	}
	if ($_SESSION['ums_view_details'] == "cantEditUser") {
		echo "<font color='red'>Insufficient permissions to edit that user.</font>";
	}
	if ($_SESSION['ums_view_details'] == "user_exists") {
		echo "<font color='red'>A user already exists with that username.</font>";
	}
	echo "</div>";
	unset ($_SESSION['ums_view_details']);
}
?>

<?php
include('connect-db.php');

echo 'Filter by Group: <br><form action="" method="post">';
if ($filterLevel == 9) {
	echo '<input type="radio" name="groupLevel" value="9" checked>All Groups</input>
	<input type="radio" name="groupLevel" value="0">Super Admin</input>
	<input type="radio" name="groupLevel" value="1">Admin</input>
	<input type="radio" name="groupLevel" value="2">User</input>
	<input type="radio" name="groupLevel" value="3">Dispatch Blacklisted</input>';
}
if ($filterLevel == 0) {
	echo '<input type="radio" name="groupLevel" value="9">All Groups</input>
	<input type="radio" name="groupLevel" value="0" checked>Super Admin</input>
	<input type="radio" name="groupLevel" value="1">Admin</input>
	<input type="radio" name="groupLevel" value="2">User</input>
	<input type="radio" name="groupLevel" value="3">Dispatch Blacklisted</input>';
}
if ($filterLevel == 1) {
	echo '<input type="radio" name="groupLevel" value="9">All Groups</input>
	<input type="radio" name="groupLevel" value="0">Super Admin</input>
	<input type="radio" name="groupLevel" value="1" checked>Admin</input>
	<input type="radio" name="groupLevel" value="2">User</input>
	<input type="radio" name="groupLevel" value="3">Dispatch Blacklisted</input>';
}
if ($filterLevel == 2) {
	echo '<input type="radio" name="groupLevel" value="9">All Groups</input>
	<input type="radio" name="groupLevel" value="0">Super Admin</input>
	<input type="radio" name="groupLevel" value="1">Admin</input>
	<input type="radio" name="groupLevel" value="2" checked>User</input>
	<input type="radio" name="groupLevel" value="3">Dispatch Blacklisted</input>';
}
if ($filterLevel == 3) {
	echo '<input type="radio" name="groupLevel" value="9">All Groups</input>
	<input type="radio" name="groupLevel" value="0">Super Admin</input>
	<input type="radio" name="groupLevel" value="1">Admin</input>
	<input type="radio" name="groupLevel" value="2">User</input>
	<input type="radio" name="groupLevel" value="3" checked>Dispatch Blacklisted</input>';
}

$maxPageSize = 6;
$currentPage = 1;
if (isset($_GET['page'])) {
	$currentPage = $_GET['page'];
}
$startNumber = ($maxPageSize * $currentPage) - $maxPageSize;
$endNumber = ($maxPageSize * $currentPage);
$i = 0;
if ($isFiltered) {
	if ($filterLevel == 9) {
		$result = mysqli_query($connection, "SELECT * FROM cad_users")
		or die(mysqli_error());
	}
	if ($filterLevel == 0) {
		$result = mysqli_query($connection, "SELECT * FROM cad_users WHERE level='0'")
		or die(mysqli_error());
	}
	if ($filterLevel == 1) {
		$result = mysqli_query($connection, "SELECT * FROM cad_users WHERE level='1'")
		or die(mysqli_error());
	}
	if ($filterLevel == 2) {
		$result = mysqli_query($connection, "SELECT * FROM cad_users WHERE level='2'")
		or die(mysqli_error());
	}
	if ($filterLevel == 3) {
		$result = mysqli_query($connection, "SELECT * FROM cad_users WHERE level='3'")
		or die(mysqli_error());
	}
} else if ($isNameFiltered) {
	$result = mysqli_query($connection, "SELECT * FROM cad_users WHERE username='$filterName'")
	or die(mysqli_error());
} else {
	$result = mysqli_query($connection, "SELECT * FROM cad_users")
	or die(mysqli_error());
}
$resRows = mysqli_num_rows($result);
$pageCount = ceil($resRows / $maxPageSize);
echo '<input type="submit" name="groupFilter" value="Apply Filter" class="btn btn-primary form-control center" style="width:auto; margin-top:0px; margin-bottom:0px; margin-left:6px;">
</form>
<form action="" method="post" id="userS">
<input type="text" name="searchUser" id="searchUser" placeholder="Search" class="form-control center" style="display:inline; width:auto; margin-top:0px; margin-bottom:0px; margin-left:6px;">
<input type="submit" name="userSearch" id="userSearch" value="Search" class="btn btn-primary form-control center" style="width:auto; margin-top:0px; margin-bottom:0px; margin-left:6px;">
</form>';
$disablePrev = false;
$disableNext = false;
if (($resRows - $endNumber) <= 0) {
	$disableNext = true;
}
if ($currentPage == 1) {
	$disablePrev = true;
}
if ($disablePrev) {
	echo '<a><button id="prevPage" class="btn btn-primary form-control center" style="width:130px;margin-top:0px;margin-bottom:6px;float:left;">< Previous Page</button></a>';
} else {
	echo '<a href="?page=' . (($currentPage - 1) + $disablePrev) . '"><button id="prevPage" class="btn btn-primary form-control center" style="width:130px;margin-top:0px;margin-bottom:6px;float:left;">< Previous Page</button></a>';
}
if ($disableNext) {
	echo '<a><button id="nextPage" class="btn btn-primary form-control center" style="width:130px;margin-top:0px;margin-bottom:6px;float:right;">Next Page ></button></a>';
} else {
	echo '<a href="?page=' . (($currentPage + 1) - $disableNext) . '"><button id="nextPage" class="btn btn-primary form-control center" style="width:130px;margin-top:0px;margin-bottom:6px;float:right;">Next Page ></button></a>';
}
$pagesHtml = "";
if ($currentPage >= 4) {
	$pagesHtml .= '<a id="link_bar" href="?page=1">1</a> ... ';
}
while ($i != $pageCount) {
	$i = $i + 1;
	if ($i >= $currentPage && $i != $currentPage+2) {
		if ($i == $currentPage) {
			$pagesHtml .= '<a id="link_bar_dis">' . $i . '</a>';
		} else {
			$pagesHtml .= '<a id="link_bar" href="?page=' . $i . '">' . $i . '</a>';
		}
	}
	if ($i == $currentPage+2) {

	}
}
$pagesHtml .= ' ... <a id="link_bar" href="?page=' . $pageCount . '">' . $pageCount . '</a>';
$i = 0;
echo '<div class="center">' . $pagesHtml . '</div>';
if ($resRows == 0) {
	echo "No results.";
} else {
	if (($resRows - $endNumber) > (-1 * $maxPageSize)) {
		echo "<table border='1' cellpadding='10'>";
		echo "<tr> <th>Username</th> <th>Access Level</th> <th>E-Mail</th> <th>UUID</th> <th></th> <th></th></tr>";
		
		while($row = mysqli_fetch_array( $result )) {
			if ($i >= $startNumber) {
				if ($i != $endNumber) {
					echo "<tr>";
					if ($row['username'] == '') {
						$result = mysql_query("DELETE FROM cad_users WHERE uuid=" . $row['uuid']);
						header("Refresh:0");
					}
					else {
						echo '<td>'.$row['username'].'</td>';
					}
					if($row['level'] == 3){
						echo '<td>Dispatch Blacklisted</td>';
					}
					elseif($row['level'] == 2){
						echo '<td>User</td>';
					}
					elseif($row['level'] == 1){
						echo '<td>Administrator</td>';
					}
					elseif($row['level'] == 0){
						echo '<td>Super Admin</td>';
					}
					echo '<td>' . $row['email'] . '</td>';
					echo '<td>' . $row['uuid'] . '</td>';
					echo '<td><button id="disp" class="btn btn-primary form-control center" onclick="window.location.href=' . "'edit?uuid=" . $row['uuid'] . "'" . '" style="width:100%;margin-top:0px;">Edit</button>
					</td>';
					echo '<td><button id="disp" class="btn btn-primary form-control center" onclick="window.location.href=' . "'delete?uuid=" . $row['uuid'] . "'" . '" style="width:100%;margin-top:0px;">Delete</button></td>';
					echo "</tr>";
				} else {
					break;
				}
			}
			$i = $i + 1;
		}
		echo "</table>";
	} else {
		echo "invalid page";
	}
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
</div>
</div>
</body>

</html>
