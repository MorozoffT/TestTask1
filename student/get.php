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

// Проверяем, передан ли параметр id через адресную строку
$id = $_GET['studentId'];
try {
    if (isset($id)) {

        // 1 вариант: Получить одного студента

        // SQL запрос
        $sql = "SELECT students.*, groups.title as group_title 
                FROM students 
                LEFT JOIN `groups` ON students.group_id = groups.id 
                WHERE students.id = ?";

        $stmt = executeQuery($pdo, $sql, [$id]);
        $student = $stmt->fetch(); // Получаем одну строку

        if ($student) {
            echo json_encode([
                'success' => true,
                'rows' => $student
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404); // Ошибка: не найдено
            echo json_encode([
                'success' => false,
                'rows' => "Student $id not found"
            ], JSON_UNESCAPED_UNICODE);
        }

    } else {

        // 2 вариант: Получить всех студентов

        // SQL запрос
        $sql = "SELECT students.*, groups.title as group_title 
                FROM students 
                LEFT JOIN `groups` ON students.group_id = groups.id";

        $stmt = executeQuery($pdo, $sql);
        $students = $stmt->fetchAll(); // Получаем все строки списком

        echo json_encode([
            'success' => true,
            'rows' => $students
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    logError($e->getMessage(), basename(__FILE__));
}
