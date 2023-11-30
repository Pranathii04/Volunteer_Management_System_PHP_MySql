<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from the form
    $organization_name = $_POST['organization_name'];
    $contact_person = $_POST['contact_person'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Process the data and insert into the database
    $sql = "INSERT INTO organizations (organization_name, contact_person, username, password, email) 
            VALUES ('$organization_name', '$contact_person', '$username', '$password', '$email')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Redirect to login.html
        header('Location: login.html');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
