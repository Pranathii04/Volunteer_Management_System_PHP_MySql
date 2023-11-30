<!DOCTYPE html>
<html lang="en">
<head>
    <title>Volunteer Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f4f4; /* Light background color */
            color: #333; /* Text color */
        }

        header {
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        nav {
            background-color: #333;
            font-family: 'Verdana', sans-serif;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        nav li {
            margin: 0 20px;
        }

        nav a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
            transition: color 0.3s ease;
            border-radius: 10px;
            padding: 10px 20px; /* Adjusted padding */
            position: relative;
        }

        nav a.login {
            background: none;
            padding: 0;
        }

        nav a:hover {
            color: #ffc107;
        }

        nav a:after {
            content: '';
            display: block;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #fff;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        nav a:hover:after {
            transform: scaleX(1);
        }

        main {
            padding: 20px;
            flex: 1;
            text-align: center;
            font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;
            color: #333; /* Text color */
        }

        h2, h3 {
            color: #007BFF;
            font-family: 'Arial', sans-serif;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            font-family: 'Arial', sans-serif;
        }

        img {
            width: 100%;
            max-width: 600px;
            height: 200px;
            object-fit: cover;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to the Volunteer Management System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="login.html" class="login">Login</a></li>
            <li><a href="register1.php">Register</a></li>
        </ul>
    </nav>
    <main>
        <h2>The Volunteer Management System is a platform that connects volunteers and organizations.</h2>
        <p>It allows volunteers to find and join various activities and enables organizations to post and manage volunteer opportunities.</p>

        <!-- Placeholder images (replace with actual paths or URLs) -->
        <img src="P1.PNG" alt="Image P1">
        <img src="P2.PNG" alt="Image P2">
        <!-- <img src="P3.jpg" alt="Image P3"> -->
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Volunteer Management System</p>
        <p>Developed by Pathakota Pranathi and Pannuru Sidhvi</p>
    </footer>
</body>
</html>
