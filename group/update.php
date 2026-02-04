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
$title = $_POST['title'] ?? null;
$course = $_POST['course'] ?? null;

// Проверка на наличие id
requiredParams([$id], 'groupId is required');

try {
    // 1. Существует ли такая группа
    $sql = "SELECT * FROM `groups` WHERE id = ?";
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

    // 2. Готовим данные для обновления
    // Если новых данных нет, оставляем старые
    $newTitle  = $title ?: $group['title'];
    $newCourse = $course ?: $group['course'];

    // 3. Выполняем сам UPDATE
    $sql = "UPDATE `groups` SET title = ?, course = ? WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$newTitle, $newCourse, $id]);

    echo json_encode([
        'success' => true,
        'rows' => "Group $id updated"
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}

// Пример использования (в терминале):
// curl -X POST -d "groupId=2&title=КС-26-03" http://localhost/projects/group/update.php
