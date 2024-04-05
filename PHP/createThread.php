<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config.php';  // Make sure this path is correct

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

$errorMessage = '';
$categoryList = ['WeLoveMESSI', 'MessiGOATArgument', 'WhyMessitheGOAT'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $body = htmlspecialchars($_POST['body']);
    $categoryName = $_POST['category'];
    $userId = $_SESSION['user_id'];

    // Validate inputs
    if (empty($title) || empty($body)) {
        $errorMessage = "Please fill out all required fields.";
    } else {
        // Getting the category_id based on the category name selected
        $category_id = NULL;
        if (in_array($categoryName, $categoryList)) {
            $stmt = $db->prepare("SELECT category_id FROM categories WHERE name = ?");
            $stmt->bind_param("s", $categoryName);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $category_id = $row['category_id'];
            }
            $stmt->close();
        }

        // No errors, proceed to insert into database
        if (empty($errorMessage)) {
            $stmt = $db->prepare("INSERT INTO threads (user_id, title, body, category_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $userId, $title, $body, $category_id);

            if ($stmt->execute()) {
                header('Location: index.php');
                exit();
            } else {
                $errorMessage = "Error: " . $db->error;
            }
            $stmt->close();
        }
    }
    $db->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGoat - Recommend Thread</title>
    <link rel="stylesheet" href="../CSS/CreatePostStylesheet-phone.css" media ="screen and (max-width: 480px)">
    <link rel="stylesheet" href="../CSS/CreatPostStylesheet.css" media ="screen and (min-width: 769px)">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">MessiIsTheGoat</div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <!-- <a href="#">Categories</a>
                <a href="#">Login</a>
                <a href="#">Register</a> -->
            </div>
            <form action="logout.php" method="POST" style="display: inline;">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </nav>
    </header>
    <div class="container">
        <h1>Recommend a New Thread !!!</h1>
        <h2>Initiate a new conversation on your topic of interest with our online community</h2>
        <form action="createThread.php" id="thread-content" method="post">
            <div class="fields">
                <p>Add a unique title for your thread !!!</p>
                <span>
                    <input placeholder="Add a Title..." type="text" name="title" required />
                </span>
                <br>
                <p>Add a description for the thread</p>
                <span>
                    <textarea placeholder="Add description..." name="body" id="textbox" required></textarea>
                </span>
                <br>
                <p>Select Category (Optional):</p>
                <span>
                    <select name="category">
                        <option value="">No Category</option>
                        <option value="WeLoveMESSI">WeLoveMESSI</option>
                        <option value="MessiGOATArgument">MessiGOATArgument</option>
                        <option value="WhyMessitheGOAT">WhyMessitheGOAT</option>
                    </select>
                </span>
            </div>
            <div class="Thread">
                <input class="submit" value="NEW THREAD" type="submit" />
            </div>
        </form>
    </div>
</body>
</html>
