<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'config.php';


if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google_Service_Calendar($client);
        $start = $_POST['start'];
        $start_time = new DateTime($start);
        $start_time = $start_time->format('Y-m-d\TH:i:s');
        $end = $_POST['end'];
        $end_time = new DateTime($end);
        $end_time = $end_time->format('Y-m-d\TH:i:s');
        $event = new Google_Service_Calendar_Event([
            'summary' => $_POST['summary'],
            'location' => $_POST['location'],
            'description' => $_POST['description'],
            'start' => [
                'dateTime' => $start_time,
                'timeZone' => 'Asia/Kathmandu',
            ],
            'end' => [
                'dateTime' => $end_time,
                'timeZone' => 'Asia/Kathmandu',
            ],
        ]);
       
        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);
        echo 'Event created: %s\n' . $event->htmlLink;
        header('Location: index.php');
    }
} else {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Create Event</h1>
    <form method="POST" action="create.php">
        <div class="form-group">
            <label for="summary">Summary</label>
            <input type="text" class="form-control" id="summary" name="summary" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="start">Start Date and Time</label>
            <input type="datetime-local" class="form-control" id="start" name="start" required>
        </div>
        <div class="form-group">
            <label for="end">End Date and Time</label>
            <input type="datetime-local" class="form-control" id="end" name="end" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>
</body>
</html>