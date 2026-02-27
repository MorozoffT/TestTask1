<?php

use app\Utils\Request;

require_once __DIR__ . '/cli-config.php';

// Отдаём JSON
header('Content-Type: application/json');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

try {
    $request = new Request();

    $act = $request->getParam('act', true);
    $controllerName = 'app\\Controllers\\' . $act . 'Controller';
    $controller = new $controllerName($entityManager);
    $method  = $request->getParam('method', true);
    $controller->$method($request);


} catch (\Throwable $t) {
    echo json_encode([
        'success' => false,
        'rows' => "bad request"
    ], JSON_UNESCAPED_UNICODE);
    logError($t->getMessage(), basename(__FILE__));
}
