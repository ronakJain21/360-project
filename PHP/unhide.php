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
        // Check if the thread exists and is hidden
        $checkThread = $db->prepare("SELECT is_hidden FROM threads WHERE thread_id = ?");
        $checkThread->bind_param("i", $threadId);
        $checkThread->execute();
        $checkThread->store_result();

        if ($checkThread->num_rows === 1) {
            $checkThread->bind_result($isHidden);
            $checkThread->fetch();
            
            if ($isHidden == 1) {
                // Unhide the thread
                $updateThread = $db->prepare("UPDATE threads SET is_hidden = 0 WHERE thread_id = ?");
                $updateThread->bind_param("i", $threadId);
                $updateThread->execute();

                // Unhide all posts associated with the thread
                $db->query("UPDATE posts SET is_hidden = 0 WHERE thread_id = $threadId");

                // Unhide all comments associated with the posts of the thread
                $db->query("UPDATE comments JOIN posts ON comments.post_id = posts.post_id SET comments.is_hidden = 0 WHERE posts.thread_id = $threadId");

                $response['success'] = true;
                $response['message'] = 'Thread and associated content unhidden.';
                $response['is_hidden'] = 0;
            } else {
                $response['message'] = 'Thread is not hidden.';
            }
        } else {
            $response['message'] = 'Thread not found.';
        }

        $db->commit();  // Commit the transaction if all updates were successful
    } catch (Exception $e) {
        $db->rollback();  // Rollback on any error
        $response['message'] = 'Error unhiding the thread and associated content.';
    }

    echo json_encode($response);
} else {
    $response['message'] = 'No thread specified.';
    echo json_encode($response);
}
?>
