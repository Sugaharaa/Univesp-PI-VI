<?php
$configFile = dirname(__DIR__) . '/config.local.php';
if (!file_exists($configFile)) {
    $configFile = dirname(__DIR__) . '/config.example.php';
}

$config = require $configFile;

$conn = mysqli_connect(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['database']
);

if (!$conn) {
    die('Falha na conexão: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
