<?php
include('../config/config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php');
    exit();
}

$order_id = $_GET['order_id'];  // Get the order_id from the URL
$user_id = $_SESSION['user_id'];

// Fetch the order details
$orderQuery = "SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = '$user_id'";
$orderResult = mysqli_query($conn, $orderQuery);
$order = mysqli_fetch_assoc($orderResult);

if (!$order) {
    echo "Order not found!";
    exit();
}

// Fetch ordered items
$orderItemsQuery = "SELECT oi.quantity, p.name, p.image, p.price FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = '$order_id'";
$orderItemsResult = mysqli_query($conn, $orderItemsQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details - ANON</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .order-details-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .order-summary, .order-items {
            margin-top: 20px;
        }

        .order-summary p, .order-items p {
            margin: 8px 0;
            font-size: 16px;
            color: #555;
        }

        .order-summary strong, .order-items strong {
            color: #333;
        }

        .order-status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status-pending {
            background-color: #f39c12;
            color: white;
        }

        .status-completed {
            background-color: #27ae60;
            color: white;
        }

        .order-item {
            display: flex;
            align-items: center;
            margin-top: 10px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 15px;
        }

        .order-item p {
            margin: 0;
            color: #333;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background: #555;
        }
    </style>
</head>
<body>

    <div class="order-details-container">
        <h1>Order Details</h1>
        <div class="order-summary">
    <p><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></p>

    <!-- Check if total_amount exists -->
    <p><strong>Total Amount:</strong> ₹<?php echo isset($order['total_amount']) ? number_format($order['total_amount'], 2) : 'N/A'; ?></p>

    <!-- Payment Method - check if empty or NULL -->
    <p><strong>Payment Method:</strong> 
        <?php 
            // If payment_method is empty or NULL, show 'Not Available'
            echo !empty($order['payment_method']) ? ucfirst($order['payment_method']) : 'Not Available'; 
        ?>
    </p>

    <p><strong>Status:</strong> 
        <span class="order-status <?php echo $order['status'] == 'Completed' ? 'status-completed' : 'status-pending'; ?>">
            <?php echo ucfirst($order['status']); ?>
        </span>
    </p>
    <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
</div>

        <div class="order-items">
            <h2>Order Items</h2>
            <?php while ($item = mysqli_fetch_assoc($orderItemsResult)): ?>
                <div class="order-item">
                    <img src="../uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <p><strong>Product Name:</strong> <?php echo $item['name']; ?></p>
                    <p><strong>Quantity:</strong> <?php echo $item['quantity']; ?></p>
                    <p><strong>Price:</strong> ₹<?php echo number_format($item['price'], 2); ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <a href="my_orders.php" class="back-btn">Back to My Orders</a>
    </div>

</body>
</html>
