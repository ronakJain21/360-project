<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'login_status.php'; // Check login status
include 'config.php'; // Database connection
if (!$userLoggedIn) {
    echo "You must be logged in to comment.";
    exit;
}

if (isset($_POST['post_id'], $_POST['comment'])) {
    $postId = (int)$_POST['post_id'];
    $comment = htmlspecialchars($_POST['comment']); // Always sanitize user input!
    $userId = $_SESSION['user_id'];

    // Insert new comment
    $stmt = $db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $postId, $userId, $comment);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Comment added.";
    } else {
        echo "An error occurred.";
    }
} else {
    echo "Invalid request.";
}
?>
