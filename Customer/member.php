<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: auth.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];
$page = $_GET['page'] ?? 'personal_data'; // Default to Personal data page

// Get member data
$stmt = $pdo->prepare('SELECT cname, email, ctel, caddr, company, cpassword FROM customer WHERE cid = ?');
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

if (!$customer) {
    echo 'User does not exist';
    exit;
}

// Handle form submission for Personal data page
if ($page == 'personal_data' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $cname = $_POST['cname'];
    $email = $_POST['email'];
    $ctel = $_POST['ctel'];
    $caddr = $_POST['caddr'];
    $company = $_POST['company'];
    $cpassword = !empty($_POST['cpassword']) ? password_hash($_POST['cpassword'], PASSWORD_DEFAULT) : $customer['cpassword'];

    $update_stmt = $pdo->prepare('UPDATE customer SET cname = ?, email = ?, ctel = ?, caddr = ?, company = ?, cpassword = ? WHERE cid = ?');
    $update_stmt->execute([$cname, $email, $ctel, $caddr, $company, $cpassword, $customer_id]);

    header('Location: member.php?page=personal_data');
    exit;
}

// Handle Order History page
if ($page == 'order_history') {
    $sort_by = $_GET['sort_by'] ?? 'odate';
    $sort_order = $_GET['sort_order'] ?? 'ASC';
    $valid_columns = ['odate', 'ocost'];
    if (!in_array($sort_by, $valid_columns)) $sort_by = 'odate';

    $stmt = $pdo->prepare("
        SELECT o.oid, o.odate, ol.pid, ol.oqty, ol.ocost, o.cid, o.odeliverdate, o.ostatus
        FROM orders o
        LEFT JOIN orderline ol ON o.oid = ol.oid
        WHERE o.cid = ?
        ORDER BY $sort_by $sort_order
    ");
    $stmt->execute([$customer_id]);
    $orders = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile - Smile & Sunshine</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="member-styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Smile & Sunshine</div>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="member-profile">
        <div class="container">
            <div class="profile-header">
                <h2><?php echo $page == 'personal_data' ? 'Personal data' : 'Order History'; ?></h2>
                <div class="nav-buttons">
                    <a href="?page=personal_data" class="nav-btn">📄</a>
                    <a href="?page=order_history" class="nav-btn">🗂️</a>
                </div>
            </div>

            <?php if ($page == 'personal_data'): ?>
                <form id="profile-form" method="post">
                    <div class="profile-header">
                        <button type="button" class="edit-btn">✏️ Edit</button>
                    </div>
                    <table id="profile-table">
                        <tr>
                            <th>Field</th>
                            <th>Data</th>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td><span class="data"><?php echo htmlspecialchars($customer['cname']); ?></span><input type="text" name="cname" value="<?php echo htmlspecialchars($customer['cname']); ?>" style="display:none;"></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><span class="data"><?php echo htmlspecialchars($customer['email']); ?></span><input type="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" style="display:none;"></td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td><span class="data"><?php echo htmlspecialchars($customer['ctel']); ?></span><input type="text" name="ctel" value="<?php echo htmlspecialchars($customer['ctel']); ?>" style="display:none;"></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><span class="data"><?php echo htmlspecialchars($customer['caddr']); ?></span><input type="text" name="caddr" value="<?php echo htmlspecialchars($customer['caddr']); ?>" style="display:none;"></td>
                        </tr>
                        <tr>
                            <td>Company</td>
                            <td><span class="data"><?php echo htmlspecialchars($customer['company']); ?></span><input type="text" name="company" value="<?php echo htmlspecialchars($customer['company']); ?>" style="display:none;"></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>
                                <span class="data">********</span>
                                <input type="password" name="cpassword" class="password-input" value="" placeholder="Enter new password (optional)" style="display:none;">
                            </td>
                        </tr>
                    </table>
                    <button type="submit" class="save-btn">Save</button>
                </form>
            <?php elseif ($page == 'order_history'): ?>
                <table border="1">
                    <tr>
                        <th><a href="?page=order_history&sort_by=odate&sort_order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>">Order Date</a></th>
                        <th>Order ID</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th><a href="?page=order_history&sort_by=ocost&sort_order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>">Cost</a></th>
                        <th>Customer ID</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Cancel ❌</th>
                    </tr>
                    <?php 
                    $current_date = new DateTime();
                    foreach ($orders as $order): 
                        $delivery_date = new DateTime($order['odeliverdate']);
                        $interval = $current_date->diff($delivery_date);
                        $days_left = $interval->days;
                        if ($interval->invert) {
                            $days_left = -$days_left;
                        }
                    ?>
                        <tr>
                            <td><?php echo $order['odate']; ?></td>
                            <td><?php echo $order['oid']; ?></td>
                            <td><?php echo $order['pid'] ?? 'N/A'; ?></td>
                            <td><?php echo $order['oqty'] ?? 'N/A'; ?></td>
                            <td><?php echo $order['ocost'] ?? 'N/A'; ?></td>
                            <td><?php echo $order['cid']; ?></td>
                            <td><?php echo $order['odeliverdate']; ?></td>
                            <td><?php echo $order['ostatus']; ?></td>
                            <td>
                                <?php if ($order['ostatus'] == 'Cancelled'): ?>
                                    Cancelled
                                <?php elseif ($days_left >= 2): ?>
                                    <form method="post" action="cancel_order.php">
                                        <input type="hidden" name="oid" value="<?php echo $order['oid']; ?>">
                                        <button type="submit" class="cancel-btn">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    Cannot Cancel
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>Shop.co © 2000-2025, All Rights Reserved</p>
        </div>
    </footer>

    <script src="member-script.js"></script>
</body>
</html>