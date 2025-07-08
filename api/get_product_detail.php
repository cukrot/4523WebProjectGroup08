<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-cache, must-revalidate');
require_once '../includes/db_connect.php';

$pid = isset($_GET['pid']) && is_numeric($_GET['pid']) ? (int)$_GET['pid'] : 0;
$response = ['result' => 'error', 'errors' => []];

if ($pid <= 0) {
    $response['errors'][] = 'Invalid product ID.';
    error_log('get_product_detail.php: Invalid product ID: ' . $pid);
    echo json_encode($response);
    exit;
}

try {
    // 查詢產品詳情
    $sql = "SELECT pid, pname, pdesc, pcost, pimage 
            FROM product 
            WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('i', $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        $response['errors'][] = 'Product not found.';
        error_log('get_product_detail.php: Product not found for pid: ' . $pid);
        echo json_encode($response);
        exit;
    }

    // 查詢相關材料（使用 LEFT JOIN 以防無材料記錄）
    $sql = "SELECT pm.mid, m.mname, pm.pmqty 
            FROM prodmat pm 
            LEFT JOIN material m ON pm.mid = m.mid 
            WHERE pm.pid = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('i', $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $materials = [];
    while ($row = $result->fetch_assoc()) {
        if ($row['mid']) { // 確保材料存在
            $materials[] = [
                'mid' => $row['mid'],
                'mname' => $row['mname'] ?: 'Unknown',
                'pmqty' => $row['pmqty']
            ];
        }
    }

    $response = [
        'result' => 'success',
        'product' => [
            'pid' => $product['pid'],
            'pname' => $product['pname'],
            'pdesc' => $product['pdesc'] ?: '',
            'pcost' => number_format($product['pcost'], 2),
            'pimage' => $product['pimage'] ?: ''
        ],
        'materials' => $materials
    ];
} catch (Exception $e) {
    $response['errors'][] = 'Database error: ' . $e->getMessage();
    error_log('get_product_detail.php: Database error: ' . $e->getMessage());
} finally {
    $conn->close();
}

echo json_encode($response);
?>