<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Your protected page content below
echo "Welcome! Here you can add a planet to our database.";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Image and Add Data to Database</title>
</head>
<body>
    <form action="db_insert.php" method="post" enctype="multipart/form-data">
        Select image of planet to upload:
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <br>
		<br>
		Planet name: <input type="text" name="planetName" id="planetName" placeholder="Planet name" required>
		<br>
		<br>
		Planet mass: Pluto <input type="range" min="0" max="500" value="278" class="slider" id="massSlider" name="planetMass"> Jupiter. Value: <span id="massValue"></span>
        <br>
		<br>
		Planet maximum distance: Mercury <input type="range" min="48" max="500" value="148" class="slider" id="distanceSlider" name="planetDistance"> Jupiter. Value: <span id="distanceValue"></span>
        <br>
		<br>
		Planet speed at maximum distance: Stationary <input type="range" min="0" max="500" value="297" class="slider" id="velocitySlider" name="planetVelocity"> Mercury. Value: <span id="velocityValue"></span>
        <br>
		<br>
		Planet argument of periapsis: <input type="range" min="0" max="360" value="0" class="slider" id="tiltSlider" name="planetTilt"> Value: <span id="tiltValue"></span>
        <br>
		<br>
		Planet angle from periapsis: <input type="range" min="0" max="360" value="0" class="slider" id="initialAngleSlider" name="planetInitialAngle"> Value: <span id="initialAngleValue"></span>
        <br>
		<br>
		Description of planet: <textarea rows="10" cols="50" name="planetDescription" placeholder="Description of planet" required></textarea>
        <br>
		<br>
		Discovered by: <input type="text" placeholder="Your name" name="planetAuthor" required>
        <br>
		<br>
		
		<input type="checkbox" required> I consent for my planet to be added to the database to appear on meshresearch.org/odyssey. Further I release my artwork to be used in current and future meshresearch maths communication projects.
		<br>
		<br>
		<input type="submit" value="Add Planet" name="submit">
    </form>
	
<script>
function prettyNumber(x) {
	return Number.parseFloat(x).toLocaleString(0);
}

function getRandomInt(max) {
  return Math.floor(Math.random() * max);
}

// Display Mass
var massSlider = document.getElementById("massSlider");
var massDisplay = document.getElementById("massValue");
massDisplay.innerHTML = prettyNumber(Math.floor(10**(massSlider.value/100)) * 1e22) + " kg";

massSlider.oninput = function() {
  massDisplay.innerHTML = prettyNumber(Math.floor(10**(massSlider.value/100)) * 1e22) + " kg";
}

// Display Distance

var distanceSlider = document.getElementById("distanceSlider");
var distanceDisplay = document.getElementById("distanceValue");
distanceDisplay.innerHTML = prettyNumber(distanceSlider.value*1e6) + " km";

distanceSlider.oninput = function() {
  distanceDisplay.innerHTML = prettyNumber(this.value*1e6) + " km";
}

// Display Velocity

var velocitySlider = document.getElementById("velocitySlider");
var velocityDisplay = document.getElementById("velocityValue");
velocityDisplay.innerHTML = prettyNumber(velocitySlider.value/10) + " km/s";

velocitySlider.oninput = function() {
  velocityDisplay.innerHTML = prettyNumber(this.value/10) + " km/s";
}

// Display AxialTilt
var tiltSlider = document.getElementById("tiltSlider");
var tiltDisplay = document.getElementById("tiltValue");
tiltSlider.value = getRandomInt(360); // Random initial value
tiltDisplay.innerHTML = tiltSlider.value + "\u00B0";

tiltSlider.oninput = function() {
  tiltDisplay.innerHTML = this.value + "\u00B0";
}


// Display Initial Angle
var initialAngleSlider = document.getElementById("initialAngleSlider");
var initialAngleDisplay = document.getElementById("initialAngleValue");
initialAngleSlider.value = getRandomInt(360); // Random initial value
initialAngleDisplay.innerHTML = initialAngleSlider.value + "\u00B0";

initialAngleSlider.oninput = function() {
  initialAngleDisplay.innerHTML = this.value + "\u00B0";
}


</script>

</body>
</html>

