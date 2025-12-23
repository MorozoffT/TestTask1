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
$fullName = $_POST['full_name'] ?? null;
$groupId  = $_POST['group_id'] ?? null;

// Проверка на наличие ФИО
if (!$fullName) {
    http_response_code(400); // Плохой запрос
    echo json_encode(['error' => 'full_name is required']);
    exit;
}

try {
    // Если group_id пустой — пишем NULL
    if ($groupId === '') {
        $groupId = null;
    }

    // SQL запрос
    $sql = "INSERT INTO students (full_name, group_id) VALUES (:full_name, :group_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':full_name', $fullName, PDO::PARAM_STR);

    if ($groupId === null) {
        $stmt->bindValue(':group_id', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':group_id', (int)$groupId, PDO::PARAM_INT);
    }

    $stmt->execute(); // Вызываем

    // Получаем ID только что добавленного студента
    $newId = $pdo->lastInsertId();

    http_response_code(201); // Создан
    echo json_encode([
        'status' => 'ok',
        'id'     => $newId,
        'full_name' => $fullName,
        'group_id'  => $groupId,
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) { //  Если ошибка
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

// Пример использования (в терминале):
// curl -X POST -d "full_name=Новый Студент&group_id=1" http://localhost/projects/student/create.php
