
<?php
    include(__DIR__ . "/../../config.php");
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
    
    $query = mysqli_query($connection, "SELECT * FROM units WHERE callsign LIKE '%".$searchTerm."%' AND status='1' AND active='1' OR active='3' AND oncall_ucid='' ORDER BY callsign ASC")
        or die(mysqli_error($connection));
    while ($row = mysqli_fetch_assoc($query)) {
        if (in_array($row['callsign'], $data) == false) {
            $data[] = $row['callsign'];
        }
    }
    
    echo json_encode($data);
?>
