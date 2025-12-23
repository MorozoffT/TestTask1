<?php
// Для PhpStorm
/**
 * @var PDO $pdo
 */

// Подключаем файл с настройками БД
require_once __DIR__ . '/../include.php';

// Отдаём JSON
header('Content-Type: application/json');

// Разрешаем только POST-запросы
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Метод не разрешён
    echo json_encode(['error' => 'Only POST method is allowed']);
    exit;
}

// Получаем данные из POST
$title  = $_POST['title'] ?? null;
$course = $_POST['course'] ?? null;

// Проверка на наличие названия и курса
if (!$title || !$course) {
    http_response_code(400); // Плохой запрос
    echo json_encode(['error' => 'title and course are required']);
    exit;
}

try {
    // SQL запрос
    $sql = "INSERT INTO `groups` (title, course) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $course]); // Вызываем и подставляем название и курс вместо знака вопроса

    echo json_encode([
        'status' => 'ok',
        'id' => $pdo->lastInsertId(),
        'title' => $title
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) { //  Если ошибка
    echo json_encode(['error' => $e->getMessage()]);
}

// Пример использования (в терминале):
// curl -X POST -d "title=КХ-24-05&course=2" http://localhost/projects/group/create.php
