<?php

use Controller\SolicitudPersonal;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$response = [
    "message" => "error",
    "status" => "error",
];

try {
    $solicitudPersonal = new SolicitudPersonal;

    $action = $_GET["action"] ?? false;

    switch ($action) {
        case 'ssp_solicitud':
            $response = $solicitudPersonal::sspSolicitud([
                ["db" => "SP.id"],
                ["db" => "SPP.nombre", "as" => "proceso"],
                ["db" => "SP.nombre_cargo"],
                ["db" => "SPE.nombre", "as" => "estado"],
                ["db" => "SP.id", "formatter" => fn ($d) => "Muchos botones {$d}"]
            ]);
            break;
        default:
            $response["message"] = "Action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["message"] = "Error: {$th->getMessage()}";
} finally {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
