<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming you have the pastActivities array from your session
    $selectedActivity = $_POST['delete_activity'];

    // Assuming you have the organization_id and organization_name in your session
    $organizationId = $_SESSION['organization_id'];
    $organizationName = $_SESSION['organization_name'];

    $query = "DELETE FROM activities 
              WHERE activity_name = '$selectedActivity' 
                AND organization_id = '$organizationId' 
                AND organization_name = '$organizationName'";

    if (mysqli_query($conn, $query)) {
        header('Location: organization_dashboard.php');
        exit();
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>



