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
$fullName = $_POST['full_name'] ?? null;
$groupId  = $_POST['group_id'] ?? null;

// Проверка на наличие id
if (!$id) {
    http_response_code(400); // Плохой запрос
    echo json_encode(['error' => 'Student ID is required']);
    exit;
}

try {
    // 1. Существует ли такой студент
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]); // Вызываем и подставляем id вместо знака вопроса
    $student = $stmt->fetch();

    if (!$student) {
        http_response_code(404);
        echo json_encode(['error' => 'Student not found']);
        exit;
    }

    // 2. Готовим данные для обновления
    // Если нет нового имени, оставляем старое
    $newName = $fullName ?: $student['full_name'];

    // Если нет group_id, оставляем старый.
    if ($groupId === null) {
        $newGroupId = $student['group_id'];
    } elseif ($groupId === '') {
        $newGroupId = null;
    } else {
        $newGroupId = (int)$groupId;
    }

    // 3. Выполняем сам UPDATE
    $sql = "UPDATE students SET full_name = ?, group_id = ? WHERE id = ?";
    $updateStmt = $pdo->prepare($sql);

    $updateStmt->execute([$newName, $newGroupId, $id]); // Вызываем

    echo json_encode([
        'status' => 'ok',
        'message' => 'Student updated',
        'id' => $id,
        'new_data' => [
            'full_name' => $newName,
            'group_id' => $newGroupId
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) { //  Если ошибка
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

// Пример использования (в терминале):
// curl -X POST -d "id=1&full_name=Обновлено&group_id=2" http://localhost/projects/student/update.php
