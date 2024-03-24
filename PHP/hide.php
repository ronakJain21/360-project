<?php
session_start();
include 'login_status.php'; // Include to check login status
include 'config.php'; // Database connection setup

$response = ['success' => false, 'message' => '', 'is_hidden' => 0];

if (!$isAdmin) {
    $response['message'] = 'Unauthorized access.';
    echo json_encode($response);
    exit;
}

// Check if the thread ID is provided
if (isset($_POST['thread_id'])) {
    $threadId = intval($_POST['thread_id']);

    // Start transaction
    $db->begin_transaction();

    try {
        // Hide all comments associated with the posts of the thread
        $db->query("UPDATE comments JOIN posts ON comments.post_id = posts.post_id SET comments.is_hidden = 1 WHERE posts.thread_id = $threadId");

        // Hide all posts associated with the thread
        $db->query("UPDATE posts SET is_hidden = 1 WHERE thread_id = $threadId");

        // Finally, hide the thread itself
        $updateThread = $db->prepare("UPDATE threads SET is_hidden = 1 WHERE thread_id = ?");
        $updateThread->bind_param("i", $threadId);
        $updateThread->execute();
        $isThreadUpdated = $updateThread->affected_rows > 0;
        $updateThread->close();

        if ($isThreadUpdated) {
            $db->commit();  // Commit the transaction if all updates were successful
            $response['success'] = true;
            $response['message'] = 'Thread and associated content hidden.';
            $response['is_hidden'] = 1;
        } else {
            $db->rollback();  // Rollback if the thread update failed
            $response['message'] = 'Thread not found.';
        }
    } catch (Exception $e) {
        $db->rollback();  // Rollback on any error
        $response['message'] = 'Error hiding the thread and associated content.';
    }

    echo json_encode($response);
} else {
    $response['message'] = 'No thread specified.';
    echo json_encode($response);
}
?>
