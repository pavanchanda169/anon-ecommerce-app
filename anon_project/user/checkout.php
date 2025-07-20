<?php
session_start();
include('config/config.php'); // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to checkout.";
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Get the cart items
$query = "SELECT c.cart_id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Display cart items
if ($result->num_rows > 0) {
    echo "<h3>Your Cart</h3>";
    echo "<table>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $product_name = $row['name'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $total_price = $price * $quantity;
        $total += $total_price;

        echo "<tr><td>$product_name</td><td>$price</td><td>$quantity</td><td>$total_price</td></tr>";
    }
    echo "<tr><td colspan='3'>Total:</td><td>$total</td></tr>";
    echo "</table>";

    // Checkout form
    echo "<form action='process_checkout.php' method='POST'>
            <label for='payment_method'>Select Payment Method:</label>
            <select name='payment_method' id='payment_method'>
                <option value='COD'>Cash on Delivery (COD)</option>
                <!-- Add more payment options here -->
            </select>
            <button type='submit'>Confirm Order</button>
          </form>";
} else {
    echo "Your cart is empty.";
}
?>
