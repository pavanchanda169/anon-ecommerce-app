<?php
include('../config/config.php');
session_start();

// Check if admin is logged in (optional, but good practice)
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }

// Validate order_id
if (!isset($_GET['order_id'])) {
    echo "Invalid order.";
    exit();
}

$order_id = intval($_GET['order_id']); // Always secure IDs

// Fetch order details
$order_query = "SELECT * FROM orders WHERE order_id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    echo "Order not found.";
    exit();
}

// Fetch order items
$order_items_query = "SELECT oi.quantity, oi.price, p.name, p.image 
                      FROM order_items oi 
                      JOIN products p ON oi.product_id = p.product_id 
                      WHERE oi.order_id = '$order_id'";
$order_items_result = mysqli_query($conn, $order_items_query);

// Handle update (when admin changes status)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $update_query = "UPDATE orders SET status = '$new_status' WHERE order_id = '$order_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: order_details.php?order_id=$order_id&success=1");
        exit();
    } else {
        echo "Failed to update status: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Order Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 20px;
        }
        .order-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .order-details, .order-items {
            margin-top: 20px;
        }
        .order-item {
            display: flex;
            align-items: center;
            margin-top: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 15px;
        }
        .order-item-details {
            flex: 1;
        }
        .form-group {
            margin-top: 20px;
        }
        label {
            font-weight: bold;
        }
        select {
            padding: 8px;
            width: 100%;
            font-size: 16px;
        }
        .btn-update {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-update:hover {
            background-color: #0056b3;
        }
        .success {
            text-align: center;
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="order-container">
    <h1>Order #<?php echo $order['order_id']; ?></h1>

    <?php if (isset($_GET['success'])) { echo "<p class='success'>Order status updated successfully!</p>"; } ?>

    <div class="order-details">
        <h2>Order Summary</h2>
        <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
        <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
        <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
    </div>

    <div class="order-items">
        <h2>Order Items</h2>
        <?php while ($item = mysqli_fetch_assoc($order_items_result)) { ?>
            <div class="order-item">
                <img src="../uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                <div class="order-item-details">
                    <p><strong>Product:</strong> <?php echo $item['name']; ?></p>
                    <p><strong>Quantity:</strong> <?php echo $item['quantity']; ?></p>
                    <p><strong>Price:</strong> ₹<?php echo number_format($item['price'], 2); ?></p>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="form-group">
        <h2>Update Order Status</h2>
        <form method="POST">
            <label for="status">Change Status:</label>
            <select name="status" id="status" required>
                <option value="">-- Select Status --</option>
                <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>

            <button type="submit" class="btn-update">Update Status</button>
        </form>
    </div>

</div>

</body>
</html>
