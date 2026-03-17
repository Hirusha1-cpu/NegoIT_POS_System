<?php
$conn  = mysqli_connect('127.0.0.1', 'root', 'HiruMySql5', 'negoit');
$conn2 = mysqli_connect('127.0.0.1', 'root', 'HiruMySql5', 'negoit');

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
mysqli_set_charset($conn2, 'utf8mb4');
