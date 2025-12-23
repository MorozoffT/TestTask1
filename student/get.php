<?php
// Для PhpStorm
/**
 * @var PDO $pdo
 */

// Подключаем файл с настройками БД
require_once __DIR__ . '/../include.php';

// Отдаём JSON
header('Content-Type: application/json');

// Проверяем, передан ли параметр id через адресную строку
if (isset($_GET['id'])) {

    // 1 вариант: Получить одного студента

    // SQL запрос
    $sql = "SELECT students.*, groups.title as group_title 
            FROM students 
            LEFT JOIN `groups` ON students.group_id = groups.id 
            WHERE students.id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]); // Вызываем и подставляем id вместо знака вопроса

    $student = $stmt->fetch(); // Получаем одну строку

    if ($student) {
        echo json_encode($student);
    } else {
        http_response_code(404); // Ошибка: не найдено
        echo json_encode(['error' => 'Student not found']);
    }

} else {

    // 2 вариант: Получить всех студентов

    // SQL запрос
    $sql = "SELECT students.*, groups.title as group_title 
            FROM students 
            LEFT JOIN `groups` ON students.group_id = groups.id";

    $stmt = $pdo->query($sql);

    $students = $stmt->fetchAll(); // Получаем все строки списком

    echo json_encode($students, JSON_UNESCAPED_UNICODE);
}
