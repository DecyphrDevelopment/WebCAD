<?php session_start(); ?>

<title>AFS CAD</title>
<nav id="primary_nav_wrap">
<ul>
  <li><a href="http://pbslivestream.org/cad">Home</a></li>
  <li><a href="http://pbslivestream.org/cad/dispatch">Dispatch</a></li>
  <li><a href="http://pbslivestream.org/cad/field">Field Page</a></li>
<?php
if (!isset($_SESSION['cad_user'])) {
	echo '</ul><ul><li style="float:right" id="sett"><a href="http://pbslivestream.org/cad/ums/login">Login</a></li></ul>';
} else {
    echo '<li style="float:right" id="sett"><a href="">' . $_SESSION['cad_user'] . '</a><ul id="settt">';
	if ($_SESSION['cad_level'] <= 1) {
        echo '<li><a href="http://pbslivestream.org/cad/ums">User Managment System</a></li>';
	}
	echo '<li><a href="http://pbslivestream.org/cad/ums/logout">Logout</a></li></ul></li></ul></nav>';
}
?>