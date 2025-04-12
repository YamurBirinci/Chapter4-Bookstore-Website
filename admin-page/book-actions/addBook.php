<?php

require_once("connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookName = $_POST['bookName'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock_status = $_POST['stock_status'];
    $image = "";

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "Chapter4/bookimg/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            $message = "Only JPG, JPEG, PNG or GIF files allowed.";
        } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            $message = "Problem occured.";
        }
    }

    $checkBookQuery = "SELECT bookName FROM books WHERE bookName = '$bookName'";
    $checkBookResult = myQuery($checkBookQuery);

    if (mysqli_num_rows($checkBookResult) > 0) {
        $message = "This book alread exists!";
    } else {
        $insertQuery = "INSERT INTO books (bookName, description, author, price, category, stock_status, image) 
                        VALUES ('$bookName', '$description', '$author', '$price', '$category', '$stock_status', '$image')";
        $insertResult = myQuery($insertQuery);
        if ($insertResult) {
            $message = "Book added eklendi!";
        } else {
            $message = "Problem occured. Try again..";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Ekleme</title>
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

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form input,
        form textarea,
        form select,
        form button {
            padding: 10px;
            font-size: 14px;
        }

        form textarea {
            resize: vertical;
        }

        .submit-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        <h2>Add New Book</h2>
        <form action="addBook.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="bookName">Book Name</label>
                <input type="text" id="bookName" name="bookName" required>
            </div>
            <div class="input-group">
                <label for="description">Decription</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="input-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" required>
            </div>
            <div class="input-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="input-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="Science Fiction">Science Fiction</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Classic">Classic</option>
                    <option value="Mystery Thriller">Mystery / Thriller</option>
                </select>
            </div>
            <div class="input-group">
                <label for="stock_status">Stock Status</label>
                <input type="number" id="stock_status" name="stock_status" step="0.01" required>
            </div>

            <label for="image">New Image:</label>
            <input type="file" id="image" name="image">

            <?php if (isset($message)): ?>
                <p class="message-text"><?= $message ?></p>
            <?php endif; ?>
            <button type="submit" class="signin-button">Add Book</button>
        </form>
        <a href="/Chapter4/admin-page/adminMainPage.html" class="go-to-dashboard">
            Back to the Admin Main Page
        </a>
    </div>
</body>

</html>