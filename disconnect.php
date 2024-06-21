<?php
require 'config.php';

unset($_SESSION['access_token']);
// echo "Disconnect <br/>";
// print_r($_SESSION['access_token']);
//exit;
// remove all session variables
session_unset();

// destroy the session
session_destroy();
foreach ($_COOKIE as $key => $value) {
    setcookie($key, $value, $expire, "/");
}
header('Location: index.php');
?>
