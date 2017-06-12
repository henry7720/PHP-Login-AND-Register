<?php
session_start();
if(isset($_SESSION['username'])) {
$user = "username";
$pass = "password";
$db = new PDO('mysql:host=127.0.0.1;dbname=dbname', $user, $pass);

$query = $db->prepare("SELECT * FROM users ORDER BY id DESC LIMIT 1");
$query2 = $db->prepare("SELECT * FROM users");
$query->execute();
$query2->execute();
$details = $query->fetch(PDO::FETCH_ASSOC);
$numusers = $query2->rowCount();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Advanced Options</title>
</head>
<h1>Advanced Options</h1>
<p>This page uses some other helpful queries to display username, newest user, and the number of users registered.</p>
<!-- <p>Welcome, <?php echo htmlentities($_SESSION['username']); ?>! Newest user: <?php echo $details['username']; ?> - <?php echo $numusers; ?> user(s) registered.</p> -->
<p>You are currently logged in as <?php echo htmlentities($_SESSION['username']); ?> - <a href="logout.php">Logout</a>.</p>
</body>
</html>
<?php
} else {
    header("Location: http://example.com/login.php?next=".$_SERVER['PHP_SELF']);
} ?>
