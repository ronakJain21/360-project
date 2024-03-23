<?php
// Start the session
session_start();

// Include the database configuration file
include 'config.php';

// Ensure that the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header('Location: Login.php');
    exit();
}

$username = $_SESSION['username']; // Username from the session

// Prepare a statement to select the image from the database
$stmt = $db->prepare("SELECT profile_pic_blob, profile_pic_type FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the image and its type
    $row = $result->fetch_assoc();
    $imageData = $row['profile_pic_blob'];
    $imageType = $row['profile_pic_type'];

    // If the image type is not stored in the database, you might want to have a default
    if (empty($imageType)) {
        $imageType = 'image/jpeg'; // Or whatever default you'd prefer
    }

    // Send the correct content type header and output the image data
    header("Content-Type: $imageType");
    echo $imageData;
} else {
    // Handle the error scenario where no image is found, perhaps send a placeholder image
    header("Content-Type: image/png");
    readfile('path/to/placeholder.png');
}

// Close the statement
$stmt->close();
