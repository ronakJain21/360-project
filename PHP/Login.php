<?php
include 'config.php'; // Include the database connection configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "Please fill out all fields.";
    } else {
        // Prepare SQL statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        // Fetch the user
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and verify password
        if ($user && password_verify($password, $user['password'])) {
            echo "Login successful!";
            // Start a new session and set session variables here
        } else {
            echo "Login failed. Invalid email or password.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGOAT | Log in</title>
    <link rel="stylesheet" href="/CSS/login_css.css">
</head>
<body>
    <div id="bar">
        <div id="logo">MessiIsTheGOAT</div>
        <div id="signup_button">Sign Up</div>
    </div>

    <div id="bar2">
        <form action="login.php" method="post">
            Login To MessiIsTheGOAT<br><br>
            <input type="text" name="email" placeholder="Email"><br><br>
            <input type="password" name="password" placeholder="Password"><br><br>
            <input type="submit" value="Log in">
        </form>
    </div>
</body>
</html>
