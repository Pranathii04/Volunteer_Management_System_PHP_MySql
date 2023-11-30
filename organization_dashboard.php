<?php
// Assuming you have a database connection
include 'config.php';
session_start();

// Retrieve organization ID from the session
$organizationId = $_SESSION['organization_id'];

// Fetch past activities for the organization
$query  = "SELECT activity_name FROM activities WHERE organization_id = $organizationId";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $pastActivities = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle the error, e.g., log it or show an error message
    die('Error fetching past activities: ' . mysqli_error($conn));
}

// Fetch past activities for the organization with organization details
$query = "SELECT a.activity_id, a.organization_id, a.activity_name, a.description, a.start_date, a.end_date, a.location, o.organization_name
          FROM activities a
          JOIN organizations o ON a.organization_id = o.organization_id
          WHERE a.organization_id = $organizationId";


$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $pastActivities = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle the error, e.g., log it or show an error message
    die('Error fetching past activities: ' . mysqli_error($conn));
}
?>

<?php
// Fetch total volunteers for all activities in the organization
$totalVolunteersQuery = "SELECT COUNT(DISTINCT r.volunteer_id) AS total_volunteers
                        FROM registrations r
                        INNER JOIN activities a ON r.activity_id = a.activity_id
                        WHERE a.organization_id = $organizationId";

$totalVolunteersResult = mysqli_query($conn, $totalVolunteersQuery);

if ($totalVolunteersResult) {
    $totalVolunteersRow = mysqli_fetch_assoc($totalVolunteersResult);
    $totalVolunteers = $totalVolunteersRow['total_volunteers'];
} else {
    // Handle the error, e.g., log it or show a default message
    $totalVolunteers = 0;
}
?>


<?php
// Fetch organization details for the given ID
$query  = "SELECT * FROM organizations WHERE organization_id = $organizationId";
$result1 = mysqli_query($conn, $query);

if ($result1) {
    $organizationDetails = mysqli_fetch_assoc($result1);
} else {
    // Handle the error, e.g., log it or show an error message
    die('Error fetching organization details: ' . mysqli_error($conn));
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $organizationName = mysqli_real_escape_string($conn, $_POST['organization_name']);
    $contactPerson = mysqli_real_escape_string($conn, $_POST['contact_person']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Update organization details in the database
    $updateQuery = "UPDATE organizations SET 
                    organization_name = '$organizationName',
                    contact_person = '$contactPerson',
                    email = '$email',
                    username = '$username'
                    WHERE organization_id = $organizationId";

    $updateResult = mysqli_query($conn, $updateQuery);

    // if ($updateResult) {
    //     echo "Profile updated successfully!";
    // } else {
    //     // Handle the error, e.g., log it or show an error message
    //     echo 'Error updating profile: ' . mysqli_error($conn);
    // }
}
?>
<!-- Add this PHP code after fetching organization details -->
<?php
$latestActivityQuery = "SELECT a.activity_name, a.description, a.start_date, a.end_date, a.location
                        FROM activities a
                        WHERE a.organization_id = $organizationId
                        ORDER BY a.start_date DESC
                        LIMIT 1";
$latestActivityResult = mysqli_query($conn, $latestActivityQuery);

if ($latestActivityResult) {
    $latestActivityDetails = mysqli_fetch_assoc($latestActivityResult);
} else {
    // Handle the error, e.g., log it or show a default message
    $latestActivityDetails = array(
        'activity_name' => 'No recent activities',
        'description' => '',
        'start_date' => '',
        'end_date' => '',
        'location' => ''
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Dashboard</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

.header {
    background-color: #007BFF;
    color: #fff;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.actions button {
    margin-left: 10px;
    background-color: #fff;
    color: #007BFF;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.posts {
    padding: 20px;
}

.post {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
}

.post img {
    max-width: 100%;
    height: auto;
}

/* Modal styling */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.total-volunteers {
            margin-top: 20px;
            font-size: 18px;
        }
.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    position: relative;
}

.close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    cursor: pointer;
}
.actions a {
            text-decoration: none;
            margin-right: 1px;
            color:#fff;
        }
        .modal-content {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 300px;
        text-align: center;
        margin: auto;
        margin-top: 50px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: bold;
    }

    input, textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-sizing: border-box;
    }

    button {
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 3px;
        padding: 7px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        color: #333;
        cursor: pointer;
    }

    h2 {
        color: #007BFF;
    }
    /* Modal styling */
.modal-content {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 300px;
    text-align: center;
    margin: auto;
    margin-top: 50px;
}

/* Close button styling */
.close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    color: #333;
    cursor: pointer;
}
#editActivity {
    margin-bottom: 10px;
}

/* Add some space between rows */
label,
textarea,
input,
select {
    margin-bottom: 15px;
}
/* Form group styling */
.form-group {
    margin-bottom: 15px;
}

/* Label styling */
label {
    display: block;
    margin-bottom: 5px;
    color: #333;
    font-weight: bold;
}

/* Input and textarea styling */
input, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}

/* Button styling */
button {
    background-color: #007BFF;
    color: #fff;
    border: none;
    border-radius: 3px;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
}

</style>
</head>
<body>
<div id="updateSuccessMessage" style="display: none;">
    Profile updated successfully!
</div>
    <div class="header">
            <?php
            // Fetch organization username
            $queryUsername = "SELECT username FROM organizations WHERE organization_id = $organizationId";
            $resultUsername = mysqli_query($conn, $queryUsername);

            if ($resultUsername) {
                $username = mysqli_fetch_assoc($resultUsername)['username'];
            } else {
                // Handle the error, e.g., log it or show a default username
                $username = "Organization";
            }

            // Display the welcome message with the organization username
            echo "<h1>Welcome, $username</h1>";
            
            ?>

        <div class="actions">
            <button onclick="showCreatePostModal()">Create Post</button>
            <button onclick="showEditPostModal()">Edit Past Posts</button>
            <button onclick="showDeletePostModal()">Delete Posts</button>
            <button onclick="showUpdateProfileModal()">Update Profile</button>
            <a href="logout_organization.php">Logout</a>
        </div>
    </div>
    <div class="actions">
        
    </div>
    <div class="posts">
    <div class="total-volunteers">
            <p>Total Volunteers: <?php echo $totalVolunteers; ?></p>
        </div>
    <?php
$query = "SELECT activity_id, organization_id, organization_name, activity_name, description, start_date, end_date, location FROM activities WHERE organization_id = $organizationId";
$result = mysqli_query($conn, $query);

if ($result) {
    $pastActivities = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle the error, e.g., log it or show an error message
    die('Error fetching past activities: ' . mysqli_error($conn));
}
?>

    <!-- Add this PHP code before the foreach loop -->
<!-- <?php
foreach ($pastActivities as $activity) {
    $activityId = $activity['activity_id'];
    $volunteerCountQuery = "SELECT COUNT(*) AS volunteer_count FROM registrations WHERE activity_id = $activityId";
    $volunteerCountResult = mysqli_query($conn, $volunteerCountQuery);
    $volunteerCount = mysqli_fetch_assoc($volunteerCountResult)['volunteer_count'];
    $activity['volunteer_count'] = $volunteerCount;
}
?> -->
<?php
foreach ($pastActivities as &$activity) {
    $activityId = $activity['activity_id'];
    $volunteerCountQuery = "SELECT COUNT(*) AS volunteer_count FROM registrations WHERE activity_id = $activityId";
    $volunteerCountResult = mysqli_query($conn, $volunteerCountQuery);
    
    if ($volunteerCountResult) {
        $volunteerCount = mysqli_fetch_assoc($volunteerCountResult)['volunteer_count'];
        $activity['volunteer_count'] = $volunteerCount;
    } else {
        // Handle the error, e.g., log it or set a default count
        $activity['volunteer_count'] = 0;
    }
}

?>


<!-- <?php foreach ($pastActivities as $activity) : ?>
    <div class="post">
        <h2><?php echo $activity['activity_name']; ?></h2>
        <p><?php echo $activity['description']; ?></p>
        <p>Date: <?php echo $activity['start_date']; ?> to <?php echo $activity['end_date']; ?></p>
        <p>Location: <?php echo $activity['location']; ?></p>
        <p>Organization: <?php echo $activity['organization_name']; ?></p>
        <button onclick="showEditPostModal()">Edit Post</button>
    </div>
<?php endforeach; ?>
</div> -->

<?php foreach ($pastActivities as $activity) : ?>
    <div class="post">
        <h2><?php echo $activity['activity_name']; ?></h2>
        <p><?php echo $activity['description']; ?></p>
        <p>Date: <?php echo $activity['start_date']; ?> to <?php echo $activity['end_date']; ?></p>
        <p>Location: <?php echo $activity['location']; ?></p>
        <p>Organization: <?php echo $activity['organization_name']; ?></p>
        <p>Registered Volunteers: <?php echo $activity['volunteer_count']; ?></p>
        <button onclick="showEditPostModal()">Edit Post</button>
    </div>
<?php endforeach; ?>



    <!-- Create Post Modal -->
<div id="createPostModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCreatePostModal()">&times;</span>
        <h2>Create Post</h2>
        <form action="create_post.php" method="POST">
            <label for="activityName">Activity Name:</label>
            <input type="text" name="activity_name" required>
            
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            
            <label for="startDate">Start Date:</label>
            <input type="date" name="start_date" required>

            <label for="endDate">End Date:</label>
            <input type="date" name="end_date" required>

            <label for="location">Location:</label>
            <input type="text" name="location">

            <button type="submit">Create Post</button>
        </form>
    </div>
</div>
    <div id="updateProfileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateProfileModal()">&times;</span>
            <h2>Update Profile</h2>
            <form action="" method="POST">
                <label for="organizationName">Organization Name:</label>
                <input type="text" name="organization_name" value="<?php echo $organizationDetails['organization_name']; ?>" required>

                <label for="contactPerson">Contact Person:</label>
                <input type="text" name="contact_person" value="<?php echo $organizationDetails['contact_person']; ?>" required>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $organizationDetails['email']; ?>" required>

                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $organizationDetails['username']; ?>" required>

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editPostModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditPostModal()">&times;</span>
            <h2>Edit Post</h2>
            
            <!-- Form to edit a post -->
            <form action="edit_post.php" method="POST">
                <label for="editActivity">Select Activity to Edit:</label>
                <select id="editActivity" name="edit_activity">
                    <?php
                    // Loop through past activities and create options
                    foreach ($pastActivities as $activity) {
                        echo '<option value="' . $activity['activity_name'] . '">' . $activity['activity_name'] . '</option>';
                    }
                    ?>
                </select>

                <!-- Other fields for editing -->
                <label for="editDescription">Edit Description:</label>
                <textarea id="editDescription" name="edit_description" rows="4" cols="50"></textarea>

                <label for="editStartDate">Edit Start Date:</label>
                <input type="date" id="editStartDate" name="edit_start_date">

                <label for="editEndDate">Edit End Date:</label>
                <input type="date" id="editEndDate" name="edit_end_date">

                <label for="editLocation">Edit Location:</label>
                <input type="text" id="editLocation" name="edit_location">

                <!-- Submit button to edit the selected post -->
                <button type="submit">Edit Post</button>
            </form>
        </div>
    </div>
    <!-- Delete Post Modal -->
    <div id="deletePostModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeletePostModal()">&times;</span>
            <h2>Delete Post</h2>

            <!-- Form to confirm post deletion -->
            <form action="delete_post.php" method="POST">
                <div class="form-group">
                <label for="deleteActivity">Select Activity to Delete:</label>
                <select id="deleteActivity" name="delete_activity">
                    <?php
                    // Loop through past activities and create options
                    foreach ($pastActivities as $activity) {
                        echo '<option value="' . $activity['activity_name'] . '">' . $activity['activity_name'] . '</option>';
                    }
                    ?>
                </select>
                </div>
                <div class="form-group">
                <!-- Submit button to confirm deletion -->
                <button type="submit">Delete Post</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function showDeletePostModal() {
        var deletePostModal = document.getElementById('deletePostModal');
        deletePostModal.style.display = 'block';
            }

    // Add a similar function to close the modal
        function closeDeletePostModal() {
        var deletePostModal = document.getElementById('deletePostModal');
        deletePostModal.style.display = 'none';
            }
        
        function showCreatePostModal() {
            document.getElementById('createPostModal').style.display = 'block';
            }

        function closeCreatePostModal() {
            document.getElementById('createPostModal').style.display = 'none';
            }

        function showEditPostModal() {
            document.getElementById('editPostModal').style.display = 'block';
        }

        function closeEditPostModal() {
            document.getElementById('editPostModal').style.display = 'none';
        }
        function showUpdateProfileModal() {
        document.getElementById('updateProfileModal').style.display = 'block';
        }

        function closeUpdateProfileModal() {
            document.getElementById('updateProfileModal').style.display = 'none';
        }
            // Check for update success message and display pop-up
    const updateSuccess = <?php echo isset($_GET['update_success']) ? $_GET['update_success'] : '0'; ?>;
    
    if (updateSuccess) {
        document.getElementById('updateSuccessMessage').style.display = 'block';

        // Hide the message after 3 seconds
        setTimeout(function() {
            document.getElementById('updateSuccessMessage').style.display = 'none';
        }, 3000);
    }
    </script>
</body>
</html> 
