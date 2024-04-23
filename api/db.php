<?php
$servername = "127.0.0.1";
$db_username = "root";
$db_password = "";
$dbname = "54RegionalJunior";
$charset = "utf8mb4";

$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

$pdo = new PDO($dsn, $db_username, $db_password);