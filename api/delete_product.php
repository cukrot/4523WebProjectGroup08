<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../includes/db_connect.php';

$pid = isset($_GET['pid']) && is_numeric($_GET['pid']) ? (int)$_GET['pid'] : 0;
$response = ['result' => 'error', 'errors' => []];

if ($pid <= 0) {
    $response['errors'][] = 'Invalid product ID.';
    echo json_encode($response);
    exit;
}

try {
    // 檢查是否有相關訂單
    $sql = "SELECT COUNT(*) as count FROM orderline WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        $response['errors'][] = 'Cannot delete product with existing orders.';
        echo json_encode($response);
        exit;
    }

    // 開始事務
    $conn->begin_transaction();

    // 刪除產品材料關聯
    $sql = "DELETE FROM prodmat WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pid);
    $stmt->execute();

    // 刪除產品
    $sql = "DELETE FROM product WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pid);
    $stmt->execute();

    // 提交事務
    $conn->commit();

    $response = ['result' => 'success'];
} catch (Exception $e) {
    $conn->rollback();
    $response['errors'][] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>