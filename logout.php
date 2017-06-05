<?php
session_start();
session_destroy();
header("Location: http://example.com/login.php");
# Can also use:
# echo "<script>window.location = 'http://example.com/login.php'</script>";
?>
