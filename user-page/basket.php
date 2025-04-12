<?php
require_once("../connect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-signup/login.php');
    exit;
}

$userID = $_SESSION['user_id'];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['increase_quantity'])) {
        $book_id = intval($_POST['book_id']);

        $stockQuery = "SELECT stock_status FROM books WHERE book_id = '$book_id'";
        $stockResult = myQuery($stockQuery);
        $stockRow = mysqli_fetch_assoc($stockResult);
        $availableStock = $stockRow['stock_status'];

        $checkQuery = "SELECT quantity FROM basket WHERE user_id = '$userID' AND book_id = '$book_id'";
        $checkResult = myQuery($checkQuery);
        $basketRow = mysqli_fetch_assoc($checkResult);
        $currentQuantity = $basketRow['quantity'];

        if ($currentQuantity < $availableStock) {
            $updateQuery = "UPDATE basket SET quantity = quantity + 1 WHERE user_id = '$userID' AND book_id = '$book_id'";
            $result = myQuery($updateQuery);
            $message = $result ? "Quantity increased successfully!" : "Failed to increase quantity!";
        } else {
            $message = "Sorry, not enough stock available!";
        }
    }

    if (isset($_POST['decrease_quantity'])) {
        $book_id = intval($_POST['book_id']);
        $checkQuery = "SELECT quantity FROM basket WHERE user_id = '$userID' AND book_id = '$book_id'";
        $checkResult = myQuery($checkQuery);
        $basketRow = mysqli_fetch_assoc($checkResult);
        $currentQuantity = $basketRow['quantity'];

        if ($currentQuantity > 1) {
            $updateQuery = "UPDATE basket SET quantity = quantity - 1 WHERE user_id = '$userID' AND book_id = '$book_id'";
            $result = myQuery($updateQuery);
            $message = $result ? "Quantity decreased successfully!" : "Failed to decrease quantity!";
        } else {
            $message = "Cannot decrease below 1. Use remove option to delete the book.";
        }
    }

    if (isset($_POST['remove_book'])) {
        $book_id = intval($_POST['book_id']);
        $deleteQuery = "DELETE FROM basket WHERE user_id = '$userID' AND book_id = '$book_id'";
        $result = myQuery($deleteQuery);
        $message = $result ? "Book removed from basket successfully!" : "Failed to remove book from basket!";
    }

    echo "<script>alert('$message'); window.location.href='basket.php';</script>";
}

$query = "SELECT b.book_id, b.bookName, b.author, b.price, b.image, bs.quantity
          FROM basket bs
          INNER JOIN books b ON bs.book_id = b.book_id
          WHERE bs.user_id = $userID";
$result = myQuery($query);

$userCart = [];
$orderTotal = 0;
$deliveryFee = 5;

if ($result) {
    while ($item = mysqli_fetch_assoc($result)) {
        $userCart[] = [
            "book_id" => $item['book_id'],
            "bookName" => $item['bookName'],
            "author" => $item['author'],
            "price" => $item['price'],
            "quantity" => $item['quantity'],
            "img" => $item['image']
        ];
    }
}

foreach ($userCart as $item) {
    $orderTotal += $item['price'] * $item['quantity'];
}
$total = $orderTotal + $deliveryFee;


if (isset($_POST['continue_payment'])) {
    $address = isset($_SESSION['address']) ? trim($_SESSION['address']) : '';
    if (empty($address)) {
        $_SESSION['error_message'] = "Address not found. Please add your address to proceed.";
        header('Location: basket.php');
        exit;
    } else {
        header('Location: Chapter4/user-page/cart.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background: #C4A893;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 60px;
            height: 120px;
            background: #F2EFE8;
            border: 1px solid #948172;
            box-shadow: 0px 8px 10px rgba(9, 9, 55, 0.02);
        }

        .header img {
            height: 80px;
        }

        .navigation {
            display: flex;
            gap: 12px;
        }

        .navigation div {
            padding: 10px 20px;
            border-radius: 8px;
            background: #F5F5F5;
            font-size: 16px;
        }

        .icons {
            display: flex;
            gap: 16px;
        }

        .icons div {
            width: 50px;
            height: 50px;
            background: #F4F4FF;
            border-radius: 8px;
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

        .content {
            margin: 60px auto;
            display: flex;
            justify-content: space-between;
            padding: 40px;
            gap: 40px;
            max-width: 1400px;
            background: #E1CBBA;
            border-radius: 20px;
            border: 1px solid #948172;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .cart-items {
            width: 65%;
            background: #FFFFFF;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .cart-items h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #473a30;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding: 15px;
            border-radius: 8px;
            background: #F2EFE8;
            border: 1px solid #b6a293;
        }

        .cart-item img {
            width: 100px;
            height: 140px;
            border-radius: 8px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-details h4 {
            margin: 0;
            font-size: 18px;
            color: #090937;
        }

        .cart-item-details span {
            font-size: 14px;
            color: #666;
        }

        .cart-item-price {
            font-size: 18px;
            font-weight: bold;
            color: #6251DD;
        }

        .cart-summary {
            width: 30%;
            background: #FFFFFF;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .cart-summary h3 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #473a30;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .checkout-btn {
            margin-top: 30px;
            width: 100%;
            padding: 15px;
            font-size: 18px;
            text-align: center;
            background: #C4A893;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .checkout-btn a {
            color: white;
            text-decoration: none;
        }

        .basket-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .basket-item img {
            width: 80px;
            height: auto;
            border-radius: 5px;
        }

        .basket-item-details {
            flex: 1;
            margin-left: 20px;
        }

        .basket-item-details h3 {
            margin: 0;
            font-size: 18px;
        }

        .basket-item-details p {
            margin: 5px 0;
            font-size: 14px;
        }

        .basket-item-actions {
            display: flex;
            gap: 10px;
        }

        .basket-item-actions form button {
            padding: 5px 10px;
            background-color: #ef6b4a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .basket-item-actions form button:hover {
            background-color: #d45434;
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
        <img src="../main-page/logo.png" alt="Logo">
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

    <?php if (isset($_SESSION['error_message'])): ?>
        <div style="color: red; text-align: center; font-weight: bold; margin: 20px;">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>


    <div class="content">
        <div class="cart-items">
            <h2>Shopping Cart</h2>
            <?php foreach ($userCart as $item): ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['bookName']) ?>">
                    <div class="cart-item-details">
                        <h4><?= htmlspecialchars($item['bookName']) ?></h4>
                        <span>Author: <?= htmlspecialchars($item['author']) ?></span>
                        <div class="cart-item-quantity">
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="book_id" value="<?= htmlspecialchars($item['book_id']) ?>">
                                <button type="submit" name="decrease_quantity">-</button>
                            </form>
                            <span><?= htmlspecialchars($item['quantity']) ?></span>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="book_id" value="<?= htmlspecialchars($item['book_id']) ?>">
                                <button type="submit" name="increase_quantity">+</button>
                            </form>
                        </div>
                    </div>
                    <div class="cart-item-price">
                        $<?= number_format($item['price'], 2) ?>
                    </div>
                    <div class="cart-item-remove">
                        <form method="post">
                            <input type="hidden" name="book_id" value="<?= htmlspecialchars($item['book_id']) ?>">
                            <button type="submit" name="remove_book">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <div class="summary-item">
                <span>Order:</span>
                <span>$<?= number_format($orderTotal, 2) ?></span>
            </div>
            <div class="summary-item">
                <span>Delivery:</span>
                <span>$<?= number_format($deliveryFee, 2) ?></span>
            </div>
            <hr>
            <div class="summary-item">
                <strong>Total:</strong>
                <strong>$<?= number_format($total, 2) ?></strong>
            </div>
            <form method="post">
                <button class="checkout-btn" type="submit" name="continue_payment">Continue to Payment</button>
            </form>
        </div>
    </div>
</body>

</html>