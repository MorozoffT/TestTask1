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
$id = $_POST['groupId'] ?? null;

// Проверка на наличие id
requiredParams([$id], 'groupId is required');

try {
    // SQL запрос
    $sql = "DELETE FROM `groups` WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'rows' => "Group $id deleted"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'success' => false,
            'rows' => "Group $id not found"
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}

// Пример использования (в терминале):
// curl -X POST -d "id=3" http://localhost/projects/group/delete.php
