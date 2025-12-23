<?php
// Для PhpStorm
/**
 * @var PDO $pdo
 */

// Подключаем файл с настройками БД
require_once __DIR__ . '/../include.php';

// Отдаём JSON
header('Content-Type: application/json');

// Принимаем id группы
$groupId = $_GET['id'] ?? null;

// Проверяем, передан ли параметр id группы через адресную строку
if (!$groupId) {
    http_response_code(400);
    echo json_encode(['error' => 'Group ID is required']);
    exit;
}

try {
    // 1. Существует ли сама группа
    $sql = "SELECT title FROM `groups` WHERE id = ?";
    $groupStmt = $pdo->prepare($sql);
    $groupStmt->execute([$groupId]);
    $group = $groupStmt->fetch();

    if (!$group) {
        http_response_code(404);
        echo json_encode(['error' => 'Group not found']);
        exit;
    }

    // 2. Выбираем всех студентов этой группы
    $sql = "SELECT id, full_name FROM students WHERE group_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$groupId]);
    $students = $stmt->fetchAll();

    echo json_encode([
        'group_title' => $group['title'],
        'count'       => count($students),
        'students'    => $students
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) { //  Если ошибка
    echo json_encode(['error' => $e->getMessage()]);
}
