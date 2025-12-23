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

    // 1 вариант: Получить одну группу

    // SQL запрос
    $sql = 'SELECT * FROM `groups` WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]); // Вызываем и подставляем id вместо знака вопроса
    $group = $stmt->fetch(); // Получаем одну строку

    if ($group) {
        echo json_encode($group, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);  // Ошибка: не найдено
        echo json_encode(['error' => 'Group not found']);
    }
} else {

    // 2 вариант: Получить все группы

    // SQL запрос
    $sql = 'SELECT * FROM `groups`';
    $stmt = $pdo->query($sql);
    $groups = $stmt->fetchAll(); // Получаем все строки списком

    echo json_encode($groups, JSON_UNESCAPED_UNICODE);
}
