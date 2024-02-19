<?php

use Controller\Login;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";


$user = $_POST["data"]["user"] ?? null;
$pass = $_POST["data"]["pass"] ?? null;

$response = [];

try {
    $login = new Login;
    $response = $login->startSession($user, $pass);
} catch (Error | Exception $th) {
    $response = [
        "status" => false,
        "error" => $th->getMessage(),
        "file" => $th->getFile(),
        "line" => $th->getLine(),
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
