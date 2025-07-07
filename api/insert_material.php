<?php
require_once '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mname = mysqli_real_escape_string($conn, $_POST['mname']);
    $munit = mysqli_real_escape_string($conn, $_POST['munit']);
    $mreorderqty = intval($_POST['mreorderqty']);
    $mqty = 0; // 預設為0
    $mrqty = 0; // 預設為0

    $errors = [];
    if (empty($mname)) $errors[] = "Material name is required.";
    if (empty($munit)) $errors[] = "Unit is required.";
    if ($mreorderqty <= 0) $errors[] = "Re-order level must be a positive number.";

    if (empty($errors)) {
        $sql = "INSERT INTO material (mname, mqty, mrqty, munit, mreorderqty) VALUES ('$mname', $mqty, $mrqty, '$munit', $mreorderqty)";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["result" => "success", "message" => "Material inserted successfully."]);
        } else {
            $errors[] = "Failed to insert material: " . mysqli_error($conn);
            echo json_encode(["result" => "error", "errors" => $errors]);
        }
    } else {
        echo json_encode(["result" => "error", "errors" => $errors]);
    }
}

mysqli_close($conn);
?>