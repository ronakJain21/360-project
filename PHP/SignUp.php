<?php
include 'config.php'; // Ensure this path is correct

// Initialize error messages
$usernameError = '';
$emailError = '';
$passwordError = '';
$confirmPasswordError = '';

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
    
    if (empty($usernameError) && empty($emailError) && empty($passwordError) && empty($confirmPasswordError)) {
        // Check if username or email already exists
        $stmt = $db->prepare("SELECT user_id FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $usernameError = "Username or Email already exists.";
        } else {
            // Insert new user
            $stmt = $db->prepare("INSERT INTO users (username, email, password, status) VALUES (?, ?, ?, 'active')");
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sss", $username, $email, $passwordHash);
            
            if ($stmt->execute()) {
                header('Location: Login.php'); // Redirect after successful signup
                exit();
            } else {
                echo "Error: " . $db->error;
            }
        }
        $stmt->close();
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
    <style>
        .form-error {
            color: red;
            font-size: 14px;
            height: 20px; /* Adjust if necessary */
        }
    </style>
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

        <input type="submit" id="button" value="Sign up">
    </form>
</body>
</html>
