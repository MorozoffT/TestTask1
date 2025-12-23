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

// Проверка на наличие названия и курса
if (!$id) {
    http_response_code(400); // Плохой запрос
    echo json_encode(['error' => 'id is required']);
    exit;
}

try {
    // SQL запрос
    $sql = "DELETE FROM `groups` WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]); // Вызываем

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'ok',
            'message' => 'Group deleted'
        ]);
    } else {
        echo json_encode(['error' => 'Group not found']);
    }

} catch (Exception $e) { //  Если ошибка
    echo json_encode(['error' => $e->getMessage()]);
}

// Пример использования (в терминале):
// curl -X POST -d "id=3" http://localhost/projects/group/delete.php
