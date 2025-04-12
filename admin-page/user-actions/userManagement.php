<?php

require_once("connect.php");
session_start();

$users = [];
$query = "SELECT * FROM users";
$result = myQuery($query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
} else {
    die("A problem occured: " . mysqli_error($conn));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user'])) {
        $user_id = intval($_POST['delete_user']);

        $delete_query = "DELETE FROM users WHERE user_id = $user_id";
        $delete_result = myQuery($delete_query);

        if ($delete_result) {
            header('Location: Chapter4/admin-page/user-actions/userManagement.php');
            exit;
        } else {
            echo "Problem occured: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
        <h1>User Management</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['firstName']) . " " . htmlspecialchars($user['lastName']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <button type="submit" name="delete_user" value="<?= $user['user_id'] ?>">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="/Chapter4/admin-page/adminMainPage.html" class="go-to-dashboard">
            Back to the Admin Main Page
        </a>
    </div>
</body>

</html>