<?php
session_start();

require 'config.php';

// Fetch posts from activities table
$query = "SELECT * FROM activities";
$result = mysqli_query($conn, $query);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

$query = "SELECT a.*, 
    (SELECT COUNT(*) FROM registrations r WHERE r.activity_id = a.activity_id) AS registered_volunteers,
    (SELECT organization_name FROM organizations o WHERE o.organization_id = a.organization_id) AS organization_name
    FROM activities a";

$result = mysqli_query($conn, $query);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a1a;
            margin: 0;
            padding: 0;
            color: #fff;
        }

        header {
            background-color: #343a40;
            padding: 10px;
            text-align: right;
        }

        header h1, header a {
            color: #fff;
            text-decoration: none;
        }

        main {
            display: flex;
            flex-direction: column;
            margin: 20px;
            align-items: center;
        }

        section {
            margin-bottom: 20px;
            width: 100%;
        }

        ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        li {
            margin: 10px;
            background-color: #2c2c2c;
            border: 1px solid #444;
            border-radius: 5px;
            padding: 20px;
            width: 300px;
            transition: transform 0.3s;
            box-sizing: border-box;
        }

        li:hover {
            transform: scale(1.03);
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            width: 100%;
        }

        .card {
            width: 100%;
            margin: 10px;
            padding: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #2c2c2c;
            transition: transform 0.3s;
            box-sizing: border-box;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .hidden {
            display: none;
        }

        .toggle-heading {
            cursor: pointer;
            user-select: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .arrow {
            transition: transform 0.3s;
        }
         /* Adjusted Button Styles */
    .flip-button {
        background-color: #3498db; /* Blue color */
        color: #fff;
        border: none;
        border-radius: 3px;
        padding: 10px 15px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: inline-block; /* Align buttons horizontally */
    }

    .flip-button:hover {
        background-color: #2980b9; /* Darker blue on hover */
    }

    /* Notification Styles */
    .notification {
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        padding: 10px;
        background-color: #27ae60; /* Green color */
        color: #fff;
        border-radius: 5px;
        display: none;
    }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Welcome, <?php echo $_SESSION['volunteer_name']; ?>!</h1>
        <a href="logout_volunteer.php">Logout</a>
    </header>

    <!-- Main Content Section -->
    <main>
        <!-- Available Posts Section -->
        <section>
            <h2 class="toggle-heading" onclick="togglePosts('availablePosts')">
                Available Posts
                <span class="arrow">&#9660;</span>
            </h2>
            <ul id="availablePosts" class="hidden">
                <?php foreach ($posts as $post): ?>
                    <li>
                        <strong><?php echo $post['activity_name']; ?></strong>
                        <p><?php echo $post['description']; ?></p>
                        <p>Start Date: <?php echo $post['start_date']; ?></p>
                        <p>End Date: <?php echo $post['end_date']; ?></p>
                        <p>Location: <?php echo $post['location']; ?></p>
                        <p>Registered Volunteers: <?php echo $post['registered_volunteers']; ?></p>
                        <p>Organization: <?php echo $post['organization_name']; ?></p>
                        <form action="register_activity.php" method="POST">
                            <input type="hidden" name="activity_id" value="<?php echo $post['activity_id']; ?>">
                            <button type="submit" class="flip-button">&#8594; Register</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Registered Posts Section -->
        <section>
            <h2 class="toggle-heading" onclick="togglePosts('registeredPosts')">
                Registered Posts
                <span class="arrow">&#9660;</span>
            </h2>
            <ul id="registeredPosts" class="hidden">
                <?php
              $volunteerId = $_SESSION['volunteer_id'];
              $query = "SELECT a.activity_id, a.activity_name, a.description, a.start_date, a.end_date, a.location, o.organization_name
                      FROM activities a
                      JOIN registrations r ON a.activity_id = r.activity_id
                      JOIN organizations o ON a.organization_id = o.organization_id
                      WHERE r.volunteer_id = $volunteerId";
              $result = mysqli_query($conn, $query);
              
              if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      echo '<li>';
                      echo '<strong>' . $row['activity_name'] . '</strong>';
                      echo '<p>' . $row['description'] . '</p>';
                      echo '<p>Start Date: ' . $row['start_date'] . '</p>';
                      echo '<p>End Date: ' . $row['end_date'] . '</p>';
                      echo '<p>Location: ' . $row['location'] . '</p>';
                      echo '<p>Organization: ' . $row['organization_name'] . '</p>';
                      echo '<form action="cancel_registration.php" method="POST">';
                      echo '<input type="hidden" name="activity_id" value="' . $row['activity_id'] . '">';
                      echo '<button type="submit" class="flip-button">Cancel Registration</button>';
                      echo '</form>';
                      echo '</li>';
                  }
              } else {
                  echo '<li>No registered posts yet.</li>';
              }
              
                ?>
            </ul>
        </section>
    </main>
    
    <?php
    // Check for successful registration
    $registerSuccess = isset($_GET['register_success']) ? $_GET['register_success'] : '0';
    if ($registerSuccess) {
        echo '<div class="notification" id="registerNotification">Successfully registered for the event!</div>';
    }

    // Check for successful cancellation
    $cancelSuccess = isset($_GET['cancel_success']) ? $_GET['cancel_success'] : '0';
    if ($cancelSuccess) {
        echo '<div class="notification" id="cancelNotification">Successfully cancelled  registration!</div>';
    }
?>

    <script>
        function togglePosts(sectionId) {
            var section = document.getElementById(sectionId);
            section.classList.toggle('hidden');
            var arrow = section.previousElementSibling.querySelector('.arrow');
            arrow.style.transform = section.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(90deg)';
        }
    const registerNotification = document.getElementById('registerNotification');
    const cancelNotification = document.getElementById('cancelNotification');

    if (registerNotification) {
        registerNotification.style.display = 'block';
        setTimeout(() => {
            registerNotification.style.display = 'none';
        }, 10000); // Hide after 5 seconds
    }

    if (cancelNotification) {
        cancelNotification.style.display = 'block';
        setTimeout(() => {
            cancelNotification.style.display = 'none';
        }, 10000); // Hide after 5 seconds
    }
    </script>
</body>
</html>
