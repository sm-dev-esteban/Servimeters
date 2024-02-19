<?php

use Config\Select2;
use Controller\HorasExtras;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$action = $_GET["action"] ?? false;

$select2 = new Select2;

$horasExtras = new HorasExtras;

$response = [
    "status" => "error",
    "message" => "failed",
];

$data = [...$_POST, ...$_FILES];

try {
    switch ($action) {
        case 'sspCargo':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "cargos",
                id: "id",
                text: "nombre"
            );
            break;
        case 'sspCeco':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "centroscosto",
                id: "id",
                text: "nombre"
            );
            break;
        case 'sspJefes':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "aprobador",
                id: "id",
                text: "nombre",
                config: ["condition" => "id_tipo = 2"]
            );
            break;
        case 'sspGerentes':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "aprobador",
                id: "id",
                text: "nombre",
                config: ["condition" => "id_tipo = 3"]
            );
            break;
        case 'INSERT':
            $result = $horasExtras->registrarReporte($data);
            $response["status"] = $result ? "success" : "error";
            $response["message"] = $result ? "Reporte Agregado con exito" : "Ocurrio un error :/";

            break;
        default:
            echo json_encode($_REQUEST, JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception | Error $th) {
    echo json_encode(["error" => $th->getMessage()]);
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
