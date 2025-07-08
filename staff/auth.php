<?php
    session_start();
    include 'config.php'; // Include database connection files, such as PDO connections

    // Handle login form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

    // Query database
    $stmt = $pdo->prepare('SELECT * FROM customer WHERE email = ?');
    $stmt->execute([$email]);
    $customer = $stmt->fetch();

    // Verify password
    if ($customer && password_verify($password, $customer['cpassword'])) {
        $_SESSION['customer_id'] = $customer['cid']; // Store user ID in session
        header('Location: index.php'); // Redirect to index.php
        exit;
    } else {
        $error = 'Invalid email or password'; // Display error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smile & Sunshine</title>
    <link rel="stylesheet" href="auth-styles.css">
    <link rel="stylesheet" href="h_f_styles.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="logo">Smile & Sunshine</div>
            <nav>
                <ul>
                    <li><a href="staff_auth.php">Staff Login</a></li>
                    <li><a href="index.php">Back to Home</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Auth Section -->
    <section class="auth-section">
        <div class="auth-wrapper">
            <div class="auth-form login-form active">
                <h2>Login</h2>
                <p>Access your account to continue shopping.</p>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="auth-btn">Login</button>
                    <div class="form-footer">
                        <p>Don't have an account? <a href="register.php">Sign up here!</a></p>
                    </div>
                </form>
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

    <script src="auth-script.js"></script>
</body>
</html>