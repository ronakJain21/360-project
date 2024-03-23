<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start(); // Ensure session start is at the beginning.
include 'config.php'; // Database connection.

if (!isset($_SESSION['user_id'])) {
    echo 'You must be logged in to delete comments.';
    exit;
}

if (isset($_POST['comment_id'])) {
    $commentId = $_POST['comment_id'];
    $userId = $_SESSION['user_id'];

    // Check if the user is an admin.
    $adminCheckStmt = $db->prepare("SELECT admin_id FROM admins WHERE user_id = ?");
    $adminCheckStmt->bind_param("i", $userId);
    $adminCheckStmt->execute();
    if ($adminCheckStmt->get_result()->num_rows > 0) {
        $stmt = $db->prepare("DELETE FROM comments WHERE comment_id = ?");
        $stmt->bind_param("i", $commentId);
        if ($stmt->execute()) {
            echo 'Comment deleted.'; // Same as add_comment.php
        } else {
            echo 'Error deleting comment.';
        }
    } else {
        echo 'Not authorized.';
    }
} else {
    echo 'Invalid request.';
}
?>
