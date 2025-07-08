<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: auth.php');
    exit;
}

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
$stmt->execute([$_SESSION['customer_id']]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Record</title>
</head>
<body>
    <table border="1">
        <tr>
            <th><a href="?sort_by=odate&sort_order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>">Order Date</a></th>
            <th>Order ID</th>
            <th>Product ID</th>
            <th>Quantity</th>
            <th><a href="?sort_by=ocost&sort_order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>">Fees</a></th>
            <th>Customer ID</th>
            <th>Delivery Date</th>
            <th>Status</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['odate']; ?></td>
                <td><?php echo $order['oid']; ?></td>
                <td><?php echo $order['pid'] ?? 'N/A'; ?></td>
                <td><?php echo $order['oqty'] ?? 'N/A'; ?></td>
                <td><?php echo $order['ocost'] ?? 'N/A'; ?></td>
                <td><?php echo $order['cid']; ?></td>
                <td><?php echo $order['odeliverdate']; ?></td>
                <td><?php echo $order['ostatus']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>