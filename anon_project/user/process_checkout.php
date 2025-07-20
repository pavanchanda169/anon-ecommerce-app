<?php
session_start();
include('../config/config.php'); // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to complete your order.";
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$payment_method = $_POST['payment_method']; // Get the payment method from the form

// Get the cart items
$query = "SELECT c.cart_id, c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total order amount
$total = 0;
$order_items = [];
while ($row = $result->fetch_assoc()) {
    $total += $row['price'] * $row['quantity'];
    $order_items[] = $row;
}

// Insert the order into the 'orders' table
$query = "INSERT INTO orders (user_id, total_amount, payment_method, status) VALUES (?, ?, ?, 'Pending')";
$stmt = $conn->prepare($query);
$stmt->bind_param("ids", $user_id, $total, $payment_method);
$stmt->execute();
$order_id = $stmt->insert_id; // Get the newly created order ID

// Insert each item into the 'order_items' table
foreach ($order_items as $item) {
    $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Clear the cart after successful checkout
$query = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Redirect to the thank you page
header("Location: thank_you.php?order_id=$order_id");
exit();
?>
