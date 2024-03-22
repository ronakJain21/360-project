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
    $stmt = $db->prepare("SELECT username, profile_pic FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userProfilePic = $user['profile_pic'] ?: '/path/to/default/profile_pic.png';
        $username = $user['username'];
    } else {
        // Handle case where user data is not found
        $userProfilePic = '/path/to/default/profile_pic.png';
        $username = '@defaultUser';
    }

    $stmt->close();
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
            <a href="#" class="nav-link">Home</a>
            <a href="#" class="nav-link">Categories</a>
            <div class="search-container">
                <input type="text" placeholder="Search..." class="search-input">
                <button class="search-btn"><i class="fa fa-search"></i></button>
            </div>
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
                <img src="<?php echo htmlspecialchars($userProfilePic); ?>" alt="Profile Pic" class="profile_pic">
                <h2><?php echo htmlspecialchars($username); ?></h2>
                <button class="settings"><a href="User_Profile_Settings.php">Settings</a></button>
            </section>

            <section>
                <h3>Menu</h3>
                <ul>
                    <li><a href="#"><img src="../Pictures/home_icon.webp" alt="Navigate to Home" class="menubar_icon">Home</a></li>
                    <li><a href="#"><img src="../Pictures/admin_icon.webp" alt="Navigate to Admin" class="menubar_icon">Admin Portal</a></li>
                    <li><a href="#"><img src="../Pictures/threads_icon2.webp" alt="Navigate to Threads" class="menubar_icon">Threads</a></li>
                </ul>
            </section>
        </aside>

        <main>
            <section class="profile_activity">
                <h3>Profile Activity</h3>
                <hr>
                <?php
                // Assume $userActivities is an array containing the user's activities
                $userActivities = []; // Example: array(array("type" => "comment", "content" => "New comment!"))

                if (empty($userActivities)) {
                    echo "<p>No activities to display.</p>";
                } else {
                    foreach ($userActivities as $activity) {
                        $activityType = $activity['type']; // 'comment' or 'post'
                        $activityContent = $activity['content'];
                        $activityIcon = $activityType == 'comment' ? "/Pictures/comment_icon.webp" : "/Pictures/post_icon.webp";
                        
                        echo "<article class=\"activities\">";
                        echo "<img src=\"$activityIcon\" alt=\"Activity\" class=\"activities_icon\">";
                        echo "<div>";
                        echo "<p class=\"comment\"><i>New " . ucfirst($activityType) . ":</i></p>";
                        echo "<p>$activityContent</p>";
                        echo "</div>";
                        echo "</article>";
                    }
                }
                ?>
            </section>
        </main>
    </div>

    <footer>
        <p>&copy; 2024 MessiIsTheGoat</p>
    </footer>
</body>
</html>
