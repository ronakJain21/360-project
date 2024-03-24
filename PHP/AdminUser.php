<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'login_status.php'; // Include login status check file

if (!$isAdmin) { // Check if the user is an admin
    header('Location: Login.php'); // Redirect non-admins to the login page
    exit;
}

include 'config.php'; // Database connection file

// Assuming $_SESSION['user_id'] holds the ID of the currently logged in user
$adminUserId = $_SESSION['user_id'];
$query = "SELECT user_id, username, email, registration_date, status FROM users WHERE user_id != ? ORDER BY registration_date DESC";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $adminUserId); // Exclude the admin's user_id
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users - Admin Dashboard - MessiIsTheGoat</title>
    <link rel="stylesheet" href="../CSS/stylesadmin3.css">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <!-- Sidebar content -->
            <div class="logo-container">
                <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQHnhWFBFpqpDEQE_DyEaYEXHwa8QY4mAsBTeZaif0XvmL1sXI2" alt="MessiIsTheGoat Logo" class="logo-image">
                <div class="logo-title">MessiIsTheGoat</div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="Index.php">Home</a></li>
                <li><a href="adminPage.php">Admin Portal</a></li>
                <li class="active"><a href="#">Users</a></li>
                <li><a href="AdminThreadPage.php">Threads</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="users-container">
                <h1>All Users</h1>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Joined</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['registration_date']); ?></td>
                            <td class="status <?php echo htmlspecialchars($user['status']); ?>"><?php echo ucfirst(htmlspecialchars($user['status'])); ?></td>
                            <td>
                                <button onclick="toggleBlockStatus(this, <?php echo htmlspecialchars($user['user_id']); ?>)" class="action-btn <?php echo $user['status'] === 'active' ? 'block-btn' : 'unblock-btn'; ?>">
                                    <?php echo $user['status'] === 'active' ? 'Block' : 'Unblock'; ?>
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
        function toggleBlockStatus(button, userId) {
            // AJAX request to toggle user status
            var currentStatus = button.textContent.trim();
            var newStatus = currentStatus === 'Block' ? 'blocked' : 'active';

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "updateUserStatus.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        button.textContent = newStatus === 'active' ? 'Block' : 'Unblock';
                        button.parentNode.parentNode.querySelector('.status').textContent = newStatus === 'active' ? 'Active' : 'Blocked';
                        button.className = newStatus === 'active' ? 'action-btn block-btn' : 'action-btn unblock-btn';
                    } else {
                        alert('Error updating user status: ' + response.error);
                    }
                }
            };
            xhr.send("user_id=" + userId + "&new_status=" + newStatus);
        }
    </script>
</body>
</html>
