<?php
$user = ''; // add usernames and passwords here
$password = '';

// Hash the password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);


// SQL query
$query = "INSERT INTO credentials (username, password_hash) VALUES (?, ?)";

// MySQL server connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stfc_universe";

echo "Parameters defined";
echo $servername;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

echo "Connection created";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Execute insertion
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $user, $passwordHash);
$stmt->execute();

echo "Execution completed";
?>