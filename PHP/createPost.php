<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit;
}

$errorMessage = '';
$categoryList = ['WeLoveMESSI', 'MessiGOATArgument', 'WhyMessitheGOAT'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $body = htmlspecialchars($_POST['body']);
    $userId = $_SESSION['user_id'];
    $threadId = !empty($_POST['thread']) ? intval($_POST['thread']) : null;
    $category = $_POST['category'];
    
    if (empty($title) || empty($body)) {
        $errorMessage = "Please fill out all required fields.";
    } else {
        $categoryId = null;
        if (!empty($category) && in_array($category, $categoryList)) {
            $categoryIdQuery = $db->prepare("SELECT category_id FROM categories WHERE name = ?");
            $categoryIdQuery->bind_param("s", $category);
            $categoryIdQuery->execute();
            $result = $categoryIdQuery->get_result();
            if ($categoryData = $result->fetch_assoc()) {
                $categoryId = $categoryData['category_id'];
            }
            $categoryIdQuery->close();
        }

        if (empty($errorMessage)) {
            $stmt = $db->prepare("INSERT INTO posts (user_id, thread_id, title, content, category_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iissi", $userId, $threadId, $title, $body, $categoryId);

            if ($stmt->execute()) {
                // Redirect to Index.php after successful post creation
                header('Location: Index.php');
                exit();
            } else {
                $errorMessage = "Error: " . $stmt->error;
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
    <title>MessiIsTheGoat - Create Post</title>
    <link rel="stylesheet" href="../CSS/CreatPostStylesheet.css">
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
        <h1>Create Post</h1>
        <h2>Join the discussion!!!</h2>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <form action="createPost.php" id="post-content" method="post">
            <div class="fields">
                <p>Add a unique title to your post!!!</p>
                <span>
                    <input placeholder="Add a Title..." type="text" name="title" required />
                </span>
                <br>
                <p>Add body text to your post</p>
                <span>
                    <textarea placeholder="Add body text..." name="body" id="textbox" required></textarea>
                </span>
                <br>
                <p>Select Thread (Optional):</p>
                <span>
                    <select name="thread">
                        <option value="">No Thread</option>
                        <?php
                        $threadQuery = $db->query("SELECT thread_id, title FROM threads");
                        while ($thread = $threadQuery->fetch_assoc()) {
                            echo "<option value='" . $thread['thread_id'] . "'>" . htmlspecialchars($thread['title']) . "</option>";
                        }
                        ?>
                    </select>
                </span>
                <br>
                <p>Select Category (Optional):</p>
                <span>
                    <select name="category">
                        <option value="">No Category</option>
                        <?php foreach ($categoryList as $categoryName): ?>
                            <option value="<?php echo htmlspecialchars($categoryName); ?>"><?php echo htmlspecialchars($categoryName); ?></option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <br>
            </div>
            <div class="Post">
                <input class="submit" value="Post" type="submit" />
            </div>
        </form>
    </div>
</body>
</html>
