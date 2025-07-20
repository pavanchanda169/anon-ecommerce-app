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
        $contact = $user['phone'] ?? 'Not Available'; // use 'phone' not 'contact'
        $avatar = !empty($user['avatar']) ? $user['avatar'] : 'default.png'; // fallback
    } else {
        $error = "User not found.";
    }
} else {
    $error = "Error fetching user data. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - ANON</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            width: 50%;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .profile-header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }
        h2 {
            margin: 0;
            color: #333;
        }
        .profile-info {
            margin: 15px 0;
            font-size: 18px;
        }
        .profile-info span {
            font-weight: bold;
        }
        a.edit-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
        }
        a.edit-link:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <img src="../uploads/<?php echo htmlspecialchars($avatar); ?>" alt="Avatar">
        <h2><?php echo htmlspecialchars($name); ?>'s Profile</h2>
    </div>

    <?php if (isset($error)) echo '<p class="error">' . $error . '</p>'; ?>

    <div class="profile-info">
        <span>Email:</span> <?php echo htmlspecialchars($email); ?>
    </div>
    <div class="profile-info">
        <span>Phone:</span> <?php echo htmlspecialchars($contact); ?>
    </div>

    <a href="edit_profile.php" class="edit-link">Edit Profile</a>
</div>

</body>
</html>
