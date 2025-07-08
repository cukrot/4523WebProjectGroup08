<?php
require_once '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pname = mysqli_real_escape_string($conn, $_POST['pname']);
    $pdesc = mysqli_real_escape_string($conn, $_POST['pdesc'] ?? '');
    $pcost = floatval($_POST['pcost']);
    $materials = json_decode($_POST['materials'], true);

    $errors = [];
    if (empty($pname)) $errors[] = "Product name is required.";
    if ($pcost <= 0) $errors[] = "Single cost must be a positive number.";
    if (empty($materials)) $errors[] = "At least one material is required.";
    foreach ($materials as $material) {
        if (!isset($material['mid']) || !isset($material['pmqty']) || $material['pmqty'] <= 0) {
            $errors[] = "Invalid material data.";
        }
        $mid = intval($material['mid']);
        $sql = "SELECT COUNT(*) FROM material WHERE mid = $mid";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_fetch_row($result)[0];
        if ($count == 0) {
            $errors[] = "Material ID $mid does not exist.";
        }
    }

    $pimage = null;
    if (isset($_FILES['pimage']) && $_FILES['pimage']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['pimage']['type'], $allowed_types)) {
            $errors[] = "Invalid image format. Only JPEG, PNG, or GIF allowed.";
        } else {
            $upload_dir = "/Applications/XAMPP/htdocs/4523WebProjectGroup08/uploads/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES['pimage']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext; // 使用純 ASCII 名稱
            $pimage = "/4523WebProjectGroup08/uploads/" . $filename;
            $server_path = $upload_dir . $filename;
            if (!move_uploaded_file($_FILES['pimage']['tmp_name'], $server_path)) {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (empty($errors)) {
        mysqli_begin_transaction($conn);
        try {
            $sql = "INSERT INTO product (pname, pdesc, pcost, pimage) VALUES ('$pname', '$pdesc', $pcost, " . ($pimage ? "'$pimage'" : "NULL") . ")";
            if (!mysqli_query($conn, $sql)) {
                throw new Exception("Failed to insert product: " . mysqli_error($conn));
            }
            $pid = mysqli_insert_id($conn);

            foreach ($materials as $material) {
                $mid = intval($material['mid']);
                $pmqty = intval($material['pmqty']);
                $sql = "INSERT INTO prodmat (pid, mid, pmqty) VALUES ($pid, $mid, $pmqty)";
                if (!mysqli_query($conn, $sql)) {
                    throw new Exception("Failed to insert material ID: $mid. Error: " . mysqli_error($conn));
                }
            }

            mysqli_commit($conn);
            echo json_encode(["result" => "success", "message" => "Product inserted successfully."]);
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errors[] = $e->getMessage();
            echo json_encode(["result" => "error", "errors" => $errors]);
        }
    } else {
        echo json_encode(["result" => "error", "errors" => $errors]);
    }
}

mysqli_close($conn);
?>