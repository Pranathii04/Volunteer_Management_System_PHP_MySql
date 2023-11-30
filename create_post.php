<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $organizationId = $_SESSION['organization_id'];
    $organizationName = $_SESSION['organization_name'];

    $activityName = mysqli_real_escape_string($conn, $_POST['activity_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Convert start and end date strings to timestamp and then to 'YYYY-MM-DD' format
    $startDate = date('Y-m-d', strtotime($_POST['start_date']));
    $endDate = date('Y-m-d', strtotime($_POST['end_date']));

    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $query = "INSERT INTO activities (organization_id, organization_name, activity_name, description, start_date, end_date, location)
              VALUES ('$organizationId', '$organizationName', '$activityName', '$description', '$startDate', '$endDate', '$location')";

    if (mysqli_query($conn, $query)) {
        header('Location: organization_dashboard.php');
        exit();
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>
