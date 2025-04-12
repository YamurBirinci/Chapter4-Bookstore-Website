<?php
require_once("../connect.php");
session_start();

$books = [];
$search = $_GET['search'];
$query = "SELECT * FROM books WHERE 
          bookName LIKE '%$search%' OR 
          description LIKE '%$search%'";
$result = myQuery($query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
} else {
    die("Error fetching books: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Books</title>
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

        .books-container {
            margin: 40px auto;
            width: 90%;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        .book {
            width: 200px;
            background: #F4F4FF;
            border: 1px solid rgba(9, 9, 55, 0.1);
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease, transform 0.2s ease;
            cursor: pointer;
            border-radius: 25px;
        }

        .book:hover {
            background: #d0cde4;
            transform: translateY(-5px);
        }

        .book img {
            width: 150px;
            height: 200px;
            border-radius: 4px;
            object-fit: cover;
        }

        .book-info {
            text-align: center;
            margin-top: 10px;
        }

        .book-info h3 {
            font-size: 18px;
            font-weight: 600;
            color: #090937;
            margin: 0;
        }

        .book-info p {
            font-size: 14px;
            color: rgba(9, 9, 55, 0.6);
            margin: 5px 0;
        }

        .book-info span {
            font-size: 16px;
            font-weight: 700;
            color: #6251DD;
        }

        .back-button {
            display: inline-block;
            margin: 20px;
            padding: 10px 20px;
            background-color: #EF6B4A;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }

        .back-button:hover {
            background-color: #d94c32;
        }

        .header-buttons {
            border: 1px solid #b6a293;
            border-radius: 5px;
            cursor: pointer
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
            cursor: pointer;
        }

        .icons img {
            width: 24px;
            height: 24px;
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
            <div><img src="user.png" alt="Profile"></div>
            <div><img src="cart.png" alt="Cart"></div>
        </div>
    </div>

    <div class="books-container">
        <?php foreach ($books as $book): ?>
            <div class="book" onclick="window.location.href='selectedBook.php?book_id=<?= $book['book_id'] ?>'">
                <img src="<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['bookName']) ?>">
                <div class="book-info">
                    <h3><?= htmlspecialchars($book['bookName']) ?></h3>
                    <p><?= htmlspecialchars($book['author']) ?></p>
                    <span>
                        <?= $book['stock_status'] == 0 ? 'Out of Stock' : '$' . htmlspecialchars($book['price']) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>