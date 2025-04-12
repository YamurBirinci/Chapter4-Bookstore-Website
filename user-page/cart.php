<?php

require_once("../connect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-signup/login.php');
    exit;
}

$userID = $_SESSION['user_id'];
$orderDate = date("Y-m-d");
$orderStatus = "Pending";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    $orderQuery = "INSERT INTO orders (user_id, order_date, order_status) VALUES ('$userID', '$orderDate', '$orderStatus')";
    $orderResult = myQuery($orderQuery);
    echo "<script>alert('Form submitted 2222!');</script>";


    if ($orderResult) {
        $clearBasketQuery = "DELETE FROM basket WHERE user_id = '$userID'";
        $clearResult = myQuery($clearBasketQuery);

        if ($clearResult) {
            echo "<script>alert('Order completed successfully!');</script>";
        } else {
            echo "<script>alert('Failed to clear the basket!');</script>";
        }
    } else {
        echo "<script>alert('Failed to complete the order!');</script>";
    }

    header('Location: Chapter4/main-page/mainPage.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-container {
            width: 400px;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .payment-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .payment-header h2 {
            font-size: 24px;
            color: #333333;
            margin: 0;
        }

        .payment-header p {
            font-size: 14px;
            color: #666666;
        }

        .payment-form label {
            font-size: 14px;
            font-weight: bold;
            color: black;
            margin-bottom: 5px;
            display: block;
        }

        .payment-form input {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 12px;
            font-size: 14px;
        }

        .payment-form .flex-row {
            display: flex;
            gap: 10px;
        }

        .payment-form .flex-row input {
            flex: 1;
        }

        .payment-form button {
            width: 100%;
            padding: 12px;
            background: #1BB66E;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .payment-form button:hover {
            background: #159d5c;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-top: -15px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <div class="payment-header">
            <h2>Payment Details</h2>
            <p>Securely enter your card information below.</p>
        </div>
        <form class="payment-form" id="paymentForm" method="POST">
            <label for="card_name">Cardholder Name</label>
            <input type="text" id="card_name" name="card_name" placeholder="Name Surname" required>
            <div class="error" id="nameError"></div>

            <label for="card_number">Card Number</label>
            <input type="text" id="card_number" name="card_number" placeholder="1234567890123456" required>
            <div class="error" id="cardNumberError"></div>

            <div class="flex-row">
                <div>
                    <label for="expiry_date">Expiry Date (MM/YY)</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                    <div class="error" id="expiryError"></div>
                </div>
                <div>
                    <label for="cvc">CVC</label>
                    <input type="text" id="cvc" name="cvc" placeholder="123" required>
                    <div class="error" id="cvcError"></div>
                </div>
            </div>
            <?php if (isset($message)): ?>
                <p class="message-text"><?= $message ?></p>
            <?php endif; ?>
            <button type="submit" name="pay">Pay</button>
        </form>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            let isValid = true;

            const name = document.getElementById('card_name').value.trim();
            if (name === "" || !/^[A-Za-z\s]+$/.test(name)) {
                isValid = false;
                document.getElementById('nameError').textContent = "Please enter a valid name.";
            } else {
                document.getElementById('nameError').textContent = "";
            }

            const cardNumber = document.getElementById('card_number').value.trim();
            if (!/^\d{16}$/.test(cardNumber)) {
                isValid = false;
                document.getElementById('cardNumberError').textContent = "Card number must be 16 digits.";
            } else {
                document.getElementById('cardNumberError').textContent = "";
            }

            const expiry = document.getElementById('expiry_date').value.trim();
            if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) {
                isValid = false;
                document.getElementById('expiryError').textContent = "Enter expiry in MM/YY format.";
            } else {
                document.getElementById('expiryError').textContent = "";
            }

            const cvc = document.getElementById('cvc').value.trim();
            if (!/^\d{3}$/.test(cvc)) {
                isValid = false;
                document.getElementById('cvcError').textContent = "CVC must be 3 digits.";
            } else {
                document.getElementById('cvcError').textContent = "";
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>