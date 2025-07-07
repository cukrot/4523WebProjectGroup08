<?php
session_start();

// 默認語言
$default_lang = 'en';
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'zh' ? 'zh' : 'en';
}
$lang = $_SESSION['lang'] ?? $default_lang;

// 語言詞彙
$translations = [
    'en' => [
        'page_title' => 'Insert items\' information',
        'product_name' => 'Product Name',
        'description' => 'Description',
        'single_cost' => 'Single Cost',
        'upload_image' => 'Upload Image',
        'submit' => 'Submit',
        'material' => 'Material',
        'material_quantity' => 'Material Quantity',
        'add' => 'ADD',
        'material_name' => 'Material Name',
        'quantity' => 'Quantity',
        'action' => 'Action',
        'remove' => 'Remove',
        'select_material' => 'Select Material',
        'error_no_material' => 'Please add at least one material.',
        'error_invalid_input' => 'Please select a material and enter a valid quantity.',
        'success_message' => 'Product inserted successfully.'
    ],
    'zh' => [
        'page_title' => '插入產品信息',
        'product_name' => '產品名稱',
        'description' => '描述',
        'single_cost' => '單價',
        'upload_image' => '上傳圖片',
        'submit' => '提交',
        'material' => '材料',
        'material_quantity' => '材料數量',
        'add' => '添加',
        'material_name' => '材料名稱',
        'quantity' => '數量',
        'action' => '操作',
        'remove' => '移除',
        'select_material' => '選擇材料',
        'error_no_material' => '請至少添加一種材料。',
        'error_invalid_input' => '請選擇材料並輸入有效的數量。',
        'success_message' => '產品插入成功。'
    ]
];

// 獲取當前語言的詞彙
$t = $translations[$lang];
?>