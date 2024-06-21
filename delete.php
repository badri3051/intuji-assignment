<?php
require 'config.php';
//print_r($_SESSION['access_token']); exit;
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $service = new Google_Service_Calendar($client);

    $calendarId = 'primary';
    
    $eventId = $_POST['id']; // Replace with the ID of the event you want to delete

    $service->events->delete($calendarId, $eventId);
    echo 'Event deleted.';
} else {
    header('Location: index.php');
}
?>