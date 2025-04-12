<?php

require_once("connect.php");
session_start();

$books = [];
$query = "SELECT * FROM books";
$result = myQuery($query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
} else {
    die("Error fetching books: " . mysqli_error($conn));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_book'])) {
        $book_id = intval($_POST['delete_book']);
        $delete_query = "DELETE FROM books WHERE book_id = $book_id";
        $delete_result = myQuery($delete_query);

        if ($delete_result) {
            header('Location: Chapter4/admin-page/book-actions/bookManagement.php');
            exit;
        } else {
            echo "Problem occured" . mysqli_error($conn);
        }
    }

    if (isset($_POST['add_book'])) {
        $target_dir = "bookimg/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $bookName = mysqli_real_escape_string($conn, $_POST['bookName']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $author = mysqli_real_escape_string($conn, $_POST['author']);
            $price = floatval($_POST['price']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $stock_status = intval($_POST['stock_status']);

            $insert_query = "INSERT INTO books (bookName, description, author, price, category, stock_status, image)
                             VALUES ('$bookName', '$description', '$author', $price, '$category', $stock_status, '$target_file')";
            $insert_result = myQuery($insert_query);

            if ($insert_result) {
                header('Location: bookManagement.php');
                exit;
            } else {
                echo "A problem occured. " . mysqli_error($conn);
            }
        } else {
            echo "A problem occured.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 80vh;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form input,
        form button {
            padding: 10px;
            font-size: 14px;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        .add-book-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .go-to-dashboard {
            display: inline-block;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .go-to-dashboard:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Book Management</h2>
        <a href="addBook.php" class="add-book-btn">Add New Book</a>
        <a href="/Chapter4/admin-page/adminMainPage.html" class="go-to-dashboard">
            Back to the Admin Main Page
        </a>
        <table>
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Stock Status</th>
                    <th>Image</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['bookName']) ?></td>
                        <td><?= htmlspecialchars($book['description']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['price']) ?></td>
                        <td><?= htmlspecialchars($book['category']) ?></td>
                        <td><?= $book['stock_status'] ? 'In Stock' : 'Out Of Stock' ?></td>
                        <td><img src="<?= htmlspecialchars($book['image']) ?>" alt="book image"></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_book" value="<?= $book['book_id'] ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this book?')">Delete</button>
                            </form>
                            <a href="updateBook.php?book_id=<?= $book['book_id'] ?>">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>