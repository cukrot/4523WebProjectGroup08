<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../includes/db_connect.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 25;
$offset = ($page - 1) * $perPage;

$statusMap = [
    0 => 'Rejected',
    1 => 'Pending',
    2 => 'In Progress',
    3 => 'Accepted',
    4 => 'Completed',
    5 => 'Cancelled'
];

$where = $search ? "WHERE oid LIKE '%$search%' OR ostatus IN (0, 1, 2, 3, 4, 5) AND (" . implode(' OR ', array_map(function($status, $label) use ($search) {
    return "ostatus = $status AND '$label' LIKE '%$search%'";
}, array_keys($statusMap), $statusMap)) . ")" : '';

$sql = "SELECT oid, odate, odeliverdate, ostatus FROM orders $where ORDER BY oid ASC LIMIT $perPage OFFSET $offset";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo json_encode(["result" => "error", "message" => "Query failed: " . mysqli_error($conn)]);
    exit;
}

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['ostatus'] = $statusMap[$row['ostatus']] ?? 'Unknown';
    $orders[] = $row;
}

$totalSql = "SELECT COUNT(*) FROM orders $where";
$totalResult = mysqli_query($conn, $totalSql);
if (!$totalResult) {
    echo json_encode(["result" => "error", "message" => "Total query failed: " . mysqli_error($conn)]);
    exit;
}
$totalRows = mysqli_fetch_row($totalResult)[0];
$totalPages = ceil($totalRows / $perPage);

mysqli_close($conn);

echo json_encode([
    'result' => 'success',
    'orders' => $orders,
    'total_pages' => $totalPages,
    'current_page' => $page
]);
?>