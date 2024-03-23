<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    include 'login_status.php'; // Handles user login status and checks for admin

    // Retrieve number of registered users
    $userQuery = "SELECT * FROM `users`;";
    $userResult = $db->query($userQuery);
    $users = $userResult->num_rows;
    // Retrieve number of Threads
    $threadsQuery = "SELECT * FROM `threads`;";
    $threadsResult = $db->query($threadsQuery);
    $threads = $threadsResult->num_rows;
    // Retrieve number of Posts
    $postsQuery = "SELECT * FROM `posts`;";
    $postsResult = $db->query($postsQuery);
    $posts = $postsResult->num_rows;
    // Retrieve number of registered users
    $commentsQuery = "SELECT * FROM `comments`;";
    $commentsResult = $db->query($commentsQuery);
    $comments = $commentsResult->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MessiIsTheGoat</title>
    <link rel="stylesheet" href="../CSS/stylesadmin1.css">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <!-- Sidebar navigation -->
            <h2 class="sidebar-title">MENU</h2>
            <ul class="sidebar-menu">
                <li><a href="Index.php">Home</a></li>
                <li class="active"><a href="#">Admin Portal</a></li>
                <li><a href="AdminUser.php">Users</a></li>
                <li><a href="#">Threads</a></li>
            </ul>
        </aside>
        <main class="admin-main">
            <div class="admin-content">
                <div class="admin-stat-cards">
                    <!-- Stat cards -->
                    <div class="stat-card blue-card">
                        <h3>Registered Users</h3>
                        <?php echo "<p> ".$users."</p>" ?>
                    </div>
                    <div class="stat-card red-card">
                        <h3>Threads</h3>
                        <?php echo "<p> ".$threads."</p>" ?>
                    </div>
                    <div class="stat-card green-card">
                        <h3>Posts</h3>
                        <?php echo "<p> ".$posts."</p>" ?>
                    </div>
                    <div class="stat-card purple-card">
                        <h3>Comments</h3>
                        <?php echo "<p> ".$comments."</p>" ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
