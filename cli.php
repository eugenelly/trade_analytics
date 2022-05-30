<?php

require_once 'Storage.php';

$command = $argv[1];
$typeStorage = $argv[2];
$operation = $argv[3];
$key = $argv[4];
if (isset($argv[5])) {
    $value = $argv[5];
}

if (($command !== 'command') ||
    !(($typeStorage === 'redis') || ($typeStorage === 'memcached')) ||
    !($operation === 'add' || $operation === 'delete')) {
    die('Неизвестная команда' . PHP_EOL);
}

$storage = Storage::getInstance();
$storage->connect();

if ($argv[3] === 'add' && isset($value)) {
    $storage->set($key, $value);
}

if ($argv[3] === 'delete') {
    $storage->delete($key);
}

$storage->close();
