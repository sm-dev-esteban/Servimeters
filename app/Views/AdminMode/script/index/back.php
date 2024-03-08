<?php

use Controller\Aprobador;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$response = [
    "message" => "error",
    "status" => "error",
];

try {
    $action = $_GET["action"] ?? null;
    switch ($action) {
        case 'sspAprov':
            $response = Aprobador::sspApprover([
                ["db" => "APPROVER.nombre"],
                ["db" => "APPROVER.email"],
            ], ["condition" => "APPROVER.id != 1"]);
            break;

        default:
            $response["Action is undefined"];
            break;
    }
} catch (Exception | Error $th) {
    //throw $th;
} finally {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
