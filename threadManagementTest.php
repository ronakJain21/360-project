<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'login_status.php'; // Include login_status.php for session and user validation

/*
    threadManagementTest should:
        - test thread creation
        - test thread listing
        - test thread viewing
        - thread searching
*/

// Test Thread Creation
    $title = "Test1";
    $body = "This is a test";
    $categoryName = "WeLoveMESSI";
    $userId = 1;

    // Validate inputs
    if (empty($title) || empty($body)) {
        $errorMessage = "Please fill out all required fields.";
    } else {
        // Getting the category_id based on the category name selected
        $category_id = NULL;
        if (in_array($categoryName, $categoryList)) {
            $stmt1 = $db->prepare("SELECT category_id FROM categories WHERE name = ?");
            $stmt1->bind_param("s", $categoryName);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            if ($row1 = $result1->fetch_assoc()) {
                $category_id = $row1['category_id'];
            }
            $stmt1->close();
        }
        // No errors, proceed to insert into database
        if (empty($errorMessage)) {
            $stmt1 = $db->prepare("INSERT INTO threads (user_id, title, body, category_id) VALUES (?, ?, ?, ?)");
            $stmt1->bind_param("isss", $userId, $title, $body, $category_id);

            if ($stmt1->execute()) {
                header('Location: index.php');
                exit();
            } else {
                $errorMessage = "Error: " . $db->error;
            }
            $stmt1->close();
        }
    }

// Test Thread Listing
$sql2 = "SELECT threads.thread_id, threads.title, threads.creation_date, threads.is_hidden, users.username 
        FROM threads
        JOIN users ON threads.user_id = users.user_id";
$stmt2 = $db->prepare($sql2);
$stmt2->execute();
$result2 = $stmt2->get_result();
$threads2 = [];
while ($row2 = $result2->fetch_assoc()) {
    $threads2[] = $row2;
}
foreach ($threads2 as $thread) {
    echo "Thread ID: " . $thread['thread_id'] . "<br>";
    echo "Title: " . $thread['title'] . "<br>";
    echo "Creation Date: " . $thread['creation_date'] . "<br>";
    echo "Is Hidden: " . $thread['is_hidden'] . "<br>";
    echo "Created By: " . $thread['username'] . "<br>";
    echo "<hr>";
}
// Test Thread Searching and Viewing
    $searchTerm = "Why Messi is the GOAT";

    $sql3 = "SELECT threads.thread_id, threads.title, threads.creation_date, threads.is_hidden, users.username FROM threads JOIN users ON threads.user_id = users.user_id WHERE threads.title LIKE ?;";
    $stmt3 = $db->prepare($sql);
    $likeSearchTerm = '%' . $searchTerm . '%';
    $stmt3->bind_param("s", $likeSearchTerm);
    $stmt3->execute();
    $result3 = $stmt3->get_result();

    $threads3 = [];
    while ($row3 = $result3->fetch_assoc()) {
        $threads3[] = $row3;
    }
?>