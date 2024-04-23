<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "54RegionalJunior";
$charset = "utf8mb4";

$dsn = "mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;mysql:host=$servername;dbname=$dbname;charset=$charset";

$pdo = new PDO($dsn, $db_username, $db_password);