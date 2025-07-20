<?php
include('config/config.php');
session_start();

// Default values
$avatar = "uploads\user1.png";
$name = "Profile";

// If user is logged in, fetch their avatar and name
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name, avatar FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $avatar = !empty($user['avatar']) ? $user['avatar'] : "default.png";
        $name = $user['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ANON - Your Shopping Haven</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Century+Gothic&display=swap" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<header style="background-color: #87ceeb; padding: 20px; display: flex; align-items: center; justify-content: space-between;">

    <!-- Profile Avatar & Name -->
    <div style="display: flex; align-items: center;">
        <a href="user/profile.php" style="text-decoration: none; display: flex; align-items: center;">
            <img src="uploads/<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" style="width:10px; height:40px; border-radius:50%; object-fit:cover;">
            <span style="margin-left: 10px; font-weight: bold; color: #000;"><?php echo htmlspecialchars($name); ?></span>
        </a>
    </div>

    <!-- Search Bar -->
    <div style="flex-grow: 1; margin: 0 20px;">
        <form action="products.php" method="GET" style="display: flex;">
            <input type="text" name="search" id="searchInput" placeholder="Search products..." style="flex:1; padding:10px; font-size:16px; border-radius:5px 0 0 5px; border:1px solid #ccc;">
            <button type="button" onclick="startVoice()" style="background-color:#fff; border:1px solid #ccc; padding: 0 10px; border-left: none; cursor:pointer;">🎤</button>
            <button type="submit" style="background-color: #4caf50; color:white; padding:10px 20px; border:none; border-radius:0 5px 5px 0; cursor:pointer;">Search</button>
        </form>
    </div>

    <!-- Right Side -->
    <div>
        <a href="cart/view_cart.php" style="color:white; text-decoration:none; font-size:18px;">🛒</a>
    </div>
</header>

<!-- NAVBAR -->
<nav style="background-color: #4caf50; padding: 10px 20px; display: flex; gap: 20px; align-items: center;">
    <div style="font-size: 24px; font-weight: bold; color: white; font-family: 'Century Gothic', sans-serif;">ANON</div>
    <a href="index.php" style="color: white; text-decoration: none; font-size: 16px;">Home</a>
    <a href="products/products.php" style="color: white; text-decoration: none; font-size: 16px;">Products</a>
    <a href="user/login.php" style="color: white; text-decoration: none; font-size: 16px;">Login</a>
    <a href="user/register.php" style="color: white; text-decoration: none; font-size: 16px;">Register</a>
    <a href="cart/view_cart.php" style="color: white; text-decoration: none; font-size: 16px;">Cart</a>
    <a href="user/my_orders.php" style="color: white; text-decoration: none; font-size: 16px;">My Orders</a>
</nav><!-- BANNERS SECTION (Text-based carousel) -->
<div id="textBanner" style="background-color: #ffeb3b; padding: 12px; text-align: center; font-weight: bold; font-size: 18px;">
    <span id="bannerText">⚡ Big Summer Sale — Up to 70% OFF</span>
</div>

<script>
// Rotating banner messages
const banners = [
    "⚡ Big Summer Sale — Up to 70% OFF",
    "🚚 Free Delivery on orders above ₹499",
    "🎁 New Arrivals are Live Now!",
    "💳 Pay with UPI & get 10% cashback"
];

let currentBanner = 0;
setInterval(() => {
    currentBanner = (currentBanner + 1) % banners.length;
    document.getElementById('bannerText').textContent = banners[currentBanner];
}, 4000); // Changes every 4 seconds
<script>
function toggleChat() {
    const chat = document.getElementById("chatBotContainer");
    chat.style.display = chat.style.display === "none" ? "block" : "none";
}

function sendMessage() {
    const input = document.getElementById("userMsg");
    const msg = input.value.trim();
    if (!msg) return;

    const chatBox = document.getElementById("chatBox");
    chatBox.innerHTML += `<div style="text-align:right; margin-bottom:5px;"><b>You:</b> ${msg}</div>`;
    input.value = "";

    setTimeout(() => {
        const reply = getBotReply(msg);
        chatBox.innerHTML += `<div style="text-align:left; margin-bottom:10px;"><b>Bot:</b> ${reply}</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
    }, 500);
}

function getBotReply(msg) {
    msg = msg.toLowerCase();
    if (msg.includes("order")) return "You can view your orders in the 'My Orders' section.";
    if (msg.includes("product")) return "Explore our 'Products' page for everything!";
    if (msg.includes("offers")) return "Check out Spin & Win or Timer-based Offers for exciting deals!";
    if (msg.includes("hello") || msg.includes("hi")) return "Hello! How can I assist you today?";
    return "Sorry, I didn’t get that. Try asking about orders, products, or offers.";
}
</script>

</script>


<!-- HERO SECTION -->
<section class="hero">
    <h1>Welcome to ANON!</h1>
    <p>Shop Everything You Love ❤️</p>
</section>
<div style="text-align: center; margin-top: 20px;">
    <button onclick="openSpinModal()" style="padding: 12px 25px; font-size: 16px; background-color: #ff9800; color: white; border: none; border-radius: 8px; cursor: pointer;">
        🎡 Spin & Win
    </button>
</div>
<!-- Chatbot Button -->
<div style="text-align:center; margin: 30px 0;">
    <button onclick="openChatbotModal()" style="padding: 12px 25px; background-color: #4caf50; color: white; font-size: 16px; border: none; border-radius: 8px; cursor: pointer;">
        🤖 Chat with ANON Assistant
    </button>
</div>

<!-- CATEGORIES -->
<section class="categories">
    <h2>Categories Available</h2>
    <div class="category-list">
        <div class="category-item">Electronics</div>
        <div class="category-item">Fashion</div>
        <div class="category-item">Home & Living</div>
        <div class="category-item">Beauty & Health</div>
        <div class="category-item">Toys & Games</div>
        <div class="category-item">Books</div>
        <div class="category-item">Sports</div>
        <div class="category-item">Gadgets</div>
        <div class="category-item">Automobiles</div>
    </div>
</section>
<div class="product-card">
    <img src="assets/images/product1.jpg" alt="Voltas AC">
    <h3>VOltas AIR 44t</h3>
    <p>₹55999</p>
</div>
<div class="product-card">
    <img src="assets/images/product2.jpg" alt="Air cooler">
    <h3>KUHL Air cooler</h3>
    <p>₹5999</p>
</div>


<!-- FOOTER -->
<footer>
    <p>© 2025 ANON. All rights reserved.</p>
</footer>
<!-- SPIN WHEEL MODAL -->
<div id="spinModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.7); z-index:1000; justify-content:center; align-items:center;">
    <div style="background:white; padding:30px; border-radius:15px; text-align:center; width:300px; position:relative;">
        <h2>🎉 Spin & Win!</h2>
        <canvas id="wheelCanvas" width="250" height="250" style="margin: 20px 0;"></canvas>
        <button onclick="spinWheel()" style="padding:10px 20px; background-color:#4caf50; color:white; border:none; border-radius:5px; cursor:pointer;">Spin</button>
        <button onclick="closeSpinModal()" style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:18px; cursor:pointer;">✖</button>
        <p id="spinResult" style="margin-top:15px; font-weight:bold;"></p>
    </div>
</div>
<script>
const wheelPrizes = ["10% OFF", "Free Shipping", "₹100 Cashback", "No Luck! 😢", "Buy 1 Get 1", "15% OFF"];
let spinning = false;

function drawWheel() {
    const canvas = document.getElementById("wheelCanvas");
    const ctx = canvas.getContext("2d");
    const numSegments = wheelPrizes.length;
    const angle = (2 * Math.PI) / numSegments;

    for (let i = 0; i < numSegments; i++) {
        ctx.beginPath();
        ctx.fillStyle = i % 2 === 0 ? "#4caf50" : "#8bc34a";
        ctx.moveTo(125, 125);
        ctx.arc(125, 125, 120, i * angle, (i + 1) * angle);
        ctx.fill();
        ctx.fillStyle = "white";
        ctx.font = "14px sans-serif";
        ctx.textAlign = "center";
        ctx.save();
        ctx.translate(125, 125);
        ctx.rotate(i * angle + angle / 2);
        ctx.fillText(wheelPrizes[i], 60, 5);
        ctx.restore();
    }
}

function spinWheel() {
    if (spinning) return;
    spinning = true;

    const prizeIndex = Math.floor(Math.random() * wheelPrizes.length);
    const anglePerPrize = 360 / wheelPrizes.length;
    const rotation = 360 * 5 + (prizeIndex * anglePerPrize);

    const canvas = document.getElementById("wheelCanvas");
    canvas.style.transition = "transform 4s ease-out";
    canvas.style.transform = `rotate(${rotation}deg)`;

    setTimeout(() => {
        document.getElementById("spinResult").textContent = `🎁 You won: ${wheelPrizes[prizeIndex]}!`;
        spinning = false;
    }, 4000);
}

function openSpinModal() {
    document.getElementById("spinModal").style.display = "flex";
    drawWheel();
}

function closeSpinModal() {
    document.getElementById("spinModal").style.display = "none";
}

// Auto open after 5 seconds
window.addEventListener("load", () => {
    setTimeout(openSpinModal, 5000);
});
</script>
<!-- CHATBOT FLOATING BUTTON -->
<div id="chatIcon" onclick="toggleChat()" style="position:fixed; bottom:30px; right:30px; background:#4caf50; color:white; padding:15px; border-radius:50%; cursor:pointer; font-size:20px; z-index:999;">💬</div>

<!-- CHATBOT CONTAINER -->
<div id="chatBotContainer" style="display:none; position:fixed; bottom:90px; right:30px; width:300px; background:white; border:1px solid #ccc; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.2); z-index:999; overflow:hidden;">
    <div style="background:#4caf50; color:white; padding:10px; border-radius:10px 10px 0 0;">🤖 ANON Assistant</div>
    <div id="chatBox" style="height:250px; padding:10px; overflow-y:auto; font-size:14px;"></div>
    <div style="display:flex;">
        <input type="text" id="userMsg" placeholder="Ask me..." style="flex:1; padding:10px; border:none; border-top:1px solid #ccc;">
        <button onclick="sendMessage()" style="padding:10px; border:none; background:#4caf50; color:white;">➤</button>
    </div>
</div>
<!-- Chatbot Modal -->
<div id="chatbotModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:999;">
    <div style="background:white; width:350px; max-height:90vh; margin:60px auto; border-radius:10px; overflow:hidden; display:flex; flex-direction:column;">
        <div style="background:#4caf50; color:white; padding:12px; font-size:18px; display:flex; justify-content:space-between; align-items:center;">
            <span>ANON Assistant 🤖</span>
            <button onclick="closeChatbotModal()" style="background:none; border:none; color:white; font-size:20px;">×</button>
        </div>
        <div id="chatBotBox" style="flex:1; padding:10px; overflow-y:auto; font-size:14px;"></div>
        <div style="display:flex; border-top:1px solid #ccc;">
            <input type="text" id="chatUserInput" placeholder="Ask me anything..." style="flex:1; padding:10px; border:none;">
            <button onclick="sendChatMessage()" style="padding:10px; background:#4caf50; color:white; border:none;">➤</button>
        </div>
    </div>
</div>

</body>
</html>
