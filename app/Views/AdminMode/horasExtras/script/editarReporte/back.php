<?php

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$response = [
    "status" => "error",
    "message" => "failed",
];

try {
    $action = $_GET["action"] ?? null;

    $data = [...$_POST, ...$_FILES];

    switch ($action) {
        case 'UPDATE':
            # code...
            break;

        default:
            $response["message"] = "Action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["message"] = $th->getMessage();
} finally {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
