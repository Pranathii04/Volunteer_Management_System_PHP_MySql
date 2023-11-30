<?php

include 'config.php';

// Fetch posts from activities table
$query = "SELECT * FROM activities";
$result = mysqli_query($conn, $query);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

?>
