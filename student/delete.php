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
$id = $_POST['id'] ?? null;

// Проверка на наличие id
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Student ID is required']);
    exit;
}

try {
    // SQL запрос
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]); // Вызываем и подставляем id вместо знака вопроса

    // Проверяем, удалилась ли строка
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'ok',
            'message' => "Student with id $id deleted"
        ]);
    } else {
        // Если rowCount == 0, значит такого id не было в базе
        http_response_code(404);
        echo json_encode(['error' => 'Student not found or already deleted']);
    }

} catch (PDOException $e) { //  Если ошибка
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

// Пример использования (в терминале):
// curl -X POST -d "id=28" http://localhost/projects/student/delete.php
