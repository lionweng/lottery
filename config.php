<?php
// PDO 連線設定
$options = [
    PDO::ATTR_PERSISTENT => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
];

//資料庫連線
try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=lottery', 'lion', '59209167icp', $options);
    $pdo->exec('SET CHARACTER SET utf8mb4');
} catch (PDOException $e) {
    throw new PDOException($e->getMessage());
}