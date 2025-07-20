<?php
include('../config/config.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$query = "SELECT p.name, p.price, c.quantity, p.image, c.product_id 
          FROM cart c 
          JOIN products p ON c.product_id = p.product_id 
          WHERE c.user_id = '$user_id'";
$result = mysqli_query($conn, $query);

// Calculate total
$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $total += $row['price'] * $row['quantity'];
    $items[] = $row;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    if (count($items) > 0) {
        // Insert into orders
        $order_query = "INSERT INTO orders (user_id, total_amount, payment_method, status, order_date) 
                VALUES ('$user_id', '$total', '$payment_method', 'Pending', NOW())";

        if (mysqli_query($conn, $order_query)) {
            $order_id = mysqli_insert_id($conn);

            // Insert each cart item into order_items
            foreach ($items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                     VALUES ('$order_id', '$product_id', '$quantity', '$price')";
                mysqli_query($conn, $order_item_query);
            }

            // Clear cart
            mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

            // Redirect to order success page
            header('Location: order_success.php');
            exit();
        } else {
            echo "Error placing order: " . mysqli_error($conn);
        }
    } else {
        echo "Cart is empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - ANON</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- External CSS if any -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #87ceeb;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .checkout-container {
            width: 60%;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 16px;
            color: #555;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }
        .form-group input[type="radio"] {
            width: auto;
        }
        .payment-method {
            margin-bottom: 20px;
        }
        .order-summary {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }
        .order-summary h3 {
            margin-top: 0;
        }
        .order-summary p {
            font-size: 18px;
            color: #555;
        }
        .btn {
            display: inline-block;
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
            width: 100%;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<header>
    <h1>Checkout</h1>
</header>

<div class="checkout-container">
    <form method="POST" action="checkout.php">
        <h3>Shipping Details</h3>
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="address">Shipping Address:</label>
            <textarea id="address" name="address" required></textarea>
        </div>

        <div class="form-group">
            <label for="contact">Contact Number:</label>
            <input type="text" id="contact" name="contact" required>
        </div>

        <h3>Payment Method</h3>
        <div class="payment-method">
            <label>
                <input type="radio" name="payment_method" value="cod" checked> Cash on Delivery (COD)
            </label><br><br>
            <label>
                <input type="radio" name="payment_method" value="online_payment"> Online Payment (Dummy)
            </label>
        </div>

        <div class="order-summary">
            <h3>Order Summary</h3>
            <p>Total Amount: ₹ <?php echo $total; ?></p>
        </div>

        <button type="submit" class="btn">Place Order</button>
    </form>
</div>

</body>
</html>
