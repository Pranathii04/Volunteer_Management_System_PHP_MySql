<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the authentication stored procedure
    $query = "CALL VolunteerAuthentication('$username', '$password', @auth_success)";
    mysqli_query($conn, $query);

   
    // Check organization credentials and get organization details
    $query   = "SELECT volunteer_id, first_name FROM volunteers WHERE username = '$username' AND password = '$password'";
    $result1 = mysqli_query($conn, $query);

    // Retrieve the result
    $result = mysqli_query($conn, "SELECT @auth_success as auth_success");
    $row = mysqli_fetch_assoc($result);
    $auth_success = $row['auth_success'];

    if ($auth_success) {
        $volunteer = mysqli_fetch_assoc($result1);
        session_start();

        $_SESSION['volunteer_id']     = $volunteer['volunteer_id'];
        $_SESSION['volunteer_name']   = $volunteer['first_name'];

        // Redirect to the volunteer dashboard or any other page
        header('Location: volunteer_dashboard.php');
        exit();
    } else {
        // Redirect to the login page with an error message
        header('Location: volunteer_login.php?error=1');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
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
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #007BFF;
        }

        input {
            margin: 10px 0;
            padding: 12px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 12px;
            width: 100%;
            box-sizing: border-box;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }

        p {
            margin-top: 20px;
            font-size: 14px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Volunteer Login</h2>
        <?php
            // Display error message if authentication failed
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo '<p class="error-message">Invalid username or password. Please try again.</p>';
            }
        ?>
        <form action="volunteer_login.php" method="POST" id="volunteerLoginForm">
            <!-- Common fields -->
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <!-- Login button -->
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register1.php">Register</a></p>
    </div>
</body>
</html>
