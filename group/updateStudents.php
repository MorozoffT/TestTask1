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
$fromGroupId = $_POST['from_group_id'] ?? null; // Откуда забираем
$toGroupId   = $_POST['to_group_id'] ?? null;   // Куда переводим

// Проверка на наличие id групп
if (!$fromGroupId  || !$toGroupId) {
    http_response_code(400); // Плохой запрос
    echo json_encode(['error' => 'from_group_id and to_group_id are required']);
    exit;
}

try {
    // Выполняем обновление
    $sql = "UPDATE students SET group_id = ? WHERE group_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$toGroupId, $fromGroupId]); // Вызываем

    $count = $stmt->rowCount(); // Сколько студентов было затронуто

    echo json_encode([
        'status'  => 'ok',
        'message' => "Moved $count students from group $fromGroupId to group $toGroupId"
    ]);

} catch (Exception $e) { //  Если ошибка
    echo json_encode(['error' => $e->getMessage()]);
}

// Пример использования (в терминале):
// curl -X POST -d "from_group_id=1&to_group_id=2" http://localhost/projects/group/updateStudents.php
