<?php
header("Content-Type: application/json");// 告訴瀏覽器使用 JSON 格式
include 'db.php'; // 引入 db.php

$method = $_SERVER['REQUEST_METHOD']; // 取得請求方法
$data = json_decode(file_get_contents("php://input"), true); // 取得請求內容

switch ($method) { // 判斷請求方法
    case 'POST': // 如果是 POST 請求
        $stationName = $data['stationName'];
        $drivenTime = $data['drivenTime'];
        $stopTime = $data['stopTime'];
        $sql = "INSERT INTO station (stationName, drivenTime, stopTime) VALUES ('$stationName', $drivenTime, $stopTime)"; // 新增資料
        $pdo->exec($sql);
        echo json_encode(["message" => "站點新增成功!"]); // 回傳訊息
        break; // 結束

    case 'GET': // 如果是 GET 請求
        $sql = "SELECT * FROM station";
        $stmt = $pdo->query($sql);
        $stations = $stmt->fetchAll(PDO::FETCH_ASSOC); // 取得所有資料
        echo json_encode($stations);
        break;

    case 'PUT': // 如果是 PUT 請求
        $id = $data['id'];
        $stationName = $data['stationName'];
        $drivenTime = $data['drivenTime'];
        $stopTime = $data['stopTime'];
        $sql = "UPDATE station SET stationName='$stationName', drivenTime=$drivenTime, stopTime=$stopTime WHERE id=$id"; // 更新資料
        $pdo->exec($sql);
        echo json_encode(["message" => "站點更新成功!"]);
        break;

    case 'DELETE': // 如果是 DELETE 請求
        $id = $data['id'];
        $sql = "DELETE FROM station WHERE id=$id"; // 刪除資料
        $pdo->exec($sql);
        echo json_encode(["message" => "站點刪除成功!"]);
        break;

    default: // 如果是其他請求
        echo json_encode(["message" => "無效的請求方法"]);
        break;
}

$pdo = null; // 關閉資料庫連線
