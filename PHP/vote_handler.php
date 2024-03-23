<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'login_status.php'; // Check login status and admin status
include 'config.php'; // Database connection

$response = ['logged_in' => $userLoggedIn];

if ($userLoggedIn && isset($_POST['post_id'], $_POST['vote_type'])) {
    $postId = (int)$_POST['post_id'];
    $voteType = (int)$_POST['vote_type']; // 1 for upvote, -1 for downvote
    $userId = $_SESSION['user_id'];

    // Begin transaction for data consistency
    $db->begin_transaction();

    try {
        // First, manage the individual vote
        $existVote = $db->prepare("SELECT vote_type FROM votes WHERE post_id = ? AND user_id = ?");
        $existVote->bind_param("ii", $postId, $userId);
        $existVote->execute();
        $existing = $existVote->get_result()->fetch_assoc();

        if ($existing) {
            if ($existing['vote_type'] != $voteType) {
                // Update existing vote
                $updateVote = $db->prepare("UPDATE votes SET vote_type = ? WHERE post_id = ? AND user_id = ?");
                $updateVote->bind_param("iii", $voteType, $postId, $userId);
                $updateVote->execute();
            } else {
                // No change required, user is repeating their vote
                $response['message'] = "You've already cast this vote.";
            }
        } else {
            // Insert new vote
            $insertVote = $db->prepare("INSERT INTO votes (user_id, post_id, vote_type) VALUES (?, ?, ?)");
            $insertVote->bind_param("iii", $userId, $postId, $voteType);
            $insertVote->execute();
        }

        // Update the post's total vote count
        $updatePost = $db->prepare("UPDATE posts SET vote_count = vote_count + ? WHERE post_id = ?");
        // Note: if reversing a vote, you should subtract twice the voteType value since you're flipping the vote
        $adjustment = $existing ? ($existing['vote_type'] != $voteType ? 2 * $voteType : 0) : $voteType;
        $updatePost->bind_param("ii", $adjustment, $postId);
        $updatePost->execute();

        // Get the updated vote count to return to the client
        $newCount = $db->prepare("SELECT vote_count FROM posts WHERE post_id = ?");
        $newCount->bind_param("i", $postId);
        $newCount->execute();
        $response['new_count'] = $newCount->get_result()->fetch_assoc()['vote_count'];

        // Commit transaction and send response
        $db->commit();
        $response['message'] = "Vote successfully recorded.";
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();
        $response['message'] = "An error occurred: " . $e->getMessage();
    }
} else {
    $response['message'] = $userLoggedIn ? "Invalid request." : "You must be logged in to vote.";
}

echo json_encode($response);
?>