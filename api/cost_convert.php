<?php
header('Content-Type: application/json');

// 接收前端傳來的參數
$amount = isset($_POST['amount']) ? $_POST['amount'] : null;
$currency = isset($_POST['currency']) ? $_POST['currency'] : null;
$rate = isset($_POST['rate']) ? $_POST['rate'] : null;

// 驗證參數是否完整
if ($amount === null || $currency === null || $rate === null) {
    echo json_encode([
        'result' => 'rejected',
        'reason' => 'Missing required parameters'
    ]);
    exit;
}

// 驗證金額和匯率是否為數字
if (!is_numeric($amount) || !is_numeric($rate)) {
    echo json_encode([
        'result' => 'rejected',
        'reason' => 'Amount and rate must be numeric'
    ]);
    exit;
}

// 驗證貨幣是否有效
$allowed_currencies = ['HKD', 'EUR', 'JPY'];
if (!in_array($currency, $allowed_currencies)) {
    echo json_encode([
        'result' => 'rejected',
        'reason' => "Error: Currency must be 'HKD' or 'EUR' or 'JPY'"
    ]);
    exit;
}

// 構建Python API的URL
$python_api_url = "http://127.0.0.1:80/cost_convert/" . urlencode($amount) . "/" . urlencode($currency) . "/" . urlencode($rate);

// 初始化cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $python_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPGET, true);

// 執行cURL請求
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// 檢查cURL是否成功
if ($response === false || $http_code != 200) {
    echo json_encode([
        'result' => 'rejected',
        'reason' => 'Failed to connect to currency conversion service'
    ]);
    curl_close($ch);
    exit;
}

// 關閉cURL
curl_close($ch);

// 直接返回Python API的JSON響應
echo $response;
?>