<?php
$target_dir = "images/originals/";
$rescaled_dir = "images/rescaled/";
$source_file = basename($_FILES["fileToUpload"]["name"]);
//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));

$target_file_name = date("Y-m-d-His").".".$imageFileType;
$target_file = $target_dir .$target_file_name;

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
} else {
	rescaleImage($target_file, $rescaled_dir .$target_file_name);
}

$planetName = sanitise_input($_POST['planetName']);
$planetMass = $_POST['planetMass'];
$planetDistance = $_POST['planetDistance'];
$planetVelocity = $_POST['planetVelocity'];
$planetTilt = $_POST['planetTilt'];
$planetInitialAngle = $_POST['planetInitialAngle'];
$planetDescription = sanitise_input($_POST['planetDescription']);
$planetAuthor = sanitise_input($_POST['planetAuthor']);

// Rescale
$planetMass = pow(10,($planetMass/100)) * 1e16;
$planetVelocity = $planetVelocity * 1e-7;
$planetTilt = $planetTilt * 2 / 360 * pi();
$planetInitialAngle = $planetInitialAngle * 2 / 360 * pi();

// MySQL server connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stfc_universe";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
}

$query = "INSERT INTO celestial_objects (name, imageFileName, mass, maxDistance, velocityAtMaxDistance, axialTilt, initialAngle, description, author) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";



$stmt = $conn->prepare($query);
$stmt->bind_param("ssdddddss", $planetName, $target_file_name, $planetMass, $planetDistance, $planetVelocity, $planetTilt, $planetInitialAngle, $planetDescription, $planetAuthor);
$stmt->execute();


$conn->close();


function sanitise_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function rescaleImage($originalImagePath, $rescaledImagePath) {
    // Specify the new dimensions
    $newWidth = 480;
    $newHeight = 480;

    // Get the original image's dimensions and type
    list($width, $height, $imageType) = getimagesize($originalImagePath);

    // Create a new true color image with the specified dimensions
    $rescaledImage = imagecreatetruecolor($newWidth, $newHeight);

    // Load the original image based on its type
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $originalImage = imagecreatefromjpeg($originalImagePath);
            break;
        case IMAGETYPE_PNG:
            $originalImage = imagecreatefrompng($originalImagePath);
            break;
        case IMAGETYPE_GIF:
            $originalImage = imagecreatefromgif($originalImagePath);
            break;
        default:
            echo "Unsupported image type!";
            return;
    }

    // Resample the image
    imagecopyresampled($rescaledImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Save the rescaled image
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($rescaledImage, $rescaledImagePath);
            break;
        case IMAGETYPE_PNG:
            imagepng($rescaledImage, $rescaledImagePath);
            break;
        case IMAGETYPE_GIF:
            imagegif($rescaledImage, $rescaledImagePath);
            break;
    }

    // Free up memory
    imagedestroy($originalImage);
    imagedestroy($rescaledImage);
}
?>


