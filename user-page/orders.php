<?php
require_once("../connect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-signup/login.php');
    exit;
}

$userID = $_SESSION['user_id'];

$sql = "SELECT 
            o.order_id,
            o.order_date,
            o.order_status
        FROM orders o
        WHERE o.user_id = '$userID'";
$result = myQuery($sql);

$userOrders = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $userOrders[] = [
            "order_id" => $row['order_id'],
            "order_date" => $row['order_date'],
            "order_status" => $row['order_status'],
        ];
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
            margin-right: 100px;
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
            cursor: pointer
        }

        .icons img {
            width: 24px;
            height: 24px;
        }

        .sidebar-container {
            width: 250px;
            height: 100vh;
            padding: 20px;
            background: #D4C2AE;
            padding: 20px;
            border-radius: 20px;
            border: 1px solid #948172;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 120px 20px 20px 20px;
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
        }

        .content-container {
            width: 80%;
            background: #D4C2AE;
            padding: 30px;
            border-radius: 20px;
            border: 1px solid #948172;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 150px auto;
        }

        .content {
            background: #FFFFFF;
            /* Beyaz Arka Plan */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .content h2 {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #473a30;
        }

        .order-card {
            background: #F9F9F9;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #EAEAEA;
            padding-bottom: 15px;
        }

        .order-products {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .order-product {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .order-product img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #EAEAEA;
        }

        .order-product .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .order-product .product-info h4 {
            font-size: 14px;
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .order-product .product-info span {
            font-size: 12px;
            color: #666;
        }

        .order-product .product-price {
            font-size: 14px;
            font-weight: bold;
            color: #1E1E1E;
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
            <div onclick="window.location.href='../user-page/profile.php'">
                <img src="../main-page/user.png" alt="Profile">
            </div>
            <div onclick="window.location.href='../user-page/basket.php'">
                <img src="../main-page/cart.png" alt="Cart">
            </div>
            <div onclick="window.location.href='../login-signup/logout.php'">
                <img src="../main-page/exit.png" alt="Logout">
            </div>
        </div>
    </div>

    <!-- Yan Menü -->
    <div class="sidebar-container">
        <div class="sidebar">
            <ul>
                <li><a href="profile.php">Edit Profile</a></li>
                <li><a href="orders.php">Orders</a></li>
            </ul>
        </div>
    </div>
</body>

<body>
    <div class="content-container">
        <div class="content">
            <h2>My Orders</h2>
            <?php if (empty($userOrders)) : ?>
                <p>No orders found.</p>
            <?php else : ?>
                <?php foreach ($userOrders as $order) : ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>Order Number: <?= htmlspecialchars($order['order_id']) ?></div>
                            <div>Date: <?= htmlspecialchars($order['order_date']) ?></div>
                        </div>
                        <div class="order-details">
                            <p>Status: <?= htmlspecialchars($order['order_status']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>