<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php');
    exit();
}

// Optional: Fetch order details using $_GET['order_id']
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Successful - ANON</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f4f4f4;
        }
        .success-message {
            background-color: #d4edda;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
        }
        h1 {
            color: #28a745;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="success-message">
    <h1>Thank You for Your Order!</h1>
    <p>Your order has been placed successfully.</p>
    <a href="../index.php">Continue Shopping</a>
</div>

</body>
</html>
