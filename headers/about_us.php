<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }

        .icons img {
            width: 24px;
            height: 24px;
        }

        .company-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            background: #F2EFE8;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 36px;
            color: #3b3b3b;
            font-weight: bold;
        }

        h2 {
            font-size: 24px;
            color: #3b3b3b;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
        }

        .company-page>div {
            margin-bottom: 40px;
        }

        .company-page div:last-child {
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3b3b3b;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: #f9f9f9;
        }

        a:hover {
            text-decoration: underline;
        }

        .header-buttons {
            border: 1px solid #b6a293;
            border-radius: 5px;
            cursor: pointer;
        }

        .return-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #EF6B4A;
            color: black;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .return-button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
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

    <div class="company-page">
        <header>
            <h1>About Chapter4</h1>
        </header>
        <div>
            <div>
                <h2>Who we are</h2>
                <p>
                    Chapter4 is a comprehensive online bookstore created by four girls who share a deep love for books. Our mission is to make finding and purchasing books easy and accessible for everyone.
                </p>
            </div>
            <div>
                <h2>How it was started</h2>
                <p>
                    In 2024, we started this project Chapter4 as our academic project, but we decided to make it available to the public so people can interact with each other to share books. After this thought, we redesigned many parts and features of the website to make it more user-friendly.
                </p>
            </div>
            <div>
                <h2>Our Team</h2>
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                    </tr>
                    <tr>
                        <td>Yağmur Fatma Birinci</td>
                        <td>CEO & Co-founder</td>
                    </tr>
                    <tr>
                        <td>Öykü Sucuoğlu</td>
                        <td>Designer & Enginner</td>
                    </tr>
                    <tr>
                        <td>Helin Arslan</td>
                        <td>Designer & Enginner</td>
                    </tr>
                    <tr>
                        <td>Mariya Elena Aygül</td>
                        <td>Designer & Enginner</td>
                    </tr>
                </table>
            </div>
            <div class="return-button-container">
                <button class="return-button" onclick="window.location.href='../main-page/mainPage.php';">Back To Home</button>
            </div>
        </div>
    </div>
</body>

</html>