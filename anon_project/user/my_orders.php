<?php
include('../config/config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders
$query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - ANON</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Your styles here */
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        .order-container {
            max-width: 800px;
            margin: auto;
        }
        .order-card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .order-card h3 {
            margin-top: 0;
            color: #333;
        }
        .order-info {
            margin: 10px 0;
            color: #555;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-completed {
            color: green;
            font-weight: bold;
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
        .order-item {
            display: flex;
            align-items: center;
            margin-top: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .order-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 15px;
        }
        .order-item p {
            margin: 0;
            color: #333;
        }
        .order-item .product-name {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="order-container">
    <h1 style="text-align:center;">🛒 My Orders</h1>
    
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='order-card'>";
            echo "<h3>Order ID: #" . $row['order_id'] . "</h3>";
            
            // Total amount
            if (isset($row['total_amount'])) {
                echo "<p class='order-info'><strong>Total Amount:</strong> ₹" . number_format($row['total_amount'], 2) . "</p>";
            } else {
                echo "<p class='order-info'><strong>Total Amount:</strong> ₹0</p>";
            }

            // Payment Method (COD or Online)
            $paymentMethod = !empty($row['payment_method']) ? ucfirst($row['payment_method']) : "Not Available";
            echo "<p class='order-info'><strong>Payment Method:</strong> $paymentMethod</p>";

            // Status
            $status = ucfirst($row['status']);
            $statusClass = ($status == 'Completed') ? 'status-completed' : 'status-pending';
            echo "<p class='order-info'><strong>Status:</strong> <span class='$statusClass'>$status</span></p>";

            // Order Date
            echo "<p class='order-info'><strong>Order Date:</strong> " . $row['order_date'] . "</p>";
            
            // Fetching ordered items and showing product images
            $order_id = $row['order_id'];
            $orderItemsQuery = "SELECT oi.quantity, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = '$order_id'";
            $orderItemsResult = mysqli_query($conn, $orderItemsQuery);

            echo "<div class='order-items'>";
            while ($item = mysqli_fetch_assoc($orderItemsResult)) {
                echo "<div class='order-item'>";
                // Adjusted image path to point to the correct directory
                echo "<img src='../uploads/" . $item['image'] . "' alt='" . $item['name'] . "'>";
                echo "<p class='product-name'>" . $item['name'] . " (x" . $item['quantity'] . ")</p>";
                echo "</div>";
            }
            echo "</div>";
            
            // View Order button for each order
            echo "<div style='text-align:center;'>";
            echo "<a href='order_details.php?order_id=" . $row['order_id'] . "'>View Order</a>";
            echo "</div>";

            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>No orders found.</p>";
    }
    ?>
    
    <div style="text-align:center;">
        <a href="../index.php" class="back-btn">← Back to Home</a>
    </div>
</div>

</body>
</html>
