<?php
include('../config/config.php');
session_start();

// Optional: You can add admin login session check here if needed

// Fetch all orders
$query = "SELECT * FROM orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- optional -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 20px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #333;
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .action-btn {
            padding: 6px 12px;
            background: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .action-btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<h1>📦 Manage Orders</h1>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Amount</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>₹" . number_format($row['total_amount'], 2) . "</td>";
                echo "<td>" . ucfirst($row['payment_method']) . "</td>";
                echo "<td>" . ucfirst($row['status']) . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td><a href='order_details.php?order_id=" . $row['order_id'] . "' class='action-btn'>View / Update</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No orders found</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
