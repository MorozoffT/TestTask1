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
$studentId = $_POST['studentId'] ?? null;
$fullName = $_POST['fullName'] ?? null;
$groupId  = $_POST['groupId'] ?? null;

// Проверка на наличие id
requiredParams([$studentId, $groupId], 'studentId and groupId are required');

try {
    // 1. Существует ли такой студент
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$studentId]);
    $student = $stmt->fetch();

    if (!$student) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'rows' => "Student $studentId not found"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 2. Существует ли такая группа
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

    // 3. Готовим данные для обновления
    // Если нет нового имени, оставляем старое
    $newName = $fullName ?: $student['full_name'];

    // 4. Выполняем сам UPDATE
    $sql = "UPDATE students SET full_name = ?, group_id = ? WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$newName, $groupId, $studentId]);

    echo json_encode([
        'success' => true,
        'rows' => "Student $studentId updated"
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}

// Пример использования (в терминале):
// curl -X POST -d "studentId=29&fullName=Обновлено&groupId=2" http://localhost/projects/student/update.php
