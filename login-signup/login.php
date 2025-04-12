<?php
require_once("../connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, firstName, lastName, roleID, email, password, phoneNumber, address FROM users WHERE email = '$email' AND password = '$password'";
    $result = myQuery($sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName'] = $user['lastName'];
        $_SESSION['roleID'] = $user['roleID'];
        $_SESSION['phoneNumber'] = $user['phoneNumber'];
        $_SESSION['address'] = $user['address'];
        $_SESSION['email'] = $user['email'];

        if ($user['roleID'] == 1) {
            header("Location: helin.com/Chapter4/main-page/mainPage.php");
        } elseif ($user['roleID'] == 2) {
            header("Location: helin.com/Chapter4/admin-page/adminMainPage.html");
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter4 Bookstore - Login</title>
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
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            height: auto;
            z-index: 2;
        }

        .login-container h2 {
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

        .login-button {
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

        .login-button:hover {
            background-color: #d45c30;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .logo-container {
            position: absolute;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 1;
        }

        .logo-container img {
            width: 430px;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="left-section"></div>
    <div class="right-section">
        <div class="logo-container">
            <img src="logo.png" alt="Chapter4 Logo">
        </div>
        <div class="login-container">
            <h2 style="font-size: 18px; color: #777;">Welcome back!</h2>
            <h2>Login To Your Account</h2>
            <form action="login.php" method="post">
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
            <?php if (isset($error)): ?>
                <p class="error-message"><?= $error ?></p>
            <?php endif; ?>
            <p style="text-align: center; margin-top: 20px;">
                I don't have an account yet
                <a href="signup.php" style="color: #f26b3c; text-decoration: none; font-weight: bold;">Sign Up</a>
            </p>
        </div>
    </div>
</body>

</html>