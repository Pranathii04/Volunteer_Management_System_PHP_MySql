<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $email = $_POST['email'];

    // Common fields
    $common_fields = array('username', 'password', 'email');

    // Volunteer-specific fields
    $volunteer_fields = array('first_name', 'last_name', 'volunteer_skill');

    // Organization-specific fields
    $organization_fields = array('organization_name', 'contact_person');

    // Extract and validate common fields
    $common_values = array();
    foreach ($common_fields as $field) {
        if (isset($_POST[$field])) {
            $common_values[$field] = htmlspecialchars($_POST[$field]);
        } else {
            die("Error: Missing required field - $field");
        }
    }

    // Extract and validate user type-specific fields
    $user_type_values = array();
    switch ($user_type) {
        case 'volunteer':
            $fields = $volunteer_fields;
            break;
        case 'organization':
            $fields = $organization_fields;
            break;
        default:
            die("Error: Invalid user type");
    }

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $user_type_values[$field] = htmlspecialchars($_POST[$field]);
        } else {
            die("Error: Missing required field - $field");
        }
    }

    // Insert data into the appropriate table based on user type
    switch ($user_type) {
        case 'volunteer':
            $sql = "INSERT INTO volunteers (username, password, email, first_name, last_name, volunteer_skill) VALUES (?, ?, ?, ?, ?, ?)";
            break;
        case 'organization':
            $sql = "INSERT INTO organizations (username, password, email, organization_name, contact_person) VALUES (?, ?, ?, ?, ?)";
            break;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $common_values['username'], $hashed_password, $common_values['email'], $user_type_values['first_name'], $user_type_values['last_name'], $user_type_values['volunteer_skill']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Registration successful!";
    } else {
        echo "Error during registration: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
