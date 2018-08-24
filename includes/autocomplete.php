<?php
    include(__DIR__ . "/../config.php");
    $dbHost = DB_HOST;
    $dbUsername = DB_USER;
    $dbPassword = DB_PASSWORD;
    $dbName = DB_NAME;

$connection = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

    $searchTerm = $_GET['term'];

    $data = array();

    $query = mysqli_query($connection, "SELECT * FROM cad_users WHERE username LIKE '%".$searchTerm."%' ORDER BY username ASC")
        or die(mysqli_error($connection));
    while ($row = mysqli_fetch_assoc($query)) {
        if (in_array($row['username'], $data) == false) {
            $data[] = $row['username'];
        }
    }

    echo json_encode($data);
?>
