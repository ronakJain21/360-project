<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = htmlspecialchars($_POST['title']);
        $body = htmlspecialchars($_POST['body']);
        $image = htmlspecialchars($_POST['image']);

        // Validate inputs
        if(empty($title) || empty($body)){ // images are optional
            echo "<p> Please fill out all required fields. </p>";
        } else {
            // Process the data (e.g. save to database, publish to homepage, etc.)
            echo "<p>Post successfully created</p>";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MessiIsTheGoat - Create Post</title>
        <link rel="stylesheet" href="/CSS/CreatPostStylesheet.css">
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
            <form action="createPost.php" id="post-content" method="post">
                <div class="fields">
                    <p>Add a unique title to your post!!!</p>
                    <span>
                        <input placeholder="Add a Title..." type="text" name="title" />
                    </span>
                    <br>
                    <p>Add body text to your post</p>
                    <span>
                        <textarea placeholder="Add body text..." name="body" id="textbox"></textarea>
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