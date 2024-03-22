<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $body = htmlspecialchars($_POST['body']);
    $userId = $_SESSION['user_id']; // Extract user_id from session

    // Initialize imagePath to null since image is optional
    $imagePath = null;

    // Validate inputs
    if (empty($title) || empty($body)) {
        $errorMessage = "Please fill out all required fields.";
    } else {
        // Handle file upload if it exists
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Your existing file upload handling logic here
            // If successful, set $imagePath to the new file path
        }

        // Proceed with the database insertion if there are no errors
        if (empty($errorMessage)) {
            $stmt = $db->prepare("INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $userId, $title, $body, $imagePath);
            
            // Try to execute the statement. If there's a problem, store an error message
            if (!$stmt->execute()) {
                $errorMessage = "Error: " . $stmt->error;
            } else {
                // Optional: Redirect to a success page or set a success message
                $errorMessage = "Post successfully created";
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
