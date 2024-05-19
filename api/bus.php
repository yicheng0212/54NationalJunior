<?php
header("Content-Type: application/json");//告訴瀏覽器要使用json格式
include 'db.php';//引入db.php

$method = $_SERVER['REQUEST_METHOD'];//取得請求方法
$data = json_decode(file_get_contents("php://input"), true);//取得請求內容

switch ($method) {//判斷請求方法
    case 'POST'://如果是POST請求
        $busNumber = $data['busNumber'];
        $drivenTime = $data['drivenTime'];
        $sql = "INSERT INTO bus (busNumber, drivenTime) VALUES ('$busNumber', $drivenTime)";//新增資料
        $pdo->exec($sql);
        echo json_encode(["message" => "Bus added successfully!"]);//回傳訊息
        break;//結束

    case 'GET'://如果是GET請求
        $sql = "SELECT * FROM bus";
        $stmt = $pdo->query($sql);
        $buses = $stmt->fetchAll(PDO::FETCH_ASSOC);//取得所有資料
        echo json_encode($buses);
        break;

    case 'PUT'://如果是PUT請求
        $id = $data['id'];
        $busNumber = $data['busNumber'];
        $drivenTime = $data['drivenTime'];
        $sql = "UPDATE bus SET busNumber='$busNumber', drivenTime=$drivenTime WHERE id=$id";//更新資料
        $pdo->exec($sql);
        echo json_encode(["message" => "Bus updated successfully!"]);
        break;

    case 'DELETE'://如果是DELETE請求
        $id = $data['id'];
        $sql = "DELETE FROM bus WHERE id=$id";
        $pdo->exec($sql);
        echo json_encode(["message" => "Bus deleted successfully!"]);//刪除資料
        break;

    default://如果是其他請求
        echo json_encode(["message" => "Invalid request method"]);
        break;
}

$pdo = null;//關閉資料庫連線