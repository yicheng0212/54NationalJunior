<?php
session_start();    // 啟用 session
include 'db.php';   // 引入資料庫
header('Content-Type: application/json'); // 設定輸出為 JSON 格式

$response = [];

$data = json_decode(file_get_contents("php://input"), true);  // 取得請求資料
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';
$captcha = $data['captcha'] ?? '';


if (strtolower($captcha) === strtolower($_SESSION['captcha'] ?? '')) {  // 驗證圖形驗證碼
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username AND password = :password");  // 準備 SQL 語句
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();   // 執行 SQL 語句
        $user = $stmt->fetch(PDO::FETCH_ASSOC);  // 取得使用者資料

        if ($user) { // 如果有使用者資料
            $_SESSION['id'] = (int)$user['id'];     // 設定 session
            $_SESSION['username'] = $username;
            $response['status'] = 'success';        // 設定回應
            $response['message'] = '登入成功';       // 設定回應
        } else {
            $response['status'] = 'error';
            $response['message'] = '帳號或密碼錯誤';
        }
} else {
    $response['status'] = 'error';
    $response['message'] = '圖形驗證碼錯誤';
}

$pdo = null;  // 關閉連接

echo json_encode($response); // 輸出 JSON