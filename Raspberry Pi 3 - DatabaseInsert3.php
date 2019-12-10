<?php
$servername = "172.28.175.41";
$username = "raspbian_3";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO beaconusers (beacon_name, beacon_mac_address, user_first_name, user_last_name, room_name) VALUES ('Beacon3', 'f2:36:00:21:c0:50', 'Niko', 'Kulmanen', '5004');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
