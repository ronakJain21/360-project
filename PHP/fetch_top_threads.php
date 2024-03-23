<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php'; // Make sure this path correctly points to your database configuration file

// Query to get the top four threads based on the number of posts
$topThreadsQuery = "SELECT threads.*, COUNT(posts.post_id) AS post_count
                    FROM threads
                    LEFT JOIN posts ON threads.thread_id = posts.thread_id
                    GROUP BY threads.thread_id
                    ORDER BY post_count DESC, threads.creation_date DESC
                    LIMIT 4";

$result = $db->query($topThreadsQuery);
$topThreads = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $topThreads[] = $row;
    }
}
?>
