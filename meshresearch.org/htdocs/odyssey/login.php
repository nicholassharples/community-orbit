<?php

session_start();

$loginError = '';

//if ($_SESSION['loggedin']) {
//		echo "logged in";
//	} else {
//		echo "not logged in";
//	}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $user = sanitise_input($_POST['username']);
    $password = sanitise_input($_POST['password']);

    // Validate credentials
    if (validateUser($user, $password)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user; // Store the username in session
        header("Location: dbinterface.php");
        exit;
    } else {
        $loginError = 'Invalid username or password.';
    }
}


function validateUser($user, $password) {
    // MySQL server connection settings
	$servername = "localhost";
	$username = "root";
	$dbpassword = "";
	$dbname = "stfc_universe";

	// Create connection
	$conn = new mysqli($servername, $username, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	
	// SQL to get the hashed password
	$query = "SELECT password_hash FROM credentials WHERE username = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();
	
	
	if ($row = $result->fetch_assoc()) {
		//echo strval($row['password_hash']);
		if (password_verify($password, $row['password_hash'])) {
			return true;
		} else {
			return false;
		}
	} else {
		echo "user not found";
	} 
	$conn->close(); // Close connection
}

function sanitise_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if ($loginError) : ?>
        <p style="color: red;"><?php echo $loginError; ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
