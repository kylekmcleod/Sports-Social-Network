<?php
session_start();

session_unset();
session_destroy();
header("Location: /COSC360/public/login.php");
exit();
?>
