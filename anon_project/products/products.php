<?php
include('../config/config.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - ANON</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <h1 style="text-align: center; font-family: 'Century Gothic'; color: #87ceeb;">ANON PRODUCTS</h1>
</header>

<div class="products-container">
    <h2>Browse Categories</h2>
    <div class="categories">
        <a href="products.php">Browse All</a>
        <a href="products.php?cat=electronics">Electronics</a>
        <a href="products.php?cat=clothing">Clothing</a>
        <a href="products.php?cat=home">Home & Living</a>
        <a href="products.php?cat=beauty">Beauty</a>
        <a href="products.php?cat=sports">Sports</a>
    </div>

    <div class="product-list">
        <?php
        $category = isset($_GET['cat']) ? $_GET['cat'] : '';
        $query = "SELECT * FROM products" . ($category ? " WHERE category=?" : "");

        if ($stmt = mysqli_prepare($conn, $query)) {
            if ($category) {
                mysqli_stmt_bind_param($stmt, "s", $category);
            }

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product-card'>
                            <img src='../uploads/{$row['image']}' alt='{$row['name']}' height='150'>
                            <h3>{$row['name']}</h3>
                            <p>{$row['description']}</p>
                            <p>₹ {$row['price']}</p>
                            <a href='../cart/add_to_cart.php?product_id={$row['product_id']}' class='add-to-cart-btn'>Add to Cart</a>
                          </div>";
                }
            } else {
                echo "<p>No products found in this category.</p>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing query: " . mysqli_error($conn);
        }
        ?>
    </div>
</div>

</body>
</html>
