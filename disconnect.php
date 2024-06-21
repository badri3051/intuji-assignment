<?php
require 'config.php';

unset($_SESSION['access_token']);

session_unset();

// destroy the session
session_destroy();
foreach ($_COOKIE as $key => $value) {
    setcookie($key, $value, $expire, "/");
}
header('Location: index.php');
exit;
?>
