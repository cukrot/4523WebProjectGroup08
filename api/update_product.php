<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../includes/db_connect.php';

$response = ['result' => 'error', 'errors' => []];

try {
    $pid = isset($_POST['pid']) && is_numeric($_POST['pid']) ? (int)$_POST['pid'] : 0;
    $pname = isset($_POST['pname']) ? trim($_POST['pname']) : '';
    $pdesc = isset($_POST['pdesc']) ? trim($_POST['pdesc']) : '';
    $pcost = isset($_POST['pcost']) && is_numeric($_POST['pcost']) && $_POST['pcost'] >= 0 ? (float)$_POST['pcost'] : 0;
    $materials = isset($_POST['materials']) ? json_decode($_POST['materials'], true) : [];

    // 驗證輸入
    if ($pid <= 0) {
        $response['errors'][] = 'Invalid product ID.';
        echo json_encode($response);
        exit;
    }
    if (empty($pname)) {
        $response['errors'][] = 'Product name is required.';
        echo json_encode($response);
        exit;
    }
    if ($pcost <= 0) {
        $response['errors'][] = 'Single cost must be a positive number.';
        echo json_encode($response);
        exit;
    }
    if (empty($materials)) {
        $response['errors'][] = 'At least one material is required.';
        echo json_encode($response);
        exit;
    }

    // 處理圖片上傳
    $pimage = null;
    if (isset($_FILES['pimage']) && $_FILES['pimage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $fileName = time() . '_' . basename($_FILES['pimage']['name']);
        $uploadPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['pimage']['tmp_name'], $uploadPath)) {
            $pimage = 'uploads/' . $fileName;
        } else {
            $response['errors'][] = 'Failed to upload image.';
            echo json_encode($response);
            exit;
        }
    }

    // 開始事務
    $conn->begin_transaction();

    // 更新產品資訊
    if ($pimage) {
        $sql = "UPDATE product SET pname = ?, pdesc = ?, pcost = ?, pimage = ? WHERE pid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdsi', $pname, $pdesc, $pcost, $pimage, $pid);
    } else {
        $sql = "UPDATE product SET pname = ?, pdesc = ?, pcost = ? WHERE pid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdi', $pname, $pdesc, $pcost, $pid);
    }
    $stmt->execute();

    // 刪除現有材料關聯
    $sql = "DELETE FROM prodmat WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pid);
    $stmt->execute();

    // 插入新的材料關聯
    $sql = "INSERT INTO prodmat (pid, mid, pmqty) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($materials as $material) {
        $mid = (int)$material['mid'];
        $pmqty = (int)$material['pmqty'];
        if ($mid <= 0 || $pmqty <= 0) {
            $conn->rollback();
            $response['errors'][] = 'Invalid material ID or quantity.';
            echo json_encode($response);
            exit;
        }
        $stmt->bind_param('iii', $pid, $mid, $pmqty);
        $stmt->execute();
    }

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