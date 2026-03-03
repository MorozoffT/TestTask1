<?php

use app\Utils\Request;
use app\Utils\MyException;

require_once __DIR__ . '/cli-config.php';

try {
    $request = new Request();

    $act = $request->getParam('act', true);
    $controllerName = 'app\\Controllers\\' . $act . 'Controller';
    $controller = new $controllerName($entityManager);
    $method  = $request->getParam('method', true);
    $answer = $controller->$method($request);

    if (!is_null($answer)) {
        echo json_encode([
            'success' => true,
            'rows' => $answer,
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (MyException $e) {
    logError($e->getMessage(), basename(__FILE__));
    errorOutputToUser($e);

} catch (\Throwable $t) {
    logError($t->getMessage(), basename(__FILE__));
    errorOutputToUser($t);
}
