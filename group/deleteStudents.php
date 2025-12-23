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
$groupId = $_POST['group_id'] ?? null;

// Проверка на наличие id группы
if (!$groupId) {
    http_response_code(400); // Плохой запрос
    echo json_encode(['error' => 'Group id is required']);
    exit;
}

try {
    // Обнуляем группу у всех студентов этой группы
    $sql = "UPDATE students SET group_id = NULL WHERE group_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$groupId]);

    $count = $stmt->rowCount();

    echo json_encode([
        'status'  => 'ok',
        'message' => "Removed $count students from group $groupId"
    ]);

} catch (Exception $e) { //  Если ошибка
    echo json_encode(['error' => $e->getMessage()]);
}

// Пример использования (в терминале):
// curl -X POST -d "group_id=1" http://localhost/projects/group/deleteStudents.php
