<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming you have the pastActivities array from your session
    $selectedActivity = $_POST['edit_activity'];

    // Assuming $pastActivities is an array with details of past activities
    $editDescription = mysqli_real_escape_string($conn, $_POST['edit_description']);
    $editStartDate = date('Y-m-d', strtotime($_POST['edit_start_date']));
    $editEndDate = date('Y-m-d', strtotime($_POST['edit_end_date']));
    $editLocation = mysqli_real_escape_string($conn, $_POST['edit_location']);

    // Assuming you have the organization_id and organization_name in your session
    $organizationId = $_SESSION['organization_id'];
    $organizationName = $_SESSION['organization_name'];

    $query = "UPDATE activities 
              SET description = '$editDescription', 
                  start_date = '$editStartDate', 
                  end_date = '$editEndDate', 
                  location = '$editLocation' 
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
