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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Метод не разрешён
    echo json_encode(['error' => 'Only POST method is allowed']);
    exit;
}

// Получаем данные из POST
$id = $_POST['id'] ?? null;
$title = $_POST['title'] ?? null;
$course = $_POST['course'] ?? null;

// Проверка на наличие id
if (!$id) {
    http_response_code(400); // Плохой запрос
    echo json_encode(['error' => 'id is required']);
    exit;
}

try {
    // 1. Существует ли такая группа
    $sql = "SELECT * FROM `groups` WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]); // Вызываем и подставляем id вместо знака вопроса
    $group = $stmt->fetch();

    if (!$group) {
        http_response_code(404);
        echo json_encode(['error' => 'Group not found']);
        exit;
    }

    // 2. Готовим данные для обновления
    // Если новых данных нет, оставляем старые
    $newTitle  = $title ?: $group['title'];
    $newCourse = $course ?: $group['course'];

    // 3. Выполняем сам UPDATE
    $sql = "UPDATE `groups` SET title = ?, course = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newTitle, $newCourse, $id]);

    echo json_encode([
        'status' => 'ok',
        'message' => 'Group updated'
    ]);

} catch (Exception $e) { //  Если ошибка
    echo json_encode(['error' => $e->getMessage()]);
}

// Пример использования (в терминале):
// curl -X POST -d "id=2&title=КС-26-03" http://localhost/projects/group/update.php
