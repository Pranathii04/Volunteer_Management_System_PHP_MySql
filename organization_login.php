<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the authentication stored procedure for organization
    $query = "CALL OrganizationAuthentication('$username', '$password', @auth_success)";
    mysqli_query($conn, $query);

    // Check organization credentials and get organization details
    $query   = "SELECT organization_id, organization_name FROM organizations WHERE username = '$username' AND password = '$password'";
    $result1 = mysqli_query($conn, $query);

    // Retrieve the result
    $result = mysqli_query($conn, "SELECT @auth_success as auth_success");
    $row    = mysqli_fetch_assoc($result);
    $auth_success = $row['auth_success'];

    if ($auth_success) {

        $organization = mysqli_fetch_assoc($result1);
        // Store organization ID and name in the session
        session_start();
        $_SESSION['organization_id']   = $organization['organization_id'];
        $_SESSION['organization_name'] = $organization['organization_name'];
        // Redirect to the organization dashboard or any other page
        header('Location: organization_dashboard.php');
        exit();
    }
    else {
        // Redirect to the login page with an error message
        header('Location: organization_login.php?error=1');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 320px;
            text-align: center;
            transition: box-shadow 0.3s ease;
        }

        .container:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #d9534f;
            margin: 10px 0;
        }

        p {
            margin-top: 20px;
            color: #555;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Organization Login</h2>
        <?php
            // Display error message if authentication failed
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo '<p class="error-message">Invalid username or password. Please try again.</p>';
            }
        ?>
        <form action="organization_login.php" method="POST" id="organizationLoginForm">
            <!-- Common fields -->
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <!-- Login button -->
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="organization_registration.php">Register</a></p>
    </div>
</body>
</html>
