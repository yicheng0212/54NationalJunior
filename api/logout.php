<?php
session_start(); // 啟用 session

unset($_SESSION['username']);   // 刪除 session 變數

header("Location: ../index.php");  // 跳轉回首頁
exit;
?>
