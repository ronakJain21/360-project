<?php
include 'config.php'; // Ensure this path is correct

// Initialize error message
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errorMessage = "Please fill out all fields.";
    } else {
        // Check if email exists and password is correct
        $stmt = $db->prepare("SELECT user_id, username, password FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                // Start a new session and save user information (like user_id and username) in session variables
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // Redirect user to another page (e.g., their profile page)
                header('Location: User_Profile.php'); // Change this to the path of your profile page or dashboard
                exit();
            } else {
                // Password is not correct
                $errorMessage = "Invalid email or password.";
            }
        } else {
            // Email does not exist
            $errorMessage = "Invalid email or password.";
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
    <title>MessiIsTheGOAT | Log in</title>
    <link rel="stylesheet" href="../CSS/login_css.css">
</head>
<body>
    <div id="bar">
        <div id="logo">MessiIsTheGOAT</div>
        <div id="signup_button"><a href="SignUp.php">Sign Up</a></div> <!-- Add link to your signup page -->
    </div>

    <div id="bar2">
        <form action="" method="post">
            Login To MessiIsTheGOAT<br><br>
            <div class="form-error"><?php echo $errorMessage; ?></div>
            <input type="text" name="email" placeholder="Email"><br><br>
            <input type="password" name="password" placeholder="Password"><br><br>
            <input type="submit" value="Log in">
        </form>
    </div>
</body>
</html>
