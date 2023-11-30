<?php
session_start();

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $volunteerId = $_SESSION['volunteer_id'];
    $activityId = $_POST['activity_id'];

    // Delete the registration record
    $deleteQuery = "DELETE FROM registrations WHERE volunteer_id = $volunteerId AND activity_id = $activityId";
    if (mysqli_query($conn, $deleteQuery)) {
        // Log the cancellation event
        logVolunteerActivity($volunteerId, $activityId, 'cancellation');

        // Redirect to the main page with a success message
        header('Location: volunteer_dashboard.php?cancel_success=1');
        exit();
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// If user navigates to this page without a POST request, redirect them to the homepage
header('Location: index.php');
exit();

function logVolunteerActivity($volunteerId, $activityId, $event_type) {
    global $conn;

    // Log the cancellation event in the volunteer_log table
    $logQuery = "INSERT INTO volunteer_log (activity_id, volunteer_id, event_type) VALUES ($activityId, $volunteerId, '$event_type')";
    if (!mysqli_query($conn, $logQuery)) {
        echo 'Error logging volunteer activity: ' . mysqli_error($conn);
    }
}
?>
