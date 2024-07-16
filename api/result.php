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
} else if ($method == 'GET' && isset($_GET['type']) && $_GET['type'] == 'count') {
    $sql = "SELECT COUNT(*) as count FROM participants WHERE bus_number IS NULL";
    $stmt = $pdo->query($sql);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($count);
} else if ($method == 'GET') {
    $sql_buses = "SELECT DISTINCT bus_number FROM participants";
    $stmt_buses = $pdo->query($sql_buses);
    $buses = $stmt_buses->fetchAll(PDO::FETCH_ASSOC);
    
    $response = [];

    foreach ($buses as $bus) {
        $sql = "SELECT id, name, email FROM participants WHERE bus_number = :bus_number";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['bus_number' => $bus['bus_number']]);
        $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($participants)) {
            $bus_info = [
                'bus' => $bus['bus_number'],
                'participants' => []
            ];

            foreach ($participants as $participant) {
                $bus_info['participants'][] = [
                    'id' => $participant['id'],
                    'name' => $participant['name'],
                    'email' => $participant['email']
                ];
            }

            $response[] = $bus_info;
        }
    }

    echo json_encode($response);
} else if ($method == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'])) {
        $id = $data['id'];
        $sql = "DELETE FROM participants WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        echo json_encode(["message" => "參與者已刪除"]);
    }
} else {
    echo json_encode(["message" => "無效的請求"]);
}