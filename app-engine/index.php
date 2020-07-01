<?php

include __DIR__ . "/autoload.php";
include __DIR__ . "/constants.php";


use Helper\Requests;


list($pathStr, $queryStr) = explode("?", $_SERVER["REQUEST_URI"], 2);
$path = explode('/', $pathStr);

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    $data = $_POST;
}

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, GET, PATCH, DELETE');

if (!$queryStr) {
    $queryStr = '';
}

$result = (new Requests($data ?? []))->handleRequest($path, $queryStr);
if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
    http_response_code($result['status'] ?? 200);
}

echo isset($result['result_data']) ? json_encode($result['result_data']) : '{}';







