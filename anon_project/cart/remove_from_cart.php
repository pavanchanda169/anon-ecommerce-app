<?php
include('../config/config.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php');
    exit();
}

if (isset($_GET['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_GET['product_id'];

    // Delete the item from the cart
    $delete_query = "DELETE FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    mysqli_query($conn, $delete_query);

    // Redirect back to cart
    header('Location: view_cart.php');
    exit();
} else {
    echo "Invalid request!";
}
?>
