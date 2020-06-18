<?php

include __DIR__ . "/autoload.php";


use Helper\Requests;


/**
 * GET
 *
 * api/spending                 - получить все траты
 * api/spending/1               - получить трату с id=1
 *
 *
 * POST
 * api/spending                 - добавить трату
 * api/spending/1               - изменить трату с id=1
 * api/spending/1?action=delete - удалить трату с id=1
 *
 */


list($pathStr, $queryStr) = explode("?", $_SERVER["REQUEST_URI"], 2);
$path = explode('/', $pathStr);

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    $data = $_POST;
}

if (empty($path[2]))
{
    echo 'The URI doesn\'t contain request';
    exit;
}

// for react test mode
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type');

if (!$queryStr) {
    $queryStr = '';
}
$result = (new Requests($data ?? []))->handleRequest($path, $queryStr);

echo $result;







