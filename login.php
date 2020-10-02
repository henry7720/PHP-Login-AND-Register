<?php
session_start();
require "config.php";
if(isset($_POST['username']) && isset($_POST['password'])) {
	// Database connection
	$db = new PDO('mysql:host=127.0.0.1;dbname=' . DB_NAME, DB_USER, DB_PASS);
	
	// Check if the user exists
	$query = $db->prepare("SELECT * FROM users WHERE username = :username");
	$query->bindParam(':username', $_POST['username']);
	$query->execute();
	if($query->rowCount() == 1) {
		// The user has been found, so we check the password
		$details = $query->fetch(PDO::FETCH_ASSOC);
		if(PASSWORD_VERIFY($_POST['password'], $details['password'])) {
			$_SESSION['username'] = $details['username'];
		} else {
			// Incorrect password
			$msg = "Incorrect password.";
		}
	} else {
		// The username does not exist
                $msg = "That username does not exist!";
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
<?php if (!empty($msg)) {
   echo "<p>$msg</p>";
} ?>
<h1>Login</h1>
<?php 
if(isset($_GET['next'])){
echo htmlentities($_GET['next']); 
}else{
	$next = $_SERVER['REQUEST_URI'];
}
?>
<form action="login.php?next=<?php echo $next; ?>" method="post">
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
	header("Location: http://example.com".$_GET['next']);
	}
# Change "http://example.com" to your IP or URL	
?>
