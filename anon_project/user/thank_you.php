<?php
session_start();
include('config/config.php'); // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to see the confirmation.";
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Get the last order placed by the user
$query = "SELECT o.order_id, o.total_amount, o.payment_method, o.status, u.name AS user_name 
          FROM orders o 
          JOIN users u ON o.user_id = u.user_id 
          WHERE o.user_id = ? ORDER BY o.order_id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    ?>

    <h2>Thank you for your order, <?php echo $order['user_name']; ?>!</h2>
    <p>Your order ID is: <?php echo $order['order_id']; ?></p>
    <p>Total amount: ₹<?php echo number_format($order['total_amount'], 2); ?></p>
    <p>Payment method: <?php echo $order['payment_method']; ?></p>
    <p>Status: <?php echo $order['status']; ?></p>

    <h3>Order Items:</h3>
    <ul>
    <?php
    // Get the items in the order
    $query = "SELECT oi.quantity, p.name, oi.price 
              FROM order_items oi 
              JOIN products p ON oi.product_id = p.product_id 
              WHERE oi.order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order['order_id']);
    $stmt->execute();
    $items_result = $stmt->get_result();

    while ($item = $items_result->fetch_assoc()) {
        echo "<li>" . $item['quantity'] . " x " . $item['name'] . " at ₹" . number_format($item['price'], 2) . "</li>";
    }
    ?>
    </ul>

    <p>You will receive a confirmation email shortly.</p>

    <?php
} else {
    echo "No recent orders found.";
}

$stmt->close();
$conn->close();
?>
