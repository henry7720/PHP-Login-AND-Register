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
						$msg = "Successfully registered! You may now <a href=\"login.php\">login</a>.";
					} else {
						$msg = "The reCaptcha was not completed correctly.";
					}
				} else {
					$msg = "Your passwords did not match.";
				}
			} else {
				$msg = "Your password must be between 8 and 32 characters.";
			}
		} else {
			$msg = "That username is already taken!";
		}
	} else {
		$msg = "Your username must be between 4 and 12 characters and contain only letters, numbers, and/or spaces.";
	}
}
?>				
<html>
<head>
<title>Register</title>
<script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
<?php {
echo "<p>$msg</p><br>";
} ?>
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
