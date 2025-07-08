<?php
session_start();
include 'config.php';

// On Sale: 
$stmt_on_sale = $pdo->query('SELECT * FROM product ORDER BY pid ASC LIMIT 4');
$on_sale = $stmt_on_sale->fetchAll();

// New Arrivals: 
$stmt_new_arrivals = $pdo->query('SELECT * FROM product ORDER BY pid DESC LIMIT 4');
$new_arrivals = $stmt_new_arrivals->fetchAll();

// Top Selling: 
$stmt_top_selling = $pdo->query('
    SELECT p.* 
    FROM product p 
    JOIN topselling t ON p.pid = t.pid 
    ORDER BY t.sales_count DESC 
    LIMIT 4
');
$top_selling = $stmt_top_selling->fetchAll();

$login_link = isset($_SESSION['customer_id']) ? 'member.php' : 'auth.php';
$logged_in = isset($_SESSION['customer_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smile & Sunshine - Toy Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body data-page="home">
    <!-- Top Banner -->
    <div class="top-banner">
    <?php if ($logged_in): ?>
        <p>Enter the discount code: VIP666</p>
    <?php else: ?>
        <p>Sign up and get 20% off your first order. <a href="register.php">Sign Up Now</a></p>
    <?php endif; ?>
    <button class="close-banner">×</button>
</div>

    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="logo">Smile & Sunshine</div>
            <nav>
                <ul>
                    <li><a href="#on-sale">On Sale</a></li>
                    <li><a href="#new-arrivals">New Arrivals</a></li>
                    <li><a href="#top-selling">Top Selling</a></li>
                    <li><a href="#customer-reviews">Customer Reviews</a></li>
                </ul>
            </nav>
            <div class="header-right">
                <div class="search-bar">
                    <form action="search.php" method="get">
                        <input type="text" name="query" placeholder="Search for products...">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                <div class="header-icons">
                    <a href="cart.php" class="cart-icon">🛒</a>
                    <a href="<?php echo $login_link; ?>" class="user-icon">👤</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>DISCOVER TOYS THAT SPARK JOY AND IMAGINATION</h1>
                <p>Explore our wide range of fun and engaging toys, designed to inspire creativity and bring endless hours of playtime excitement.</p>
                <button href="#top-selling" class="shop-now">Shop Now</button>
            </div>
            <div class="hero-image"></div>
        </div>
        <div class="stats">
            <div class="stat">
                <h3>200+</h3>
                <p>Trusted Brands</p>
            </div>
            <div class="stat">
                <h3>2,000+</h3>
                <p>High-Quality Toys</p>
            </div>
            <div class="stat">
                <h3>30,000+</h3>
                <p>Customer Reviews</p>
            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="new-arrivals" id="new-arrivals">
        <div class="container">
        <h2>NEW ARRIVALS</h2>
        <div class="product-grid">
            <?php foreach ($new_arrivals as $product): ?>
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
        </div>
    </section>

    <!-- On Sale Section -->
    <section class="on-sale" id="on-sale">
        <div class="container">
            <h2>ON SALE</h2>
            <div class="product-grid">
                <?php foreach ($on_sale as $product): ?>
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
        </div>
    </section>

    <!-- Top Selling Section -->
    <section class="top-selling" id="top-selling">
        <div class="container">
            <h2>TOP SELLING</h2>
            <div class="product-grid">
                <?php foreach ($top_selling as $product): ?>
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
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="customer-reviews" id="customer-reviews">
        <div class="container">
            <h2>CUSTOMER REVIEWS</h2>
            <div class="reviews-carousel">
                <button class="prev-btn">❮</button>
                <div class="reviews">
                    <div class="review">
                        <div class="stars">★★★★★</div>
                        <h5>Sarah M.</h5>
                        <p>"I’m amazed by the quality and fun of the toys I got from this shop. Every piece brings so much joy to my kids!"</p>
                    </div>
                    <div class="review">
                        <div class="stars">★★★★★</div>
                        <h5>Alex K.</h5>
                        <p>"Finally found a toy store that has the perfect playthings for my children! The collection is exciting and unique. Will definitely shop again!"</p>
                    </div>
                    <div class="review">
                        <div class="stars">★★★★★</div>
                        <h5>James L.</h5>
                        <p>"The attention to detail and the durability of the toys are incredible. These are now my kids’ favorite playtime buddies!"</p>
                    </div>
                    <div class="review">
                        <div class="stars">★★★★★</div>
                        <h5>Michael R.</h5>
                        <p>"Shopping here has been a fantastic experience. The toys are fun, creative, and the service is top-notch!"</p>
                    </div>
                </div>
                <button class="next-btn">❯</button>
            </div>
            <div class="pagination-dots">
                <!-- Dots will be dynamically added via JavaScript -->
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <h2>STAY UP TO DATE ABOUT OUR LATEST OFFERS</h2>
            <div class="newsletter-form">
                <input type="email" placeholder="Enter your email address">
                <button>Subscribe to Newsletter</button>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <div class="footer-section">
                <h3>Smile & Sunshine</h3>
                <p>We have toys that bring joy and spark creativity for kids of all ages. From toddlers to teens.</p>
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