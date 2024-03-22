<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Include database configuration
include 'config.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header('Location: Login.php');
    exit;
}

$errorMessage = '';
$categoryList = ['WeLoveMESSI', 'MessiGOATArgument', 'WhyMessitheGOAT']; // List of categories

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $body = htmlspecialchars($_POST['body']);
    $userId = $_SESSION['user_id']; // Assuming the user's ID is stored in the session
    $threadId = !empty($_POST['thread']) ? intval($_POST['thread']) : null; // Get thread id from form and ensure it's an integer or null
    $category = $_POST['category']; // Retrieve selected category
    $imagePath = null; // Default to no image

    // Validate inputs
    if (empty($title) || empty($body)) {
        $errorMessage = "Please fill out all required fields.";
    } else {
        // Handle file upload if it exists
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];

            $fileNameCmps = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $destFilePath = 'uploaded_images/' . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destFilePath)) {
                    $imagePath = $destFilePath;
                } else {
                    $errorMessage = 'Error uploading file.';
                }
            } else {
                $errorMessage = 'Invalid file type or size.';
            }
        }

        // Convert category name to category_id, NULL if 'No Category' is selected
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
            // Insert the post into the database, include category selection
            $stmt = $db->prepare("INSERT INTO posts (user_id, thread_id, title, content, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssi", $userId, $threadId, $title, $body, $imagePath, $categoryId);

            if ($stmt->execute()) {
                $errorMessage = "Post successfully created";
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
                    <a href="#">Home</a>
                    <a href="#">Categories</a>
                    <a href="#">Login</a>
                    <a href="#">Register</a>
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
            <form action="createPost.php" id="post-content" method="post" enctype="multipart/form-data">
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
                    <p>Upload Images (Optional)</p>
                    <span>
                        <input placeholder="Upload Image" type="file" accept="image/*" name="image" />
                    </span>
                </div>
                <div class="Post">
                    <input class="submit" value="Post" type="submit" />
                </div>
            </form>
        </div>
    </body>
</html>
