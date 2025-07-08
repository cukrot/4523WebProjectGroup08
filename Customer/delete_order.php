<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
header('Location: auth.php');
exit;
}

$order_id = $_GET['id'];
$stmt = $pdo->prepare('SELECT odeliverdate FROM orders WHERE oid = ? AND cid = ?');
$stmt->execute([$order_id, $_SESSION['customer_id']]);
$order = $stmt->fetch();

if ($order && strtotime($order['odeliverdate']) - time() > 2 * 24 * 3600) {
//Restore material inventory
$stmt = $pdo->prepare('SELECT pm.mid, pm.pmqty * o.oqty AS total_qty                           FROM orders o
JOIN prodmat pm ON o.pid = pm.pid
WHERE o.oid = ?');
$stmt->execute([$order_id]);
$materials = $stmt->fetchAll();
foreach ($materials as $material) {
$stmt = $pdo->prepare('UPDATE material SET mqty = mqty + ? WHERE mid = ?');
$stmt->execute([$material['total_qty'], $material['mid']]);
}
// Delete order
$stmt = $pdo->prepare('DELETE FROM orders WHERE oid = ?');
$stmt->execute([$order_id]);
}
header('Location: order_history.php');
exit;
?>