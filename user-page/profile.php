<?php
require_once("../connect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-signup/login.php');
    exit;
}

$userID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];

    $updateQuery = "UPDATE users 
                    SET firstName = '$firstName', 
                        lastName = '$lastName', 
                        email = '$email', 
                        phoneNumber = '$phoneNumber', 
                        address = '$address' 
                    WHERE user_id = '$userID'";

    $result = myQuery($updateQuery);
    if ($result) {
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['phoneNumber'] = $phoneNumber;
        $_SESSION['address'] = $address;
        $_SESSION['email'] = $email;
        $message = "Profile updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <style>
        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background: #C4A893;
            display: flex;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 60px;
            height: 120px;
            background: #F2EFE8;
            border-bottom: 1px solid #948172;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .header img {
            height: 80px;
        }

        .navigation {
            display: flex;
            gap: 8px;
        }

        .navigation div {
            padding: 8px 16px;
            border-radius: 8px;
            background: #F5F5F5;
        }

        .icons {
            display: flex;
            gap: 16px;
            align-items: center;
            justify-content: flex-end;
            /* Sağ tarafa yaslama */
            margin-right: 100px;
            /* Sayfa kenarından biraz boşluk bırak */
        }


        .icons div {
            width: 50px;
            height: 50px;
            background: #F4F4FF;
            border-radius: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #b6a293;
            border-radius: 5px;
            cursor: pointer;
        }


        .icons img {
            width: 24px;
            height: 24px;
        }


        /* Yan Menü Çubuğu */
        .sidebar-container {
            width: 250px;
            height: auto;
            padding: 20px;
            background: #D4C2AE;
            border: 1px solid #948172;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 120px;

        }

        .sidebar {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 15px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 10px;
            text-align: center;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: block;
        }

        .sidebar ul li:hover {
            background: #F4F4FF;
            /* Hover efekti */
        }

        .content {
            margin: 200px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
            gap: 40px;
            max-width: 1200px;
            background: #D4C2AE;
            border-radius: 20px;
            border: 1px solid #948172;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .profile-container {
            width: 100%;
            background: #FFFFFF;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-container h2 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #473a30;
        }

        .profile-container .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .profile-container .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .profile-container .form-group input {
            padding: 10px;
            border: 1px solid #CACED8;
            border-radius: 8px;
        }

        .profile-container .button-group {
            text-align: center;
            margin-top: 20px;
        }

        .profile-container .save-button {
            width: 50%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background-color: #26B893;
        }

        .profile-container .save-button:hover {
            background-color: #1e9174;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #CACED8;
            border-radius: 8px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .save-button {
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background-color: #26B893;
        }

        .save-button:hover {
            background-color: #1e9174;
        }

        .success-message {
            color: #26B893;
            text-align: center;
            margin-top: 20px;
        }

        .message-text {
            color: #f26b3c;
        }

        .header-buttons {
            border: 1px solid #b6a293;
            border-radius: 5px;
            cursor: pointer
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="../main-page/logo.png" alt="Chapter4 Bookstore">
        <div class="navigation">
            <div onclick="window.location.href='../main-page/mainPage.php';" class="header-buttons">Home</div>
            <div onclick="window.location.href='../headers/about_us.php';" class="header-buttons">About Us</div>
        </div>
        <div class="icons">
            <div onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? '../user-page/profile.php' : '../login-signup/login.php'; ?>'">
                <img src="../main-page/user.png" alt="Profile">
            </div>
            <div onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? '../user-page/basket.php' : '../login-signup/login.php'; ?>'">
                <img src="../main-page/cart.png" alt="Cart">
            </div>
            <div onclick="window.location.href='../login-signup/logout.php'">
                <img src="../main-page/exit.png" alt="Logout">
            </div>
        </div>
    </div>

    <div class="sidebar-container">
        <div class="sidebar">
            <ul>
                <li><a href="profile.php">Edit Profile</a></li>
                <li><a href="orders.php">Orders</a></li>
            </ul>
        </div>
    </div>

    <div class="content">
        <div class="profile-container">
            <h2>Edit Profile</h2>
            <form action="profile.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstName" value="<?= htmlspecialchars($_SESSION['firstName']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Surname</label>
                        <input type="text" name="lastName" value="<?= htmlspecialchars($_SESSION['lastName']) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phoneNumber" value="<?= htmlspecialchars($_SESSION['phoneNumber']) ?>" placeholder="+90 5xxxxxxx">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($_SESSION['address']) ?>" placeholder="Enter your address">
                </div>
                <?php if (isset($message)): ?>
                    <p class="message-text"><?= $message ?></p>
                <?php endif; ?>

                <div class="button-group">
                    <button type="submit" class="save-button">Save</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>