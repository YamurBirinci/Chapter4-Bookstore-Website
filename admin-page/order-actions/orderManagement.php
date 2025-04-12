<?php

require_once("connect.php");
session_start();

$orders = [];
$query = "SELECT * FROM orders";
$result = myQuery($query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
} else {
    die("Siparişler alınırken bir hata oluştu.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $id = intval($_POST['order_id']);
        $new_status = $_POST['order_status'];

        $update_query = "UPDATE orders SET order_status = '$new_status' WHERE order_id = $id";
        $update_result = myQuery($update_query);

        if (!$update_result) {
            die("A problem occurder while updating the order status.");
        }

        header('Location: Chapter4/admin-page/order-actions/orderManagement.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
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
        <h1>Order Management</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['user_id']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td><?= htmlspecialchars($order['order_status']) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                <select name="order_status">
                                    <option value="Completed" <?= $order['order_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="Pending" <?= $order['order_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Cancelled" <?= $order['order_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status">Update Status</button>
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