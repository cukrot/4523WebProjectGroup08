<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../includes/db_connect.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$itemsPerPage = 25;
$offset = ($page - 1) * $itemsPerPage;

$response = ['result' => 'error', 'errors' => []];

try {
    // 計算總記錄數
    $countSql = "SELECT COUNT(*) as total 
                 FROM product 
                 WHERE pid LIKE ? OR pname LIKE ?";
    $countStmt = $conn->prepare($countSql);
    $searchParam = "%$search%";
    $countStmt->bind_param('ss', $searchParam, $searchParam);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalItems = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalItems / $itemsPerPage);

    // 查詢產品列表
    $sql = "SELECT pid, pname, pdesc, pcost, pimage 
            FROM product 
            WHERE pid LIKE ? OR pname LIKE ?
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $searchParam, $searchParam, $itemsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'pid' => $row['pid'],
            'pname' => $row['pname'],
            'pdesc' => $row['pdesc'] ?: '',
            'pcost' => number_format($row['pcost'], 2),
            'pimage' => $row['pimage'] ?: '/4523WebProjectGroup08/assets/images/placeholder.png'
        ];
    }

    $response = [
        'result' => 'success',
        'products' => $products,
        'total_pages' => $totalPages,
        'current_page' => $page
    ];
} catch (Exception $e) {
    $response['errors'][] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>