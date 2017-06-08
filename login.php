<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password'])) {
	// Database connection
	$user = "username";
	$pass = "password";
	$db = new PDO('mysql:host=127.0.0.1;dbname=mydatabase', $user, $pass);
	
	// Check if the user exists
	$query = $db->prepare("SELECT * FROM users WHERE username = :username");
	$query->bindParam(':username', $_POST['username']);
	$query->execute();
	if($query->rowCount() == 1) {
		// The user has been found, so we check the password
		$details = $query->fetch(PDO::FETCH_ASSOC);
		if(md5($_POST['password'] . $details['salt']) == $details['password']) {
			$_SESSION['username'] = $details['username'];
		} else {
			// Incorrect password
			echo "<p>Incorrect password.</p><br>";
		}
	} else {
		// The username does not exist
			echo "<p>That username does not exist!</p><br>";
	}
}
if(!isset($_SESSION['username'])) {
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Login to Continue</title>
<meta charset="utf-8">
</head>
<body>
<h1>Login</h1>
<form action="login.php?next=<?php echo $_GET['next']; ?>" method="post">
<input type="text" name="username" id="username" placeholder="Username">
<br>
<input type="password" name="password" id="password" placeholder="Password">
<br>
<br>
<input type="submit" value="Login">
</form>
<p><a href="register.php">Click here to Register</a>.</p>
</body>
</html>
<?php
} else {
	header("Location: http://example.com" .$_GET['next']);
	}
# Change "http://example.com" to your IP or URL	
?>
