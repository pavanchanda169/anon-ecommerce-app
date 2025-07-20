<?php
include('../config/config.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the items in the cart
$query = "SELECT p.name, p.price, c.quantity, p.image, c.product_id 
          FROM cart c 
          JOIN products p ON c.product_id = p.product_id 
          WHERE c.user_id = '$user_id'";

$result = mysqli_query($conn, $query);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Century Gothic', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #87ceeb;
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
        }
        .cart-container {
            width: 80%;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .cart-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 8px;
        }
        .cart-item-details {
            flex: 1;
        }
        .cart-item-details h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .cart-item-details p {
            margin: 5px 0;
            color: #666;
        }
        .total-price {
            text-align: right;
            margin-top: 20px;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }
        .checkout-btn {
            display: inline-block;
            padding: 12px 25px;
            margin-top: 20px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        .checkout-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<header>
    Your Shopping Cart
</header>

<div class="cart-container">
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $total += $row['price'] * $row['quantity'];
            echo "<div class='cart-item'>
                    <img src='../uploads/{$row['image']}' alt='{$row['name']}' height='100'>
                    <div class='item-details'>
                        <h3>{$row['name']}</h3>
                        <p>Price: ₹ {$row['price']}</p>
                        <p>Quantity: {$row['quantity']}</p>
                        <a href='remove_from_cart.php?product_id={$row['product_id']}' class='remove-btn'>🗑️ Remove</a>
                    </div>
                  </div>";
        }
    } else {
        echo "<p>Your cart is empty!</p>";
    }
    ?>

    <div class="total-price">
        <h3>Total: ₹ <?php echo $total; ?></h3>
    </div>

    <?php
    if (mysqli_num_rows($result) > 0) {  // Only show checkout if cart is not empty
        echo "<a href='../checkout/checkout.php' class='checkout-btn'>Proceed to Checkout</a>";
    }
    ?>
</div>

</body>
</html>
