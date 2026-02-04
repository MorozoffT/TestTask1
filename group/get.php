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
$id = $_GET['groupId'];
try {
    if (isset($id)) {

        // 1 вариант: Получить одну группу

        // SQL запрос
        $sql = 'SELECT * FROM `groups` WHERE id = ?';
        $stmt = executeQuery($pdo, $sql, [$id]);
        $group = $stmt->fetch(); // Получаем одну строку

        if ($group) {
            echo json_encode([
                'success' => true,
                'rows' => $group
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);  // Ошибка: не найдено
            echo json_encode([
                'success' => false,
                'rows' => "Group $id not found"
            ], JSON_UNESCAPED_UNICODE);
        }
    } else {

        // 2 вариант: Получить все группы

        // SQL запрос
        $sql = 'SELECT * FROM `groups`';
        $stmt = executeQuery($pdo, $sql);
        $groups = $stmt->fetchAll(); // Получаем все строки списком

        echo json_encode([
            'success' => true,
            'rows' => $groups
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    logError($e->getMessage(), basename(__FILE__));
}
