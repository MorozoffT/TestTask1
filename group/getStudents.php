<?php
// Для PhpStorm
/**
 * @var PDO $pdo
 */

// Подключаем файл с настройками БД
require_once __DIR__ . '/../include.php';

// Отдаём JSON
header('Content-Type: application/json');

// Разрешаем только GET-запросы
checkMethod('GET');

// Принимаем id группы
$id = $_GET['groupId'] ?? null;

// Проверяем, передан ли параметр id группы через адресную строку
requiredParams([$id], 'groupId is required');

try {
    // 1. Существует ли сама группа
    $sql = "SELECT title FROM `groups` WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$id]);
    $group = $stmt->fetch();

    if (!$group) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'rows' => "Group $id not found"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 2. Выбираем всех студентов этой группы
    $sql = "SELECT id, full_name FROM `students` WHERE group_id = ?";
    $stmt = executeQuery($pdo, $sql, [$id]);
    $students = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'rows' => [
            'group_title' => $group['title'],
            'count' => count($students),
            'students' => $students
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}
