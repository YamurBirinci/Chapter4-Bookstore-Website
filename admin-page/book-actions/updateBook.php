<?php

require_once("connect.php");
session_start();

$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0; // ID'yi al
if ($book_id === 0) {
    die("Book ID not found");
}

$query = "SELECT * FROM books WHERE book_id = $book_id";
$result = myQuery($query);


if (!$result || mysqli_num_rows($result) === 0) {
    die("Book not found!");
}

$book_to_update = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookName = $_POST['bookName'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $category = $_POST['category'];
    $stock_status = isset($_POST['stock_status']) ? intval($_POST['stock_status']) : 0;

    $target_dir = "Chapter4/bookimg/";
    $image = $book_to_update['image'];
    if (!empty($_FILES["image"]["name"])) {
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            die("A problem occured while uploading the image.");
        }
    }

    $update_query = "UPDATE books SET 
                     bookName = '$bookName', 
                     description = '$description', 
                     author = '$author', 
                     price = $price, 
                     category = '$category', 
                     stock_status = $stock_status, 
                     image = '$image' 
                     WHERE book_id = $book_id";

    $update_result = myQuery($update_query);

    if ($update_result) {
        header('Location: Chapter4/admin-page/book-actions/bookManagement.php');
        exit;
    } else {
        die("A problem occured while updating.");
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        form input,
        form textarea,
        form select,
        form button {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form textarea {
            resize: vertical;
        }

        .submit-btn {
            margin-top: 20px;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .submit-btn:hover {
            background-color: #45a049;
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
        <h2>Update Book</h2>
        <form action="updateBook.php?book_id=<?= $book_to_update['book_id'] ?>" method="POST" enctype="multipart/form-data">
            <label for="bookName">Book Name:</label>
            <input type="text" id="bookName" name="bookName" value="<?= htmlspecialchars($book_to_update['bookName']) ?>" required>

            <label for="description">Decription:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($book_to_update['description']) ?></textarea>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($book_to_update['author']) ?>" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($book_to_update['price']) ?>" step="0.01" required>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($book_to_update['category']) ?>" required>

            <label for="stock_status">Stock:</label>
            <input type="number" id="stock_status" name="stock_status" value="<?= htmlspecialchars($book_to_update['stock_status']) ?>" step="0.01" required>

            <label for="image">New Image:</label>
            <input type="file" id="image" name="image">

            <button type="submit" class="submit-btn">Update</button>
        </form>
        <a href="/Chapter4/admin-page/adminMainPage.html" class="go-to-dashboard">
            Back to the Admin Main Page
        </a>
    </div>
</body>

</html>