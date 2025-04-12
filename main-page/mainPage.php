<?php
require_once("connect.php");
session_start();

$books = [];
$query = "SELECT * FROM books where stock_status>0 order by bookName";
$result = myQuery($query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
} else {
    die("Error fetching books: " . mysqli_error($conn));
}

$groupedBooks = [];
foreach ($books as $book) {
    $groupedBooks[$book['category']][] = $book;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home Page</title>
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

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #F4F4FF;
            border-radius: 4px;
            padding: 10px 20px;
            width: 400px;
            border: 1px solid #b6a293;
            border-radius: 5px;
        }

        .search-bar input {
            border: none;
            outline: none;
            font-size: 20px;
            color: rgba(9, 9, 55, 0.4);
            width: 100%;
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
            cursor: pointer;
        }

        .icons img {
            width: 24px;
            height: 24px;
        }

        .section {
            margin: 20px auto;
            width: 1320px;
            background-color: #e1cbba;
            padding: 20px;
            border-radius: 20px;
            border: 1px solid #948172;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 24px;
            font-weight: 700;
            color: #473a30;
            padding-left: 20px;
            margin-top: -30px;
            margin-bottom: -30px;
        }

        .section-title a {
            font-size: 20px;
            color: #EF6B4A;
            text-decoration: none;
            padding-right: 20px;
        }

        .books {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            overflow-x: auto;
            background: #F2EFE8;
            border-radius: 25px;
            padding: 20px;
        }

        .book {
            width: 320px;
            background: #F4F4FF;
            border: 1px solid rgba(9, 9, 55, 0.1);
            border-radius: 4px;
            padding: 10px;
            display: flex;
            gap: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease, transform 0.2s ease;
            cursor: pointer;
        }

        .book:hover {
            background: #d0cde4;
            transform: translateY(-5px);
        }

        .book img {
            width: 120px;
            height: 180px;
            border-radius: 4px;
        }

        .book-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-info h3 {
            font-size: 20px;
            font-weight: 600;
            color: #090937;
            margin: 0;
        }

        .book-info p {
            font-size: 16px;
            color: rgba(9, 9, 55, 0.6);
            margin: 0;
        }

        .book-info span {
            font-size: 24px;
            font-weight: 700;
            color: #6251DD;
        }

        .header-buttons {
            border: 1px solid #b6a293;
            border-radius: 5px;
            cursor: pointer;
        }

        .name-text {
            font-size: 20px;
            margin-left: 8px;
            color: #333;
            width: 50px;
            height: 50px;
            border-radius: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
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
        <form method="GET" action="searchAll.php" style="display: flex; gap: 10px; align-items: center;">
            <input
                type="text"
                name="search"
                placeholder=" Please Search..."
                style="padding: 10px; font-size: 16px; border: 1px solid #b6a293; border-radius: 5px; flex-grow: 1;" />
            <button
                type="submit"
                style="padding: 10px 20px; font-size: 16px; color: white; background-color: #EF6B4A; border: none; border-radius: 5px; cursor: pointer;">
                Search
            </button>
        </form>


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

    <?php if (isset($_SESSION['success_message'])): ?>
        <div style="color: green; text-align: center; font-weight: bold; margin: 20px;">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']);  ?>
    <?php endif; ?>

    <?php foreach ($groupedBooks as $category => $books): ?>
        <div class="section">
            <div class="section-title">
                <h2><?= htmlspecialchars($category) ?></h2>
                <a href="viewAll.php?category=<?= urlencode($category) ?>">View All</a>
            </div>
            <div class="books">
                <?php foreach (array_slice($books, 0, 5) as $book): ?>
                    <div class="book" onclick="window.location.href='selectedBook.php?book_id=<?= $book['book_id'] ?>'">
                        <img src="<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['bookName']) ?>">
                        <div class="book-info">
                            <h3><?= htmlspecialchars($book['bookName']) ?></h3>
                            <p><?= htmlspecialchars($book['author']) ?></p>
                            <span>$<?= htmlspecialchars($book['price']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</body>

</html>