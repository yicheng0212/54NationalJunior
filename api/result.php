<?php
header("Content-Type: application/json");
include './db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET' && isset($_GET['email'])) {
    $email = $_GET['email'];

    $sql = "SELECT bus_number FROM participants WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $participant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$participant) {
        echo json_encode(["message" => "您不在參與名單當中"]);
        exit;
    }

    if (!$participant['bus_number']) {
        echo json_encode(["message" => "目前尚未分配接駁車"]);
        exit;
    }

    $busNumber = $participant['bus_number'];
    $sql = "SELECT name FROM participants WHERE bus_number = :bus_number";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['bus_number' => $busNumber]);
    $passengers = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        "bus_number" => $busNumber,
        "passengers" => $passengers
    ]);
} else {
    $sql = "SELECT bus_number, GROUP_CONCAT(name SEPARATOR ', ') as passengers FROM participants WHERE bus_number IS NOT NULL GROUP BY bus_number";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
}
?>