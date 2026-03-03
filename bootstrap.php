<?php

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

require_once "vendor/autoload.php";

function getEntityManager(): EntityManager
{

    $config = new Configuration;

    $queryCache = new ArrayAdapter();
    $metadataCache = new ArrayAdapter();

    $config->setMetadataCache($metadataCache);
    $config->setQueryCache($queryCache);

    $driver = new AttributeDriver([__DIR__ . '/Entities']);
    $config->setMetadataDriverImpl($driver);

    $config->setProxyDir(__DIR__ . '/var/cache');
    $config->setProxyNamespace('Cache\Proxies');
    $config->setAutoGenerateProxyClasses(true);


    $connectionOptions = $dbParams = array(
        'driver' => 'pdo_mysql',
        'user' => 'user',
        'password' => '1',
        'host' => 'localhost',
        'dbname' => 'university',
    );

    return EntityManager::create($connectionOptions, $config);
}

function logError(string $message, string $scriptName = ''): void
{
    http_response_code(500);

    $logFile = __DIR__ . '/errors.log';

    $timestamp = date('Y-m-d H:i:s');
    $context = $scriptName ? "[Script: $scriptName] " : "";
    $logEntry = "[{$timestamp}] {$context}Error: {$message}" . PHP_EOL;

    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

function errorOutputToUser($e): void
{
    echo json_encode([
        'success' => false,
        'code' => $e->getCode(),
        'rows' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
