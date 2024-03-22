<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: Login.php');
    exit;
}

$username = $_SESSION['username'];

// Fetch user information
$stmt = $db->prepare("SELECT username, profile_pic FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $currentUserProfilePic = $user['profile_pic'] ?: '/path/to/default/profile_pic.png';
    $currentUsername = $user['username'];
} else {
    // Handle error or redirect
    $userProfilePic = '/path/to/default/profile_pic.png';
    $username = '@defaultUser';
}

$profilePicError = $usernameError = $passwordError = $successMessage = '';

// Process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile picture
    if (isset($_FILES['new_profile_pic'])) {
        $file = $_FILES['new_profile_pic'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($file['type'], $allowedTypes) && $file['size'] < 5000000) { // 5MB limit
                $uploadDir = __DIR__ . '/uploaded_images/';
                $fileName = basename($file['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    // Update database with new profile picture path
                    $stmt = $db->prepare("UPDATE users SET profile_pic = ? WHERE username = ?");
                    $stmt->bind_param("ss", $targetPath, $username);
                    $stmt->execute();
                    $stmt->close();
                    $successMessage = "Profile picture updated successfully.";
                } else {
                    $profilePicError = "Failed to upload file.";
                }
            } else {
                $profilePicError = "Invalid file type or size.";
            }
        } else {
            $profilePicError = "Error uploading file.";
        }
    }

    // Update username
    if (isset($_POST['new_username']) && $_POST['new_username'] != $currentUsername) {
        $newUsername = trim(htmlspecialchars($_POST['new_username']));
        // Check for uniqueness
        $stmt = $db->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $newUsername);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            // Update in the database
            $updateStmt = $db->prepare("UPDATE users SET username = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $newUsername, $username);
            $updateStmt->execute();
            $updateStmt->close();
            $_SESSION['username'] = $newUsername;  // Update the session variable
            $successMessage = "Username updated successfully.";
        } else {
            $usernameError = "Username is already taken.";
        }
        $stmt->close();
    }

    // Update password
    if (!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        // Verify old password
        $stmt = $db->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            if (password_verify($oldPassword, $user['password'])) {
                // Update with the new one in the database
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
                $updateStmt->bind_param("ss", $newPasswordHash, $username);
                $updateStmt->execute();
                $updateStmt->close();
                $successMessage = "Password updated successfully.";
            } else {
                $passwordError = "Old password is incorrect.";
            }
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGoat - Profile Settings</title>
    <link rel="stylesheet" href="../CSS/styles_user_profile.css">
    <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <!-- Navigation Bar Content -->
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
            <!-- User Menu Content -->
            <section>
                <img src="<?php echo htmlspecialchars($currentuserProfilePic); ?>" alt="Profile Pic" class="profile_pic">
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
                <form action="user_profile_settings.php" method="post" enctype="multipart/form-data">
                    <!-- Profile Picture Update Section -->
                    <h4>Change Profile Picture:</h4>
                    <img src="<?php echo htmlspecialchars($currentUserProfilePic); ?>" alt="Profile Pic" class="profile_pic_small"><br><br>
                    <input type="file" name="new_profile_pic">
                    <?php if ($profilePicError): ?>
                        <p class="error"><?php echo $profilePicError; ?></p>
                    <?php endif; ?>

                    <!-- Username Update Section -->
                    <h4>Change Username:</h4>
                    <input type="text" class="input-box" placeholder="New Username" name="new_username" value="<?php echo htmlspecialchars($currentUsername); ?>">
                    <?php if ($usernameError): ?>
                        <p class="error"><?php echo $usernameError; ?></p>
                    <?php endif; ?>

                    <!-- Password Update Section -->
                    <h4>Change Password:</h4>
                    <input type="password" class="input-box" placeholder="Old Password" name="old_password">
                    <input type="password" class="input-box" placeholder="New Password" name="new_password">
                    <?php if ($passwordError): ?>
                        <p class="error"><?php echo $passwordError; ?></p>
                    <?php endif; ?>
                    <br><br>

                    <!-- Submission Button -->
                    <button type="submit">Update Settings</button>
                    <?php if ($successMessage): ?>
                        <p class="success"><?php echo $successMessage; ?></p>
                    <?php endif; ?>
                </form>
            </section>
        </main>
    </div>

    <footer>
        <!-- Footer Content -->
        <p>&copy; 2024 MessiIsTheGoat</p>
    </footer>
</body>
</html>
