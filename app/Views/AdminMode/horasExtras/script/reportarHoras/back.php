<?php

use Config\Select2;
use Controller\HorasExtras;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$response = [
    "status" => "error",
    "message" => "failed",
];

try {
    $action = $_GET["action"] ?? null;

    $select2 = new Select2;
    $horasExtras = new HorasExtras;

    $data = [...$_POST, ...$_FILES];

    switch ($action) {
        case 'INSERT':
            $result = $horasExtras->registrarReporte($data);
            $response["status"] = $result ? "success" : "error";
            $response["message"] = $result ? "Reporte Agregado con exito" : "Ocurrio un error :/";

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