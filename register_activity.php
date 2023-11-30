<?php
session_start();

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $volunteerId = $_SESSION['volunteer_id'];
    $activityId = $_POST['activity_id'];

    // Register the volunteer for the activity
    $registrationResult = registerForActivity($volunteerId, $activityId, 'registration');

    // Redirect to the main page with the result message
    header('Location: volunteer_dashboard.php?registration_result=' . urlencode($registrationResult));
    exit();
}

// If user navigates to this page without a POST request, redirect them to the homepage
header('Location: index.php');
exit();

function registerForActivity($volunteerId, $activityId, $event_type) {
    global $conn;

    // Use prepared statements to prevent SQL injection
    $checkQuery = "SELECT * FROM registrations WHERE volunteer_id = ? AND activity_id = ?";
    $checkStatement = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStatement, "ii", $volunteerId, $activityId);
    mysqli_stmt_execute($checkStatement);
    $checkResult = mysqli_stmt_get_result($checkStatement);

    if (mysqli_num_rows($checkResult) > 0) {
        // Volunteer is already registered
        return 'You are already registered for this activity.';
    } else {
        // Get organization_id for the activity
        $orgQuery = "SELECT organization_id FROM activities WHERE activity_id = ?";
        $orgStatement = mysqli_prepare($conn, $orgQuery);
        mysqli_stmt_bind_param($orgStatement, "i", $activityId);
        mysqli_stmt_execute($orgStatement);
        $orgResult = mysqli_stmt_get_result($orgStatement);

        // Check if the organization_id is found
        if ($orgRow = mysqli_fetch_assoc($orgResult)) {
            $organizationId = $orgRow['organization_id'];

            // Register the volunteer for the activity
            $registerQuery = "INSERT INTO registrations (volunteer_id, activity_id, organization_id) VALUES (?, ?, ?)";
            $registerStatement = mysqli_prepare($conn, $registerQuery);
            mysqli_stmt_bind_param($registerStatement, "iii", $volunteerId, $activityId, $organizationId);

            if (mysqli_stmt_execute($registerStatement)) {
                // Log the registration event
                $logMessage = logVolunteerActivity($volunteerId, $activityId, $event_type);

                return 'Registration successful! ' . $logMessage;
            } else {
                return 'Error registering: ' . mysqli_error($conn);
            }
        } else {
            return 'Error getting organization_id: ' . mysqli_error($conn);
        }
    }
}

function logVolunteerActivity($volunteerId, $activityId, $event_type) {
    global $conn;

    // Log the registration event in the volunteer_log table
    $logQuery = "INSERT INTO volunteer_log (activity_id, volunteer_id, event_type) VALUES (?, ?, ?)";
    $logStatement = mysqli_prepare($conn, $logQuery);
    mysqli_stmt_bind_param($logStatement, "iis", $activityId, $volunteerId, $event_type);

    if (mysqli_stmt_execute($logStatement)) {
        return 'Event logged successfully.';
    } else {
        return 'Error logging volunteer activity: ' . mysqli_error($conn);
    }
}
?>
