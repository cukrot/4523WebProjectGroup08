<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/insert_item.css">
    <script src="../assets/js/language.js"></script>
    <script src="../assets/js/insert_item.js" defer></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="main-content">
        <div class="content-container">
            <aside class="sidebar">
                <h1 class="page-title"><?php echo $t['page_title']; ?></h1>
            </aside>
            <section class="forms-section">
                <div class="forms-container">
                    <form id="product-form" class="product-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label" for="pname"><?php echo $t['product_name']; ?></label>
                            <input type="text" id="pname" name="pname" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="pdesc"><?php echo $t['description']; ?></label>
                            <textarea id="pdesc" name="pdesc" class="form-textarea"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="pcost"><?php echo $t['single_cost']; ?></label>
                            <input type="number" id="pcost" name="pcost" class="form-input" step="0.01" min="0.01" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="pimage"><?php echo $t['upload_image']; ?></label>
                            <input type="file" id="pimage" name="pimage" class="form-input" accept="image/jpeg,image/png,image/gif">
                        </div>
                        <button type="submit" class="submit-button"><?php echo $t['submit']; ?></button>
                        <input type="hidden" id="materials" name="materials">
                    </form>

                    <div class="materials-section">
                        <div class="materials-form">
                            <div class="form-group">
                                <label class="form-label" for="mid"><?php echo $t['material']; ?></label>
                                <select id="mid" name="mid" class="form-input" required>
                                    <option value=""><?php echo $t['select_material']; ?></option>
                                    <?php
                                    require_once '../includes/db_connect.php';
                                    $sql = "SELECT mid, mname FROM material";
                                    $result = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['mid']}'>{$row['mname']} (ID: {$row['mid']})</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="pmqty"><?php echo $t['material_quantity']; ?></label>
                                <input type="number" id="pmqty" name="pmqty" class="form-input" min="1" required>
                            </div>
                            <button type="button" class="add-button" onclick="addMaterial()"><?php echo $t['add']; ?></button>
                        </div>

                        <div class="materials-list">
                            <div class="list-header">
                                <div class="list-column"><?php echo $t['material_name']; ?></div>
                                <div class="list-column"><?php echo $t['quantity']; ?></div>
                                <div class="list-column"><?php echo $t['action']; ?></div>
                            </div>
                            <div id="material-items" class="list-items"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>