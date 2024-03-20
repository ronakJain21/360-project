<?php
include 'config.php'; // Ensure this path is correct

// Initialize error messages
$usernameError = '';
$emailError = '';
$passwordError = '';
$confirmPasswordError = '';
$profilePicError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Basic validation
    if (empty($username)) {
        $usernameError = "Please enter a username.";
    }
    if (empty($email)) {
        $emailError = "Please enter an email.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
    }
    if (empty($password)) {
        $passwordError = "Please enter a password.";
    }
    if ($password != $confirmPassword) {
        $confirmPasswordError = "Passwords do not match.";
    }

    // File upload handling
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        // Define allowed file types and size limit
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileSize = $_FILES['profile_pic']['size'];
        $fileType = $_FILES['profile_pic']['type'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Check file type and size
        if (!in_array($fileType, $allowedTypes) || $fileSize > $maxSize) {
            $profilePicError = 'Invalid file type or size.';
        } else {
            // Sanitize file name and generate a new unique file name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploaded_images/';
            $destFilePath = $uploadFileDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destFilePath)) {
                $profilePicError = 'Error uploading file.';
            }
        }
    } else {
        $profilePicError = 'Error uploading file.';
    }
    
    // Continue only if there are no errors
    if (empty($usernameError) && empty($emailError) && empty($passwordError) && empty($confirmPasswordError) && empty($profilePicError)) {
        // Check if username or email already exists...
        // Insert new user including profile_pic path if file upload was successful
        if (empty($profilePicError)) {
            $stmt = $db->prepare("INSERT INTO users (username, email, password, profile_pic, status) VALUES (?, ?, ?, ?, 'active')");
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssss", $username, $email, $passwordHash, $destFilePath);
        } else {
            // Handle case without profile picture
            $stmt = $db->prepare("INSERT INTO users (username, email, password, status) VALUES (?, ?, ?, 'active')");
            $stmt->bind_param("sss", $username, $email, $passwordHash);
        }

        if ($stmt->execute()) {
            header('Location: Login.php'); // Redirect after successful signup
            exit();
        } else {
            echo "Error: " . $db->error;
        }
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGOAT | Sign Up</title>
    <link rel="stylesheet" href="../CSS/signup_css.css">
</head>
<body style="font-family: tahoma; background-color: #e9ebee">
    <div id="bar">
        <div style="font-size: 40px;">MessiIsTheGOAT</div>
        <div id="signup_button"><a href ="Login.php">Login</a></div> 
    </div>
    <form action="" method="post"> 
        Sign Up To MessiIsTheGOAT<br><br>

        <div class="form-error"><?php echo $usernameError; ?></div>
        <input type="text" name="username" id="text" placeholder="Username"><br><br>

        <div class="form-error"><?php echo $emailError; ?></div>
        <input type="text" name="email" id="text" placeholder="Email"><br><br>

        <div class="form-error"><?php echo $passwordError; ?></div>
        <input type="password" name="password" id="text" placeholder="Password"><br><br>

        <div class="form-error"><?php echo $confirmPasswordError; ?></div>
        <input type="password" name="confirm_password" id="text" placeholder="Retype Password"><br><br>

        <div class="form-error"><?php echo $profilePicError ?? ''; ?></div>
        <p type="text">Upload Profile Picture:</p>
        <input type="file" name="profile_pic" id="profile_pic"><br><br>

        <input type="submit" id="button" value="Sign up">
    </form>
</body>
</html>
