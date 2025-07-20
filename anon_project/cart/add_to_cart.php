<?php
include('../config/config.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php'); // <<< Corrected your login path
    exit();
}

if (isset($_GET['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_GET['product_id'];

    if (is_numeric($product_id)) {
        $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");

        if (mysqli_num_rows($check_cart) > 0) {
            $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id='$user_id' AND product_id='$product_id'";
            if (mysqli_query($conn, $update_query)) {
                header('Location: view_cart.php');
                exit();
            } else {
                echo "Error updating cart: " . mysqli_error($conn);
            }
        } else {
            $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)";
            if (mysqli_query($conn, $insert_query)) {
                header('Location: view_cart.php');
                exit();
            } else {
                echo "Error adding to cart: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Invalid product ID!";
    }
} else {
    echo "No product ID specified!";
}
?>
