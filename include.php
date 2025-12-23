<?php
// Настройки
$host = 'localhost'; // Адрес сервера
$db   = 'university'; // Имя базы
$user = 'user';    // Логин
$pass = '1';    // Пароль
$charset = 'utf8mb4'; // Кодировка

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Показывать ошибки
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Чистый вывод без дублирования
];

// Наше подключение
$pdo = new PDO($dsn, $user, $pass, $options);
