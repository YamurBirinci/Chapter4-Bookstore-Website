<?php
require_once("connect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-signup/login.php');
    exit;
}

$userID = intval($_SESSION['user_id']);

$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
if ($book_id === 0) {
    die("Invalid book ID.");
}

$query = "SELECT * FROM books WHERE book_id = $book_id";
$result = myQuery($query);
$selectedBook = mysqli_fetch_assoc($result);
$bookQuantityInDatabase = $selectedBook['stock_status'];

if (!$selectedBook) {
    die('Kitap bulunamadı!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

    $checkQuery = "SELECT * FROM basket WHERE user_id = '$userID' AND book_id = '$book_id'";
    $checkResult = myQuery($checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) == 1) {
        $bookInfo = mysqli_fetch_assoc($checkResult);
        $bookQuantity = $bookInfo['quantity'];
        if ($bookQuantity <= $bookQuantityInDatabase) {
            $updateQuery = "UPDATE basket SET quantity = quantity + 1 WHERE user_id = '$userID' AND book_id = '$book_id'";
            $result = myQuery($updateQuery);
            if ($result) {
                $message = "We have successfully updated to have $bookQuantity of this book!";
            } else {
                $message = "Failed to update book quantity!";
            }
        } else {
            $message = "Sorry, not enough stock!";
        }
    } else {
        if ($bookQuantityInDatabase > 0) {
            $insertQuery = "INSERT INTO basket (user_id, book_id, quantity) VALUES ('$userID', '$book_id', 1)";
            $result = myQuery($insertQuery);
            if ($result) {
                $message = "Book added to your basket!";
            } else {
                $message = "Failed to add book to basket!";
            }
        } else {
            $message = "Sorry, not enough stock!";
        }
    }
    echo "<script>alert('$message');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($selectedBook['bookName']) ?> - Details</title>
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
            box-shadow: 0px 8px 10px rgba(9, 9, 55, 0.02);
            border: 1px solid #948172;
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

        .book-details {
            width: 60%;
            background: #F2EFE8;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 40px;
            margin: 40px auto;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .book-details img {
            width: 200px;
            height: auto;
            border-radius: 8px;
        }

        .book-info {
            flex: 1;
        }

        .book-info h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .book-info p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .book-info span {
            font-size: 22px;
            color: #EF6B4A;
            font-weight: bold;
        }

        .back-button {
            display: block;
            text-decoration: none;
            background-color: #EF6B4A;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            margin-top: 20px;
            width: 150px;
        }

        .back-button:hover {
            background-color: #d45434;
        }

        .button-container {
            display: flex;
            gap: 10px;
        }

        .add-to-cart-button {
            display: block;
            text-decoration: none;
            background-color: #EF6B4A;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            margin-top: 20px;
            width: 150px;
        }

        .add-to-cart-button:hover {
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
        <img src="logo.png" alt="Chapter4 Bookstore">
        <div class="navigation">
            <div onclick="window.location.href='../main-page/mainPage.php';" class="header-buttons">Home</div>
            <div onclick="window.location.href='../headers/about_us.php';" class="header-buttons">About Us</div>
        </div>

        <div class="icons">
            <?php if (isset($_SESSION['firstName'])): ?>
                <span class="name-text">Dear, <?= htmlspecialchars($_SESSION['firstName']) ?></span>
            <?php endif; ?>
            <div onclick="window.location.href='<?php echo isset($_SESSION['roleID']) ? '../user-page/profile.php' : '../login-signup/login.php'; ?>'">
                <img src="../main-page/user.png" alt="Profile">
            </div>
            <div onclick="window.location.href='<?php echo isset($_SESSION['roleID']) ? '../user-page/basket.php' : '../login-signup/login.php'; ?>'">
                <img src="../main-page/cart.png" alt="Cart">
            </div>
            <?php if (isset($_SESSION['roleID'])): ?>
                <div onclick="window.location.href='../login-signup/logout.php'">
                    <img src="../main-page/exit.png" alt="Logout">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="book-details">
        <img src="<?= htmlspecialchars($selectedBook['image']) ?>"
            alt="<?= htmlspecialchars($selectedBook['bookName']) ?>">
        <div class="book-info">
            <h2><?= htmlspecialchars($selectedBook['bookName']) ?></h2>
            <p>Author: <?= htmlspecialchars($selectedBook['author']) ?></p>
            <p>Description: <?= htmlspecialchars($selectedBook['description']) ?></p>
            <span>Price: $<?= htmlspecialchars($selectedBook['price']) ?></span>
            <div class="button-container">
                <a href="mainPage.php" class="back-button">Back to Home</a>
                <form method="post" style="margin: 0;">
                    <button type="submit" class="back-button" name="add_to_cart">Add to Cart</button>
                </form>
            </div>

        </div>

    </div>


</body>

</html>