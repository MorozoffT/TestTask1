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
$fullName = $_POST['fullName'] ?? null;
$groupId  = $_POST['groupId'] ?? null;

// Проверка на наличие ФИО
requiredParams([$fullName, $groupId], 'fullName and groupId are required');

try {
    // Существует ли такая группа
    $sql = "SELECT * FROM `groups` WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$groupId]);
    $group = $stmt->fetch();

    if (!$group) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'rows' => "Group $groupId not found"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // SQL запрос
    $sql = "INSERT INTO `students` (full_name, group_id) VALUES (?, ?)";
    $stmt = executeQuery($pdo, $sql, [$fullName, $groupId]);

    // Получаем ID только что добавленного студента
    $newId = $pdo->lastInsertId();

    http_response_code(201); // Создан
    echo json_encode([
        'success' => true,
        'rows' => "Student $newId created"
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}

// Пример использования (в терминале):
// curl -X POST -d "fullName=Новый Студент&groupId=1" http://localhost/projects/student/create.php
