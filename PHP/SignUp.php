<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $gender = htmlspecialchars($_POST['gender']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $confirmPassword = htmlspecialchars($_POST['confirm_password']);

    // Simple validation example
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        echo "Please fill out all required fields.";
    } else if ($password != $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        // Process the data (e.g., save to database, send email, etc.)
        echo "Thank you for signing up, $firstName!";
    }
}
?>

<!DOCTYPE html>
<html lang ="en">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGOAT | Sign Up</title>
    <link rel="stylesheet" href="/CSS/signup_css.css">
    </head>


    <body style="font-family: tahoma; background-color: #e9ebee">
        <div id = "bar";>

            <div style="font-size : 40px";>MessiIsTheGOAT</div>

            <div id = "signup_botton">Login</div>


        </div>

        <form action="signup.php" method="post">
            
            Sign Up To MessiIsTheGOAT<br><br>

            <input type="text" name="first_name" id="text" placeholder="First Name"><br><br>
            <input type="text" name="last_name" id="text" placeholder="Last Name"><br><br>
            <span style="font-weight: normal;">Gender:</span><br>
            <select name="gender" id="text";>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
            <br><br>

            <input type="text" name="email" id="text" placeholder="Email"><br><br>
            <input type="password" name="password" id="text" placeholder="Password"><br><br>
            <input type="password" name="confirm_password" id="text" placeholder=" Retype Password"><br><br>
            <input type="submit" id="button" value="Sign up">

        </form>


    </body>


</html>