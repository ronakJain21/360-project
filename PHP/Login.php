<?php
include 'config.php';

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim(htmlspecialchars($_POST['login']));
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
        $errorMessage = "Please fill out all fields.";
    } else {
        $stmt = $db->prepare("SELECT user_id, username, password FROM users WHERE email=? OR username=?");
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                header('Location: User_Profile.php');
                exit();
            } else {
                $errorMessage = "Invalid login credentials.";
            }
        } else {
            $errorMessage = "Invalid login credentials.";
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
    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</head>
<body>
    <div id="bar">
    <div style="font-size : 40px";>MessiIsTheGOAT</div>
        <div id="signup_botton"><a href="SignUp.php">Sign Up</a></div> 
    </div>

    <div id="bar2">
        <form action="" method="post">
            Login To MessiIsTheGOAT<br><br>
            <div class="form-error"><?php echo $errorMessage; ?></div>
            <input type="text" name="login" id="login" placeholder="Email or Username"><br><br> 
            <input type="password" name="password" id="password" placeholder="Password"><br><br>
            <input type="checkbox" onclick="togglePassword()"> Show Password<br><br>
            <input type="submit" value="Log in">
        </form>
    </div>
</body>
</html>
