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
$id = $_POST['studentId'] ?? null;

// Проверка на наличие id
requiredParams([$id], 'studentId is required');

try {
    // SQL запрос
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [$id]);

    // Проверяем, удалилась ли строка
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'rows' => "Student $id deleted"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        // Если rowCount == 0, значит такого id не было в базе
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'rows' => "Student $id not found"
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}

// Пример использования (в терминале):
// curl -X POST -d "studentId=28" http://localhost/projects/student/delete.php
