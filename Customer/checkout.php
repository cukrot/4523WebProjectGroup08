<?php
session_start();
include 'config.php';

// Check if you are logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: auth.php?return=checkout.php');
    exit;
}

// Check if the shopping cart is empty
if (empty($_SESSION['cart'])) {
    echo '<div class="empty-cart-message">';
    echo '<p>Your shopping cart is empty. Please add items to the shopping cart before checking out.</p>';
    echo '<div class="cart-actions">';
    echo '<a href="home.php" class="action-btn">Go to the store</a>';
    echo '<a href="cart.php" class="action-btn">View the shopping cart</a>';
    echo '</div>';
    echo '</div>';
    exit;
}

// Get customer information
$stmt = $pdo->prepare('SELECT caddr, ctel, email FROM customer WHERE cid = ?');
$stmt->execute([$_SESSION['customer_id']]);
$customer = $stmt->fetch();

// Initialize variables to avoid undefined variable warnings
$error = '';
$address = $_POST['address'] ?? $customer['caddr'] ?? '';
$phone = $_POST['phone'] ?? $customer['ctel'] ?? '';
$email = $_POST['email'] ?? $customer['email'] ?? '';
$card_number = $_POST['card_number'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$payment_method = $_POST['payment_method'] ?? 'VISA'; // Default to VISA
$currency = $_POST['currency'] ?? 'USD'; // Default to USD

$login_link = isset($_SESSION['customer_id']) ? 'member.php' : 'auth.php';
$logged_in = isset($_SESSION['customer_id']);

// Calculate total cost
$total_cost = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_cost += $item['price'] * $item['quantity'] * 1.05;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['address']) || !isset($_POST['phone']) || !isset($_POST['email']) ||
        !isset($_POST['card_number']) || !isset($_POST['expiry_date']) || !isset($_POST['cvv']) ||
        !isset($_POST['payment_method']) || !isset($_POST['currency'])) {
        $error = 'Please fill in all fields.';
    } else {
        if (!preg_match('/^\d{16}$/', $_POST['card_number'])) {
            $error = 'Credit Card Number must be 16 digits.';
        } elseif (!preg_match('/^(0[1-9]|1[0-2])\d{2}$/', $_POST['expiry_date'])) {
            $error = 'Expiration Date must be in MMYY format.';
        } elseif (!preg_match('/^\d{3}$/', $_POST['cvv'])) {
            $error = 'CVV must be 3 digits.';
        } elseif (empty($_POST['address']) || empty($_POST['phone']) || empty($_POST['email'])) {
            $error = 'Please fill in all fields.';
        } else {
            // Call Flask API for currency conversion
            $amount = $total_cost;
            $selected_currency = $_POST['currency'];
            $api_url = "http://localhost:8080/cost_convert/{$amount}/{$selected_currency}";
            $response = @file_get_contents($api_url);
            if ($response === false) {
                $error = 'Failed to connect to currency conversion API.';
            } else {
                $data = json_decode($response, true);
                if ($data['result'] === 'accepted') {
                    $converted_amount = $data['converted_amount'];
                    // Create order
                    $cart = $_SESSION['cart'];
                    $customer_id = $_SESSION['customer_id'];
                    $order_date = date('Y-m-d H:i:s');
                    $delivery_date = date('Y-m-d H:i:s', strtotime('+7 days'));
                    $payment_method = $_POST['payment_method'];
                    $currency = $_POST['currency'];

                    foreach ($cart as $pid => $item) {
                        $quantity = $item['quantity'];
                        $cost = $item['price'] * $quantity; // Original cost in USD
                        $stmt = $pdo->prepare('INSERT INTO orders (odate, pid, oqty, ocost, cid, odeliverdate, ostatus, payment_method, currency) VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?)');
                        $stmt->execute([$order_date, $pid, $quantity, $cost, $customer_id, $delivery_date, $payment_method, $currency]);
                    }

                    unset($_SESSION['cart']);
                    header('Location: order_history.php');
                    exit;
                } else {
                    $error = 'Currency conversion failed: ' . $data['reason'];
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Smile & Sunshine</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="checkout.css">
</head>
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
<body>
    <h1>Checkout Page</h1>
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" class="checkout-form">
        <label>Shipping Address: <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required></label><br>
        <label>Phone Number: <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required></label><br>
        <label>Credit Card Number: <input type="text" name="card_number" value="<?php echo htmlspecialchars($card_number); ?>" required></label><br>
        <label>Expiration Date: <input type="text" name="expiry_date" placeholder="MM/YY" value="<?php echo htmlspecialchars($expiry_date); ?>" required></label><br>
        <label>CVV: <input type="text" name="cvv" value="<?php echo htmlspecialchars($cvv); ?>" required></label><br>
        <label>Payment Method:
            <select name="payment_method">
                <option value="VISA" <?php if ($payment_method == 'VISA') echo 'selected'; ?>>VISA</option>
                <option value="MASTER" <?php if ($payment_method == 'MASTER') echo 'selected'; ?>>MASTER</option>
                <option value="AE" <?php if ($payment_method == 'AE') echo 'selected'; ?>>AE</option>
            </select>
        </label>
        <label>Currency:
            <select name="currency" id="currency-select">
                <option value="USD" <?php if ($currency == 'USD') echo 'selected'; ?>>US Dollar</option>
                <option value="HKD" <?php if ($currency == 'HKD') echo 'selected'; ?>>HK Dollar</option>
                <option value="EUR" <?php if ($currency == 'EUR') echo 'selected'; ?>>Euro</option>
                <option value="JPY" <?php if ($currency == 'JPY') echo 'selected'; ?>>Japanese Yen</option>
            </select>
        </label><br>
        <p>Total Cost: <span id="total-cost"><?php echo number_format($total_cost, 2); ?></span> US Dollar</p>
        <p>Converted Amount: <span id="selected-currency">US Dollar</span> <span id="convertedAmount"></span></p>
        <button type="submit" class="checkout-btn">Confirm Order</button>
    </form>
    <footer>
        <div class="container">
            <p>Shop.co © 2000-2025 al Rights Reserved</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currencySelect = document.getElementById('currency-select');
            const selectedCurrencySpan = document.getElementById('selected-currency');
            const convertedAmountSpan = document.getElementById('convertedAmount');
            const totalCost = parseFloat(document.getElementById('total-cost').textContent);

            // Currency names mapping
            const currencyNames = {
                'USD': 'US Dollar',
                'HKD': 'HK Dollar',
                'EUR': 'Euro',
                'JPY': 'Japanese Yen'
            };

            function updateConvertedAmount() {
                const selectedCurrency = currencySelect.value;
                selectedCurrencySpan.textContent = currencyNames[selectedCurrency];

                if (selectedCurrency === 'USD') {
                    convertedAmountSpan.textContent = totalCost.toFixed(2);
                } else {
                    fetch(`http://localhost:8080/cost_convert/${totalCost}/${selectedCurrency}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.result === 'accepted') {
                                convertedAmountSpan.textContent = data.converted_amount.toFixed(2);
                            } else {
                                convertedAmountSpan.textContent = 'Error: ' + data.reason;
                            }
                        })
                        .catch(error => {
                            convertedAmountSpan.textContent = 'Error: Unable to convert';
                            console.error('Fetch error:', error);
                        });
                }
            }

            updateConvertedAmount();
            currencySelect.addEventListener('change', updateConvertedAmount);
        });
    </script>
</body>
</html>