<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'config.php';
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

if(!isset($_SESSION['access_token']) && !isset($_GET['code'])){
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    //echo '<a href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '">Connect to Google Calendar</a>';
} 

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $service = new Google_Service_Calendar($client);
    $calendarId = 'primary';
    //print_r($service->events);
    $events = $service->events->listEvents($calendarId);
    
    $eventList = [];
    foreach ($events->getItems() as $event) {
        $eventList[] = [
            'id' => $event->getId(),
            'summary' => $event->getSummary(),
            'start' => $event->getStart()->getDateTime(),
            'end' => $event->getEnd()->getDateTime()
        ];
    }
 
} // End if ! isset($_SESSION['access_token']
else {
    $authUrl = $client->createAuthUrl();
    echo '<a href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '">Connect to Google Calendar</a>';
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>List Events</title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Google Calendar App</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
    <?php
        if(! isset($_SESSION['access_token']) ){
            $authUrl = $client->createAuthUrl();
            echo '<a href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '">Connect to Google Calendar</a>';
            ?>
            <li class="nav-item">
                <a class="nav-link" href="create_event.php">Create Event</a>
            </li>
    <?php 
        } // End if  ! isset($_SESSION['access_token'] 
        else {
    ?>
      <li class="nav-item">
        <a class="nav-link" href="create.php">Create Event</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="delete.php">Delete Event</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="disconnect.php">Disconnect</a>
      </li>
      <?php } // end else condition ?>
    </ul>
  </div>
</nav>
<div class="container">
    <h1>Google Calendar Events</h1>
    <table id="eventsTable" class="table table-striped">
        <thead>
            <tr>
                <th>Summary</th>
                <th>Start</th>
                <th>End</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventList as $event): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['summary']); ?></td>
                    <td><?php echo htmlspecialchars($event['start']); ?></td>
                    <td><?php echo htmlspecialchars($event['end']); ?></td>
                    <td>
                        <button class="btn btn-danger delete-btn" data-id="<?php echo $event['id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.2.0.min.js" integrity="sha256-JAW99MJVpJBGcbzEuXk4Az05s/XyDdBomFqNlM3ic+I=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
    $('#eventsTable').DataTable();

    $("#eventsTable").find('.delete-btn').click(function() {
        var eventId = $(this).data('id');
        if (confirm('Are you sure you want to delete this event?')) {
            $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: { id: eventId },
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        }
    });
});
</script>
</body>
</html>