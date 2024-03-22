<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = htmlspecialchars($_POST['title']);
        $body = htmlspecialchars($_POST['body']);
        $image = htmlspecialchars($_POST['image']);

        // Validate inputs
        if(empty($title) || empty($body)){ // images are optional
            echo "<p> Please fill out all required fields. </p>";
        } else {
            // Process the data (e.g. save to database, publish to homepage, etc.)
            echo "<p>Thread successfully recommended</p>";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MessiIsTheGoat - Reccomend Thread</title>
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
            <h1>Recommend a New Thread !!!</h1>
            <h2>Initiate a new conversation on your topic of interest with our online community</h2>
            <form action="createThread.php" id="thread-content">
            <div class="fields">
            <p>Add a unique title for your thread !!!</p>
            <span>
                <input placeholder="Add a Title..." type="text" name="title"/>
            </span>
            <br>
            <p>Add a description for the thread</p>
            <span>
                <input placeholder="Add description..." type="textbox" id="textbox" name="body"/>
            </span>
            <br>
            <p>Upload Images (Optional)</p>
            <span>
                <input placeholder="Upload Image" type="file" accept="image/*" name="image"/>
            </span>
            </div>
            <div class="Thread">
                <input class="submit" value="NEW THREAD" type="submit" />
            </div>
            </form>
        </div>
        
    </body>
</html>