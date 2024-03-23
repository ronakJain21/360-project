<?php

include 'config.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['new_status'])) {
    $userId = intval($_POST['user_id']);
    $newStatus = $_POST['new_status'] === 'active' ? 'active' : 'blocked'; // Ensuring the status is either 'active' or 'blocked'

    // Prepare the update statement
    $stmt = $db->prepare("UPDATE users SET status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $newStatus, $userId);

    // Execute the update and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $db->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

$db->close();

?>
