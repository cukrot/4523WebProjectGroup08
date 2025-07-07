<?php
require_once '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oid = intval($_POST['oid']);
    $ostatus = isset($_POST['ostatus']) ? intval($_POST['ostatus']) : null;
    $odeliverdate = isset($_POST['odeliverdate']) ? mysqli_real_escape_string($conn, $_POST['odeliverdate']) : null;

    $errors = [];
    if ($oid <= 0) $errors[] = "Invalid order ID.";

    // 更新訂單狀態
    if ($ostatus !== null) {
        if (!in_array($ostatus, [0, 1, 2, 3, 4, 5])) {
            $errors[] = "Invalid order status.";
        } else {
            $sql = "UPDATE orders SET ostatus = $ostatus WHERE oid = $oid";
            if (!mysqli_query($conn, $sql)) {
                $errors[] = "Failed to update status: " . mysqli_error($conn);
            }
        }
    }

    // 更新送貨日期
    if ($odeliverdate !== null) {
        $sql = "UPDATE orders SET odeliverdate = '$odeliverdate' WHERE oid = $oid";
        if (!mysqli_query($conn, $sql)) {
            $errors[] = "Failed to update delivery date: " . mysqli_error($conn);
        }
    }

    if (empty($errors)) {
        mysqli_commit($conn);
        echo json_encode(["result" => "success", "message" => "Order updated successfully."]);
    } else {
        mysqli_rollback($conn);
        echo json_encode(["result" => "error", "errors" => $errors]);
    }
}

mysqli_close($conn);
?>