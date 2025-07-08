<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pid = $_POST['pid'];
    $stmt = $pdo->prepare('SELECT * FROM product WHERE pid = ?');
    $stmt->execute([$pid]);
    $product = $stmt->fetch();

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$pid] = [
                'name' => $product['pname'],
                'price' => $product['pcost'],
                'quantity' => 1
            ];
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>