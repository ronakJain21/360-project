<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'login_status.php'; // Include login_status.php for session and user validation

// Retrieve threads and their related users from the database
// $sql = "SELECT threads.thread_id, threads.title, threads.creation_date, threads.is_hidden, users.username FROM threads JOIN users ON threads.user_id = users.user_id;";
// $result = $db->query($sql);

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT threads.thread_id, threads.title, threads.creation_date, threads.is_hidden, users.username FROM threads JOIN users ON threads.user_id = users.user_id WHERE threads.title LIKE ?;";
$stmt = $db->prepare($sql);
$likeSearchTerm = '%' . $searchTerm . '%';
$stmt->bind_param("s", $likeSearchTerm);
$stmt->execute();
$result = $stmt->get_result();

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
    <link rel="stylesheet" href="../CSS/AdminThreadPage-phone.css" media ="screen and (max-width: 480px)">
    <link rel="stylesheet" href="../CSS/AdminThreadPage.css" media ="screen and (min-width: 769px)">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="admin-container">
    <aside class="admin-sidebar">
        <div class="logo-container">
            <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQHnhWFBFpqpDEQE_DyEaYEXHwa8QY4mAsBTeZaif0XvmL1sXI2" alt="MessiIsTheGoat Logo" class="logo-image">
            <div class="logo-title">MessiIsTheGoat</div>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="adminPage.php">Admin Portal</a></li>
            <li><a href="AdminUser.php">Users</a></li>
            <li class="active"><a href="AdminThreadPage.php">Threads</a></li>
        </ul>
    </aside>
    <main class="admin-main">
        <div class="threads-container">
            <h1>All Threads</h1>
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search by thread title" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <input type="submit" value="Filter">
            </form>
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
                    <tr id="thread-<?php echo $thread['thread_id']; ?>">
                        <td><?php echo htmlspecialchars($thread['thread_id']); ?></td>
                        <td><?php echo htmlspecialchars($thread['title']); ?></td>
                        <td><?php echo htmlspecialchars($thread['creation_date']); ?></td>
                        <td><?php echo htmlspecialchars($thread['username']); ?></td>
                        <td>
                        <button class="action-btn <?php echo $thread['is_hidden'] ? 'unhide-btn' : 'hide-btn'; ?>" onclick="toggleVisibility(<?php echo $thread['thread_id']; ?>, <?php echo $thread['is_hidden'] ? 'true' : 'false'; ?>, this)">
                            <?php echo $thread['is_hidden'] ? 'Unhide' : 'Hide'; ?>
                        </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
function toggleVisibility(threadId, isHidden, element) {
    var phpFile = isHidden ? 'unhide.php' : 'hide.php';
    $.ajax({
        type: 'POST',
        url: phpFile,
        data: { thread_id: threadId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $(element).text(response.is_hidden ? 'Unhide' : 'Hide').toggleClass('unhide-btn hide-btn');
                window.location.reload();
            } else {
                console.log(response.message);
            }
        },
        error: function() {
            console.log('AJAX request failed.');
        }
    });
}
</script>
</body>
</html>
