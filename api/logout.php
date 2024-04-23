<?php
session_start(); // 啟用 session

unset($_SESSION['user_id']);  // 移除 session 變數
unset($_SESSION['username']);

header("Location: ../index.php");  // 跳轉回首頁
exit;
?>
