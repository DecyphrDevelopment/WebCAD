<?php
	session_start();

	if($_SESSION['cad_level'] <= 2) {
		session_destroy();
		echo json_encode(array('response' => 'success'));
		exit();
	}
	else {
		$server = '198.71.225.65';
		$user = 'pbslivestream';
		$pass = 'PBSeagles1981';
		$db = 'database';

		$connection = mysqli_connect($server, $user, $pass, $db)
		or die ("Could not connect to server ... \n" . mysqli_error ());
 
		$uuid = $_SESSION['cad_uuid'];
		$sql = @mysqli_query($connection, "DELETE FROM cad_users WHERE uuid='$uuid'");
		
		session_destroy();
		echo json_encode(array('response' => 'success'));
		exit();
	}
?>