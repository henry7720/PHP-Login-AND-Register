<?php
session_start();
if(isset($_SESSION['username'])) {
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Password Protected Page</title>
</head>
<body>
<h1>Protected Page</h1>
<p>This content CANNOT be viewed without an account!</p>
</body>
</html>
<?php
} else {
    header("Location: http://example.com/login.php?next=" .$_SERVER['PHP_SELF']);
}
?>
