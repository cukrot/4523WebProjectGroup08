<?php
session_start();
include 'config.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $stmt = $pdo->prepare('SELECT * FROM product WHERE pname LIKE ?');
    $stmt->execute(['%' . $query . '%']);
    $results = $stmt->fetchAll();
} else {
    $results = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Smile & Sunshine</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body data-page="search">
    <header>
        <div class="container">
            <div class="logo">Smile & Sunshine</div>
            <nav>
                <ul>
                    <li><a href="home.php#on-sale">On Sale</a></li>
                    <li><a href="home.php#new-arrivals">New Arrivals</a></li>
                    <li><a href="home.php#top-selling">Top Selling</a></li>
                    <li><a href="home.php#customer-reviews">Customer Reviews</a></li>
                </ul>
            </nav>
            <div class="header-right">
                <div class="search-bar">
                    <form action="search.php" method="get">
                        <input type="text" name="query" placeholder="Search for products..." value="<?php echo htmlspecialchars($query ?? ''); ?>">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                <div class="header-icons">
                    <a href="cart.php" class="cart-icon">🛒</a>
                    <a href="<?php echo isset($_SESSION['customer_id']) ? 'member.php' : 'auth.php'; ?>" class="user-icon">👤</a>
                </div>
            </div>
        </div>
    </header>

    <section class="search-results">
        <div class="container">
            <h2>Search Results for "<?php echo htmlspecialchars($query ?? ''); ?>"</h2>
            <?php if (empty($results)): ?>
                <p>No products found.</p>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($results as $product): ?>
                        <div class="product">
                            <a href="product_detail.php?pid=<?php echo $product['pid']; ?>">
                                <div class="product-image" style="background-image: url('<?php echo $product['pimage'] ?? 'default.jpg'; ?>');"></div>
                                <h4><?php echo $product['pname']; ?></h4>
                            </a>
                            <p>$<?php echo number_format($product['pcost'], 2); ?></p>
                            <button class="add-to-cart" data-id="<?php echo $product['pid']; ?>" data-name="<?php echo $product['pname']; ?>" data-price="<?php echo $product['pcost']; ?>">Add to Cart</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-section">
                <h3>Smile & Sunshine</h3>
                <p>We have toys that bring joy and spark creativity for kids of all ages.</p>
            </div>
            <div class="footer-section">
                <h3>COMPANY</h3>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Works</a></li>
                    <li><a href="#">Career</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>HELP</h3>
                <ul>
                    <li><a href="#">Customer Support</a></li>
                    <li><a href="#">Delivery Details</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>FAQ</h3>
                <ul>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Manage Deliveries</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="#">Payments</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Shop.co © 2000-2025, All Rights Reserved</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>