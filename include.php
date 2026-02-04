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

// Универсальная функция для работы с БД
function executeQuery(PDO $pdo, string $sql, array $params = []): PDOStatement
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// Функция логирования ошибок
function logError(string $message, string $scriptName = ''): void
{
    http_response_code(500);

    // Путь к файлу логов
    $logFile = __DIR__ . '/errors.log';

    // Сама запись в лог
    $timestamp = date('Y-m-d H:i:s');
    $context = $scriptName ? "[Script: $scriptName] " : "";
    $logEntry = "[{$timestamp}] {$context}Error: {$message}" . PHP_EOL; // PHP_EOL - перенос строки

    // Записываем
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    // Вывод ошибки
    echo json_encode([
        'success' => false,
        'rows' => [
            'error' => $message
        ]
    ], JSON_UNESCAPED_UNICODE);
}

// Функция проверки метода запроса
function checkMethod(string $method): void
{
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'rows' => "Only $method method is allowed"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Функция проверки необходимых параметров
function requiredParams(array $params, string $message): void
{
    foreach ($params as $param) {
        if (!$param) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'rows' => $message
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
