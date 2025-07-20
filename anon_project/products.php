<?php
include('config/config.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - ANON</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>Our Products</h1>
</header>

<div class="products-container">
    <?php
    $products = mysqli_query($conn, "SELECT * FROM products");

    if (mysqli_num_rows($products) > 0) {
        while ($row = mysqli_fetch_assoc($products)) {
            echo "<div class='product-card'>
                    <img src='uploads/{$row['image']}' alt='{$row['name']}' height='150'>
                    <h3>{$row['name']}</h3>
                    <p>₹ {$row['price']}</p>
                    <a href='cart/add_to_cart.php?product_id={$row['product_id']}' class='add-to-cart-btn'>Add to Cart</a>
                  </div>";
        }
    } else {
        echo "<p>No products found!</p>";
    }
    ?>
</div>

</body>
</html>
