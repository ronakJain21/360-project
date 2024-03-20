<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGoat - User Profile</title>
    <link rel="stylesheet" href="/CSS/styles_user_profile.css">
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
            <a href="#" class="nav-link">Login</a>
            <a href="#" class="nav-button">Register</a>
        </nav>
    </header>

    <div class="container">
        <aside class="menubar">
            <section>
                <?php
                // Assume $userProfilePic and $username are obtained from the database or session
                $userProfilePic = '/path/to/default/profile_pic.png'; // Default profile picture path
                $username = '@defaultUser'; // Default username

                // Check if user has a profile picture set
                if ($userProfilePic) {
                    echo "<img src=\"$userProfilePic\" alt=\"Profile Pic\" class=\"profile_pic\">";
                }

                // Display the username
                echo "<h2>$username</h2>";
                ?>
                <button class="settings">Settings</button>
            </section>

            <section>
                <h3>Menu</h3>
                <ul>
                    <li><a href="#"><img src="/Pictures/home_icon.webp" alt="Navigate to Home" class="menubar_icon">Home</a></li>
                    <li><a href="#"><img src="/Pictures/admin_icon.webp" alt="Navigate to Admin" class="menubar_icon">Admin Portal</a></li>
                    <li><a href="#"><img src="/Pictures/threads_icon2.webp" alt="Navigate to Threads" class="menubar_icon">Threads</a></li>
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
