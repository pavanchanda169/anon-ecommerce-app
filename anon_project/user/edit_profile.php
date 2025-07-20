<?php
include('../config/config.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        $name = $user['name'] ?? 'N/A';
        $email = $user['email'] ?? 'N/A';
        $phone = $user['phone'] ?? 'Not Available'; // Updated to 'phone' instead of 'contact'
    } else {
        // If no user is found, show an error
        $error = "User not found.";
    }
} else {
    // If the query fails
    $error = "Error fetching user data. Please try again.";
}

// Handle form submission
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']); // Updated to 'phone' instead of 'contact'

    // Update query
    $update_query = "UPDATE users SET name = '$name', phone = '$phone' WHERE user_id = '$user_id'"; // Updated 'contact' to 'phone'

    if (mysqli_query($conn, $update_query)) {
        // Redirect back to profile page after successful update
        header('Location: profile.php');
        exit();
    } else {
        $error = "Failed to update profile. Try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - ANON</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .edit-container {
            width: 50%;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        input[type="text"], input[type="email"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
        }
        a.back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>Edit Profile</h2>
    
    <?php if (isset($error)) { echo '<p class="error">'.$error.'</p>'; } ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($name); ?>" required>
        <input type="text" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone); ?>"> <!-- Updated 'contact' to 'phone' -->
        <button type="submit" name="update">Update Profile</button>
    </form>

    <a href="profile.php" class="back-link">← Back to Profile</a>
</div>

</body>
</html>
