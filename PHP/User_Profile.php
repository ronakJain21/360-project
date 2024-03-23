<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    // Start the session
    session_start();

    include 'config.php'; // Ensure this path is correct

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        // Redirect to login page or handle accordingly
        header('Location: Login.php');
        exit;
    }

    $username = $_SESSION['username'];

    // Fetch user information from database
    $stmt = $db->prepare("SELECT username, profile_pic_blob FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // $userProfilePic = $user['profile_pic'] ?: '/path/to/default/profile_pic.png';
        $username = $user['username'];
    } else {
        // Handle case where user data is not found
        $userProfilePic = '/path/to/default/profile_pic.png';
        $username = '@defaultUser';
    }

    $stmt->close();

    // Fetch user's posts
    $postsStmt = $db->prepare("SELECT title, timestamp FROM posts WHERE user_id = ?");
    $postsStmt->bind_param("i", $userId);
    $postsStmt->execute();
    $posts = $postsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $postsStmt->close();

    // Fetch user's comments
    $commentsStmt = $db->prepare("SELECT content, timestamp FROM comments WHERE user_id = ?");
    $commentsStmt->bind_param("i", $userId);
    $commentsStmt->execute();
    $comments = $commentsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $commentsStmt->close();

    // Fetch user's threads
    $threadsStmt = $db->prepare("SELECT title, creation_date FROM threads WHERE user_id = ?");
    $threadsStmt->bind_param("i", $userId);
    $threadsStmt->execute();
    $threads = $threadsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $threadsStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGoat - User Profile</title>
    <link rel="stylesheet" href="../CSS/styles_user_profile.css">
    <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo-container">
                <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQHnhWFBFpqpDEQE_DyEaYEXHwa8QY4mAsBTeZaif0XvmL1sXI2" alt="MessiIsTheGoat Logo" class="logo-image">
                <div class="logo-title">MessiIsTheGoat</div>
            </div>
            <a href="Index.php" class="nav-link">Home</a>
            <!-- <a href="#" class="nav-link">Categories</a> -->
            <!-- <div class="search-container">
                <input type="text" placeholder="Search..." class="search-input">
                <button class="search-btn"><i class="fa fa-search"></i></button>
            </div> -->
            <a href="User_Profile.php" class="nav-link">Welcome, <?php echo htmlspecialchars($username); ?></a>
            <form action="logout.php" method="POST" style="display: inline;">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
            <!-- <a href="#" class="nav-link">Login</a>
            <a href="#" class="nav-button">Register</a> -->
        </nav>
    </header>

    <div class="container">
        <aside class="menubar">
            <section>
                <img src="user_image.php" alt="Profile Pic" class="profile_pic">
                <h2><?php echo htmlspecialchars($username); ?></h2>
                <button class="settings"><a href="User_Profile_Settings.php">Settings</a></button>
            </section>

            <section>
                <h3>Menu</h3>
                <ul>
                    <li><a href="Index.php"><img src="../Pictures/home_icon.webp" alt="Navigate to Home" class="menubar_icon">Home</a></li>
                    <li><a href="adminPage.php"><img src="../Pictures/admin_icon.webp" alt="Navigate to Admin" class="menubar_icon">Admin Portal</a></li>
                    <li><a href="#"><img src="../Pictures/threads_icon2.webp" alt="Navigate to Threads" class="menubar_icon">Threads</a></li>
                    <li><a href="User_Profile_Settings.php"><img src="../Pictures/settings_icon.png" alt="Navigate to Settings" class="menubar_icon">Settings</a></li>
                </ul>
            </section>

            <section>
                <h3>Categories</h3>
                <ul>
                    <li><a href="#">WeLoveMessi</a></li>
                    <li><a href="#">MESSIGOATArgument</a></li>
                    <li><a href="#">WhyMessitheGOAT</a></li>
                </ul>
            </section>
        </aside>

        <main>
            <section class="profile_activity">
                <h3>Profile Activity</h3>
                <hr>
                <?php if (empty($posts) && empty($comments) && empty($threads)): ?>
                    <p>No activities to display.</p>
                <?php else: ?>
                    <!-- Display posts -->
                    <?php foreach ($posts as $post): ?>
                        <article class="activities">
                            <p><strong>Posted:</strong> <?php echo htmlspecialchars($post['title']); ?></p>
                            <p><strong>Date:</strong> <?php echo $post['timestamp']; ?></p>
                        </article>
                    <?php endforeach; ?>

                    <!-- Display comments -->
                    <?php foreach ($comments as $comment): ?>
                        <article class="activities">
                            <p><strong>Commented:</strong> <?php echo htmlspecialchars($comment['content']); ?></p>
                            <p><strong>Date:</strong> <?php echo $comment['timestamp']; ?></p>
                        </article>
                    <?php endforeach; ?>

                    <!-- Display threads -->
                    <?php foreach ($threads as $thread): ?>
                        <article class="activities">
                            <p><strong>Thread Created:</strong> <?php echo htmlspecialchars($thread['title']); ?></p>
                            <p><strong>Date:</strong> <?php echo $thread['creation_date']; ?></p>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <footer>
        <p>&copy; 2024 MessiIsTheGoat</p>
    </footer>
</body>
</html>
