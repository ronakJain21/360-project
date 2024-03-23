<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php'; // Correct path to your database configuration file

// Initialize variables
$userLoggedIn = false;
$isAdmin = false;
$userDetails = [];
$username = ''; // Initialize an empty username

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userLoggedIn = true;
    $userId = $_SESSION['user_id'];

    // Fetch user details from the database
    $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $userDetails = $user; // Store user details for personalization
            $username = $user['username']; // Store username for use in the session
        }
        $stmt->close();
    }

    // Check if the logged-in user is an admin
    $adminStmt = $db->prepare("SELECT admin_id FROM admins WHERE user_id = ?");
    if ($adminStmt) {
        $adminStmt->bind_param("i", $userId);
        $adminStmt->execute();
        $adminResult = $adminStmt->get_result();
        $isAdmin = $adminResult->num_rows > 0; // User is an admin if any rows are returned
        $adminStmt->close();
    }
}
?>
