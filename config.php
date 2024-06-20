<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();

$client->setAuthConfig('credentials.json'); // Path to OAuth 2.0 credentials
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/intuji-assignment/index.php');
?>