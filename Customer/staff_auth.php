<?php
session_start();
include 'config.php'; // Include database connection (PDO)

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $sid = $_POST['sid'];
    $password = $_POST['password'];

    try {
        // Query staff table
        $stmt = $pdo->prepare('SELECT * FROM staff WHERE sid = ?');
        $stmt->execute([$sid]);
        $staff = $stmt->fetch();

        // Verify password
        if ($staff) {
            // 假設 spassword 是明文（根據 import_data.sql）
            if ($password === $staff['spassword']) {
                $_SESSION['staff_id'] = $staff['sid'];
                header('Location: /4523WebProjectGroup08/pages/staff/view_orders.html');
                exit;
            }
            // 若 spassword 是哈希，請取消註釋以下代碼並註釋上面明文驗證
            /*
            if (password_verify($password, $staff['spassword'])) {
                $_SESSION['staff_id'] = $staff['sid'];
                header('Location: /4523WebProjectGroup08/pages/staff/view_orders.html');
                exit;
            }
            */
            else {
                $error = 'Invalid staff ID or password';
            }
        } else {
            $error = 'Invalid staff ID or password';
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - Smile & Sunshine</title>
    <link rel="stylesheet" href="./auth-styles.css">
    <link rel="stylesheet" href="./h_f_styles.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="logo">Smile & Sunshine</div>
            <nav>
                <ul>
                    <li><a href="auth.php">Customer Login</a></li>
                    <li><a href="../index.php">Back to Home</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Auth Section -->
    <section class="auth-section">
        <div class="auth-wrapper">
            <div class="auth-form login-form active">
                <h2>Staff Login</h2>
                <p>Access your staff account to manage orders.</p>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                <form method="post" id="staff-login-form">
                    <div class="form-group">
                        <label for="staff-id">Staff ID</label>
                        <input type="text" id="staff-id" name="sid" required>
                    </div>
                    <div class="form-group">
                        <label for="staff-password">Password</label>
                        <input type="password" id="staff-password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="auth-btn">Login</button>
                    <div class="form-footer">
                        <p>Contact admin for account issues.</p>
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

    <script src="./staff-auth-script.js"></script>
</body>
</html>