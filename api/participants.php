<?php
header("Content-Type: application/json");
include './db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET' && isset($_GET['type']) && $_GET['type'] == 'count') {
    $sql = "SELECT COUNT(*) as count FROM participants WHERE bus_number IS NULL";
    $stmt = $pdo->query($sql);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);

    $bus_count = ceil($count['count'] / 50);

    echo json_encode(["count" => $bus_count]);
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
    echo json_encode(["message" => "Invalid request method"]);
}