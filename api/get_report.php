<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-cache, must-revalidate');
require_once '../includes/db_connect.php';

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$products_page = isset($_GET['products_page']) && is_numeric($_GET['products_page']) ? (int)$_GET['products_page'] : 1;
$materials_page = isset($_GET['materials_page']) && is_numeric($_GET['materials_page']) ? (int)$_GET['materials_page'] : 1;
$items_per_page = 25;

$response = ['result' => 'error', 'errors' => []];

try {
    // 驗證日期
    if (!strtotime($start_date) || !strtotime($end_date)) {
        $response['errors'][] = 'Invalid date range.';
        echo json_encode($response);
        exit;
    }

    // 總訂單數量
    $sql = "SELECT COUNT(*) as total FROM orders WHERE odate BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $total_orders = $stmt->get_result()->fetch_assoc()['total'];

    // 總銷售金額
    $sql = "SELECT SUM(ocost) as total FROM orderline ol JOIN orders o ON ol.oid = o.oid WHERE o.odate BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $total_sales_amount = $stmt->get_result()->fetch_assoc()['total'] ?: 0;

    // 產品統計
    $products_offset = ($products_page - 1) * $items_per_page;
    $sql = "SELECT p.pid, p.pname, p.pimage, SUM(ol.oqty) as total_qty, SUM(ol.oqty * p.pcost) as total_amount
            FROM orderline ol
            JOIN product p ON ol.pid = p.pid
            JOIN orders o ON ol.oid = o.oid
            WHERE o.odate BETWEEN ? AND ?
            GROUP BY p.pid
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $start_date, $end_date, $items_per_page, $products_offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'pid' => $row['pid'],
            'pname' => $row['pname'],
            'pimage' => $row['pimage'] ?: '',
            'total_qty' => $row['total_qty'],
            'total_amount' => $row['total_amount']
        ];
    }

    // 產品總頁數
    $sql = "SELECT COUNT(DISTINCT p.pid) as total FROM orderline ol JOIN product p ON ol.pid = p.pid JOIN orders o ON ol.oid = o.oid WHERE o.odate BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $products_total_pages = ceil($stmt->get_result()->fetch_assoc()['total'] / $items_per_page);

    // 材料統計
    $materials_offset = ($materials_page - 1) * $items_per_page;
    $sql = "SELECT m.mid, m.mname, m.munit, SUM(ol.oqty * pm.pmqty) as total_material
            FROM orderline ol
            JOIN prodmat pm ON ol.pid = pm.pid
            JOIN material m ON pm.mid = m.mid
            JOIN orders o ON ol.oid = o.oid
            WHERE o.odate BETWEEN ? AND ?
            GROUP BY m.mid
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $start_date, $end_date, $items_per_page, $materials_offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $materials = [];
    while ($row = $result->fetch_assoc()) {
        $materials[] = [
            'mid' => $row['mid'],
            'mname' => $row['mname'],
            'munit' => $row['munit'],
            'total_material' => $row['total_material']
        ];
    }

    // 材料總頁數
    $sql = "SELECT COUNT(DISTINCT m.mid) as total FROM orderline ol JOIN prodmat pm ON ol.pid = pm.pid JOIN material m ON pm.mid = m.mid JOIN orders o ON ol.oid = o.oid WHERE o.odate BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $materials_total_pages = ceil($stmt->get_result()->fetch_assoc()['total'] / $items_per_page);

    $response = [
        'result' => 'success',
        'total_orders' => $total_orders,
        'total_sales_amount' => $total_sales_amount,
        'products' => $products,
        'products_total_pages' => $products_total_pages,
        'products_current_page' => $products_page,
        'materials' => $materials,
        'materials_total_pages' => $materials_total_pages,
        'materials_current_page' => $materials_page
    ];
} catch (Exception $e) {
    $response['errors'][] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>