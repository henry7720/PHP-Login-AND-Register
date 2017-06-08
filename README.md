# Simple PHP Login AND Register

A Simple Login/Register developed in PHP with [Google's reCaptcha](https://www.google.com/recaptcha/admin).

## Download

You can download the latest version or view all of the releases [here](https://github.com/henry7720/PHP-Login-AND-Register/releases).

## Requirements

* PHP ≥ 5.4

## How To:

First create a table in a MySQL database called users, with the columns |id|username|password|salt| (in that order from left to right).

Next, open the [`register.php`](register.php) file and fill in your database details (including Google reCaptcha sitekey and secret).

```php
<?php
if(isset($_POST['username']) && isset($_POST['pass']) && isset($_POST['pass2'])) {
	// DB Info
	$user = "username";
	$pass = "password";
	$db = new PDO('mysql:host=127.0.0.1;mydatabase', $user, $pass);
	
	// Captcha info
	$recaptcha_secret = "secretgoeshere";
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);
    $response = json_decode($response, true);
    
    // Begin register
	$username = trim($_POST['username']);
	$password = $_POST['pass'];
	$confirm = $_POST['pass2'];
	$salt = uniqid(mt_rand(), true);
	$hashedPassword = md5($password . $salt);
	if(strlen($username) >= 4 && strlen($username) <= 12 && ctype_alnum(str_replace(" ","",$username))) { // Minimum 4, maximum 12 characters for username
		$nametaken = $db->prepare("SELECT * from users where username = :username");
		$nametaken->bindParam(':username', $username);
		$nametaken->execute();
		if($nametaken->rowCount() == 0) {
			if(strlen($password) >= 8 && strlen($password) <= 32) { // Minimum 8, maximum 32 characters for password
				if($password == $confirm) { // Do the passwords match?
					if($response["success"]  === true) { // They aren't a flesh eating robot from the future...
						$reg = $db->prepare("INSERT INTO users (id, username, password, salt) VALUES (NULL, :username, :password, :salt)");
						$reg->bindParam(':username', $username);
						$reg->bindParam(':password', $hashedPassword);
						$reg->bindParam(':salt', $salt);
						$reg->execute();
						echo "<p>Successfully registered! You may now <a href=\"login.php\">login</a>.</p><br>";
					} else {
						echo "<p>The Recaptcha was not completed correctly.</p><br>";
					}
				} else {
					echo "<p>Your passwords did not match.</p><br>";
				}
			} else {
				echo "<p>Your password must be between 8 and 32 characters.</p><br>";
			}
		} else {
			echo "<p>That username is already taken.</p><br>";
		}
	} else {
		echo "<p>Your username must be between 4 and 12 characters and contain only letters, numbers, and/or spaces.</p><br>";
	}
}
?>				
<html>
<head>
<title>Register</title>
<script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
<h1>Register</h1>
<form action="register.php" method="post">
<input type="text" name="username" id="username" placeholder="Create a Username" maxlength="12">
<br>
<input type="password" name="pass" id="pass" placeholder="Create a Password" maxlength="32">
<br>
<input type="password" name="pass2" id="pass2" placeholder="Confirm Password" maxlength="32">
<br>
<br>
<!-- The reCaptcha does not need to be displayed inline-block, but if you want to center align your reCaptcha, it does -->
<div class="g-recaptcha" data-sitekey="sitekeygoeshere" style="display:inline-block;"></div>
<br>
<br>
<input type="submit" value="Register">
</body>
</html>
```

Finally, open [`login.php`](login.php) and also fill in your database details.

```php
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
```

## Password Protection

This Login and Register is made for password protecting pages.

Reference [`addthistoeachpage.txt`](addthistoeachpage.txt) for more details.

## License

[MIT](LICENSE)
