<?php require_once 'language.php'; ?>
<header class="page-header">
    <div class="header-block">
        <div class="logo-container">
            <img src="../assets/images/logo.png" alt="Logo" class="logo-image">
        </div>
    </div>
    <nav class="navigation-menu">
        <a href="../pages/insert_product.php" class="nav-link<?php echo basename($_SERVER['PHP_SELF']) == 'insert_product.php' ? ' active' : ''; ?>">Products Insert</a>
        <a href="../pages/delete_product.php" class="nav-link">Products Delete</a>
        <a href="../pages/insert_material.php" class="nav-link">Materials Insert</a>
        <a href="../pages/orders.php" class="nav-link">Orders</a>
        <select onchange="switchLanguage(this.value)" class="nav-link">
            <option value="en" <?php echo $lang == 'en' ? 'selected' : ''; ?>>English</option>
            <option value="zh" <?php echo $lang == 'zh' ? 'selected' : ''; ?>>中文</option>
        </select>
    </nav>
</header>