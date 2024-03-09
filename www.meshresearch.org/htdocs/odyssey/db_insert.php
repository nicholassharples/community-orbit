<?php
$target_dir = "images/";
$source_file = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));

$target_file = $target_dir .date("Y-m-d h:i:s").".".$imageFileType;

echo $target_file;

// Check if image file is an actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
    } else {
        echo "File is not an image.";
        exit;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    exit;
}

// Check file size - Let's say we limit it to 5MB
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    exit;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    exit;
}

// Try to upload file
if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "Sorry, there was an error uploading your file.";
    exit;
}

// $number1 = $_POST['number1'];
// $number2 = $_POST['number2'];

//Insert into Database
// $servername = "localhost";
// $username = "your_username";
// $password = "your_password";
// $dbname = "your_dbname";

//Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

//Check connection
// if ($conn->connect_error) {
    // die("Connection failed: " . $conn->connect_error);
// }

// $sql = "INSERT INTO your_table_name (image, number1, number2)
// VALUES ('$target_file', $number1, $number2)";

// if ($conn->query($sql) === TRUE) {
    // echo "New record created successfully";
// } else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
// }

// $conn->close();
?>