<?php
session_start();  // 啟用 session 功能
echo $_SESSION['code']=rand(1000,9999);  // 產生一個四位數的驗譋碼