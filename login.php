<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists in the volunteers table
    $volunteer_sql = "SELECT volunteer_id, first_name, last_name FROM volunteers WHERE username = ? AND password = ?";
    $volunteer_stmt = $conn->prepare($volunteer_sql);
    $volunteer_stmt->bind_param("ss", $username, $password);
    $volunteer_stmt->execute();
    $volunteer_stmt->bind_result($user_id, $first_name, $last_name);
    $volunteer_stmt->fetch();
    $volunteer_stmt->close();

    // Check if the user exists in the organizations table
    $organization_sql = "SELECT organization_id, organization_name FROM organizations WHERE username = ? AND password = ?";
    $organization_stmt = $conn->prepare($organization_sql);
    $organization_stmt->bind_param("ss", $username, $password);
    $organization_stmt->execute();
    $organization_stmt->bind_result($user_id, $organization_name);
    $organization_stmt->fetch();
    $organization_stmt->close();

    if ($user_id) {
        // User found in either volunteers or organizations table

        // You can use $first_name, $last_name, and $organization_name as needed

        // Initialize session
        session_start();
        $_SESSION['user_id'] = $user_id;

        // Redirect based on user type
        if (isset($first_name)) {
            $_SESSION['user_type'] = 'volunteer';
            header('Location: volunteer_dashboard.php');
            exit();
        } elseif (isset($organization_name)) {
            $_SESSION['user_type'] = 'organization';
            header('Location: organization_dashboard.php');
            exit();
        }
    } else {
        // User not found in either table
        $login_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php
        if (isset($login_message)) {
            echo "<p class='error'>$login_message</p>";
        }
        ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.html">Register</a></p>
    </div>
</body>
</html>
