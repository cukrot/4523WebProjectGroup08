<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: auth.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['oid'])) {
    $oid = $_POST['oid'];
    $customer_id = $_SESSION['customer_id'];

    // check
    $stmt = $pdo->prepare('SELECT odeliverdate FROM orders WHERE oid = ? AND cid = ?');
    $stmt->execute([$oid, $customer_id]);
    $order = $stmt->fetch();

    if ($order) {
        // check before 2 days
        $current_date = new DateTime();
        $delivery_date = new DateTime($order['odeliverdate']);
        $interval = $current_date->diff($delivery_date);
        $days_left = $interval->days;
        if ($interval->invert) {
            $days_left = -$days_left;
        }

        if ($days_left >= 2) {
            // 「Cancelled」
            $update_stmt = $pdo->prepare('UPDATE orders SET ostatus = "Cancelled" WHERE oid = ?');
            $update_stmt->execute([$oid]);
            header('Location: member.php?page=order_history');
            exit;
        } else {
            echo 'Cannot cancel the order. Less than 2 days before delivery.';
        }
    } else {
        echo 'Order not found or you do not have permission to cancel this order.';
    }
} else {
    echo 'Invalid request.';
}
?>