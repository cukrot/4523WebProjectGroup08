<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-cache, must-revalidate');
require_once '../includes/db_connect.php';

$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : date('Y-m-t');
$products_search = isset($_GET['products_search']) ? trim($_GET['products_search']) : '';
$materials_search = isset($_GET['materials_search']) ? trim($_GET['materials_search']) : '';
$products_page = isset($_GET['products_page']) && is_numeric($_GET['products_page']) ? (int)$_GET['products_page'] : 1;
$materials_page = isset($_GET['materials_page']) && is_numeric($_GET['materials_page']) ? (int)$_GET['materials_page'] : 1;
$export_csv = isset($_GET['export']) && $_GET['export'] === 'csv';
$items_per_page = 25;

$response = ['result' => 'error', 'errors' => []];

try {
    // 驗證日期
    if (!strtotime($start_date) || !strtotime($end_date)) {
        $response['errors'][] = 'Invalid date range.';
        error_log('get_report.php: Invalid date range: start_date=' . $start_date . ', end_date=' . $end_date);
        echo json_encode($response);
        exit;
    }

    // 搜尋條件
    $products_condition = '';
    $materials_condition = '';
    $base_params = [$start_date, $end_date];
    $products_params = [];
    $materials_params = [];

    if ($products_search !== '') {
        $products_condition = ' AND (p.pid LIKE ? OR p.pname LIKE ?)';
        $products_search_param = "%$products_search%";
        $products_params = [$products_search_param, $products_search_param];
    }
    if ($materials_search !== '') {
        $materials_condition = ' AND (m.mid LIKE ? OR m.mname LIKE ?)';
        $materials_search_param = "%$materials_search%";
        $materials_params = [$materials_search_param, $materials_search_param];
    }

    // 總訂單數量
    $sql = "SELECT COUNT(DISTINCT o.oid) as total 
            FROM orders o";
    $params = $base_params;
    if ($products_search !== '') {
        $sql .= " JOIN orderline ol ON o.oid = ol.oid JOIN product p ON ol.pid = p.pid WHERE o.odate BETWEEN ? AND ?" . $products_condition;
        $params = array_merge($base_params, $products_params);
    } else {
        $sql .= " WHERE o.odate BETWEEN ? AND ?";
    }
    error_log('get_report.php: Total orders SQL: ' . $sql . ', Params: ' . json_encode($params));
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed for total orders: ' . $conn->error);
    }
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $total_orders = $stmt->get_result()->fetch_assoc()['total'];

    // 總銷售金額
    $sql = "SELECT SUM(ol.ocost) as total 
            FROM orderline ol 
            JOIN orders o ON ol.oid = o.oid 
            JOIN product p ON ol.pid = p.pid 
            WHERE o.odate BETWEEN ? AND ?" . $products_condition;
    $params = array_merge($base_params, $products_params);
    error_log('get_report.php: Total sales SQL: ' . $sql . ', Params: ' . json_encode($params));
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed for total sales: ' . $conn->error);
    }
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $total_sales_amount = $stmt->get_result()->fetch_assoc()['total'] ?: 0;

    // 產品統計
    $sql = "SELECT p.pid, p.pname, p.pimage, SUM(ol.oqty) as total_qty, SUM(ol.oqty * p.pcost) as total_amount
            FROM orderline ol
            JOIN product p ON ol.pid = p.pid
            JOIN orders o ON ol.oid = o.oid
            WHERE o.odate BETWEEN ? AND ?" . $products_condition . "
            GROUP BY p.pid";
    if (!$export_csv) {
        $sql .= " LIMIT ? OFFSET ?";
        $products_offset = ($products_page - 1) * $items_per_page;
        $params = array_merge($base_params, $products_params, [$items_per_page, $products_offset]);
        $bind_types = str_repeat('s', count($products_params) + 2) . 'ii';
    } else {
        $params = array_merge($base_params, $products_params);
        $bind_types = str_repeat('s', count($params));
    }
    error_log('get_report.php: Products SQL: ' . $sql . ', Params: ' . json_encode($params));
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed for products: ' . $conn->error);
    }
    $stmt->bind_param($bind_types, ...$params);
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
    $sql = "SELECT COUNT(DISTINCT p.pid) as total 
            FROM orderline ol 
            JOIN product p ON ol.pid = p.pid 
            JOIN orders o ON ol.oid = o.oid 
            WHERE o.odate BETWEEN ? AND ?" . $products_condition;
    $params = array_merge($base_params, $products_params);
    error_log('get_report.php: Products total SQL: ' . $sql . ', Params: ' . json_encode($params));
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed for products total: ' . $conn->error);
    }
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $products_total_pages = ceil($stmt->get_result()->fetch_assoc()['total'] / $items_per_page);

    // 材料統計
    $sql = "SELECT m.mid, m.mname, m.munit, SUM(ol.oqty * pm.pmqty) as total_material
            FROM orderline ol
            JOIN prodmat pm ON ol.pid = pm.pid
            JOIN material m ON pm.mid = m.mid
            JOIN orders o ON ol.oid = o.oid
            WHERE o.odate BETWEEN ? AND ?" . $materials_condition . "
            GROUP BY m.mid";
    if (!$export_csv) {
        $sql .= " LIMIT ? OFFSET ?";
        $materials_offset = ($materials_page - 1) * $items_per_page;
        $params = array_merge($base_params, $materials_params, [$items_per_page, $materials_offset]);
        $bind_types = str_repeat('s', count($materials_params) + 2) . 'ii';
    } else {
        $params = array_merge($base_params, $materials_params);
        $bind_types = str_repeat('s', count($params));
    }
    error_log('get_report.php: Materials SQL: ' . $sql . ', Params: ' . json_encode($params));
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed for materials: ' . $conn->error);
    }
    $stmt->bind_param($bind_types, ...$params);
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
    $sql = "SELECT COUNT(DISTINCT m.mid) as total 
            FROM orderline ol 
            JOIN prodmat pm ON ol.pid = pm.pid 
            JOIN material m ON pm.mid = m.mid 
            JOIN orders o ON ol.oid = o.oid 
            WHERE o.odate BETWEEN ? AND ?" . $materials_condition;
    $params = array_merge($base_params, $materials_params);
    error_log('get_report.php: Materials total SQL: ' . $sql . ', Params: ' . json_encode($params));
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed for materials total: ' . $conn->error);
    }
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $materials_total_pages = ceil($stmt->get_result()->fetch_assoc()['total'] / $items_per_page);

    $response = [
        'result' => 'success',
        'total_orders' => $total_orders,
        'total_sales_amount' => $total_sales_amount,
        'products' => $products,
        'products_total_pages' => $export_csv ? 1 : $products_total_pages,
        'products_current_page' => $export_csv ? 1 : $products_page,
        'materials' => $materials,
        'materials_total_pages' => $export_csv ? 1 : $materials_total_pages,
        'materials_current_page' => $export_csv ? 1 : $materials_page
    ];
} catch (Exception $e) {
    $response['errors'][] = 'Database error: ' . $e->getMessage();
    error_log('get_report.php: Database error: ' . $e->getMessage());
}

echo json_encode($response);
$conn->close();
?>