<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pid = $_POST['pid'];

    if (isset($_SESSION['cart'][$pid])) {
        unset($_SESSION['cart'][$pid]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not in cart']);
    }
}
?>