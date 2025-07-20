<?php
$host = 'localhost'; // Correct MySQL host
$user = 'root';      // Default username in XAMPP
$password = '';      // Default password in XAMPP (empty by default)
$dbname = 'anon_ecommerce'; // Ensure this matches exactly with the name in phpMyAdmin

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database.";
}
?>
