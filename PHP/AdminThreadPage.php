<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    include 'login_status.php'; // Include login_status.php for session and user validation
    // Retrieve threads and their related users from the database
    $sql = "SELECT threads.thread_id, threads.title, threads.creation_date, users.username FROM threads JOIN users ON threads.user_id = users.user_id;";
    $result = $db->query($sql);
    $threads = [];
    while ($row = $result->fetch_assoc()) {
        $threads[] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Threads - Admin Dashboard - MessiIsTheGoat</title>
    <link rel="stylesheet" href="../CSS/stylesadmin2.css">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="logo-container">
                <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQHnhWFBFpqpDEQE_DyEaYEXHwa8QY4mAsBTeZaif0XvmL1sXI2" alt="MessiIsTheGoat Logo" class="logo-image">
                <div class="logo-title">MessiIsTheGoat</div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="Index.php">Home</a></li>
                <li><a href="adminPage.php">Admin Portal</a></li>
                <li><a href="AdminUser.php">Users</a></li>
                <li class="active"><a href="AdminThreadPage.php">Threads</a></li>
            </ul>
        </aside>
        <main class="admin-main">
            <div class="threads-container">
                <h1>All Threads</h1>
                <table class="threads-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Creation Date</th>
                            <th>Owner</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($threads as $thread): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($thread['thread_id']); ?></td>
                                <td><?php echo htmlspecialchars($thread['title']); ?></td>
                                <td><?php echo htmlspecialchars($thread['creation_date']); ?></td>
                                <td><?php echo htmlspecialchars($thread['username']); ?></td>
                                <td>
                                    <!-- Placeholder buttons for actions -->
                                    <button class="action-btn delete-btn">Delete</button>
                                    <button class="action-btn hide-btn">Hide</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
