<?php
// MySQL server connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stfc_universe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all data from the table
$sql = "SELECT * FROM celestial_objects";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - x: " . $row["x"]. " - y: " . $row["y"]. "<br>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>