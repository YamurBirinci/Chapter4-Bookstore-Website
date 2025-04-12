<?php
require_once("../connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkEmailQuery = "SELECT email FROM users WHERE email = '$email'";
    $checkEmailResult = myQuery($checkEmailQuery);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        $message = "Email already exists!";
    } else {
        $insertQuery = "INSERT INTO users (firstName, email, password, roleID) VALUES ('$name', '$email', '$password', 1)";
        $insertResult = myQuery($insertQuery);
        if ($insertResult) {
            $message = "Registration successful! You can now sign in.";
        } else {
            $message = "An error occurred while registering. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter4 Bookstore - Signup</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f5ef;
            display: flex;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        .left-section {
            background: url('image13.png') no-repeat center center/cover;
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .right-section {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f1efe7;
            position: relative;
            padding-top: 120px;
        }

        .signin-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            height: 450px;
        }

        .signin-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .signin-button {
            width: 100%;
            padding: 15px;
            background-color: #f26b3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .signin-button:hover {
            background-color: #d45c30;
        }

        .login-button {
            width: 100%;
            padding: 15px;
            background-color: #fff;
            color: #f26b3c;
            border: 2px solid #f26b3c;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .login-button:hover {
            background-color: #f26b3c;
            color: #fff;
        }

        .logo-container {
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 1;
        }

        .logo-container img {
            width: 330px;
            height: auto;
        }

        .message-text {
            color: #f26b3c;
        }
    </style>
</head>

<body>
    <div class="left-section"></div>
    <div class="right-section">
        <div class="logo-container">
            <img src="logo.png" alt="Chapter4 Bookstore Logo">
        </div>
        <div class="signin-container">
            <h2 style="font-size: 18px; color: #777;">Welcome!</h2>
            <h2>SignUp For An Account</h2>
            <form action="signup.php" method="post">
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <?php if (isset($message)): ?>
                    <p class="message-text"><?= $message ?></p>
                <?php endif; ?>
                <button type="submit" class="signin-button">Register</button>
                <button type="button" class="login-button" onclick="window.location.href='login.php'">Login</button>
            </form>
        </div>
    </div>
</body>

</html>