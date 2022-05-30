<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/entities/Storage.php';

function return_error($code = 500, $msg = "Произошла ошибка"){
    echo json_encode([
        'status' => false,
        'code' => $code,
        'data' => ['message' => $msg]
    ]);
}

$storage = Storage::getInstance();
$storage->connect();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo json_encode([
            'status' => true,
            'code' => 200,
            'data' => $storage->list()
        ]);
        break;
    case 'DELETE':
        if ($storage->delete(substr($_SERVER['PATH_INFO'], 1))) {
            echo json_encode([
                'status' => true,
                'code' => 200,
                'data' => []
            ]);
        } else {
            return_error();
        }
        break;
    default:
        return_error();
        break;
}

$storage->close();
