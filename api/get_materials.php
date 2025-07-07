<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$sql = "SELECT mid, mname FROM material";
$result = mysqli_query($conn, $sql);
$materials = [];
while ($row = mysqli_fetch_assoc($result)) {
    $materials[] = $row;
}
mysqli_close($conn);

echo json_encode($materials);
?>