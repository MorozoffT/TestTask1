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
checkMethod('POST');

// Получаем данные из POST
$fromGroupId = $_POST['fromGroupId'] ?? null; // Откуда забираем
$toGroupId   = $_POST['toGroupId'] ?? null;   // Куда переводим

// Проверка на наличие id групп
requiredParams([$fromGroupId, $toGroupId], 'fromGroupId and toGroupId are required');

try {
    $sql = "SELECT * FROM `groups` WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$fromGroupId]);
    $fromGroup = $stmt->fetch();
    $sql = "SELECT * FROM `groups` WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$toGroupId]);
    $toGroup = $stmt->fetch();

    if (!$fromGroup) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'rows' => "Group $fromGroupId not found"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } elseif (!$toGroup) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'rows' => "Group $toGroupId not found"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Выполняем обновление
    $sql = "UPDATE students SET group_id = ? WHERE group_id = ?";
    $stmt = executeQuery($pdo, $sql, [$toGroupId, $fromGroupId]);
    $count = $stmt->rowCount(); // Сколько студентов было затронуто

    echo json_encode([
        'success' => true,
        'rows' => "Moved $count students from group $fromGroupId to group $toGroupId"
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}

// Пример использования (в терминале):
// curl -X POST -d "fromGroupId=1&toGroupId=2" http://localhost/projects/group/updateStudents.php
