<?php
header("Content-Type: application/json");
include './db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET' && isset($_GET['email'])) {
    $email = $_GET['email'];

    // 檢查 email 是否在參與者名單內
    $settings = $pdo->query("SELECT email_list FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
    if (!$settings) {
        echo json_encode(["message" => "設定數據未找到"]);
        exit;
    }

    $emailList = explode(',', $settings['email_list']);
    if (!in_array($email, $emailList)) {
        echo json_encode(["message" => "您不在參與名單當中"]);
        exit;
    }

    // 檢查參與者名單中是否存在該 email
    $sql = "SELECT email, bus_number FROM participants WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $participant = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($participant) {
        // 檢查是否已分配接駁車
        if ($participant['bus_number']) {
            echo json_encode(["message" => "您已被分配接駁車", "bus_number" => $participant['bus_number']]);
        } else {
            echo json_encode(["message" => "目前尚未分配接駁車"]);
        }
    } else {
        echo json_encode(["message" => "您不在參與名單當中"]);
    }
} else {
    echo json_encode(["message" => "無效的請求"]);
}
?>