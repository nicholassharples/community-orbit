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

// Initialize an array to store the fetched data
$data = [];

// Check if there are results
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();

// Output the data in JSON format
echo json_encode($data);
?>