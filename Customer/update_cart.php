<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pid = $_POST['pid'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$pid])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$pid]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$pid]);
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not in cart']);
    }
}
?>