<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../includes/db_connect.php';

$oid = isset($_GET['oid']) ? intval($_GET['oid']) : 0;
$errors = [];

if ($oid <= 0) {
    $errors[] = "Invalid order ID.";
    echo json_encode(["result" => "error", "errors" => $errors]);
    exit;
}

$statusMap = [
    0 => 'Rejected',
    1 => 'Pending',
    2 => 'In Progress',
    3 => 'Accepted',
    4 => 'Completed',
    5 => 'Cancelled'
];

// 訂單信息
$sql = "SELECT o.oid, o.odate, o.ostatus, o.odeliverdate, c.cname, c.ctel, c.caddr,
        SUM(ol.ocost) as total_cost
        FROM orders o
        JOIN customer c ON o.cid = c.cid
        JOIN orderline ol ON o.oid = ol.oid
        WHERE o.oid = $oid
        GROUP BY o.oid";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);
if (!$order) {
    $errors[] = "Order not found.";
    echo json_encode(["result" => "error", "errors" => $errors]);
    exit;
}
$order['ostatus'] = $statusMap[$order['ostatus']] ?? 'Unknown';

// 訂購項目
$sql = "SELECT p.pimage, p.pid, p.pname, ol.oqty, ol.ocost
        FROM orderline ol
        JOIN product p ON ol.pid = p.pid
        WHERE ol.oid = $oid";
$result = mysqli_query($conn, $sql);
$ordered_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ordered_items[] = $row;
}

// 總使用材料
$sql = "SELECT m.mid, m.mname, m.munit, SUM(pm.pmqty) as total_pmqty
        FROM orderline ol
        JOIN prodmat pm ON ol.pid = pm.pid
        JOIN material m ON pm.mid = m.mid
        WHERE ol.oid = $oid
        GROUP BY m.mid, m.mname, m.munit";
$result = mysqli_query($conn, $sql);
$materials = [];
while ($row = mysqli_fetch_assoc($result)) {
    $materials[] = $row;
}

mysqli_close($conn);

echo json_encode([
    'result' => 'success',
    'order' => $order,
    'ordered_items' => $ordered_items,
    'materials' => $materials
]);
?>