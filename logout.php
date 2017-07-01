<?php
session_start();
session_destroy();
header("Location: http://example.com/login.php");
?>
