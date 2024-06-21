<?php
require 'config.php';

unset($_SESSION['access_token']);
header('Location: index.php');
?>
