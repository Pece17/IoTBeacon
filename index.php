<!DOCTYPE html>
<html>
<body>

<meta http-equiv="refresh" content="1" >

<title>Lighthouse</title>

<h1>Joni (Beacon1)</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, user_first_name, user_last_name, room_name, updated FROM beaconusers WHERE beacon_name = 'Beacon1' ORDER BY updated DESC LIMIT 5;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "  "  . $row["beacon_name"].  " |  "  . $row["user_first_name"].  "   " . $row["user_last_name"].   " |  Last seen in " . $row["room_name"]. " |  " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<h1>Rasmus (Beacon2)</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, user_first_name, user_last_name, room_name, updated FROM beaconusers WHERE beacon_name = 'Beacon2' ORDER BY updated DESC LIMIT 5;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "  "  . $row["beacon_name"].  " |  "  . $row["user_first_name"].  "   " . $row["user_last_name"].   " |  Last seen in " . $row["room_name"]. " |  " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>


<h1>Niko (Beacon3)</h1>

<?php
$servername = "localhost";
$username = "niko";
$password = "MonialaProjekti";
$dbname = "iotbeacon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT beacon_name, user_first_name, user_last_name, room_name, updated FROM beaconusers WHERE beacon_name = 'Beacon3' ORDER BY updated DESC LIMIT 5;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "  "  . $row["beacon_name"].  " |  "  . $row["user_first_name"].  "   " . $row["user_last_name"].   " |  Last seen in " . $row["room_name"]. " |  " . $row["updated"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>
