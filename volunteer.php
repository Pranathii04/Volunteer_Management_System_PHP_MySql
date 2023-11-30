<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $volunteer_skill = $_POST['volunteer_skill'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Process the data and insert into the database
    $sql = "INSERT INTO volunteers (first_name, last_name, volunteer_skill, username, password, email) 
            VALUES ('$first_name', '$last_name', '$volunteer_skill', '$username', '$password', '$email')";

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
