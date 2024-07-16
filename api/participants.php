<?php
header("Content-Type: application/json");
include './db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    
    $sql_bus = "SELECT DISTINCT bus_number FROM participants LIMIT 1";
    $stmt_bus = $pdo->query($sql_bus);
    $bus = $stmt_bus->fetch(PDO::FETCH_ASSOC);

    if (!$bus) {
        echo json_encode(["message" => "No bus found"]);
        exit();
    }

    $sql_participants = "SELECT id, name, email FROM participants WHERE bus_number = :bus_number";
    $stmt_participants = $pdo->prepare($sql_participants);
    $stmt_participants->execute(['bus_number' => $bus['bus_number']]);
    $participants = $stmt_participants->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        'bus' => $bus['bus_number'],
        'participants' => []
    ];

    foreach ($participants as $participant) {
        $response['participants'][] = [
            'id' => $participant['id'],
            'name' => $participant['name'],
            'email' => $participant['email']
        ];
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
?>
