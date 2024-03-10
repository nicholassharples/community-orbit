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
		<br>
		Planet name: <input type="text" name="planetName" id="planetName">
		<br>
		<br>
		Planet mass : Pluto <input type="range" min="1e16" max="1e21" value="6e18" class="slider" id="massSlider"> Jupiter. Value: <span id="massValue"></span>
        <br>
		<br>
		Planet maximum distance : Mercury <input type="range" min="48" max="3700" value="148" class="slider" id="distanceSlider"> Pluto. Value: <span id="distanceValue"></span>
        <br>
		<br>
        <input type="submit" value="Upload Image" name="submit">
    </form>
	
<script>
var massSlider = document.getElementById("massSlider");
var massDisplay = document.getElementById("massValue");
massDisplay.innerHTML = massSlider.value*1e6 + " kg";

massSlider.oninput = function() {
  massDisplay.innerHTML = this.value*1e6 + " kg";
}

var distanceSlider = document.getElementById("distanceSlider");
var distanceDisplay = document.getElementById("distanceValue");
distanceDisplay.innerHTML = distanceSlider.value*1e6 + " km";

distanceSlider.oninput = function() {
  distanceDisplay.innerHTML = this.value*1e6 + " km";
}
</script>

</body>
</html>

