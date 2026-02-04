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
// Примечание: использование ?? удобнее, ибо если в получаемом массиве есть
//             искомые ключи (title и course), то они запишутся в соответствующие
//             переменные, в ином случае в них запишутся значения null,
//             которые после будут проверены.
$title  = $_POST['title'] ?? null;
$course = $_POST['course'] ?? null;

// Проверка на наличие названия и курса
// Примечание: Соответственно данная проверка. Если одно из значений будет равно
//             null или пустой строке (именно поэтому используется !, а не is_null,
//             дабы не дать возможность создать группу с пустым названием) условие
//             выполнится и выведется ошибка.
requiredParams([$title, $course], 'title and course are required');

try {
    // SQL запрос
    $sql = "INSERT INTO `groups` (title, course) VALUES (?, ?)";
    $stmt = executeQuery($pdo, $sql, [$title, $course]);

    $newId = $pdo->lastInsertId();

    http_response_code(201); // Создана
    echo json_encode([
        'success' => true,
        'rows' => "Group $newId created"
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) { //  Если ошибка
    logError($e->getMessage(), basename(__FILE__));
}

// Пример использования (в терминале):
// curl -X POST -d "title=КХ-24-05&course=2" http://localhost/projects/group/create.php
