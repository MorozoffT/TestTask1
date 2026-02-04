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
$groupId = $_POST['groupId'] ?? null;

// Проверка на наличие id группы
requiredParams([$groupId], 'groupId is required');

try {
    // Обнуляем группу у всех студентов этой группы
    $sql = "UPDATE students SET group_id = NULL WHERE group_id = ?";
    $stmt = executeQuery($pdo, $sql, [$groupId]);

    $count = $stmt->rowCount();
    if ($count > 0) {
        $rows = "Removed $count students from group $groupId";
        $success = true;
    } else {
        $rows = "Group $groupId is empty or does not exist";
        $success = false;
    }

    echo json_encode([
        'success' => $success,
        'rows' => $rows
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}

// Пример использования (в терминале):
// curl -X POST -d "groupId=1" http://localhost/projects/group/deleteStudents.php
