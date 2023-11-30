<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        select, button {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" id="registrationForm">
            <!-- User type selection -->
            <label for="userType">Select user type:</label>
            <select name="user_type" id="userType">
                <option value="-" selected disabled>Select User Type</option>
                <option value="volunteer">Volunteer</option>
                <option value="organization">Organization</option>
            </select>

            <!-- Register button -->
            <button type="button" onclick="redirectToPage()">Register</button>
        </form>
        <p>Already have an account? <a href="login.html">Login</a></p>
    </div>

    <script>
        function redirectToPage() {
            var userType = document.getElementById('userType').value;
            if (userType === 'volunteer') {
                window.location.href = 'volunteer.html';
            } else if (userType === 'organization') {
                window.location.href = 'organization.html';
            }
        }
    </script>
</body>
</html>
