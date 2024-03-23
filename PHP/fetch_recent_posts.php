<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php'; // Make sure this path correctly points to your database configuration file
// Query to get the three most recent posts
$recentPostsQuery = "SELECT * FROM posts
                     ORDER BY timestamp DESC
                     LIMIT 3";

$result = $db->query($recentPostsQuery);
$recentPosts = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentPosts[] = $row;
    }
}

// You can use $recentPosts in your HTML to display these posts
?>
