<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Your protected page content below
echo "Welcome, you are logged in!";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Image and Add Data to Database</title>
</head>
<body>
    <form action="db_insert.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br>
        Number 1: <input type="text" name="number1" id="number1">
        <br>
        Number 2: <input type="text" name="number2" id="number2">
        <br>
        <input type="submit" value="Upload Image" name="submit">
    </form>
</body>
</html>