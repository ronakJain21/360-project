<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'login_status.php'; // This will set $userLoggedIn and $isAdmin
include 'config.php'; // Database connection setup
// Fetch posts along with their total vote count, ordered by vote count (descending)
$postsQuery = "SELECT posts.*,vote_count, users.username
               FROM posts 
               LEFT JOIN votes ON posts.post_id = votes.post_id
               LEFT JOIN users ON posts.user_id = users.user_id
               GROUP BY posts.post_id
               ORDER BY vote_count DESC, posts.timestamp DESC";
$postsResult = $db->query($postsQuery);
$posts = [];

if ($postsResult) {
    while ($post = $postsResult->fetch_assoc()) {
        $post['comments'] = [];
        
        // Fetch comments for each post
        $commentsQuery = "SELECT comments.*, users.username 
                          FROM comments 
                          JOIN users ON comments.user_id = users.user_id
                          WHERE post_id = ?
                          ORDER BY timestamp ASC";
        $stmt = $db->prepare($commentsQuery);
        $stmt->bind_param("i", $post['post_id']);
        $stmt->execute();
        $commentsResult = $stmt->get_result();
        
        while ($comment = $commentsResult->fetch_assoc()) {
            $post['comments'][] = $comment;
        }

        // Append this post (with its comments if any) to the posts array
        $posts[] = $post;
    }
}
?>
