<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config.php'; // Ensure this path is correct

// Initialize error messages
$usernameError = $emailError = $passwordError = $confirmPasswordError = $profilePicError = '';

// Initialize form values
$username = $email = '';

// Create the uploaded_images directory if it doesn't exist
$uploadFileDir = __DIR__ . '/uploaded_images/';
if (!file_exists($uploadFileDir)) {
    mkdir($uploadFileDir, 0755, true); // true for recursive creation, 0755 is the permission
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $destFilePath = ''; // Initialize with empty string for the case with no file upload

    // Basic validation
    if (empty($username)) {
        $usernameError = "Please enter a username.";
    } else {
        // Check if username already exists
        $stmt = $db->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $usernameError = "Username already exists.";
        }
    }
    if (empty($email)) {
        $emailError = "Please enter an email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
    }
    if (empty($password)) {
        $passwordError = "Please enter a password.";
    }
    if ($password !== $confirmPassword) {
        $confirmPasswordError = "Passwords do not match.";
    }

    // File upload handling, proceed only if a file is actually uploaded
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['size'] > 0) {
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
    }
    
    // Continue only if there are no input validation errors
    if (empty($usernameError) && empty($emailError) && empty($passwordError) && empty($confirmPasswordError)) {
        // Insert new user into database
        $stmt = $db->prepare("INSERT INTO users (username, email, password, profile_pic, status) VALUES (?, ?, ?, ?, 'active')");
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $username, $email, $passwordHash, $destFilePath);

        if ($stmt->execute()) {
            header('Location: Login.php'); // Redirect after successful signup
            exit;
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
        <div id="signup_button"><a href="Login.php">Login</a></div> 
    </div>
    <form action="" method="post" enctype="multipart/form-data"> 
        Sign Up To MessiIsTheGOAT<br><br>

        <div class="form-error"><?php echo $usernameError; ?></div>
        <input type="text" name="username" id="text" placeholder="Username" value="<?php echo $username; ?>"><br><br>

        <div class="form-error"><?php echo $emailError; ?></div>
        <input type="text" name="email" id="text" placeholder="Email" value="<?php echo $email; ?>"><br><br>

        <div class="form-error"><?php echo $passwordError; ?></div>
        <input type="password" name="password" id="text" placeholder="Password"><br><br>

        <div class="form-error"><?php echo $confirmPasswordError; ?></div>
        <input type="password" name="confirm_password" id="text" placeholder="Retype Password"><br><br>

        <div class="form-error"><?php echo $profilePicError; ?></div>
        <p>Upload Profile Picture (Optional):</p>
        <input type="file" name="profile_pic" id="profile_pic"><br><br>

        <input type="submit" id="button" value="Sign up">
    </form>
</body>
</html>
