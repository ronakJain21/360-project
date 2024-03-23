<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'config.php'; // Database connection

if (isset($_POST['post_id']) && isset($_SESSION['user_id'])) {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];

    // Check if the user is an admin
    $adminCheckStmt = $db->prepare("SELECT admin_id FROM admins WHERE user_id = ?");
    $adminCheckStmt->bind_param("i", $userId);
    $adminCheckStmt->execute();
    if ($adminCheckStmt->get_result()->num_rows > 0) {
        $stmt = $db->prepare("DELETE FROM posts WHERE post_id = ?");
        $stmt->bind_param("i", $postId);
        if ($stmt->execute()) {
            echo 'Post deleted.';
        } else {
            echo 'Error deleting post.';
        }
    } else {
        echo 'Not authorized.';
    }
} else {
    echo 'Invalid request.';
}
?>
